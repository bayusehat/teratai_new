<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ci extends CI_Controller {

    public function cash()
    {
        $c = new grocery_CRUD();

        $c->set_subject('Cash');
        $c->set_table('tb_finance');
        $c->where('tb_finance.deleted',0);
        $c->order_by('id_finance','DESC');
        $c->display_as('id_kategori_finance','Kategori')
          ->display_as('id_bank_account','Bank')
          ->display_as('no_ref','No. Reference');
        $c->set_relation('id_kategori_finance','tb_kategori_finance','nama_kategori_finance',['deleted' => 0])
          ->set_relation('id_bank_account','tb_bank_account','{nama_bank} - {nomor_rekening} - {nama_pemilik_rekening}',['status_bank_account' => 0, 'deleted' => 0]);
        $c->columns('no_ref','id_kategori_finance','tanggal_finance','nominal','status','approved_1','approval');
        $c->fields('no_ref','id_kategori_finance','tanggal_finance','nominal','deskripsi','keterangan');
        $c->required_fields('no_ref','id_kategori_finance','tanggal_finance','nominal','status');
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->unset_texteditor('keterangan','fulltext');
        // $c->callback_insert([$this,'creator']);
        $c->callback_column('approval',[$this,'approval_ci'])
          ->callback_column('nominal',[$this,'nominal'])
          ->callback_column('status',[$this,'status'])
          ->callback_column('approved_1',[$this,'approved']);
        $c->callback_delete([$this,'delete_cash_in']);
        $c->callback_after_insert([$this,'creator']);
        $c->callback_after_update([$this,'editor']);
        $title = 'Data Cash';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    public function delete_cash_in($id)
    {
        $this->db->update('tb_finance',['deleted' => 1],['id_finance' => $id]);
        return history_action('Menghapus Cash in','DCI',$id);
    }

    public function approval_ci($value, $row)
    {
        if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2 && $row->approved_1 == 0){
            $link1 = $row->approved_1 != 0 ? '#' : 'ci/approve_ci_action/'.$row->id_finance;
            $disabled1 = $row->approved_1 != 0 ? 'disabled' : '';
            return '<a href="'.base_url($link1).'" class="btn btn-primary btn-sm btn-block '.$disabled1.'"><i class="fa fa-check"></i> Approved Kepala Toko</a>';
        }else{
            return 'No Action';
        }
    }

    public function approve_ci_action($id)
    {
        $query = $this->db->query('update tb_finance set approved_1 = '.$this->session->userdata('id_user').' ,status = 1 where id_finance = '.$id);
        redirect('ci/cash');
    }

    public function generate($post_array)
    {
        $post_array['creator'] = $this->session->userdata('id_user');

        return $post_array;
    }

    //Callback Column Global

    public function creator($post_array,$id)
    {
      $this->db->update('tb_finance',['creator' => $this->session->userdata('id_user')],['id_finance' => $id]);\
      history_action('Membuat data Cash In baru','CCI',$id);
      return true;
    }

    public function editor($post_array,$id)
    {
      history_action('Merubah data Cash In','UCI',$id);
      return true;
    }

    public function status($value,$row)
    {
        if($row->status == 0){
            return '<div class="label label-warning">Pending</div>';
        }else if($row->status == 1){
            return '<div class="label label-success">Approved</div>';
        }else{
            return '<div class="label label-danger">Rejected</div>';
        }
    }
    public function approved($value,$row){
        $user = $this->db->query("SELECT * FROM tb_user WHERE id_user = $value")->row();
        if($user){
            return $user->nama_user;
        }else{
            return '<div class="label label-danger"><i class="fa fa-times"></i></div>';
        }
    }

    public function nominal($value,$row)
    {
        return number_format($value);
    }

}

/* End of file Ci.php */
