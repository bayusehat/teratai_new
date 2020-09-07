<?php
if(!defined('BASEPATH')) exit('No direct script allowed');

if(function_exists('log_stok_helper')){
    $CI =& get_instance();
    
    function log_stok_helper($title,$jenis,$deskripsi,$id_item,$mod)
    {
        $data = [
            'judul'           => $title,
            'jenis_transaksi' => $jenis,
            'deskripsi'       => $deskripsi,
            'id_item'         => $id_item,
            'mod_stok'        => $mod,
            'created'         => date('Y-m-d H:i:s')
        ];
        $CI->load->model('Log_model');
        $CI->Log_model->log_stok($data);
    }
}