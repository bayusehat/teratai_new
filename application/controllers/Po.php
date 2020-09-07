<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Po extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('token')){
            redirect('dashboard','refresh');
         }
        date_default_timezone_set('Asia/Jakarta');
    }

    public function no_po($tipe)
    {
        $this->db->select('no_po', FALSE);
        $this->db->order_by('id_pemesanan','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('tb_pemesanan');   
        if($query->num_rows() <> 0){      
            $num = $query->row();
            $data = str_split($num->no_po,9);      
            $kode = $data[1] + 1;
        }
        else{      
            $kode = 1;
        } 
        if($kode > 9){
            $kode = $kode;
        }else{
            $kode = '0'.$kode;
        }
        $nomor_jual_max = date('ymd').$kode;   
        $nomor_tampil = $tipe."-".$nomor_jual_max;
        return $nomor_tampil;
    }

    //search item
    public function search_item()
    {
        $return = '';
        $item = $this->input->post('item');
        $query = $this->db->query("
            SELECT * FROM tb_item WHERE nama_item like '%$item%' OR sku_item like '%$item%'
        ")->result();

        if(!empty($query)){
            foreach($query as $a){
                $return .= "<li>
                                <a href='javascript:void(0);' onclick='addToTable($a->id_item,\"".addslashes($a->nama_item)."\",$a->harga_jual)'>$a->nama_item</a>
                            </li>";
            }
        }else{
            $return .= '<li>Item tidak ditemukan!</li>';
        }

        echo $return;
    }
    //Purchase Order
    public function purchase_order()
    {
	    $access_url = 'po/purchase_order';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

        $c->set_subject('Purchase Order');
        $c->set_table('tb_pemesanan');
        $c->where('tb_pemesanan.deleted',0);
        $c->order_by('tb_pemesanan.created','DESC');
        $c->display_as('id_supplier','Supplier')
          ->display_as('id_gudang','Gudang')
          ->display_as('id_bank_account','Bank Account')
          ->display_as('no_po','No. PO')
          ->display_as('created','Tgl. Nota')
          ->display_as('approved_1','Approved By')
          ->display_as('approval','Action');
        $c->set_relation('id_supplier','tb_supplier','nama_supplier',['deleted' => 0])
          ->set_relation('id_gudang','tb_gudang','nama_gudang',['deleted' => 0])
          ->set_relation('id_bank_account','tb_bank_account','{nama_bank} - {nomor_rekening} - {nama_pemilik_rekening}',['status_bank_account' => 0, 'deleted' => 0]);
        $c->columns('no_po','created','id_supplier','status','grand_total','approved_1','approval');
        $c->unset_columns('updated','deleted','status_lunas','creator','tanggal_jatuh_tempo',
    'tanggal_pelunasan');
        $c->unset_fields('created','updated','deleted');
        $c->unset_add();
        $c->unset_read();
        $c->unset_edit();
        $c->unset_delete();
        
            $c->add_action('Edit','','po/purchase_order_edit','fa-pencil');

       	if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2){
	        $c->add_action('Tambah Retur Pembelian','','retur_pembelian/create','fa-file');
	    }
        
        if($this->session->userdata('id_jabatan') == 1)
	        $c->add_action('Delete','','po/delete_purchase_order','fa-trash');
        
        //$c->add_action('Nota Pembelian','','po/load_paper','fa-file');
        $c->callback_column('status',[$this,'status_approved'])
          ->callback_column('grand_total',[$this,'nominal'])
          ->callback_column('approval',[$this,'approval_po'])
          ->callback_column('approved_1',[$this,'approved'])
          ->callback_column('approved_2',[$this,'approved']);
        // $c->callback_delete([$this,'delete_purchase_order']);
        $title = 'Data Purchase Order';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function delete_purchase_order($id)
    {
        $po = $this->db->query("SELECT * FROM TB_PEMESANAN WHERE ID_PEMESANAN = $id")->row();
        //Cek GI
        $cekgi = $this->db->query("SELECT * FROM TB_ITEM_MASUK_DETAIL A LEFT JOIN TB_ITEM_MASUK B ON A.ID_ITEM_MASUK = B.ID_ITEM_MASUK WHERE ID_PEMESANAN = $id AND B.DELETED = 0")->result();
        
        if(count($cekgi) == 0){
            //Cek Retur
            $cekretur = $this->db->query("SELECT * FROM TB_RETUR_PEMBELIAN WHERE ID_PEMESANAN = $id AND DELETED = 0")->result();
            if(count($cekretur) == 0){
                history_action('Menghapus Purchase Order','DPO',$id);
                $this->db->update('tb_pemesanan',['deleted' => 1],['id_pemesanan' => $id]);
                $this->session->set_flashdata('success','Purchase Order berhasil dihapus!');
                return redirect('po/purchase_order','refresh');
            }else{
                $this->session->set_flashdata('failed','Purchase Order '.$po->no_po.' terdapat data Retur, untuk menghapus Purchase Order silahkan hapus data Retur terlebih dahulu!');
                return redirect('po/purchase_order','refresh');
            }
        }else{
            $this->session->set_flashdata('failed','Purchase Order '.$po->no_po.' terdapat data Goods In, untuk menghapus Purchase Order silahkan hapus data Goods In terlebih dahulu!');
            return redirect('po/purchase_order','refresh');
            
        }
    }

    public function status_approved($value,$row)
    {
        if($row->approved_1 != 0){
            return '<div class="label label-success">Approved</div>';
        }else{
            return '<div class="label label-danger">Belum ada Approval</div>';
        }
    }

    public function approval_po($value,$row)
    {
        if($row->approved_1 == ''){
            if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2 || $row->approved_1 == 0){
                $link1 = $row->approved_1 != 0 ? '#' : 'po/approve_po_action/1/'.$row->id_pemesanan;
                // $link2 = $row->approved_2 != 0 ? '#' : 'po/approve_po_action/2/'.$row->id_pemesanan;
                $disabled1 = $row->approved_1 != 0 ? 'disabled' : '';
                // $disabled2 = $row->approved_2 != 0 ? 'disabled' : '';
                return '<a href="'.base_url($link1).'" class="btn btn-primary btn-sm btn-block '.$disabled1.'"><i class="fa fa-check"></i> Klik untuk Approve</a>';
            }else{
                return 'No Action';
            }
        }else{
            return 'No Action';
        }
        
    }

    public function approve_po_action($tipe,$id)
    {
        if($tipe == 1){
            $query = $this->db->query('UPDATE tb_pemesanan SET approved_1 = '.$this->session->userdata('id_user').' where id_pemesanan =  '.$id);
            history_action('Approve Purchase Order','APO',$id);
            redirect('po/purchase_order');
        }
    }
    
    //Create Page Purchase Order
    public function purchase_order_create()
    {
        $data = [
            'title' => 'Purchase Order',
            'wogc' => true,
            'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            'supplier' => $this->db->query('select id_supplier,nama_supplier from tb_supplier where deleted = 0')->result(),
            'bank' => $this->db->query('select id_bank_account,nama_bank,nomor_rekening,nama_pemilik_rekening from tb_bank_account where deleted = 0 and status_bank_account = 0')->result(),
            'customer' =>  $this->db->query('select id_customer,nama_customer from tb_customer where deleted = 0')->result(),
            'metode_pembayaran' => $this->db->query('select id_metode_pembayaran,nama_metode_pembayaran from tb_metode_pembayaran where deleted = 0')->result()
        ];

        $this->load->view('transaksi/purchase_order_create',$data);
    }

    //Insert Purchase Order
    public function purchase_order_insert()
    {
        // $detail = [];
        $total  = 0;
        $total_diskon = 0;

        $this->form_validation->set_rules('tipe_item','Tipe Item','required');
        $this->form_validation->set_rules('id_supplier','Supplier','required');
        $this->form_validation->set_rules('status','Status','required');

        $data = [
            'no_po'               => $this->no_po($this->input->post('tipe_item')),
            'tipe_item'           => $this->input->post('tipe_item'),
            'id_supplier'         => $this->input->post('id_supplier'),
            'ppn'                 => $this->input->post('ppn'),
            'creator'             => $this->session->userdata('id_user'),
            'status'              => $this->input->post('status'),
            'id_bank_account'     => $this->input->post('id_bank_account'),
            'tanggal_jatuh_tempo' => $this->input->post('tanggal_jatuh_tempo'),
            'tanggal_pelunasan'   => $this->input->post('tanggal_pelunasan'),
            'dropship'            => $this->input->post('dropship')
        ];

        if($this->form_validation->run()!= FALSE){
            $insert = $this->db->insert('tb_pemesanan',$data);
            if($insert){
                $id = $this->db->insert_id();
                if(count($this->input->post('id_item')) > 0){
                    foreach ($this->input->post('id_item') as $i => $v) {
                        if($this->input->post('diskon')[$i] > 0){
                            $diskon = ($this->input->post('diskon')[$i] / $this->input->post('harga')[$i]) * 100;
                        }else{
                            $diskon = 0;
                        }
                        $detail = [
                            'id_pemesanan'   => $id,
                            'id_item'        => $this->input->post('id_item')[$i],
                            'harga'          => $this->input->post('harga')[$i],
                            'quantity'       => $this->input->post('quantity')[$i],
                            'diskon'         => $this->input->post('diskon')[$i],
                            'catatan'        => $this->input->post('catatan')[$i],
                            'nominal_diskon' => $diskon,
                            'subtotal'       => $this->input->post('subtotal')[$i]
                        ];
                        $total += $this->input->post('subtotal')[$i];
                        $this->db->insert('tb_pemesanan_detail',$detail);
                        $total_diskon += $diskon;
                    }
                    //Update total
                    $ppn = $this->input->post('ppn') == 0 ? 0 : ($this->input->post('ppn') * $total) / 100;
                    $grandTotal = $total + $ppn - $total_diskon;
                    $this->db->update('tb_pemesanan',['grand_total' => $grandTotal,'subtotal' => $total,'ppn_nominal' => $ppn],['id_pemesanan' => $id]);
                    if($this->input->post('dropship') != 1){
                        $this->session->set_flashdata('success','Berhasil menyimpan Purchase Order!');
                        if($this->input->post('submitAndSelf') == '1'){
                            history_action('Membuat Purchase Order','CPO',$id);
                            redirect('po/purchase_order_create','refresh');
                        }else if($this->input->post('submitAndBack') == '1'){
                            history_action('Membuat Purchase Order','CPO',$id);
                            redirect('po/purchase_order','refresh');
                        }                   
                    }else{
                        $create_so = $this->create_so_dropship($this->input->post('id_customer'),$this->input->post('id_metode_pembayaran'),$id);
                        if($create_so == TRUE){
                            $this->session->set_flashdata('success','Berhasil menyimpan Purchase Order dan Sales Order dengan status Dropship = YES');
                            history_action('Membuat Purchase Order','CPO',$id);
                            redirect('so/sales_order','refresh');
                        }else{
                            $this->session->set_flashdata('failed','Terjadi Kesalahan! Gagal menyimpan Purchase Order dan Sales Order dengan status Dropship = YES');
                            history_action('Membuat Purchase Order','CPO',$id);
                            redirect('po/purchase_order_create','refresh');
                        }
                    }
                }else{
                    $this->session->set_flashdata('success','Berhasil menyimpan Purchase Order! Tanpa detail!');
                    if($this->input->post('submitAndSelf') == '1'){
                        history_action('Membuat Purchase Order','CPO',$id);
                        redirect('po/purchase_order_create','refresh');
                    }else if($this->input->post('submitAndBack') == '1'){
                        history_action('Membuat Purchase Order','CPO',$id);
                        redirect('po/purchase_order','refresh');
                    }                   
                }
            }else{
                $this->session->set_flashdata('failed','Gagal menyimpan Purchase Order!');
                redirect('po/purchase_order_create','refresh');
            }
        }else{
            $this->session->set_flashdata('error', validation_errors());
            redirect('po/purchase_order_create');
        }
    }

    public function purchase_order_edit($id)
    {
	    if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2){
			$check_po = $this->db->query("SELECT A.id_pemesanan,no_po,A.created,C.nama_supplier 
		        FROM tb_pemesanan A 
		        LEFT JOIN tb_supplier C ON A.id_supplier = C.id_supplier 
		            WHERE EXISTS (SELECT DISTINCT B.id_pemesanan FROM tb_item_masuk_detail B WHERE A.id_pemesanan = B.id_pemesanan AND B.deleted = 0 AND B.id_item_masuk NOT IN (SELECT X.id_item_masuk FROM tb_item_masuk X WHERE X.deleted = 1)) OR EXISTS (SELECT DISTINCT D.id_pemesanan FROM tb_retur_pembelian D WHERE A.id_pemesanan = D.id_pemesanan AND D.deleted = 0) AND A.deleted = 0 AND A.approved_1 = 1 AND A.id_pemesanan = $id ORDER BY created DESC")->result();
		        if(count($check_po) > 0){
		            $check = 1;
		        }else{
		            $check = 0;
		        }    
		} else {
			$check = 1;
		}
	    
        $data = [
            'title'    => 'Purchase Order Edit',
            'wogc'     => true,
            'gudang'   => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted                                                = 0')->result(),
            'supplier' => $this->db->query('select id_supplier,nama_supplier from tb_supplier where deleted                                          = 0')->result(),
            'bank'     => $this->db->query('select id_bank_account,nama_bank,nomor_rekening,nama_pemilik_rekening from tb_bank_account where deleted = 0 and status_bank_account = 0')->result(),
            'po'       => $this->db->query('select * from tb_pemesanan where id_pemesanan = '.$id)->row(),
            'detail_po'=> $this->db->query('select a.id_item,harga,quantity,diskon,subtotal,nama_item,catatan from tb_pemesanan_detail a left join tb_item b on a.id_item = b.id_item where id_pemesanan ='.$id)->result(),
            'check'    => $check
        ];

        $this->load->view('transaksi/purchase_order_edit',$data);
    }

    public function purchase_order_update($id)
    {
        $total  = 0;
        $total_diskon = 0;

        $this->form_validation->set_rules('tipe_item','Tipe Item','required');
        $this->form_validation->set_rules('id_supplier','Supplier','required');
        $this->form_validation->set_rules('status','Status','required');

        $data = [
            'tipe_item'           => $this->input->post('tipe_item'),
            'id_supplier'         => $this->input->post('id_supplier'),
            'id_gudang'           => $this->input->post('id_gudang'),
            'ppn'                 => $this->input->post('ppn'),
            'creator'             => $this->session->userdata('id_user'),
            'status'              => $this->input->post('status'),
            'id_bank_account'     => $this->input->post('id_bank_account'),
            'tanggal_jatuh_tempo' => $this->input->post('tanggal_jatuh_tempo'),
            'tanggal_pelunasan'   => $this->input->post('tanggal_pelunasan')
        ];

        if($this->form_validation->run() != FALSE){
            $update = $this->db->update('tb_pemesanan',$data,['id_pemesanan' => $id]);
            if($update){
                $this->db->query('delete from tb_pemesanan_detail where id_pemesanan = '.$id);
                if(count($this->input->post('id_item')) > 0){
                    foreach ($this->input->post('id_item') as $i => $v) {
                        if($this->input->post('diskon')[$i] > 0){
                            $diskon = ($this->input->post('diskon')[$i] / $this->input->post('harga')[$i]) * 100;
                        }else{
                            $diskon = 0;
                        }
                        $detail = [
                            'id_pemesanan'   => $id,
                            'id_item'        => $this->input->post('id_item')[$i],
                            'harga'          => $this->input->post('harga')[$i],
                            'quantity'       => $this->input->post('quantity')[$i],
                            'diskon'         => $this->input->post('diskon')[$i],
                            'catatan'        => $this->input->post('catatan')[$i],
                            'nominal_diskon' => $diskon,
                            'subtotal'       => $this->input->post('subtotal')[$i]
                        ];
                        $total += $this->input->post('subtotal')[$i];
                        $total_diskon += $diskon;
                        $this->db->insert('tb_pemesanan_detail',$detail);
                    }
                    //Update total
                    $ppn = $this->input->post('ppn') == 0 ? 0 : ($this->input->post('ppn') * $total) / 100;
                    $total = $total + $ppn - $total_diskon;
                    $this->db->update('tb_pemesanan',['grand_total' => $total,'ppn_nominal' => $ppn],['id_pemesanan' => $id]);

                    $this->session->set_flashdata('success','Berhasil mengupdate Purchase Order!');
                    if($this->input->post('submitAndSelf') == '1'){
                        history_action('Edit Purchase Order','CPO',$id);
                        redirect('po/purchase_order_create','refresh');
                    }else if($this->input->post('submitAndBack') == '1'){
                        history_action('Edit Purchase Order','CPO',$id);
                        redirect('po/purchase_order','refresh');
                    }                   
                }else{
                    $this->session->set_flashdata('success','Berhasil mengupdate Purchase Order! Tanpa detail!');
                    if($this->input->post('submitAndSelf')){
                        history_action('Edit Purchase Order','EPO',$id);
                        redirect('po/purchase_order_create','refresh');
                    }else{
                        history_action('Edit Purchase Order','EPO',$id);
                        redirect('po/purchase_order','refresh');
                    }
                }
            }else{
                $this->session->set_flashdata('failed','Gagal mengupdate Purchase Order!');
                redirect('po/purchase_order_edit/'.$id,'refresh');
            }
        }else{
            $this->session->flashdata('error',validation_errors());
            redirect('po/purchase_order_edit/'.$id,'refresh','refresh');
        }
    }

    public function create_so_dropship($customer,$metode,$id_po)
    {
        $this->db->select('no_so', FALSE);
        $this->db->order_by('id_penjualan','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('tb_penjualan');   
        if($query->num_rows() <> 0){      
            $num = $query->row();
            if(substr($num->no_so,0,1) == "0"){
                $val = $num->no_so;
            }else{
                $val = "0".$num->no_so;
            }
            if(strlen(str_replace(" ","",$val)) <= 10){
                $data = str_split($val,8);      
                $kode = $data[1] + 1;
            }else{
                $data = str_split($val,9);      
                $kode = $data[1] + 1;
            }
        }
        else{      
            $kode = 1;
        } 
        if($kode > 9){
            $kode = $kode;
        }else{
            $kode = '0'.$kode;
        }
        $nomor_jual_max = date('ymd').$kode;    
        $nomor_tampil = "0".$metode."-".$nomor_jual_max;

        $insertPenjualan = $this->db->query("
            INSERT INTO tb_penjualan (no_so,id_customer,id_gudang,ppn,subtotal,grand_total,creator,conf_logistik,status_logistik,status_penjualan,id_pemesanan)
                select '$nomor_tampil',$customer,id_gudang,ppn,subtotal,grand_total,creator,2,1,1,$id_po from tb_pemesanan where id_pemesanan = $id_po
        ");
        $id = $this->db->insert_id();
        if($id){
            $insertDetailPenjualan = $this->db->query("
                insert into tb_penjualan_detail (id_penjualan,id_item,quantity,harga,subtotal)
                    select $id,id_item,quantity,harga,subtotal from tb_pemesanan_detail where id_pemesanan = $id_po 
            ");
            return TRUE;
        }else{
            return FALSE;
        }
    }

    //Global Callback Column
    public function nominal($value,$row)
    {
        return number_format($value);
    }

    public function approved($value,$row){
        $user = $this->db->query("SELECT * FROM tb_user WHERE id_user = $value")->row();
        if($user){
            return $user->nama_user;
        }else{
            return '';
        }
    }

    public function load_paper($id){
        $po = $this->db->query('select 
                                    no_po,tipe_item,a.id_supplier,nama_supplier,a.id_gudang,nama_gudang,ppn,ppn_nominal,subtotal,grand_total,a.created,nama_bank,nomor_rekening,b.nama_pemilik_rekening,dropship 
                                        from tb_pemesanan a 
                                            left join tb_supplier b on a.id_supplier = b.id_supplier 
                                            left join tb_gudang c on a.id_gudang = c.id_gudang 
                                            left join tb_bank_account d on a.id_bank_account = d.id_bank_account
                                        where id_pemesanan = '.$id)->row();
        $data = [
            'po'       => $po,
            'detail'   => $this->db->query('select a.id_item,harga,quantity,diskon,subtotal,nama_item,catatan from tb_pemesanan_detail a left join tb_item b on a.id_item = b.id_item where id_pemesanan ='.$id)->result()
        ];
    
        $this->load->library('Pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = 'PO - '.$po->no_po.'.pdf';
        $this->pdf->load_view('print/purchase_order_paper',$data);
    }

}

/* End of file Po.php */