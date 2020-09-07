<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Log_model extends CI_Model {

    public function log_stok($param)
    {
        $sql        = $this->db->insert_string('tb_history_stok',$param);
        $ex         = $this->db->query($sql);
        return $this->db->affected_rows($sql);
    }

    public function log_action($param)
    {
        $query = $this->db->insert_string('tb_history_action',$param);
        $ex    = $this->db->query($query);
        return $this->db->affected_rows($query);
    }

}

/* End of file Log_model.php */
