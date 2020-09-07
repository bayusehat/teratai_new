<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_pembelian extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(empty($this->session->userdata('token'))){
            redirect('dashboard','refresh');
        }
    }

    public function index()
    {   
        redirect("retur_pembelian/list");
    }

    public function no_retur_pembelian()
    {
        $this->db->select('no_retur_pembelian', FALSE);
        $this->db->order_by('id_retur_pembelian','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('tb_retur_pembelian');   
        if($query->num_rows() <> 0){      
            $noso = $query->row();
            $length = strlen(str_replace(" ","",$noso->no_retur_pembelian));
            $get_subs = $length - 3;
            if(strlen(str_replace(" ","",$noso->no_retur_pembelian)) > 10){
                $num = substr($noso->no_retur_pembelian,$get_subs,$length);
                $kode = $num + 1;

                $cek_all_data = $this->db->query("SELECT COUNT(*) jumlah FROM tb_retur_pembelian")->row();
                if($cek_all_data->jumlah < 100){
                    $kode = intval(substr($kode,-2));
                }else if($cek_all_data->jumlah > 100){
                    $kode = intval(substr($kode,-3));
                }else{
                    $kode = intval(substr($kode,-4));
                }
            }else if(strlen(str_replace(" ","",$noso->no_retur_pembelian)) <= 10){
                $data = str_split($noso->no_retur_pembelian,8);      
                $kode = $data[1] + 1;
            }else{
                $data = str_split($noso->no_retur_pembelian,9);      
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
        $nomor_tampil = "RB-".$nomor_jual_max;

        return $nomor_tampil;
    }

    public function list_retur()
    {
        $c = new grocery_CRUD();

        $c->set_subject('Retur Pembelian');
        $c->set_table('tb_retur_pembelian');
        $c->where('tb_retur_pembelian.deleted',0);
        $c->order_by('tb_retur_pembelian.created','DESC');
        $c->display_as('id_supplier','Supplier')
          ->display_as('id_gudang','Gudang')
          ->display_as('no_retur_pembelian','No. Retur')
          ->display_as('tanggal_jatuh_tempo_retur','Jatuh Tempo')
          ->display_as('created','Tgl. Nota')
          ->display_as('id_pemesanan','No. Purchase Order')
          ->display_as('approved_1','Approved By')
          ->display_as('approval','Action');
        $c->set_relation('id_supplier','tb_supplier','nama_supplier',['deleted' => 0])
          ->set_relation('id_gudang','tb_gudang','nama_gudang',['deleted' => 0])
          ->set_relation('id_pemesanan','tb_pemesanan','no_po');
        $c->columns('no_retur_pembelian','created','id_pemesanan','tanggal_jatuh_tempo_retur','id_gudang','id_supplier','status','approved_1','approval');
        $c->unset_columns('updated','deleted','status_lunas','creator');
        $c->unset_fields('created','updated','deleted');
        $c->unset_add();
        $c->unset_read();
        $c->unset_edit();
        
        //$c->add_action('Nota Retur','','retur_pembelian/load_paper','fa-file');
        if($this->session->userdata('id_user') == 2 || $this->session->userdata('id_user') == 1){
            $c->add_action('Edit','','retur_pembelian/edit','fa-pencil');
        }
        $c->callback_column('status',[$this,'status_approved'])
          ->callback_column('approval',[$this,'approval_rp'])
          ->callback_column('approved_1',[$this,'approved'])
          ->callback_column('approved_2',[$this,'approved'])
          ->callback_column('retur',[$this,'retur_pembelian']);
        $c->callback_delete([$this,'delete_retur_pembelian']);
        $title = 'Data Retur Pembelian';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    public function delete_retur_pembelian($id)
    {
        $parent = $this->db->update('tb_retur_pembelian',['deleted' => 1],['id_retur_pembelian' => $id]);
        $appr = $this->db->query('select * from tb_retur_pembelian where id_retur_pembelian = '.$id)->row();

        if($appr->approved_1 != 0){
            $returDetail = $this->db->query('select * from tb_retur_pembelian_detail where id_retur_pembelian = '.$id)->result();
            foreach ($returDetail as $dr) {
                $this->db->query(
                "update tb_stok_gudang set stok = stok + $dr->quantity where id_item = $dr->id_item and id_gudang = $appr->id_gudang"
            );
            $mod = $dr->quantity;
            history_stok($dr->id_item,$mod,'Hapus Retur Pembelian Approved','DRPB',$id);
            }
        }

        return $parent;
    }

    public function status_approved($value,$row)
    {
        if($row->approved_1 != 0){
            return '<div class="label label-success">Approved</div>';
        }else {
            return '<div class="label label-danger">Belum ada Approval</div>';
        }
    }

    public function approval_rp($value,$row)
    {
        if($row->approved_1 == ''){
            if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2 || $this->session->userdata('id_jabatan') == 6 || $row->approved_1 == 0){
                $link1 = $row->approved_1 != 0 ? '#' : 'retur_pembelian/approve_rp_action/1/'.$row->id_retur_pembelian;
                // $link2 = $row->approved_2 != 0 ? '#' : 'retur_pembelian/approve_rp_action/2/'.$row->id_retur_pembelian;
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

    public function approve_rp_action($tipe,$id)
    {   
        //$query = $this->db->query('select * from tb_retur_pembelian where id_retur_pembelian = '.$id)->row();
        if($tipe == 1){
	        $checkApprovedStatus = $this->db->where('id_retur_pembelian', $id)
	        								->select('approved_1')
	        								->get('tb_retur_pembelian')
	        								->row();
			
			if($checkApprovedStatus->approved_1 == 0){
	            $query = $this->db->query('UPDATE tb_retur_pembelian SET approved_1 = '.$this->session->userdata('id_user').' where id_retur_pembelian =  '.$id);
	            $this->triggerStockPo($id);
	            history_action('Approve Retur Pembelian','ARPB',$id);
            }
            
            return redirect($_SERVER['HTTP_REFERER'],'refresh');
            
        }else{
            /*$query = $this->db->query('UPDATE tb_retur_pembelian SET approved_2 = '.$this->session->userdata('id_user').' where id_retur_pembelian =  '.$id);
            history_action('Approve Retur Pembelian','ARPB',$id);
            return redirect($_SERVER['HTTP_REFERER'],'refresh');*/
        }
    }

    public function create($id)
    {
	    $check_gi = $this->db->where("id_pemesanan", $id)->select('id_item_masuk_detail')->get('tb_item_masuk_detail')->result();
	    if(!count($check_gi)){
		    $this->session->set_flashdata('failed','Belum bisa membuat retur karena belum ada Goods In untuk PO ini');
		    redirect('po/purchase_order');
	    }
	    
        $po = $this->db->query('select no_po, id_supplier from tb_pemesanan where id_pemesanan = '.$id)->row();
        $data = [
            'title' => 'Retur Pembelian',
            'wogc' => true,
            'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            //'supplier' => $this->db->query('select id_supplier,nama_supplier from tb_supplier where deleted = 0')->result(),
            'supplier' => $this->db->query('select id_supplier, nama_supplier from tb_supplier where id_supplier = '.$po->id_supplier)->row(),
            'detail_po' => $this->db->query('
            select id_item,nama_item,id_pemesanan, harga, quantity, sum(qty_retur), quantity - sum(qty_retur) available_qty
                from(
                    select a.id_item, b.nama_item, a.id_pemesanan, a.quantity, a.harga ,case when d.quantity is null then 0 else d.quantity end qty_retur
                        from tb_pemesanan_detail a 
                        left join tb_item b on a.id_item = b.id_item
						left join tb_retur_pembelian c on a.id_pemesanan = c.id_pemesanan
                        left join tb_retur_pembelian_detail d on c.id_retur_pembelian = d.id_retur_pembelian
                where a.id_pemesanan = '.$id.'
                ) a
                group by id_item ')->result()
        ];

        $this->load->view('transaksi/retur_pembelian_create',$data);
    }

    public function insert($id)
    {
        // $this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
        $this->form_validation->set_rules('id_supplier','Supplier','required');
        $this->form_validation->set_rules('tanggal_jatuh_tempo_retur','Tanggal Jatuh Tempo','required');
        
        $gudang_from_gi = $this->db->query("select gi.id_gudang from tb_item_masuk gi where gi.id_item_masuk in (select gid.id_item_masuk from tb_item_masuk_detail gid where gid.id_pemesanan = $id) order by gi.id_item_masuk desc limit 1")->result();
        if(!count($gudang_from_gi)){
	        $this->session->set_flashdata('failed','Gagal menyimpan retur karena data gudang pada GI tidak ditemukan');
                redirect('retur_pembelian/create/'.$id,'refresh');
        }
        $id_gudang = $gudang_from_gi[0]->id_gudang;
        
        $data = [
            'no_retur_pembelian'        => $this->no_retur_pembelian(),
            'id_supplier'               => $this->input->post('id_supplier'),
            'id_gudang'                 => $id_gudang,
            'status_retur'              => $this->input->post('status_retur'),
            'id_pemesanan'              => $id,
            'tanggal_jatuh_tempo_retur' => $this->input->post('tanggal_jatuh_tempo_retur'),
            'creator'                   => $this->session->userdata('id_user')
        ];

        if($this->form_validation->run() != FALSE){
            $insert = $this->db->insert('tb_retur_pembelian',$data);
            $id_ret = $this->db->insert_id();

            if($insert){
                if(count($this->input->post('qty_retur')) > 0){
                    foreach ($this->input->post('qty_retur') as $i => $item) {
                        if($this->input->post('qty_retur')[$i] != 0){
                            $detail = [
                                'id_retur_pembelian'    => $id_ret,
                                'id_item'               => $this->input->post('id_item')[$i],
                                'quantity'              => $this->input->post('qty_retur')[$i],
                                'harga'                 => $this->input->post('harga')[$i],
                                'catatan'               => $this->input->post('catatan')[$i],
                                'status_pengembalian'   => 0,
                                'approval_pengembalian' => 0
                            ];
                            $this->db->insert('tb_retur_pembelian_detail',$detail);
                        }
                    }
                }
                $this->session->set_flashdata('success','Berhasil menambah retur!');
                history_action('Membuat Retur Pembelian','CRPB',$id_ret);
                redirect('retur_pembelian/create/'.$id,'refresh');
            }else{
                $this->session->set_flashdata('failed','Terjadi Masalah! gagal menambah retur');
                redirect('retur_pembelian/create/'.$id,'refresh');
            }
        }else{
            $this->session->set_flashdata('error',validation_errors());
            redirect('retur_pembelian/create/'.$id,'refresh');
        }
    }

    public function edit($id)
    {
        $retur = $this->db->query('select id_pemesanan,no_retur_pembelian,a.id_gudang,nama_gudang,a.id_supplier,nama_supplier,tanggal_jatuh_tempo_retur,status_retur
                                    from tb_retur_pembelian a left join tb_gudang b on a.id_gudang = b.id_gudang 
                                        left join tb_supplier c on a.id_supplier = c.id_supplier 
                                    where a.deleted = 0 and id_retur_pembelian = '.$id)->row();
        $data = [
            'title' => 'Edit Retur Pembelian '.$retur->no_retur_pembelian,
            'wogc' => true,
            'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            //'supplier' => $this->db->query('select id_supplier,nama_supplier from tb_supplier where deleted = 0')->result(),
            'supplier' => $this->db->query('select id_supplier, nama_supplier from tb_supplier where id_supplier = '.$retur->id_supplier)->row(),
            'retur' => $retur,
            'detail' => 
                $this->db->query('select id_retur_pembelian_detail, a.id_item,a.harga,a.id_retur_pembelian,a.quantity quantity_retur,c.quantity,nama_item,a.catatan 
                from tb_retur_pembelian_detail a 
                    left join tb_retur_pembelian b on a.id_retur_pembelian = b.id_retur_pembelian
                    left join tb_pemesanan_detail c on a.id_item = c.id_item
                    left join tb_item d on c.id_item = d.id_item
                    where c.id_pemesanan = b.id_pemesanan  and a.id_retur_pembelian ='.$id)->result()
        ];

        $this->load->view('transaksi/retur_pembelian_edit',$data);
    }

    public function update($id)
    {  
        //$this->form_validation->set_rules('id_gudang', 'Gudang', 'required');
        $this->form_validation->set_rules('id_supplier','Supplier','required');
        $this->form_validation->set_rules('tanggal_jatuh_tempo_retur','Tanggal Jatuh Tempo','required');
        
        $data = [
            'no_retur_pembelian'        => $this->no_retur_pembelian(),
            'id_supplier'               => $this->input->post('id_supplier'),
            //'id_gudang'                 => $this->input->post('id_gudang'),
            'status_retur'              => $this->input->post('status_retur'),
            'tanggal_jatuh_tempo_retur' => $this->input->post('tanggal_jatuh_tempo_retur'),
            'creator'                   => $this->session->userdata('id_user')
        ];

        if($this->form_validation->run() != FALSE){
            $update = $this->db->update('tb_retur_pembelian',$data,['id_retur_pembelian' => $id]);
            $id_ret = $id;

            if($update){
                if(count($this->input->post('qty_retur')) > 0){
                    // $this->db->query('DELETE FROM tb_retur_pembelian_detail where id_retur_pembelian = '.$id_ret);
                    foreach ($this->input->post('qty_retur') as $i => $item) {
                        if($this->input->post('qty_retur')[$i] != 0){
                            //Cek perubahan qty retur
                            // $currAvailable = $this->db->query('SELECT * FROM TB_RETUR_PEMBELIAN_DETAIL WHERE ID_ITEM = '.$this->input->post('id_item')[$i].' AND ID_RETUR_PEMBELIAN = '.$id_ret)->row();
                            // if($this->input->post('qty_retur')[$i] < $currAvailable->quantity){
                            //     $qtyPo = -1 * ($currAvailable->quantity - $this->input->post('qty_retur')[$i]);
                            // }else{
                            //     $qtyPo = $currAvailable->quantity - $this->input->post('qty_retur')[$i];
                            // }
                            $detail = [
                                // 'id_retur_pembelian_detail' => $this->input->post('id_retur_pembelian_detail')[$i],
                                'id_retur_pembelian'    => $id_ret,
                                'id_item'               => $this->input->post('id_item')[$i],
                                'quantity'              => $this->input->post('qty_retur')[$i],
                                'harga'                 => $this->input->post('harga')[$i],
                                'catatan'               => $this->input->post('catatan')[$i],
                                'status_pengembalian'   => 0,
                                'approval_pengembalian' => 0
                            ];
                             //Update Qty PO 
                            // $queryUpdatePo = $this->db->query("UPDATE tb_pemesanan_detail SET quantity = quantity - $qtyPo WHERE id_item = ".$this->input->post('id_item')[$i]." AND id_pemesanan = $id");

                            $this->db->update('tb_retur_pembelian_detail',$detail,['id_retur_pembelian_detail' => $this->input->post('id_retur_pembelian_detail')[$i]]);
                        }
                    }
                }
                $this->session->set_flashdata('success','Berhasil mengupdate retur!');
                history_action('Memperbarui Retur Pembelian','URPB',$id_ret);
                redirect('retur_pembelian/edit/'.$id,'refresh');
            }else{
                $this->session->set_flashdata('failed','Terjadi Masalah! gagal memperbarui retur');
                redirect('retur_pembelian/edit/'.$id,'refresh');
            }
        }else{
            $this->session->set_flashdata('error',validation_errors());
            redirect('retur_pembelian/edit/'.$id,'refresh');
        }
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
        $retur = $this->db->query('select 
                                    id_retur_pembelian,a.created,no_retur_pembelian,a.id_supplier,nama_supplier,alamat_supplier,no_telpon_supplier,a.id_gudang,nama_gudang,a.id_pemesanan,no_po
                                        from tb_retur_pembelian a 
                                            left join tb_supplier b on a.id_supplier = b.id_supplier
                                            left join tb_gudang c on a.id_gudang = c.id_gudang
                                            left join tb_pemesanan d on a.id_pemesanan = d.id_pemesanan
                                        where id_retur_pembelian ='.$id)->row();
        $data =[
            'retur' => $retur,
            'detail' => $this->db->query('select 
                                            id_retur_pembelian detail,a.id_item,nama_item,quantity,catatan,status_pengembalian,approval_pengembalian,harga
                                                from tb_retur_pembelian_detail a 
                                                    left join tb_item b on a.id_item = b.id_item
                                                where id_retur_pembelian ='.$id)->result(),
        ];
    
        $this->load->library('pdf');
        $this->pdf->setPaper('A4', 'potrait');
        $this->pdf->filename = 'RB - '.$retur->no_retur_pembelian.'.pdf';
        $this->pdf->load_view('print/retur_pembelian_paper',$data);
    }

    public function triggerStockPo($id)
    {
        $appr = $this->db->query('select * from tb_retur_pembelian where id_retur_pembelian = '.$id)->row();
        if($appr->approved_1 != 0){
            $returDetail = $this->db->query('select * from tb_retur_pembelian_detail where id_retur_pembelian = '.$id)->result();
            foreach ($returDetail as $dr) {
                $this->db->query(
                "update tb_stok_gudang set stok = stok - $dr->quantity where id_item = $dr->id_item and id_gudang = $appr->id_gudang"
            );
            $mod = $dr->quantity * -1;
            history_stok($dr->id_item,$mod,'Approval Retur Pembelian','RPB',$id);
            }
        }
    }
}

/* End of file Retur_pembelian.php */
