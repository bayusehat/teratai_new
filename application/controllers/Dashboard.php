<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Item_model');
    }

    public function index()
    {
        if($this->session->userdata('token')){
            $data = [
                'title' => 'Dashboard',
                'wogc'  => true,
            ];
            $this->load->view('dashboard',$data);
        }else{
            $this->load->view('login');
        }
    }

    public function cek_stok_habis()
    {
        $response['data'] = [];
        $query = $this->Item_model->cek_stok_habis();

        foreach ($query as $i => $v) {
            $response['data'][] = [
                ++$i,
                $v['item'],
                $v['gudang'],
                $v['stok'],
                $v['stok_minimum'],
                $v['status'],
            ];
        }

        echo json_encode($response);
    }

}

/* End of file Dashboard.php */
