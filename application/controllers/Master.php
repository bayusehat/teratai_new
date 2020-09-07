<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('token')){
            redirect('dashboard','refresh');
         }
    }
    //Gudang
    public function gudang()
    {
	    $access_url = 'master/gudang';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Gudang');
        $c->set_table('tb_gudang');
        $c->where('deleted',0);
        $c->order_by('jenis_gudang','DESC');
        $c->required_fields(
            'nama_gudang','jenis_gudang','status_gudang'
        );
        $c->field_type('status_gudang','dropdown',[
            '0' => 'Active',
            '1' => 'Non-Active'
        ]);
        $c->field_type('jenis_gudang','dropdown',[
            '0' => 'Gudang',
            '1' => 'Toko'
        ]);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        
        $c->callback_column('jenis_gudang',[$this,'valJenisGudang']);
        $c->callback_column('status_gudang',[$this,'valStatusGudang']);
        $c->callback_after_insert([$this,'creator_gudang']);
        $c->callback_after_update([$this,'editor_gudang']);
        $c->callback_delete([$this,'delete_gudang']);
        $title = 'Data Gudang';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function valJenisGudang($value,$row)
    {
        if($row->jenis_gudang == 0){
            $valjg = 'Gudang';
        }else{
            $valjg = 'Toko';
        }

        return $valjg;
    }

    public function valStatusGudang($value,$row)
    {
        if($row->status_gudang == 0){
            $valsg = 'Active';
        }else{
            $valsg = 'Non-Active';
        }

        return $valsg;
    }

    public function creator_gudang($post_array,$primary_key)
    {
        return history_action('Membuat data gudang baru','CGD',$primary_key);
    }

    public function editor_gudang($post_array,$primary_key)
    {
        return history_action('Memperbarui data gudang','UGD',$primary_key);
    }

    public function delete_gudang($primary_key)
    {
        history_action('Menghapus data gudang','DGD',$primary_key);
        return $this->db->update('tb_gudang',['deleted' => 1],['id_gudang' => $primary_key]);

    }
    //End Gudang

    //Bank Account
    public function bank_account()
    {
	    $access_url = 'master/bank_account';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Bank Account');
        $c->set_table('tb_bank_account');
        $c->where('deleted',0);
        $c->order_by('id_bank_account','ASC');
        $c->required_fields(
            'nama_bank','nomor_rekening','nama_pemilik_rekening','status_bank_account'
        );
        $c->field_type('status_bank_account','dropdown',[
            '0' => 'Active',
            '1' => 'Non-Active'
        ]);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        
        $c->callback_column('status_bank_account',[$this,'valStatusBankAccount']);
        $c->callback_after_insert([$this,'creator_bank_account']);
        $c->callback_after_update([$this,'editor_bank_account']);
        $c->callback_delete([$this,'delete_bank_account']);
        $title = 'Data Bank Account';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function valStatusBankAccount($value,$row)
    {
        if($row->status_bank_account == 0){
            $valsg = 'Active';
        }else{
            $valsg = 'Non-Active';
        }

        return $valsg;
    }

    public function creator_bank_account($post_array,$id)
    {
        return history_action('Membuat data Bank Account baru','CBA',$id);
    }

    public function editor_bank_account($post_array,$id)
    {
        return history_action('Memperbarui data Bank Account','UBA',$id);
    }

    public function delete_bank_account($id)
    {
        history_action('Menghapus data Bank Account','DBA',$id);
        return $this->db->update('tb_bank_account',['deleted' => 1],['id_bank_account' => $id]);
    }
    //End Bank Account
    
    //Diskon
    public function diskon()
    {
	    $access_url = 'master/diskon';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Diskon');
        $c->set_table('tb_diskon');
        $c->where('deleted',0);
        $c->order_by('id_diskon','ASC');
        $c->required_fields(
            'nama_diskon','jenis_diskon','status_diskon'
        );
        $c->field_type('status_diskon','dropdown',[
            '0' => 'Active',
            '1' => 'Non-Active'
        ]);
        $c->field_type('jenis_diskon','dropdown',[
            '0' => 'Nominal',
            '1' => 'Persen'
        ]);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        
        $c->callback_column('status_diskon',[$this,'valStatusDiskon']);
        $c->callback_column('jenis_diskon',[$this,'valJenisDiskon']);
        $c->callback_after_insert([$this,'creator_diskon']);
        $c->callback_after_update([$this,'editor_diskon']);
        $c->callback_delete([$this,'delete_diskon']);
        $title = 'Data Diskon';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function valStatusDiskon($value,$row)
    {
        if($row->status_diskon == 0){
            $valsg = 'Active';
        }else{
            $valsg = 'Non-Active';
        }

        return $valsg;
    }

    public function valJenisDiskon($value,$row)
    {
        if($row->jenis_diskon == 0){
            $valsg = 'Nominal';
        }else{
            $valsg = 'Persen';
        }

        return $valsg;
    }

    public function creator_diskon($post_array,$id)
    {
        return history_action('Membuat data diskon baru','CDS',$id);
    }
    
    public function editor_diskon($post_array,$id)
    {
        return history_action('Memperbarui data Diskon','UDS',$id);
    }

    public function delete_diskon($id)
    {
        history_action('Menghapus data Diskon','DDS',$id);
        return $this->db->update('tb_diskon',['deleted' => 1],['id_diskon' => $id]);
    }
    //End Diskon
    //Logistik
    public function logistik()
    {
		 $access_url = 'master/logistik';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }   
	    
        $c = new grocery_CRUD();
        $c->set_subject('Logistik');
        $c->set_table('tb_logistik');
        $c->where('deleted',0);
        $c->order_by('id_logistik','ASC');
        $c->required_fields(
            'jenis_logistik','nama_perusahaan_logistik','jenis_kendaraan_logistik',
            'rule_logistik','npwp_logistik','alamat_logistik','no_telp_logistik',
            'bank_logistik','no_rekening_logistik','nama_pemilik_rekening_logistik'
        );
        $c->field_type('jenis_logistik','dropdown',[
            '0' => 'Internal',
            '1' => 'External'
        ]);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        $c->unset_texteditor('alamat_logistik');
        
        $c->callback_column('jenis_logistik',[$this,'valJenisLogistik']);
        $c->callback_after_insert([$this,'creator_logistik']);
        $c->callback_after_update([$this,'editor_logistik']);
        $c->callback_delete([$this,'delete_logistik']);
        $title = 'Data Logistik';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function valJenisLogistik($value,$row)
    {
        if($row->jenis_logistik == 0){
            $valsg = 'Internal';
        }else{
            $valsg = 'External';
        }

        return $valsg;
    }

    public function creator_logistik($post_array,$id){
        return history_action('Membuat data logistik','CLG',$id);
    }

    public function editor_logistik($post_array,$id)
    {
        return history_action('Memperbarui data logistik','ULG',$id);
    }

    public function delete_logistik($id)
    {
        $this->db->update('tb_logistik',['deleted' => 1],['id_logistik' => $id]);
        return history_action('Menghapus data logistik','DLG',$id);
    }
    //End Logistik

    //Metode Pembayaran
    public function metode_pembayaran()
    {
	    $access_url = 'master/metode_pembayaran';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Metode Pembayaran');
        $c->set_table('tb_metode_pembayaran');
        $c->where('deleted',0);
        $c->order_by('id_metode_pembayaran','ASC');
        $c->required_fields(
           'nama_metode_pembayaran'
        );
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted','default_payment');
        
        $c->unset_read();
        
        $c->callback_column('default_payment',[$this,'default_payment']);
        $c->callback_after_insert([$this,'creator_metode_pembayaran']);
        $c->callback_after_update([$this,'editor_metode_pembayaran']);
        $c->callback_delete([$this,'delete_metode_pembayaran']);
        $title = 'Data Metode Pembayaran';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function default_payment($value,$row)
    {
        if($row->default_payment == 1){
            return 'Yes';
        }else{
            return 'No';
        }
    }

    public function creator_metode_pembayaran($post_array,$id)
    {
        return history_action('Membuat data metode pembayaran','CMP',$id);
    }

    public function editor_metode_pembayaran($post_array,$id)
    {
        return history_action('Memperbarui data metode pembayaran','UMP',$id);
    }

    public function delete_metode_pembayaran($id)
    {
        $this->db->update('tb_metode_pembayaran',['deleted' => 1],['id_metode_pembayaran' => $id]);
        return history_action('Menghapus data metode pembayaran','DMP',$id);
    }
    //End Metode Pembayaran

    //Customer
    public function customer()
    {
	    $access_url = 'master/customer';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Customer');
        $c->set_table('tb_customer');
        $c->where('deleted',0);
        $c->order_by('id_customer','ASC');
        $c->required_fields(
           'nama_customer','perusahaan_customer','jenis_identitas_customer',
           'no_identitas_customer','alamat_customer','no_telp_customer'
        );
        $c->field_type('jenis_identitas_customer','dropdown',[
           'KTP',
           'SIM',
           'PASSPORT'
        ]);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        $c->unset_texteditor('alamat_customer');
        
        $c->callback_after_insert([$this,'creator_customer']);
        $c->callback_after_update([$this,'editor_customer']);
        $c->callback_delete([$this,'delete_customer']);
        $title = 'Data Customer';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function creator_customer($post_array,$id)
    {
        return history_action('Membuat data customer','CCM',$id);
    }

    public function editor_customer($post_array,$id)
    {
        return history_action('Memperbarui data customer','UCM',$id);
    }

    public function delete_customer($id)
    {
        $this->db->update('tb_customer',['deleted' => 1],['id_customer' => $id]);
        return history_action('Menghapus data customer','DCM',$id);
    }
    //End Customer

    //User
    public function User()
    {
	    $access_url = 'master/user';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('User');
        $c->set_table('tb_user');
        $c->where('tb_user.deleted',0);
        $c->order_by('id_user','ASC');
        $c->required_fields(
           'username',
           'password',
           'id_jabatan'
        );
        $c->field_type('password', 'password');
        $c->set_field_upload('foto_profil','assets/uploads/user');
        $c->set_field_upload('foto_ktp','assets/uploads/user');
        $c->set_field_upload('foto_kk','assets/uploads/user');
        $c->display_as('id_jabatan','Jabatan');
        $c->set_relation('id_jabatan','tb_jabatan','nama_jabatan');
        $c->columns('username','nama_user','id_jabatan','foto_profil','foto_ktp','foto_kk','reset');
        $c->unset_columns('created','updated','deleted','password');
        $c->unset_add_fields('created','updated','deleted');
        $c->unset_edit_fields('password','created','updated','deleted');
        $c->callback_column('reset',[$this,'btnReset']);
        $c->unset_read();
        
        $c->callback_before_insert([$this,'encrypt_password_user']);
        // $c->callback_column('foto_profil',[$this,'thumbnail'])
        //   ->callback_column('foto_ktp',[$this,'thumbnail'])
        //   ->callback_column('foto_kk',[$this,'thumbnail']);
        $c->callback_after_insert([$this,'creator_user']);
        $c->callback_after_update([$this,'editor_user']);
        $c->callback_delete([$this,'delete_user']);
        $title = 'Data User';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function btnReset($value,$row)
    {
        return '<a href="'.base_url('master/resetPassword/'.$row->id_user).'" class="btn btn-warning btn-sm"><i class="fa fa-refresh"></i> Reset Password</a>';
    }

    public function encrypt_password_user($post_array)
    {
        $post_array['password'] = sha1($post_array['password']);

        return $post_array;
    }

    public function creator_user($post_arraym,$id)
    {
        return history_action('Membuat data user','CUS',$id);
    }

    public function editor_user($post_array,$id)
    {
        return history_action('Memperbarui data user','UUS',$id);
    }

    // public function thumbnail($value,$row)
    // {
    //     return '<img src"'.base_url('assets/uploads/user/'.$value).'" style="width:100px">';
    // }

    public function delete_user($id)
    {
        $this->db->update('tb_user',['deleted' => 1],['id_user' => $id]);
        return history_action('Menghapus data user','DUS',$id);
    }
    //End User

    //Role
    public function jabatan()
    {
	    $access_url = 'master/jabatan';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Jabatan');
        $c->set_table('tb_jabatan');
        $c->where('deleted',0);
        $c->order_by('id_jabatan','ASC');
        $c->required_fields(
           'nama_jabatan'
        );
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->add_action('Access', '', 'master/to_access', 'fa-key');
        $c->unset_delete();
        $c->unset_add();
        $c->unset_read();
        $c->unset_edit();
        $c->callback_after_insert([$this,'creator_jabatan']);
        $c->callback_after_update([$this,'editor_jabatan']);
        $c->callback_delete([$this,'delete_jabatan']);
        $title = 'Data Jabatan';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function creator_jabatan($post_array,$id)
    {
        return history_action('Membuat data jabatan','CJB',$id);
    }

    public function editor_jabatan($post_array,$id)
    {
        return history_action('Memperbarui data jabatan','UJB',$id);
    }

    public function delete_jabatan($id)
    {
        $this->db->update('tb_jabatan',['deleted' => 1],['id_jabatan' => $id]);
        return history_action('Menghapus data jabatan','DJB',$id);
    }

    public function to_access($id)
    {
        $menu = $this->db->query("select * from tb_menu")->result();
        $role = $this->db->query("select * from tb_jabatan where id_jabatan = ".$id)->row();
        $data = [
            'title' => 'Role Access Menu <strong>'.$role->nama_jabatan.'</strong>',
            'wogc' => true,
            'menu' => $menu,
            'role' => $role
        ];
        $this->load->view('pengaturan/access',$data);
    }
    //End Role

    //Menu
    public function menu()
    {
	    $access_url = 'master/menu';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Menu');
        $c->set_table('tb_menu');
        $c->order_by('id_menu','ASC');
        $c->required_fields(
           'nama_menu','url_menu'
        );
        $c->set_relation('menu_parent','tb_menu','nama_menu',array('menu_parent' => 0));
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        
        $c->callback_after_insert([$this,'creator_menu']);
        $c->callback_after_update([$this,'editor_menu']);
        $c->callback_column('tb_menu.nama_menu', array($this, 'menu_indent'));
        $title = 'Data Menu';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function creator_menu($post_array,$id)
    {
        return history_action('Membuat data menu','CMN',$id);
    }

    public function editor_menu($post_array,$id)
    {
        return history_action('Memperbarui data menu','UMN',$id);
    }
    
    function menu_indent($value, $row){
	    $indent = $row->menu_parent > 0 ? ' ---' : '';
	    return $indent.$value;
    }
    //End Menu

    //Supplier
    public function supplier()
    {
	    $access_url = 'master/supplier';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
	    
        $c = new grocery_CRUD();
        $c->set_subject('Supplier');
        $c->set_table('tb_supplier');
        $c->where('deleted',0);
        $c->order_by('id_supplier','ASC');
        $c->required_fields(
           'nama_supplier','jenis_barang_supplier','perusahaan_supplier','jenis_identitas_supplier',
           'no_identitas_supplier','alamat_supplier','no_telp_supplier'
        );
        $c->field_type('jenis_identitas_supplier','dropdown',[
           'KTP',
           'SIM',
           'PASSPORT'
        ]);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        $c->unset_texteditor('alamat_supplier');
        
        $c->callback_after_insert([$this,'creator_supplier']);
        $c->callback_after_update([$this,'editor_supplier']);
        $c->callback_delete([$this,'delete_supplier']);
        $title = 'Data Supplier';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function creator_supplier($post_array,$id)
    {
        return history_action('Membuat data supplier','CSP',$id);
    }

    public function editor_supplie($post_array,$id)
    {
        return history_action('Memperbarui data supplier','USP',$id);
    }

    public function delete_supplier($id)
    {
        $this->db->update('tb_supplier',['deleted' => 1],['id_supplier' => $id]);
        return history_action('Menghapus data supplier','DSP',$id);
    }
    //End Supplier

    //Barang
    public function item()
    {
	    $access_url = 'master/item';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Item');
        $c->set_table('tb_item');
        $c->where('tb_item.deleted',0);
        $c->order_by('id_item','ASC');
        $c->required_fields(
           'nama_item','harga_modal'
        );
        $c->display_as('id_item_kategori','Item Kategori');
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        
        $c->set_relation('id_item_kategori','tb_item_kategori','nama_item_kategori',['tb_item_kategori.deleted' => 0]);
        $c->callback_column('harga_modal',[$this,'nominal'])
          ->callback_column('harga_jual',[$this,'nominal']);
        $c->callback_after_insert([$this,'creator_item']);
        $c->callback_after_update([$this,'editor_item']);
        $c->callback_delete([$this,'delete_item']);
        $title = 'Data Barang';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function creator_item($post_array,$id)
    {
        return history_action('Membuat data item','CIT',$id);
    }

    public function editor_item($post_array,$id)
    {
        return history_action('Memperbarui data item','UIT',$id);
    }

    public function delete_item($id)
    {
        $this->db->update('tb_item',['deleted' => 1],['id_item' => $id]);
        return history_action('Menghapus data item','DIT',$id);
    }
    //End Barang
    
    //Redline
    public function redline()
    {
	    $access_url = 'master/redline';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Redline');
        $c->set_table('tb_redline');
        $c->where('tb_redline.deleted',0);
        $c->order_by('id_redline','ASC');
        $c->required_fields(
           'id_item','id_gudang','stok_minimum'
        );
        $c->set_relation('id_item','tb_item','nama_item',['deleted' => 0])
          ->set_relation('id_gudang','tb_gudang','nama_gudang',['deleted' => 0]);
        $c->display_as('id_item','Item')
          ->display_as('id_gudang','Gudang');
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        
        $c->unset_read();
        
        
        $c->callback_after_insert([$this,'creator_redline']);
        $c->callback_after_update([$this,'editor_redline']);
        $c->callback_delete([$this,'delete_redline']);
        $title = 'Data Redline';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function creator_redline($post_array,$id)
    {
        return history_action('Membuat data redline','CRL',$id);
    }

    public function editor_redline($post_array,$id)
    {
        return history_action('Memperbarui data redline','URL',$id);
    }

    public function delete_redline($id)
    {
        $this->db->update('tb_redline',['deleted' => 1],['id_redline' => $id]);
        return history_action('Menghapus data redline','DRL',$id);
    }
    //End Redline

    //Kategori Finance
    public function kategori_finance()
    {
	    $access_url = 'master/kategori_finance';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Kategori Finance');
        $c->set_table('tb_kategori_finance');
        $c->where('tb_kategori_finance.deleted',0);
        $c->order_by('id_kategori_finance','ASC');
        $c->required_fields(
           'nama_kategori_finance'
        );
        $c->display_as('nama_kategori_finance','Kategori Finance');
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->callback_after_insert([$this,'kategori_finance_creator']);
        $c->callback_after_update([$this,'kategori_finance_editor']);
        $c->callback_delete([$this,'delete_kategori_finance']);
        $title = 'Data Kategori Finance';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function kategori_finance_creator($post_array,$id)
    {
        return history_action('Membuat data kategori finance','CKF',$id);
    }

    public function kategori_finance_editor($post_array,$id)
    {
        return history_action('Memperbarui data kategori finance','UKF',$id);
    }

    public function delete_kategori_finance($id)
    {
        $this->db->update('tb_kategori_finance',['deleted' => 1],['id_kategori_finance' => $id]);
        return history_finance('Menghapus data kategori finance','DKF',$id);
    }
    //End Kategori Finance

    //Kategori Item
    public function kategori_item()
    {
	    $access_url = 'master/kategori_item';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('Kategori Item');
        $c->set_table('tb_item_kategori');
        $c->where('tb_item_kategori.deleted',0);
        $c->order_by('id_item_kategori','ASC');
        $c->required_fields(
           'nama_item_kategori'
        );
        $c->display_as('nama_item_kategori','Kategori Item');
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_read();
        
        $c->callback_after_insert([$this,'kategori_item_creator']);
        $c->callback_after_update([$this,'kategori_item_editor']);
        $c->callback_delete([$this,'delete_kategori_item']);
        $title = 'Data Kategori Item';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function kategori_item_creator($post_array,$id)
    {
        return history_action('Membuat data ketegori item','CKI',$id);
    }

    public function kategori_item_editor($post_array,$id)
    {
        return history_action('Memperbarui data kategori item','UKI',$id);
    }

    public function delete_kategori_item($id)
    {
        $this->db->update('tb_item_kategori',['deleted' => 1],['id_item_kategori' => $id]);
        return history_action('Menghapus data kategori item','DKI',$id);
    }
    //End Kategori item

    //Begin History Action
    public function history_action()
    {
	    $access_url = 'master/history_action';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
        $c->set_subject('History');
        $c->set_table('tb_history_action');
        // $c->where('tb_hsit.deleted',0);
        $c->order_by('tanggal','DESC');
        $c->display_as('id_user','User')
          ->display_as('id','ID Action');
        $c->set_relation('id_user','tb_user','nama_user');
        $c->unset_add();
        $c->unset_read();
        $c->unset_delete();
        $c->unset_edit();
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $title = 'History Action';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }
    //End History Action

    //Callback Column
    public function nominal($value,$row)
    {
        return number_format($value);
    }

    public function resetPassword($id)
    {
        $query = $this->db->query("UPDATE tb_user SET password = '".sha1('teratai123')."' WHERE id_user = $id");
        if($query){
            $this->session->set_flashdata('success','Berhasil mereset password user!');
            return redirect('master/user');
        }else{
            $this->session->set_flashdata('failed','Gagal mereset password user!');
            return redirect('master/user');
        }
    }
}

/* End of file MasterController.php */
