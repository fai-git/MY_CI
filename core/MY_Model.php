<?php

class MY_Model extends CI_Model
{

    protected $table;
    protected $db;
    protected $relation;
    /*
    * setting default untuk result data (printah get)
    * bisa disi array, object atau none (none artinya tidak dikembalikan langsung dalam bentuk data)
    * jika tidak didefinisikan nilai defaultnya adalah array
    */
    protected $default_result = 'array'; // array, object, none 

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

    public function table_name()
    {
        return $this->table;
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function insert_ignore($data)
    {
        $insert_query = $this->db->insert_string($this->table, $data);
        $query = str_replace('INSERT INTO ', 'INSERT IGNORE INTO ', $insert_query);
        return $this->db->query($query);
    }

    public function insert_batch($data)
    {
        return $this->db->insert_batch($this->table, $data);
    }

    public function insert_ignore_batch($data)
    {
        $insert_query = $this->db->insert_string($this->table, $data);
        $query = str_replace('INSERT INTO ', 'INSERT IGNORE INTO ', $insert_query);
        return $this->db->query($query);
    }

    public function get($where = null)
    {
        if ($where != null) {
            $this->where($where);
        }
        switch ($this->default_result) {
            case 'array':
                return $this->db->get($this->table)->result_array();
                break;
            case 'object':
                return $this->db->get($this->table)->result();
                break;
            case 'none':
                return $this->db->get($this->table);
                break;
            default:
                return $this->db->get($this->table)->result_array();
        }
    }
    public function get_one($where = null)
    {
        if ($where != null) {
            $this->where($where);
        }
        switch ($this->default_result) {
            case 'array':
                return $this->db->get($this->table)->row_array();
                break;
            case 'object':
                return $this->db->get($this->table)->row();
                break;
            case 'none':
                return $this->db->get($this->table);
                break;
            default:
                return $this->db->get($this->table)->row_array();
        }
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
        if ($return_data == true) {
            switch ($this->default_result) {
                case 'array':
                    return $this->db->query($sql)->result_array();
                    break;
                case 'object':
                    return $this->db->query($sql)->result();
                    break;
                case 'none':
                    return $this->db->query($sql);
                    break;
                default:
                    return $this->db->query($sql)->result_array();
            }
        } else {
            return $this->db->query($sql);
        }
    }

    public function show_query()
    {
        return $this->db->last_query();
    }

    /*
    * function set_relation digunakan untuk menginisialisasi relasi antar table
    * parameternya: nama relasi, nama tabel yang berelasi, definisi key, jenis join (left,right,dsb)
    * silakan memanggil function set_relation pada function __construct di tiap model yang akan direlasikan
    */
    public function set_relation($relation_name, $p1, $p2, $p3 = null)
    {
        return $this->relation[$relation_name] = [$p1, $p2, $p3];
    }

    /*
    * function with digunakan bila hasil seleksi akan mengikutsertakan data pada table yang berelasi
    * sebelumnya harus didefinisikan dulu dengan function set_relation
    * parameternya: nama relasi 
    */
    public function with($relation_name)
    {
        $rel = $this->relation[$relation_name];
        if ($rel != null) {
            if (isset($rel[2])) {
                $this->db->join($rel[0], $rel[1], $rel[2]);
                return $this;
            } else {
                $this->db->join($rel[0], $rel[1]);
                return $this;
            }
        }
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

    public function where_in($p)
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

    public function or_where_in($p1, $p2)
    {
        return $this->db->or_where_in($p1, $p2);
    }
}
