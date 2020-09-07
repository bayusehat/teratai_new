
<?php
if (!function_exists('history_action')) {
    function history_action($keterangan,$flag,$id)
    {
        $CI =& get_instance();
        $data = [
            'id_user' => $CI->session->userdata('id_user'),
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => $keterangan,
            'flag' => $flag,
            'id' =>  $id
        ];

        $CI->load->model('Log_model');
        $CI->Log_model->log_action($data);
    }
}