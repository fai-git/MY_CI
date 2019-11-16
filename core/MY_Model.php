<?php

class MY_Model extends CI_Model
{

    protected $table;
    protected $db;

    public function __construct()
    {
        parent::__construct();
        $this->set_db();
    }

    public function set_db($dbname = 'default')
    {
        $this->db = $this->load->database($dbname, true);
        return $this;
    }

    public function set_table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function insert_ignore($data)
    {
        $insert_query = $this->db->insert_string($this->table, $data);
        $query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        return $this->db->query($query);
    }

    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->table, $data);
    }

    public function insert_ignore_batch($data)
    {
        $insert_query = $this->db->insert_string($this->table, $data);
        echo $query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
        return $this->db->query($query);
    }

    public function get($where = null)
    {
        if ($where != null) {
            $this->where($where);
        }
        return $this->db->get($this->table)->result_array();
    }
    public function get_one($where = null)
    {
        if ($where != null) {
            $this->where($where);
        }
        return $this->db->get($this->table)->row_array();
    }
    public function update($where, $data)
    {
        return $this->db->where($where)->update($this->table, $data);
    }

    public function delete($where)
    {
        return $this->db->where($where)->delete($this->table);
    }

    public function soft_delete($where, $column_name = 'deleted')
    {
        return $this->db->where($where)->update($this->table, [$column_name => 1]);
    }

    public function replace($data)
    {
        return $this->db->replace($this->table, $data);
    }

    public function schema()
    {
        $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='$this->table'";
        return $this->db->query($sql)->result_array();
    }

    public function blank()
    {
        $r = [];
        $fields = $this->db->field_data($this->table);
        foreach ($fields as $field) {
            $r[$field->name] = '';
        }
        return $r;
    }

    public function query($sql, $return_data = FALSE)
    {
        if ($return_data === true) {
            return $this->db->query($sql)->result_array();
        } else {
            return $this->db->query($sql);
        }
    }

    public function show_query()
    {
        return $this->db->last_query();
    }

    // fungsi bawaan class database yang sering dipakai
    public function select($p)
    {
        $this->db->select($p);
        return $this;
    }

    public function where($p)
    {
        $this->db->where($p);
        return $this;
    }

    public function join($p1, $p2, $p3 = null)
    {
        $this->db->join($p1, $p2, $p3);
        return $this;
    }

    public function group_by($p)
    {
        $this->db->group_by($p);
        return $this;
    }

    public function order_by($p)
    {
        $this->db->order_by($p);
        return $this;
    }

    public function limit($p1, $p2 = null)
    {
        $this->db->limit($p1, $p2);
        return $this;
    }

    public function from($p)
    {
        $this->db->from($p);
        return $this;
    }

    public function affected_rows()
    {
        return $this->db->affected_rows();
    }

    public function insert_id()
    {
        return $this->db->insert_id();
    }
    
    public function or_where($p)
    {
        return $this->db->or_where($p);
    }
}
