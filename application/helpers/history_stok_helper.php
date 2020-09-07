
<?php
if (!function_exists('history_stok')) {
    function history_stok($id_item,$mod,$keterangan,$flag,$id)
    {
        $CI =& get_instance();
        $data = [
            'id_item' => $id_item,
            'mod_stok' => $mod,
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => $keterangan,
            'flag' => $flag,
            'id' =>  $id
        ];

        $CI->load->model('Log_model');
        $CI->Log_model->log_stok($data);
    }
}

