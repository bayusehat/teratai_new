<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Gi extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function no_gi($id_gudang)
    {
        $get_gudang = $this->db->query('select * from tb_gudang where id_gudang ='.$id_gudang)->row();
        $this->db->select('RIGHT(tb_item_masuk.no_gi,2) as no_gi', FALSE);
        $this->db->order_by('no_gi','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('tb_item_masuk');   
        if($query->num_rows() <> 0){      
   
               $data = $query->row();      
               $kode = intval($data->no_gi) + 1; 
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
        $nomor_tampil = $get_gudang->kode_gudang."-".$nomor_jual_max;
        return $nomor_tampil;
    }

    public function check_po($id_supplier = null,$id_item_masuk = null)
    {
        $response['data'] = [];
        if($id_supplier == null){
            $check = $this->db->query("SELECT A.id_pemesanan,no_po,A.created,C.nama_supplier FROM tb_pemesanan A LEFT JOIN tb_supplier C ON A.id_supplier = C.id_supplier WHERE NOT EXISTS (SELECT DISTINCT B.id_pemesanan FROM tb_item_masuk_detail B WHERE A.id_pemesanan = B.id_pemesanan AND B.deleted = 0 AND B.id_item_masuk NOT IN (SELECT X.id_item_masuk FROM tb_item_masuk X WHERE X.deleted = 1)) AND A.deleted = 0 AND A.approved_1 > 0 ORDER BY created DESC")->result();
            echo json_encode($response); exit();
        }else{
            if($id_item_masuk == null){
                $check = $this->db->query("SELECT A.id_pemesanan,no_po,A.created,C.nama_supplier FROM tb_pemesanan A LEFT JOIN tb_supplier C ON A.id_supplier = C.id_supplier WHERE NOT EXISTS (SELECT DISTINCT B.id_pemesanan FROM tb_item_masuk_detail B WHERE A.id_pemesanan = B.id_pemesanan AND B.deleted = 0 AND B.id_item_masuk NOT IN (SELECT X.id_item_masuk FROM tb_item_masuk X WHERE X.deleted = 1)) AND A.deleted = 0 AND A.approved_1 > 0 AND A.ID_SUPPLIER = $id_supplier ORDER BY created DESC")->result();
            }else{
                $check = $this->db->query("SELECT A.id_pemesanan,no_po,A.created,C.nama_supplier FROM tb_pemesanan A LEFT JOIN tb_supplier C ON A.id_supplier = C.id_supplier WHERE A.deleted = 0 AND A.approved_1 > 0 AND A.ID_SUPPLIER = $id_supplier ORDER BY created DESC")->result();
            }
        }
        
        foreach($check as $i => $v){
            if($id_item_masuk != null){
                $gi = $this->db->query("SELECT * FROM tb_item_masuk WHERE id_item_masuk = $id_item_masuk")->row();
                $detail = $this->db->query('select * from tb_item_masuk_detail where id_pemesanan = '.$v->id_pemesanan.' and id_item_masuk = '.$id_item_masuk)->row();
                if($detail){
                    $action = $detail->id_pemesanan == $v->id_pemesanan ? '<input type="checkbox" class="form-control" name="id_pemesanan[]" value="'.$v->id_pemesanan.'" checked>' : '<input type="checkbox" class="form-control" name="id_pemesanan[]" value="'.$v->id_pemesanan.'">';
                }else{
                    $action = '<input type="checkbox" class="form-control" name="id_pemesanan[]" value="'.$v->id_pemesanan.'">';
                }
               
            }else{
                $detail = '';
                $action = '<input type="checkbox" class="form-control" name="id_pemesanan[]" value="'.$v->id_pemesanan.'" >';
            }

            $response['data'][] =[
                ++$i,
                $action,
                $v->no_po,
                date('d/m/Y H:i')
            ]; 
        }
        
        echo json_encode($response);
    }

    public function goods_in()
    {
	    $access_url = 'gi/goods_in';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

        $c->set_subject('Goods In / Item Masuk');
        $c->set_table('tb_item_masuk');
        $c->where('tb_item_masuk.deleted',0);
        $c->order_by('id_item_masuk','DESC');
        $c->display_as('id_supplier','Supplier')
          ->display_as('id_gudang','Gudang')
          ->display_as('created','Tgl. Nota')
          ->display_as('no_gi','No. GI')
          ->display_as('approved_1','Approved By')
          ->display_as('approval','Action');
        $c->set_relation('id_supplier','tb_supplier','nama_supplier',['deleted' => 0])
          ->set_relation('id_gudang','tb_gudang','nama_gudang',['deleted' => 0]);
        $c->columns('no_gi','created','id_gudang','id_supplier','status','approved_1','approval');
        $c->unset_columns('updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->unset_add();
        $c->unset_read();
        $c->unset_edit();
        
        $c->add_action('Detail','','gi/goods_in_detail','fa-file');
        
        //dicomment sementara sampai edit diperbaiki dan diverifikasi
        $c->add_action('Edit','','gi/goods_in_edit','fa-pencil');
        
        if($this->session->userdata('id_jabatan') != 1)
        	$c->unset_delete();
        
        $c->callback_column('status',[$this,'status_approved'])
          ->callback_column('approval',[$this,'approval_gi'])
          ->callback_column('approved_1',[$this,'approved']);
        //   ->callback_column('approved_2',[$this,'approved']);
        $c->callback_delete([$this,'delete_goods_in']);
        $title = 'Data Goods In';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    // public function is_approved($value,$row)
    // {
    //     if($row->approved_1 == ''){
    //         return site_url('gi/goods_in_edit/'.$row->id_item_masuk);
    //     }else{
    //         // $this->session->set_flashdata('failed','Edit gagal! Goods in sudah terapprove');
    //         return site_url('gi/goods_in/');
    //     }
    // }

    public function delete_goods_in($id)
    {   
        $this->returStok($id);
        history_action('Menghapus data Goods In','DGI',$id);
        $this->db->update('tb_item_masuk',['deleted' => 1],['id_item_masuk' => $id]);
        $this->db->update('tb_item_masuk_detail',['deleted' => 1],['id_item_masuk' => $id]);
        return true;
    }

    public function status_approved($value,$row)
    {
        if($row->approved_1 != 0){
            return '<div class="label label-success">Approved</div>';
        }else{
            return '<div class="label label-danger">Belum ada Approval</div>';
        }
    }

    public function approval_gi($value,$row)
    {
        if($row->approved_1 == ''){
            if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2 || $this->session->userdata('id_jabatan') == 6){
                $link1 = $row->approved_1 != 0 ? '#' : 'gi/approve_gi_action/1/'.$row->id_item_masuk;
                $disabled1 = $row->approved_1 != 0 ? 'disabled' : '';
                return '<a href="'.base_url($link1).'" class="btn btn-primary btn-sm btn-block '.$disabled1.'"><i class="fa fa-check"></i> Klik untuk Approve</a>
                        <!--<a href="'.base_url('gi/goods_in_edit/'.$row->id_item_masuk).'" class="btn btn-warning btn-sm btn-block '.$disabled1.'"><i class="fa fa-edit"></i> Edit GI</a>-->';
            }else{
                return 'No Action';
            }
        }else{
            return 'No Action';
        }
        
    }

    public function approve_gi_action($tipe,$id)
    {
        if($tipe == 1){
	        $checkApprovedStatus = $this->db->where('id_item_masuk', $id)
	        								->select('approved_1')
	        								->get('tb_item_masuk')
	        								->row();
			
			if($checkApprovedStatus->approved_1 == 0){
				$query = $this->db->query('UPDATE tb_item_masuk SET approved_1 = '.$this->session->userdata('id_user').' where id_item_masuk =  '.$id);
	            $this->checkApproveComplete($id);
	            history_action('Approval data Goods In','AGI',$id);
            }
            
            redirect('gi/goods_in');
        }
    }

    public function goods_in_create()
    {
        $data = [
            'title'    => 'Tambah Goods In',
            'wogc'     => true,
            'gudang'   => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted       = 0')->result(),
            'supplier' => $this->db->query('select id_supplier,nama_supplier from tb_supplier where deleted = 0')->result(),
            'po'       => $this->db->query('SELECT A.id_pemesanan,no_po,A.created,C.nama_supplier FROM tb_pemesanan A LEFT JOIN tb_supplier C ON A.id_supplier = C.id_supplier WHERE NOT EXISTS (SELECT DISTINCT B.id_pemesanan FROM tb_item_masuk_detail B WHERE A.id_pemesanan = B.id_pemesanan AND B.id_item_masuk NOT IN (SELECT X.id_item_masuk FROM tb_item_masuk X WHERE X.deleted = 1)) AND A.deleted = 0 AND A.approved_1 > 0 ORDER BY created DESC')->result()
        ];
        
        $this->load->view('transaksi/goods_in_create',$data);
    }

    public function goods_in_insert()
    {
	    if($this->input->post('id_gudang') < 1){
		    $this->session->set_flashdata('failed','Harap Pilih Gudang');
			redirect('gi/goods_in_create','refresh');
			exit();
	    }
	    
	    $id_supplier = 0;
	    if(count($this->input->post('id_pemesanan')) > 0){
		    foreach ($this->input->post('id_pemesanan') as $idp) {
			    $pems = $this->db->where('id_pemesanan', $idp)->select('id_supplier')->get('tb_pemesanan')->row();
			    if(count($pems) > 0){
				    if($id_supplier == 0){
					    $id_supplier = $pems->id_supplier;
				    }
				    else{
					    if($id_supplier != $pems->id_supplier){
						    $this->session->set_flashdata('failed','Pilih hanya PO yang berasal dari supplier yang sama!');
							redirect('gi/goods_in_create','refresh');
							exit();
					    }
				    }
			    }
			}
		}
	    
	    if($id_supplier == 0){
		    $this->session->set_flashdata('failed','Goods In gagal disimpan, pastikan anda sudah memilih PO!');
			redirect('gi/goods_in_create','refresh');
			exit();
	    }
	    
        $data = [
            'no_gi'       => $this->no_gi($this->input->post('id_gudang')),
            'id_supplier' => $id_supplier,
            'id_gudang'   => $this->input->post('id_gudang'),
            'creator'     => $this->session->userdata('id_user')
        ];

        $insert = $this->db->insert('tb_item_masuk',$data);
        $id = $this->db->insert_id();

        if($insert){
            if(count($this->input->post('id_pemesanan')) > 0){
                foreach ($this->input->post('id_pemesanan') as $v) {
                    $this->db->query("
                        INSERT INTO tb_item_masuk_detail (id_item_masuk,id_item,quantity,satuan,id_pemesanan,catatan)
                            select $id,a.id_item,a.quantity,a.satuan,b.id_pemesanan,a.catatan from tb_pemesanan_detail a left join tb_pemesanan b on a.id_pemesanan = b.id_pemesanan where a.id_pemesanan = $v 
                    ");
                }
                $this->session->set_flashdata('success','Goods In berhasil disimpan');
                history_action('Membuat data Goods In baru','CGI',$id);
                redirect('gi/goods_in');
            }else{
                $this->session->set_flashdata('success','Goods In berhasil disimpan! Tanpa detail');
                history_action('Membuat data Goods In baru','CGI',$id);
                redirect('gi/goods_in_create');
            }
        }else{
            $this->session->set_flashdata('failed','Goods In gagal disimpan!');
            redirect('gi/goods_in_create','refresh');
        }
    }

    public function goods_in_detail($id_item_masuk)
    {
        $detail1 = [];
        $gi = $this->db->query("
            select a.no_gi,a.approved_1,a.approved_2,a.id_item_masuk,b.nama_gudang,c.nama_supplier,a.created 
                from tb_item_masuk a 
                    left join tb_gudang b on a.id_gudang = b.id_gudang 
                    left join tb_supplier c on a.id_supplier = c.id_supplier
                        where id_item_masuk = $id_item_masuk
                        ")->row();
        $pemesanan = $this->db->query("
            select a.id_pemesanan,a.id_item_masuk,b.no_po 
                from tb_item_masuk_detail a 
                    left join tb_pemesanan b on a.id_pemesanan = b.id_pemesanan 
                        where a.id_item_masuk = $gi->id_item_masuk 
            group by a.id_pemesanan,b.no_po,a.id_item_masuk
            ")->result();
            foreach ($pemesanan as $i => $p) {
                $detail1[$p->id_pemesanan] = $p;
                $detail1[$p->id_pemesanan]->detail = $this->db->query("
                    select a.id_item,b.nama_item,b.harga_jual,a.quantity 
                        from tb_item_masuk_detail a 
                            left join tb_item b on a.id_item = b.id_item 
                        where a.id_pemesanan = $p->id_pemesanan and a.id_item_masuk = $p->id_item_masuk
                ")->result();
            }
            
        $data = [
            'title'  => 'Goods In detail',
            'wogc'   => true,
            'gi'     => $gi,
            'detail' => $detail1
        ];
        $this->load->view('report/goods_in_detail',$data);
    }

    public function returStok($id)
    {
        $query = $this->db->query('SELECT * FROM tb_item_masuk where id_item_masuk ='.$id)->row();
        if($query->approved_1){
           $detailGi = $this->db->query('select * from tb_item_masuk_detail where id_item_masuk = '.$id)->result();
           foreach($detailGi as $dg){
                $queryUpdateStok = $this->db->query('
                    update tb_stok_gudang set stok = stok - '.$dg->quantity.' where id_gudang = '.$query->id_gudang.' and id_item = '.$dg->id_item);
                    history_stok($dg->id_item,($dg->quantity*-1),'Pembatalan Goods In','GI',$id); 
           }
        }
        return true;
    }
    
    public function checkApproveComplete($id)
    {
        $query = $this->db->query('SELECT * FROM tb_item_masuk where id_item_masuk ='.$id)->row();
        if($query->approved_1 != 0){
           $detailGi = $this->db->query('select * from tb_item_masuk_detail where id_item_masuk = '.$id)->result();
           foreach($detailGi as $dg){
	            $checkStokGudangEntry = $this->db->where('id_gudang', $query->id_gudang)
	            								->where('id_item', $dg->id_item)
	            								->where('deleted', 0)
	            								->get('tb_stok_gudang')
	            								->row();
				
				if(count($checkStokGudangEntry) > 0){	           
	                $queryUpdateStok = $this->db->query('
	                    update tb_stok_gudang set stok = stok + '.$dg->quantity.' where id_gudang = '.$query->id_gudang.' and id_item = '.$dg->id_item);
	            } else {
		            $data = [
			            'id_item' => $dg->id_item,
			            'id_gudang' => $query->id_gudang,
			            'stok' => $dg->quantity,
			        ];
			
			        $insert = $this->db->insert('tb_stok_gudang',$data);
	            }
	            
	            history_stok($dg->id_item,$dg->quantity,'Goods In','GI',$id); 
           }
        }
    }

    public function goods_in_edit($id)
    {
        $gi = $this->db->query('select no_gi,id_item_masuk,a.id_supplier,nama_supplier,a.id_gudang,nama_gudang,approved_1 from tb_item_masuk a
                left join tb_supplier b on a.id_supplier = b.id_supplier
                left join tb_gudang c on a.id_gudang = c.id_gudang
                where id_item_masuk = '.$id)->row();

        if($gi->approved_1 > 0){
            $this->session->set_flashdata('failed','Tidak bisa edit! Goods In sudah terapprove');
            redirect('gi/goods_in');
        }
        $data = [
            'title'     => 'Goods In Edit '.$gi->no_gi,
            'wogc'      => true,
            'gudang'    => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted       = 0')->result(),
            'gudang_selected'    => $this->db->query('select id_gudang,nama_gudang from tb_gudang where id_gudang = '.$gi->id_gudang)->row(),
            //'supplier'  => $this->db->query('select id_supplier,nama_supplier from tb_supplier where deleted = 0')->result(),
            'supplier'  => $this->db->query('select id_supplier,nama_supplier from tb_supplier where id_supplier = '.$gi->id_supplier)->result(),
            'gi'        => $gi,
            'po'        => $this->db->query('SELECT A.id_pemesanan,no_po,created FROM tb_pemesanan A WHERE deleted = 0 ORDER BY created DESC')->result(),
        ];

        $this->load->view('transaksi/goods_in_edit',$data);
    }

    public function goods_in_update($id)
    {
        if($update){
            if(count($this->input->post('id_pemesanan')) > 0){
                $this->db->query('delete from tb_item_masuk_detail where id_item_masuk = '.$id);
                foreach ($this->input->post('id_pemesanan') as $v) {
                    $this->db->query("
                        INSERT INTO tb_item_masuk_detail (id_item_masuk,id_item,quantity,satuan,id_pemesanan,catatan)
                            select $id,a.id_item,a.quantity,a.satuan,b.id_pemesanan,a.catatan from tb_pemesanan_detail a left join tb_pemesanan b on a.id_pemesanan = b.id_pemesanan where a.id_pemesanan = $v 
                    ");
                }
                $this->session->set_flashdata('success','Goods In berhasil diupdate');
                history_action('Memperbarui data Goods In','UGI',$id);
                redirect('gi/goods_in');
            }else{
                $this->session->set_flashdata('success','Goods In berhasil diupdate! Tanpa detail');
                history_action('Memperbarui data Goods In','UGI',$id);
                redirect('gi/goods_in');
            }
        }else{
            $this->session->set_flashdata('failed','Goods In gagal diupdate!');
            redirect('gi/goods_in_edit/'.$id,'refresh');
        }
    }

    public function hapus_po($id_pemesanan,$id_item_masuk)
    {
        $delete = $this->db->query('delete from tb_item_masuk_detail where id_item_masuk = '.$id_item_masuk.' and id_pemesanan = '.$id_pemesanan);
        if($this->db->affected_rows() > 0){
            $res = [
                'status' => 200
            ];
        }else{
            $res = [
                'status' => 500
            ];
        }

        echo json_encode($res);
    }


    //Global Callback Column
    public function approved($value,$row){
        $user = $this->db->query("SELECT * FROM tb_user WHERE id_user = $value")->row();
        if($user){
            return $user->nama_user;
        }else{
            return '';
        }
    }
    

}

/* End of file Gi.php */
