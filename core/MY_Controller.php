<?php

class MY_Controller extends CI_Controller
{

    protected $template = 'template';
    protected $acl_level = [];
    protected $acl_module = '';

    function __construct()
    {
        parent::__construct();
    }

    function acl($config_name = 'acl')
    {
        // load config, defaultnya: application/config/acl.php
        $config = $this->load->config($config_name, true);

        // setting variabel
        $acl_mode = $config['acl_mode'];
        $acl_session_user = $config['acl_session_user'];
        $acl_session_level = $config['acl_session_level'];
        $acl_redirect = $config['acl_redirect'];
        $acl_table = $config['acl_table_name'];
        $acl_field_user = $config['acl_user_field_name'];
        $acl_field_level = $config['acl_level_field_name'];
        $acl_field_uri = $config['acl_uri_field_name'];
        $acl_field_modules = $config['acl_field_modules_name'];
        $acl_denied_url = $config['acl_denied_url'];

        switch ($acl_mode) {
            case 1:
                // cek session user
                if ($this->session->userdata($acl_session_user) == '') {
                    redirect($acl_redirect);
                }
                break;
            case 2:
                // cek session level
                if ($this->acl_level != null && $this->session->userdata($acl_session_level) != '' && !in_array($this->session->userdata($acl_session_level), $this->acl_level)) {
                    redirect($acl_denied_url);
                } else if($this->acl_level != null && $this->session->userdata($acl_session_level) == ''){
                    $r = $this->db->where([$acl_field_user => $this->session->userdata($acl_session_user)])->get($acl_table)->row_array();
                    $acl = $r[$acl_field_level];
                    if (!in_array($acl, $this->acl_level)) {
                        redirect($acl_denied_url);
                    }
                }
                break;
            case 3:
                // cek session user
                if ($this->session->userdata($acl_session_user) == '') {
                    redirect($acl_redirect);
                }
                // cek uri
                $r = $this->db->where([$acl_field_user => $this->session->userdata($acl_field_user)])->get($acl_table)->row_array();
                if ($r[$acl_field_uri] != 'all') {
                    $access = 0;
                    $acl = preg_split('/\r\n|[\r\n]/', $r[$acl_field_uri]);
                    if (in_array($this->uri->uri_string(), $acl)) {
                        $access++;
                    }
                    if ($access == 0) {
                        redirect($acl_denied_url);
                        exit();
                    }
                }
                break;
            case 4:
                // cek session user
                if ($this->session->userdata($acl_session_user) == '') {
                    redirect($acl_redirect);
                }
                // cek session level
                if ($this->acl_level != null && $this->session->userdata($acl_session_level) != '' && !in_array($this->session->userdata($acl_session_level), $this->acl_level)) {
                    redirect($acl_denied_url);
                } else if($this->acl_level != null && $this->session->userdata($acl_session_level) == ''){
                    $r = $this->db->where([$acl_field_user => $this->session->userdata($acl_session_user)])->get($acl_table)->row_array();
                    $acl = $r[$acl_field_level];
                    if (!in_array($acl, $this->acl_level)) {
                        redirect($acl_denied_url);
                    }
                }
                // cek uri
                $r = $this->db->where([$acl_field_user => $this->session->userdata($acl_field_user)])->get($acl_table)->row_array();
                if ($r[$acl_field_uri] != 'all') {
                    $access = 0;
                    $acl = preg_split('/\r\n|[\r\n]/', $r[$acl_field_uri]);
                    if (in_array($this->uri->uri_string(), $acl)) {
                        $access++;
                    }
                    if ($access == 0) {
                        redirect($acl_denied_url);
                        exit();
                    }
                }
                break;
            case 5:
                // cek session user
                if ($this->session->userdata($acl_session_user) == '') {
                    redirect($acl_redirect);
                }
                // cek modules
                $acl_denied_url = $config['acl_denied_url'];
                $r = $this->db->where([$acl_field_user => $this->session->userdata($acl_session_user)])->get($acl_table)->row_array();
                if ($r[$acl_field_modules] != 'all') {
                    $acl = preg_split('/\r\n|[\r\n]/', $r[$acl_field_modules]);
                    if (!in_array($this->acl_module, $acl)) {
                        redirect($acl_denied_url);
                    }
                }
                break;
            case 6:
                // cek session user
                if ($this->session->userdata($acl_session_user) == '') {
                    redirect($acl_redirect);
                }
                // cek modules
                $r = $this->db->where([$acl_field_user => $this->session->userdata($acl_session_user)])->get($acl_table)->row_array();
                if ($r[$acl_field_modules] != 'all') {
                    $acl = preg_split('/\r\n|[\r\n]/', $r[$acl_field_modules]);
                    if (!in_array($this->acl_module, $acl)) {
                        redirect($acl_denied_url);
                    }
                }
                // cek uri
                $r = $this->db->where([$acl_field_user => $this->session->userdata($acl_field_user)])->get($acl_table)->row_array();
                if ($r[$acl_field_uri] != 'all') {
                    $access = 0;
                    $acl = preg_split('/\r\n|[\r\n]/', $r[$acl_field_uri]);
                    if (in_array($this->uri->uri_string(), $acl)) {
                        $access++;
                    }
                    if ($access == 0) {
                        redirect($acl_denied_url);
                        exit();
                    }
                }
                break;
            default:
                redirect($acl_redirect);
                break;
        }
    }

    function render($view_name = null, $data = null)
    {
        if ($this->template != null || $this->template != '') {
            $view = $view_name != null ? $this->load->view($view_name, $data, true) : '';
            $this->load->view($this->template, [
                'content' => $view
            ]);
        } else {
            $this->load->view($view_name, $data);
        }
    }
}
