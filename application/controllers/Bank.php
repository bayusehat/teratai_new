<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bank extends CI_Controller {

    public function __construct()
    {
       parent::__construct();
    }

    public function setor()
    {
        $c = new grocery_CRUD();

        $c->set_subject('Setor');
        $c->set_table('tb_finance');
        $c->where('tb_finance.deleted',0)
          ->where('tb_finance.id_kategori_finance',4);
        $c->order_by('id_finance','DESC');
        $c->display_as('id_kategori_finance','Kategori')
          ->display_as('id_bank_account','Bank')
          ->display_as('no_ref','No. Reference');
        $c->set_relation('id_kategori_finance','tb_kategori_finance','nama_kategori_finance',['deleted' => 0])
          ->set_relation('id_bank_account','tb_bank_account','{nama_bank} - {nomor_rekening} - {nama_pemilik_rekening}',['status_bank_account' => 0, 'deleted' => 0]);
        $c->columns('no_ref','id_kategori_finance','tanggal_finance','id_bank_account','nominal','status','bank_verification_status','approved_1','approval');
        $c->fields('no_ref','id_kategori_finance','tanggal_finance','id_bank_account','nominal','deskripsi','bank_verification_status','keterangan');
        $c->required_fields('no_ref','id_kategori_finance','tanggal_finance','nominal','status','bank_verification_status');
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->unset_texteditor('keterangan','fulltext');
        $c->callback_column('approval',[$this,'approval_bank'])
          ->callback_column('nominal',[$this,'nominal'])
          ->callback_column('status',[$this,'status'])
          ->callback_column('bank_verification_status',[$this,'status_bank_verif'])
          ->callback_column('approved_1',[$this,'approved']);
        $c->callback_field('bank_verification_status',[$this,'bank_verif']);
        $c->callback_after_insert([$this,'creator']);
        $c->callback_after_update([$this,'editor']);
        $c->callback_delete([$this,'delete_bank']);
        $title = 'Data Setor';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    public function penarikan()
    {
        $c = new grocery_CRUD();

        $c->set_subject('Penarikan');
        $c->set_table('tb_finance');
        $c->where('tb_finance.deleted',0)
          ->where('tb_finance.id_kategori_finance',5);
        $c->order_by('id_finance','DESC');
        $c->display_as('id_kategori_finance','Kategori')
          ->display_as('id_bank_account','Bank')
          ->display_as('no_ref','No. Reference');
        $c->set_relation('id_kategori_finance','tb_kategori_finance','nama_kategori_finance',['deleted' => 0])
          ->set_relation('id_bank_account','tb_bank_account','{nama_bank} - {nomor_rekening} - {nama_pemilik_rekening}',['status_bank_account' => 0, 'deleted' => 0]);
        $c->columns('no_ref','id_kategori_finance','tanggal_finance','id_bank_account','nominal','status','bank_verification_status','approved_1','approval');
        $c->fields('no_ref','id_kategori_finance','tanggal_finance','id_bank_account','nominal','deskripsi','bank_verification_status','keterangan');
        $c->required_fields('no_ref','id_kategori_finance','tanggal_finance','nominal','status');
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->unset_texteditor('keterangan','fulltext');
        $c->callback_column('approval',[$this,'approval_bank'])
          ->callback_column('nominal',[$this,'nominal'])
          ->callback_column('status',[$this,'status'])
          ->callback_column('bank_verification_status',[$this,'status_bank_verif'])
          ->callback_column('approved_1',[$this,'approved']);
        $c->callback_field('bank_verification_status',[$this,'bank_verif']);
        $c->callback_after_insert([$this,'creator']);
        $c->callback_after_update([$this,'editor']);
        $c->callback_delete([$this,'delete_bank']);
        $title = 'Data Penarikan';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    public function transfer()
    {
        $c = new grocery_CRUD();

        $c->set_subject('Transfer');
        $c->set_table('tb_finance');
        $c->where('tb_finance.deleted',0)
          ->where('tb_finance.id_kategori_finance',6);
        $c->order_by('id_finance','DESC');
        $c->display_as('id_kategori_finance','Kategori')
          ->display_as('id_bank_account','Bank Asal')
          ->display_as('no_ref','No. Reference')
          ->display_as('id_bank_account_tujuan','Bank Tujuan (Internal)')
          ->display_as('nama_bank','Nama Bank (External)')
          ->display_as('no_rekening_bank','Bank Tujuan (External)')
          ->display_as('nama_pemilik_rekening','Bank Tujuan (External)');
        $c->set_relation('id_kategori_finance','tb_kategori_finance','nama_kategori_finance',['deleted' => 0])
          ->set_relation('id_bank_account','tb_bank_account','{nama_bank} - {nomor_rekening} - {nama_pemilik_rekening}',['status_bank_account' => 0, 'deleted' => 0])
          ->set_relation('id_bank_account_tujuan','tb_bank_account','{nama_bank} - {nomor_rekening} - {nama_pemilik_rekening}',['status_bank_account' => 0, 'deleted' => 0]);
        $c->columns('no_ref','id_kategori_finance','tanggal_finance','id_bank_account','nominal','status','bank_verification_status','approved_1','approval');
        $c->fields('no_ref','id_kategori_finance','tanggal_finance','jenis_tansfer','id_bank_account','id_bank_account_tujuan','nama_bank','no_rekening_bank','nama_pemilik_rekening','nominal','deskripsi','keterangan','bank_verification_status');
        $c->required_fields('no_ref','id_kategori_finance','tanggal_finance','nominal','bank_verification_status');
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->unset_texteditor('keterangan','fulltext');
        // $c->callback_insert([$this,'creator']);
        $c->callback_column('approval',[$this,'approval_bank'])
          ->callback_column('nominal',[$this,'nominal'])
          ->callback_column('status',[$this,'status'])
          ->callback_column('bank_verification_status',[$this,'status_bank_verif'])
          ->callback_column('approved_1',[$this,'approved']);
        $c->callback_field('bank_verification_status',[$this,'bank_verif']);
        $c->callback_after_insert([$this,'creator']);
        $c->callback_after_update([$this,'editor']);
        $c->callback_delete([$this,'delete_bank']);
        $title = 'Data Transfer';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    public function delete_bank($id)
    { 
      history_action('Menghapus data Bank','DBNK',$id);
      return $this->db->update('tb_finance',['deleted' => 1],['id_finance' => $id]);
    }

    public function approval_bank($value, $row)
    {
        if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2){
          if($row->approved_1 == 0){
              $link1 = $row->approved_1 != 0 ? '#' : 'bank/approve_bank_action/'.$row->id_kategori_finance.'/'.$row->id_finance;
              $disabled1 = $row->approved_1 != 0 ? 'disabled' : '';
              return '<a href="'.base_url($link1).'" class="btn btn-primary btn-sm btn-block '.$disabled1.'"><i class="fa fa-check"></i> Approved Kepala Toko</a>';
          }else{
              return 'Telah diverifikasi';
          }
        }else{
            return 'No Action';
        }
    }

    public function approve_bank_action($cat,$id)
    {
        $query = $this->db->query('update tb_finance set approved_1 = '.$this->session->userdata('id_user').' ,status = 1 where id_finance = '.$id);
        if($cat == 4){
          redirect('bank/setor');
        }else if($cat == 5){
          redirect('bank/penarikan');
        }else{
          redirect('bank/transfer');
        }
    }

    //Global Callback 
    public function creator($post_array,$id)
    {
      $this->db->update('tb_finance',['creator' => $this->session->userdata('id_user')],['id_finance' => $id]);\
      history_action('Membuat data Bank baru','CBNK',$id);
      return true;
    }

    public function editor($post_array,$id)
    {
      history_action('Merubah data Bank','EBNK',$id);
      return true;
    }

    public function bank_verif($value,$row)
    {
        if($value == ''){
           $slctd = '';
           $slctd1= '';
        }else if($value == 0){
           $slctd = 'selected';
           $slctd1= '';
        }else{
           $slctd1 = 'selected';
           $slctd  = '';
        }
        $input = '
                <select class="form-control chosen-select" name="bank_verification_status" id="bank_verification_status">
                    <option value="0" '.$slctd.'>Pending</option>
                    <option value="1" '.$slctd1.'>Verified</option>
                </select>';
        return $input;
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

    public function status_bank_verif($value,$row)
    {
        if($row->status == 0){
            return '<div class="label label-warning">Pending</div>';
        }else{
            return '<div class="label label-success">Verified</div>';
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

    function nominal($value, $row){
        return number_format($value);
    }
}

/* End of file Bank.php */
