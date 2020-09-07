<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Go extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function no_go($id_gudang)
    {
        $get_gudang = $this->db->query('select * from tb_gudang where id_gudang ='.$id_gudang)->row();
        $this->db->select('RIGHT(tb_item_keluar.no_go,2) as no_go', FALSE);
        $this->db->order_by('no_go','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('tb_item_keluar');   
        if($query->num_rows() <> 0){      
   
               $data = $query->row();      
               $kode = intval($data->no_go) + 1; 
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

    public function check_so($id_gudang = null,$id_item_keluar = null)
    {
        $response['data'] = [];
        if($id_gudang == null){
            $check = $this->db->query("SELECT A.id_penjualan,no_so,A.created,C.nama_gudang FROM tb_penjualan A LEFT JOIN tb_gudang C ON A.id_gudang = C.id_gudang WHERE NOT EXISTS (SELECT DISTINCT B.id_penjualan FROM tb_item_keluar_detail B WHERE A.id_penjualan = B.id_penjualan AND B.deleted = 0 AND B.id_item_keluar NOT IN (SELECT X.id_item_keluar FROM tb_item_keluar X WHERE X.deleted = 1)) AND A.deleted = 0 ORDER BY created DESC")->result();
            echo json_encode($response); exit();
        }else{
            if($id_item_keluar == null){
                $check = $this->db->query("SELECT A.id_penjualan,no_so,A.created,C.nama_gudang FROM tb_penjualan A LEFT JOIN tb_gudang C ON A.id_gudang = C.id_gudang WHERE NOT EXISTS (SELECT DISTINCT B.id_penjualan FROM tb_item_keluar_detail B WHERE A.id_penjualan = B.id_penjualan AND B.deleted = 0 AND B.id_item_keluar NOT IN (SELECT X.id_item_keluar FROM tb_item_keluar X WHERE X.deleted = 1)) AND A.deleted = 0 AND A.id_gudang = $id_gudang ORDER BY created DESC")->result();
            }else{
                $check = $this->db->query("SELECT A.id_penjualan,no_so,A.created,C.nama_gudang FROM tb_penjualan A LEFT JOIN tb_gudang C ON A.id_gudang = C.id_gudang WHERE A.deleted = 0 AND A.id_gudang = $id_gudang ORDER BY created DESC")->result();
            }
            
        }

        foreach($check as $i => $v){
            if($id_item_keluar != null){
                $go = $this->db->query("SELECT * FROM tb_item_keluar WHERE id_item_keluar = $id_item_keluar")->row();
                $detail = $this->db->query('select * from tb_item_keluar_detail where id_penjualan = '.$v->id_penjualan.' and id_item_keluar = '.$go->id_item_keluar)->row();
                if($detail){
                    $action = $detail->id_penjualan == $v->id_penjualan ? '<input type="checkbox" class="form-control" name="id_penjualan[]" value="'.$v->id_penjualan.'" checked>' : '<input type="checkbox" class="form-control" name="id_penjualan[]" value="'.$v->id_penjualan.'">';
                }else{
                    $action = '<input type="checkbox" class="form-control" name="id_penjualan[]" value="'.$v->id_penjualan.'">';
                }
            }else{
                $detail = '';
                $action = '<input type="checkbox" class="form-control" name="id_penjualan[]" value="'.$v->id_penjualan.'" >';
            }
            $response['data'][] =[
                ++$i,
                $action,
                $v->no_so,
                date('d/m/Y H:i')
            ]; 
        }
        
        echo json_encode($response);
    }

    public function goods_out()
    {
	    $access_url = 'go/goods_out';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

        $c->set_subject('Goods Out / Item Keluar');
        $c->set_table('tb_item_keluar');
        $c->where('tb_item_keluar.deleted',0);
        $c->order_by('tb_item_keluar.id_item_keluar','DESC');
        $c->display_as('id_gudang','Gudang')
          ->display_as('created','Tgl. Nota')
          ->display_as('no_go','No. GO');
        $c->set_relation('id_gudang','tb_gudang','nama_gudang',['deleted' => 0]);
        $c->columns('no_go','created','id_gudang');
        $c->unset_columns('created','updated','deleted', 'status');
        $c->unset_fields('created','updated','deleted');
        $c->unset_add();
        $c->unset_read();
        $c->unset_edit();
        
        if($this->session->userdata('id_jabatan') != 1)
        	$c->unset_delete();
        
        $c->add_action('Detail','','go/goods_out_detail','fa-file');
        
        //dicomment sementara sampai edit diperbaiki dan diverifikasi
        //$c->add_action('Edit','','go/goods_out_edit','fa-pencil');
        
        $c->callback_column('status',[$this,'status_approved']);
        $c->callback_delete([$this,'delete_goods_out']);
        $title = 'Data Goods Out';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    public function delete_goods_out($id)
    {
        $this->returStok($id);
        history_action('Menghapus data Goods Out','DGO',$id);
        $this->db->update('tb_item_keluar',['deleted' => 1],['id_item_keluar' => $id]);
        $this->db->update('tb_item_keluar_detail',['deleted' => 1],['id_item_keluar' => $id]);
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

    public function goods_out_create()
    {
        $data = [
            'title'    => 'Tambah Goods Out',
            'wogc'     => true,
            'gudang'   => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            'so'       => $this->db->query('SELECT A.id_penjualan,no_so,C.nama_gudang,A.created FROM tb_penjualan A LEFT JOIN tb_gudang C ON A.id_gudang = C.id_gudang WHERE NOT EXISTS (SELECT DISTINCT B.id_penjualan FROM tb_item_keluar_detail B WHERE A.id_penjualan = B.id_penjualan AND B.id_item_keluar NOT IN (SELECT X.id_item_keluar FROM tb_item_keluar X WHERE X.deleted = 1)) AND A.deleted = 0 ORDER BY created DESC')->result()
        ];
        $this->load->view('transaksi/goods_out_create',$data);
    }

    public function goods_out_insert()
    {
	    $id_gudang = 0;
	    if(count($this->input->post('id_penjualan')) > 0){
		    foreach ($this->input->post('id_penjualan') as $idp) {
			    $penj = $this->db->where('id_penjualan', $idp)->select('id_gudang')->get('tb_penjualan')->row();
			    if(count($penj) > 0){
				    if($id_gudang == 0){
					    $id_gudang = $penj->id_gudang;
				    }
				    else{
					    if($id_gudang != $penj->id_gudang){
						    $this->session->set_flashdata('failed','Pilih hanya SO yang berasal dari gudang yang sama!');
							redirect('go/goods_out_create','refresh');
							exit();
					    }
				    }
			    }
			}
		}
	    
	    if($id_gudang == 0){
		    $this->session->set_flashdata('failed','Goods Out gagal disimpan, pastikan anda sudah memilih SO!');
			redirect('go/goods_out_create','refresh');
			exit();
	    }
	    
        $data = [
            'no_go'       => $this->no_go($id_gudang),
            'id_gudang'   => $id_gudang,
            'creator'     => $this->session->userdata('id_user')
        ];

        $insert = $this->db->insert('tb_item_keluar',$data);
        $id = $this->db->insert_id();

        if($insert){
            if(count($this->input->post('id_penjualan')) > 0){
                foreach ($this->input->post('id_penjualan') as $v) {
                    $this->db->query("
                        INSERT INTO tb_item_keluar_detail (id_item_keluar,id_item,quantity,satuan,id_penjualan,catatan)
                            select $id,a.id_item,a.quantity,a.satuan,b.id_penjualan,a.catatan from tb_penjualan_detail a left join tb_penjualan b on a.id_penjualan = b.id_penjualan where a.id_penjualan = $v 
                    ");
                }
                $this->triggerStock($id);
                $this->session->set_flashdata('success','Goods Out berhasil disimpan');
                history_action('Membuat data Goods Out baru','CGO',$id);
                redirect('go/goods_out');
            }else{
                $this->session->set_flashdata('success','Goods Out berhasil disimpan! Tanpa detail');
                history_action('Membuat data Goods Out baru','CGO',$id);
                redirect('go/goods_out');
            }
        }else{
            $this->session->set_flashdata('failed','Goods Out gagal disimpan!');
            redirect('go/goods_out_create','refresh');
        }
    }

    public function goods_out_detail($id_item_keluar)
    {
        $detail1 = [];
        $go = $this->db->query("
            select a.no_go,a.id_item_keluar,b.nama_gudang,a.created 
                from tb_item_keluar a 
                    left join tb_gudang b on a.id_gudang = b.id_gudang 
                        where id_item_keluar = $id_item_keluar
                        ")->row();
        $penjualan = $this->db->query("
            select a.id_penjualan,a.id_item_keluar,b.no_so 
                from tb_item_keluar_detail a 
                    left join tb_penjualan b on a.id_penjualan = b.id_penjualan 
                        where a.id_item_keluar = $go->id_item_keluar 
            group by a.id_penjualan,b.no_so,a.id_item_keluar
            ")->result();
            foreach ($penjualan as $i => $p) {
                $detail1[$p->id_penjualan] = $p;
                $detail1[$p->id_penjualan]->detail = $this->db->query("
                    select a.id_item,b.nama_item,b.harga_jual,a.quantity 
                        from tb_item_keluar_detail a 
                            left join tb_item b on a.id_item = b.id_item 
                        where a.id_penjualan = $p->id_penjualan and a.id_item_keluar = $p->id_item_keluar
                ")->result();
            }
        $data = [
            'title'  => 'Goods Out detail',
            'wogc'   => true,
            'go'     => $go,
            'detail' => $detail1
        ];
        $this->load->view('report/goods_out_detail',$data);
    }

    public function goods_out_edit($id)
    {
        $go = $this->db->query('select id_item_keluar,no_go,id_gudang from tb_item_keluar where id_item_keluar = '.$id)->row();
        $data = [
            'title'    => 'Goods Out Edit '.$go->no_go,
            'wogc'     => true,
            'gudang'   => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            'so'       => $this->db->query('SELECT A.id_penjualan,no_so,created FROM tb_penjualan A WHERE deleted = 0 ORDER BY created DESC')->result(),
            'go'       => $go
        ];
        $this->load->view('transaksi/goods_out_edit',$data);
    }

    public function goods_out_update($id)
    {
        $data = [
            'id_gudang'   => $this->input->post('id_gudang'),
            'creator'     => $this->session->userdata('id_user')
        ];

        $update = $this->db->update('tb_item_keluar',$data,['id_item_keluar' =>  $id]);

        if($update){
            if(count($this->input->post('id_penjualan')) > 0){
                //Retur Stok
                $this->returStok($id);
                $this->db->query('delete from tb_item_keluar_detail where id_item_keluar = '.$id);
                foreach ($this->input->post('id_penjualan') as $v) {
                    $this->db->query("
                        INSERT INTO tb_item_keluar_detail (id_item_keluar,id_item,quantity,satuan,id_penjualan,catatan)
                            select $id,a.id_item,a.quantity,a.satuan,b.id_penjualan,a.catatan from tb_penjualan_detail a left join tb_penjualan b on a.id_penjualan = b.id_penjualan where a.id_penjualan = $v 
                    ");
                }
                $this->triggerStock($id);
                $this->session->set_flashdata('success','Goods Out berhasil diupdate');
                history_action('Memperbarui data Goods Out','UGO',$id);
                redirect('go/goods_out');
            }else{
                $this->session->set_flashdata('success','Goods Out berhasil diupdate! Tanpa detail');
                history_action('Memperbarui data Goods Out','UGO',$id);
                redirect('go/goods_out');
            }
        }else{
            $this->session->set_flashdata('failed','Goods Out gagal diupdate!');
            redirect('go/goods_out_edit/'.$id,'refresh');
        }
    }

    //Return Stok Sebelum Edit
    public function returStok($id)
    {
        $query = $this->db->query('SELECT * FROM tb_item_keluar where id_item_keluar ='.$id)->row();
        $detailGo = $this->db->query('select * from tb_item_keluar_detail where id_item_keluar = '.$id)->result();
        foreach($detailGo as $dg){
            $queryUpdateStok = $this->db->query('
                update tb_stok_gudang set stok = stok + '.$dg->quantity.' where id_gudang = '.$query->id_gudang.' and id_item = '.$dg->id_item);
                $mod = $dg->quantity;
                history_stok($dg->id_item,$mod,'Pembatalan Goods Out','GO',$id); 
        }
        return true;
    }

    public function triggerStock($id)
    {
        $query = $this->db->query('SELECT * FROM tb_item_keluar where id_item_keluar ='.$id)->row();
           $detailGo = $this->db->query('select * from tb_item_keluar_detail where id_item_keluar = '.$id)->result();
           foreach($detailGo as $dg){
                $checkStokGudangEntry = $this->db->where('id_gudang', $query->id_gudang)
	            								->where('id_item', $dg->id_item)
	            								->where('deleted', 0)
	            								->get('tb_stok_gudang')
	            								->row();
				if(count($checkStokGudangEntry) > 0){	           
	                $queryUpdateStok = $this->db->query('
                    	update tb_stok_gudang set stok = stok - '.$dg->quantity.' where id_gudang = '.$query->id_gudang.' and id_item = '.$dg->id_item);
	            } else {
		            $data = [
			            'id_item' => $dg->id_item,
			            'id_gudang' => $query->id_gudang,
			            'stok' => -1*$dg->quantity,
			        ];
			
			        $insert = $this->db->insert('tb_stok_gudang',$data);
	            }
                
                
                    
                    
                $mod = -1*$dg->quantity;
                history_stok($dg->id_item,$mod,'Goods Out','GO',$id); 
           }
        return true;
    }

    //Global Callback Column
    public function approved($value,$row){
        $user = $this->db->query("SELECT * FROM tb_user WHERE id_user = $value")->row();
        if($user){
            return $user->nama_user;
        }else{
            return '<div class="label label-danger"><i class="fa fa-times"></i></div>';
        }
    }

}

/* End of file Go.php */
