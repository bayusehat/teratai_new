<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function doLogin()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');

        $check = $this->db->where('username',$username)
                          ->where('password',sha1($password))
                          ->get('tb_user');
        if($check->num_rows() > 0){
            $data = $check->row();
            $session = [
                'id_user'    => $data->id_user,
                'username'   => $data->username,
                'nama'       => $data->nama_user,
                'id_jabatan' => $data->id_jabatan,
                'token'      => rand()
            ];
            $this->session->set_userdata($session);
            history_action('User login '.$data->username,'LGN','');
            redirect('dashboard','refresh');  
        }else{
            $this->session->set_flashdata('failed','Login gagal! user tidak ditemukan');
            redirect('dashboard','refresh');
        }
    }

    public function doLogout()
    {
        $data = $this->db->query("SELECT * FROM tb_user WHERE id_user = ".$this->session->userdata('id_user'))->row();
        $arr = [
            'token',
            'id_user'
        ];

        $this->session->unset_userdata($arr);
        // history_action('User logout '.$data->username,'LGT','');
        redirect('dashboard');
    }

    public function change_password()
    {
        $data = [
            'title' => 'Ganti Password',
            'wogc' => true
        ];
        $this->load->view('pengaturan/ganti_password',$data);
    }

    public function doChangePassword()
    {
        $pass_lama   = $this->input->post('pass_lama');
        $c_pass_lama = $this->input->post('c_pass_lama');
        $pass_baru   = $this->input->post('pass_baru');

        if(strcasecmp($pass_lama,$c_pass_lama) === 0){
            $check = $this->db->query("select * from tb_user where password = '".sha1($pass_lama)."'");
            if($check->num_rows() > 0){
                $this->db->update('tb_user',['password' => sha1($pass_baru)],['id_user' => $this->session->userdata('id_user')]);
                $this->session->set_flashdata('success','Berhasil memperbarui Password!');
                history_action('Ganti password User','GPW','');
                redirect('user/change_password');
            }else{
                $this->session->set_flashdata('failed','Password yang anda masukkan salah!');
                redirect('user/change_password');
            }
        }else{
            $this->session->set_flashdata('failed','Password lama dan Konfirmasi password tidak sama!');
            redirect('user/change_password');
        }
    }

    public function haveAccess($id_menu,$id_role)
    {
        $check = $this->db->where('id_jabatan',$id_role)
                          ->where('id_menu',$id_menu)
                          ->get('tb_user_access');
        $role = $this->db->where('id_jabatan',$id_role)
                          ->get('tb_jabatan')
                          ->row();
        $menu = $this->db->where('id_menu',$id_menu)
                          ->get('tb_menu')
                          ->row();
        if($check->num_rows() > 0){
            $do = $this->db->query("delete from tb_user_access where id_jabatan = '$id_role' and id_menu = '$id_menu'");
            $message = 'Access deleted untuk role '.$role->nama_jabatan.' pada menu '.$menu->nama_menu;
            history_action('Menghapus access untuk jabatan '.$role->nama_jabatan.' menu '.$menu->nama_menu,'DAC','');
        }else {
            $do = $this->db->insert('tb_user_access',[
                'id_menu' => $id_menu,
                'id_jabatan' => $id_role
            ]);
            $message = 'Access created untuk role '.$role->nama_jabatan.' pada menu '.$menu->nama_menu;
            history_action('Membuat access untuk jabatan '.$role->nama_jabatan.' menu '.$menu->nama_menu,'CAC','');
        }

        if($do){
            $data = [
                'pesan' => $message
            ];
            echo json_encode($data);
        }else{
            $data = [
                'pesan' => 'Terjadi kesalahan!'
            ];
            echo json_encode($data);
        }
    }
}

/* End of file User.php */
