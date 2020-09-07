<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class So extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        if(!$this->session->userdata('token')){
            redirect('dashboard','refresh');
         }
        date_default_timezone_set('Asia/Jakarta');
    }

    public function no_so($metode)
    { 
        $query = $this->db->query('select no_so from tb_penjualan order by id_penjualan desc limit 1');   
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
        }else{      
            $kode = 1;
        } 
        if($kode > 9){
            $kode = $kode;
        }else{
            $kode = '0'.$kode;
        }
        $nomor_jual_max = date('ymd').$kode;
        $nomor_tampil = "0".$metode."-".$nomor_jual_max;
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
    public function sales_order()
    {
	    $access_url = 'so/sales_order';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

        $c->set_subject('Sales Order');
        $c->set_table('tb_penjualan');
        $c->where('tb_penjualan.deleted',0);
        $c->order_by('tb_penjualan.created','DESC');
        $c->display_as('id_customer','Customer')
          ->display_as('id_gudang','Gudang')
          ->display_as('id_logistik','Logistik')
          ->display_as('conf_logistik','Jenis Logistik')
          ->display_as('created','Tgl. Nota')
          ->display_as('no_so','No. SO');
        $c->set_relation('id_gudang','tb_gudang','nama_gudang',['deleted' => 0])
          ->set_relation('id_logistik','tb_logistik','nama_perusahaan_logistik',['deleted' => 0]);
        //$c->columns('no_so','created','id_gudang','id_customer','id_logistik','conf_logistik','status_logistik','dropship','diskon','grand_total','status_penjualan','detail_logistik');
        $c->columns('no_so','created','id_customer','conf_logistik','status_logistik','dropship','grand_total','status_penjualan','detail_logistik','action');
        $c->unset_columns('updated','deleted','status_lunas','creator','tanggal_jatuh_tempo',
    'tanggal_pelunasan');
        $c->callback_column('action',[$this,'approval']);
        $c->unset_fields('created','updated','deleted');
        $c->unset_add();
        $c->unset_read();
        $c->unset_edit();
        $c->unset_delete();
        
        $c->add_action('Edit','','so/sales_order_edit','fa-pencil');
        if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2){
	        $c->add_action('Tambah Retur Penjualan','','retur_penjualan/create','fa-file');
	    }
	    
	    if($this->session->userdata('id_jabatan') == 1)
	        $c->add_action('Delete','','so/delete_sales_order','fa-trash');
	        
        $c->callback_column('status_penjualan',[$this,'status_penjualan'])
          ->callback_column('detail_logistik',[$this,'detail_logistik'])
          ->callback_column('conf_logistik',[$this,'jenis_logistik'])
          ->callback_column('status_logistik',[$this,'status_logistik'])
          ->callback_column('dropship',[$this,'dropship'])
          ->callback_column('grand_total',[$this,'nominal'])
          ->callback_column('id_customer',[$this,'customer_so']);
        // $c->callback_delete([$this,'delete_sales_order']);
        $title = 'Data Sales Order';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function customer_so($value,$row)
    {
        $query = $this->db->query('select * from tb_customer where id_customer = '.$row->id_customer)->row();
        if($query){
            return $query->nama_customer;
        }else{
            return 'Walk In';
        }
    }

    public function approval($value,$row)
    {
	    /*
        if($row->approved_1 == 0){
            return '<a href="'.base_url('so/approved_kt/'.$row->id_penjualan).'" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Kepala Toko</a>';
        }else{
            return 'No Action';
        }*/
        return false;
    }


	
    public function approved_kt($id)
    {
	    /*
        $query = $this->db->query("UPDATE TB_PENJUALAN SET APPROVED_1 = 1 WHERE ID_PENJUALAN = $id");

        if($query){
            return redirect('so/sales_order');
        }else{
            $this->session->set_flashdata('error','Gagal approve SO!');
            return redirect('so/sales_order');
        }*/
        return false;
    }

    public function delete_sales_order($id)
    {
        // history_action('Menghapus Sales Order','DSO',$id);
        // return $this->db->update('tb_penjualan',['deleted' => 1],['id_penjualan' => $id]);
        $so = $this->db->query("SELECT * FROM TB_PENJUALAN WHERE ID_PENJUALAN = $id")->row();
        //Cek GO
        $cekgo = $this->db->query("SELECT * FROM TB_ITEM_KELUAR_DETAIL A LEFT JOIN TB_ITEM_KELUAR B ON A.ID_ITEM_KELUAR = B.ID_ITEM_KELUAR WHERE ID_PENJUALAN = $id AND B.DELETED = 0")->result();
        
        if(count($cekgo) == 0){
            //Cek Retur
            $cekretur = $this->db->query("SELECT * FROM TB_RETUR_PENJUALAN WHERE ID_PENJUALAN = $id AND DELETED = 0")->result();
            if(count($cekretur) == 0){
                history_action('Menghapus Sales Order','DSO',$id);
                $this->db->update('tb_penjualan',['deleted' => 1],['id_penjualan' => $id]);
                $this->session->set_flashdata('success','Sales Order berhasil dihapus!');
                return redirect('so/sales_order','refresh');
            }else{
                $this->session->set_flashdata('failed','Sales Order '.$so->no_so.' terdapat data Retur, untuk menghapus Sales Order silahkan hapus data Retur terlebih dahulu!');
                return redirect('so/sales_order','refresh');
            }
        }else{
            $this->session->set_flashdata('failed','Sales Order '.$so->no_so.' terdapat data Goods Out, untuk menghapus Sales Order silahkan hapus data Goods Out terlebih dahulu!');
            return redirect('so/sales_order','refresh');
            
        }
    }

    public function status_penjualan($value,$row)
    {
        if($row->status_penjualan == 0){
            return '<div class="label label-warning"><a onclick="return window.confirm(\'Anda Yakin akan mengubah status SO menjadi
            Selesai?\');" href="'.base_url('so/status_penjualan_action/1/'.$row->id_penjualan).'" >Pending</a></div>';
        }else if($row->status_penjualan == 1){
            return '<div class="label label-success"><a onclick="return window.confirm(\'Anda Yakin akan mengubah status SO menjadi
            Pending?\');" href="'.base_url('so/status_penjualan_action/0/'.$row->id_penjualan).'">Selesai</a></div>';
        }else{
            return '<div class="label label-danger">Batal</div>';
        }
    }

    public function status_penjualan_action($param,$id)
    {
        $update = $this->db->query('update tb_penjualan set status_penjualan = '.$param.' where id_penjualan = '.$id); 
        redirect('so/sales_order','refresh');
    }
    
    public function status_logistik_action($param,$id)
    {
        $update = $this->db->query('update tb_penjualan set status_logistik = '.$param.' where id_penjualan = '.$id); 
        redirect('so/sales_order','refresh');
    }

    public function jenis_logistik($value,$row)
    {
        if($row->conf_logistik == 0){
            return '<div class="label label-primary">Internal</div>';
        }else if($row->conf_logistik == 1){
	        $namalogistik = $this->db->query("select nama_perusahaan_logistik from tb_logistik where id_logistik = ".$row->id_logistik)->row();
            return '<div class="label label-warning">External</div><br>'.$namalogistik->nama_perusahaan_logistik;
        }else{
            //return '<div class="label label-danger">Tanpa Logistik</div>';
            return '';
        }
    }

    public function status_logistik($value,$row)
    {
        if($row->status_logistik == 0){
	        return '<div class="label label-danger"><a onclick="return window.confirm(\'Anda Yakin akan mengubah status logistik menjadi
            Approved?\');" href="'.base_url('so/status_logistik_action/1/'.$row->id_penjualan).'" >Pending</a></div>';
            //return '<div class="label label-danger">Pending</div>';
        }else{
	        return '<div class="label label-success"><a onclick="return window.confirm(\'Anda Yakin akan mengubah status logistik menjadi
            Pending?\');" href="'.base_url('so/status_logistik_action/0/'.$row->id_penjualan).'">Approved</a></div>';
            //return '<div class="label label-success">Approved</div>';
        }
    }

    public function dropship($value,$row)
    {
        if($row->dropship == 0){
            //return '<div class="label label-danger">Tidak</div>';
            return '';
        }else{
            return '<div class="label label-success">Ya</div>';
        }
    }

    public function detail_logistik($value,$row)
    {
        if($row->conf_logistik != 2){
            // if($row->status_logistik == 0){
                return '<a href="'.base_url().'so/input_detail_logistik/'.$row->id_penjualan.'" class="btn btn-primary btn-sm"><i class="fa fa-file"></i> Detail Logistik</a>';
            // }else{
            //     return 'No Action';
            // }
        }else{
            return 'No Action';
        }
    }

    // public function retur_penjualan($value,$row)
    // {
    //     return '<a href="'.site_url('retur_penjualan/list/'.$row->id_penjualan).'" class="btn btn-warning btn-sm"><i class="fa fa-file"></i> Retur Penjualan</a>';
    // }

    //Create Page Sales Order
    public function sales_order_create()
    {
        $data = [
            'title' => 'Sales Order',
            'wogc' => true,
            'gudang' => $this->db->query('select id_gudang,nama_gudang,jenis_gudang from tb_gudang where deleted = 0')->result(),
            'customer' => $this->db->query('select id_customer,nama_customer from tb_customer where deleted = 0')->result(),
            'logistik' => $this->db->query('select id_logistik,nama_perusahaan_logistik from tb_logistik where deleted = 0')->result(),
            'metode_pembayaran' => $this->db->query('select * from tb_metode_pembayaran where deleted = 0')->result(),
            'diskon' => $this->db->query('select * from tb_diskon where deleted = 0 and status_diskon = 0')->result()
        ];

        $this->load->view('transaksi/sales_order_create',$data);
    }

    //Insert Sales Order
    public function sales_order_insert()
    {
        $total  = 0;
        $total_diskon = 0;
        $data = [
            'no_so'                => $this->no_so($this->input->post('id_metode_pembayaran')),
            'id_customer'          => $this->input->post('id_customer'),
            'id_gudang'            => $this->input->post('id_gudang'),
            'id_logistik'          => $this->input->post('id_logistik'),
            'conf_logistik'        => $this->input->post('conf_logistik'),
            'status_logistik'      => $this->input->post('status_logistik'),
            'dropship'             => $this->input->post('dropship'),
            'ppn'                  => $this->input->post('ppn'),
            'creator'              => $this->session->userdata('id_user'),
            'status_penjualan'     => $this->input->post('status'),
            'id_metode_pembayaran' => $this->input->post('id_metode_pembayaran'),
            'tanggal_jatuh_tempo'  => $this->input->post('tanggal_jatuh_tempo'),
            'tanggal_pelunasan'    => $this->input->post('tanggal_pelunasan')
        ];

        $insert = $this->db->insert('tb_penjualan',$data);
        if($insert){
            $id = $this->db->insert_id();
            if(count($this->input->post('id_item')) > 0){
                foreach ($this->input->post('id_item') as $i => $v) {
                    if($this->input->post('diskon')[$i]!=0){
                        $getDiskon = $this->db->query('select * from tb_diskon where id_diskon = '.$this->input->post('diskon')[$i])->row();
                        $diskon = $getDiskon->jenis_diskon == 0 ? $getDiskon->nominal_diskon : ($getDiskon->nominal_diskon * $this->input->post('harga')[$i]) / 100;
                    }else{
                        $diskon = 0;
                    }
                    $detail = [
                        'id_penjualan'   => $id,
                        'id_item'        => $this->input->post('id_item')[$i],
                        'harga'          => $this->input->post('harga')[$i],
                        'id_diskon'      => $this->input->post('diskon')[$i],
                        'quantity'       => $this->input->post('quantity')[$i],
                        'biaya_logistik' => $this->input->post('biaya_logistik')[$i],
                        'catatan'        => $this->input->post('catatan')[$i],
                        'nominal_diskon' => $diskon,
                        'subtotal'       => $this->input->post('subtotal')[$i]
                    ];
                    $total += $this->input->post('subtotal')[$i];
                    $total_diskon += $diskon;
                    $this->db->insert('tb_penjualan_detail',$detail);
                }
                $grandTotal = $total - $total_diskon;
                $ppn = $this->input->post('ppn') == 0 ? 0 : ($this->input->post('ppn') * $total) / 100;
                $grandTotal = $grandTotal + $ppn;
                $this->db->update('tb_penjualan',['grand_total' => $grandTotal,'subtotal' => $total, 'ppn_nominal' => $ppn ],['id_penjualan' => $id]);
                $this->session->set_flashdata('success','Berhasil menyimpan Sales Order!');
                if($this->input->post('submitAndSelf') == '1'){
                    history_action('Membuat Sales Order','CSO',$id);
                    redirect('so/sales_order_create','refresh');
                }else if($this->input->post('submitAndBack') == '1'){
                    history_action('Membuat Sales Order','CSO',$id);
                    redirect('so/sales_order','refresh');
                }                   
            }else{
                $this->session->set_flashdata('success','Berhasil menyimpan Sales Order! Tanpa detail!');
                if($this->input->post('submitAndSelf') == '1'){
                    history_action('Membuat Sales Order','CSO',$id);
                    redirect('so/sales_order_create','refresh');
                }else if($this->input->post('submitAndBack') == '1'){
                    history_action('Membuat Sales Order','CSO',$id);
                    redirect('so/sales_order','refresh');
                }
            }
        }else{
            $this->session->set_flashdata('failed','Gagal menyimpan Sales Order!');
            redirect('so/sales_order_create','refresh');
        }
    }

    public function sales_order_edit($id)
    {
	    if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2){
	        $check_so = $this->db->query("SELECT A.id_penjualan,no_so,A.created,C.nama_gudang FROM tb_penjualan A LEFT JOIN tb_gudang C ON A.id_gudang = C.id_gudang WHERE (EXISTS (SELECT DISTINCT B.id_penjualan FROM tb_item_keluar_detail B WHERE A.id_penjualan = B.id_penjualan AND B.deleted = 0 AND B.id_item_keluar NOT IN (SELECT X.id_item_keluar FROM tb_item_keluar X WHERE X.deleted = 1)) OR EXISTS (SELECT DISTINCT D.id_penjualan FROM tb_retur_penjualan D WHERE A.id_penjualan = D.id_penjualan AND D.deleted = 0)) AND A.deleted = 0 AND A.id_penjualan = $id ORDER BY created DESC")->result();
	        //echo $this->db->last_query(); exit();
	        if(count($check_so) > 0){
	            $check = 1;
	        }else{
	            $check = 0;
	        }
        } else {
	        $check = 1;
        }

        $data = [
            'title'    => 'Sales Order Edit',
            'wogc'     => true,
            'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            'customer' => $this->db->query('select id_customer,nama_customer from tb_customer where deleted = 0')->result(),
            'logistik' => $this->db->query('select id_logistik,nama_perusahaan_logistik from tb_logistik where deleted = 0')->result(),
            'metode_pembayaran' => $this->db->query('select * from tb_metode_pembayaran where deleted = 0')->result(),
            'so'       => $this->db->query('select * from tb_penjualan where id_penjualan = '.$id)->row(),
            'detail_so'=> $this->db->query('select a.id_item,harga,quantity,subtotal,nama_item,biaya_logistik,id_diskon,nominal_diskon,catatan from tb_penjualan_detail a left join tb_item b on a.id_item = b.id_item where id_penjualan ='.$id)->result(),
            'diskon' => $this->db->query('select * from tb_diskon where deleted = 0 and status_diskon = 0')->result(),
            'check'  => $check
        ];

        $this->load->view('transaksi/sales_order_edit',$data);
    }

    public function sales_order_update($id)
    {
        $total  = 0;
        $total_diskon = 0;
        $data = [
            'id_customer'          => $this->input->post('id_customer'),
            'id_gudang'            => $this->input->post('id_gudang'),
            'id_logistik'          => $this->input->post('id_logistik'),
            'conf_logistik'        => $this->input->post('conf_logistik'),
            'status_logistik'      => $this->input->post('status_logistik'),
            'dropship'             => $this->input->post('dropship'),
            'ppn'                  => $this->input->post('ppn'),
            'creator'              => $this->session->userdata('id_user'),
            'status_penjualan'     => $this->input->post('status'),
            'id_metode_pembayaran' => $this->input->post('id_metode_pembayaran'),
            'tanggal_jatuh_tempo'  => $this->input->post('tanggal_jatuh_tempo'),
            'tanggal_pelunasan'    => $this->input->post('tanggal_pelunasan')
        ];

        $update = $this->db->update('tb_penjualan',$data,['id_penjualan' => $id]);
        if($update){
            $this->db->query('delete from tb_penjualan_detail where id_penjualan = '.$id);
            if(count($this->input->post('id_item')) > 0){
	            foreach ($this->input->post('id_item') as $i => $v) {
                    if($this->input->post('diskon')[$i]!=0){
                        $getDiskon = $this->db->query('select * from tb_diskon where id_diskon = '.$this->input->post('diskon')[$i])->row();
                        $diskon = $getDiskon->jenis_diskon == 0 ? $getDiskon->nominal_diskon : ($getDiskon->nominal_diskon * $this->input->post('harga')[$i]) / 100;
                    }else{
                        $diskon = 0;
                    }
                    $detail = [
                        'id_penjualan'   => $id,
                        'id_item'        => $this->input->post('id_item')[$i],
                        'harga'          => $this->input->post('harga')[$i],
                        'id_diskon'      => $this->input->post('diskon')[$i],
                        'quantity'       => $this->input->post('quantity')[$i],
                        'biaya_logistik' => $this->input->post('biaya_logistik')[$i],
                        'catatan'        => $this->input->post('catatan')[$i],
                        'nominal_diskon' => $diskon,
                        'subtotal'       => $this->input->post('subtotal')[$i]
                    ];
                    $total += $this->input->post('subtotal')[$i];
                    $total_diskon += $diskon;
                    $this->db->insert('tb_penjualan_detail',$detail);
                }
                //Update total
                $grandTotal = $total - $diskon;
                $ppn = $this->input->post('ppn') == 0 ? 0 : ($this->input->post('ppn') * $total) / 100;
                $grandTotal = $grandTotal + $ppn;
                $this->db->update('tb_penjualan',['grand_total' => $grandTotal,'subtotal' => $total, 'ppn_nominal' => $ppn ],['id_penjualan' => $id]);

                $this->session->set_flashdata('success','Berhasil menyimpan Sales Order!');
                if($this->input->post('submitAndSelf') == '1'){
                    history_action('Edit Sales Order','ESO',$id);
                    redirect('so/sales_order_create','refresh');
                }else if($this->input->post('submitAndBack') == '1'){
                    history_action('Edit Sales Order','ESO',$id);
                    redirect('so/sales_order','refresh');
                }
                else{
	                history_action('Edit Sales Order','ESO',$id);
                    redirect('so/sales_order','refresh');
                }
            }else{
	            $this->session->set_flashdata('success','Berhasil menyimpan Sales Order! Tanpa detail!');
                if($this->input->post('submitAndSelf') == '1'){
                    history_action('Edit Sales Order','ESO',$id);
                    redirect('so/sales_order_create','refresh');
                }else if($this->input->post('submitAndBack') == '1'){
                    history_action('Edit Sales Order','ESO',$id);
                    redirect('so/sales_order','refresh');
                }
                else{
	                history_action('Edit Sales Order','ESO',$id);
                    redirect('so/sales_order','refresh');
                }
            }
        }else{
            $this->session->set_flashdata('failed','Gagal menyimpan Sales Order!');
            redirect('so/sales_order_edit/'.$id,'refresh');
        }
    }

    public function input_detail_logistik($id)
    {
        $data = [
            'title'    => 'Sales Order Edit',
            'wogc'     => true,
            'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            'customer' => $this->db->query('select id_customer,nama_customer from tb_customer where deleted = 0')->result(),
            'logistik' => $this->db->query('select id_logistik,nama_perusahaan_logistik from tb_logistik where deleted = 0')->result(),
            'metode_pembayaran' => $this->db->query('select * from tb_metode_pembayaran where deleted = 0')->result(),
            'so'       => $this->db->query('select * from tb_penjualan where id_penjualan = '.$id)->row(),
            'detail_so'=> $this->db->query('select id_penjualan_detail,a.id_item,harga,quantity,subtotal,nama_item,biaya_logistik from tb_penjualan_detail a left join tb_item b on a.id_item = b.id_item where id_penjualan ='.$id)->result(),
            'diskon' => $this->db->query('select * from tb_diskon where deleted = 0 and status_diskon = 0')->result()
        ];

        $this->load->view('transaksi/input_detail_logistik',$data);
    }

    public function input_detail_logistik_action($id)
    {
        $total = 0;
        $getSales = $this->db->query('select * from tb_penjualan where id_penjualan = '.$id)->row();
        $data = [
            'id_logistik' => $this->input->post('id_logistik'),
            'conf_logistik' => $this->input->post('conf_logistik'),
            'status_logistik' => $this->input->post('status_logistik')
        ];

        $update = $this->db->update('tb_penjualan',$data,['id_penjualan' => $id]);

        if($update){
            if(count($this->input->post('id_penjualan_detail')) > 0){
                foreach($this->input->post('id_penjualan_detail') as $i => $d){
                    $updateDetail = [
                        'biaya_logistik' => $this->input->post('biaya_logistik')[$i],
                        'subtotal' =>  $this->input->post('subtotal')[$i]
                    ];

                    $this->db->update('tb_penjualan_detail',$updateDetail,['id_penjualan_detail' => $this->input->post('id_penjualan_detail')[$i]]);
                    $total += $this->input->post('subtotal')[$i];
                }

                //Recalculate 
                if($getSales->diskon != 0){
                    $getDiskon = $this->db->query('select * from tb_diskon where id_diskon = '.$getSales->diskon)->row();
                    $diskon = $getDiskon->jenis_diskon == 0 ? $getDiskon->nominal_diskon : ($getDiskon->nominal_diskon * $total) / 100;
                }else{
                    $diskon = 0;
                }
                $grandTotal = $total - $diskon;
                $ppn = $getSales->ppn == 0 ? 0 : ($getSales->ppn * $total) / 100;
                $grandTotal = $grandTotal + $ppn;
                $this->db->update('tb_penjualan',['grand_total' => $grandTotal,'subtotal' => $total, 'ppn_nominal' => $ppn ],['id_penjualan' => $id]);
                $this->session->set_flashdata('success','Berhasil memperbarui Detail Logistik!');
                redirect('so/input_detail_logistik/'.$id,'refresh');
            }
        }else{
            $this->session->set_flashdata('failed','Error, gagal memperbarui Detail Logistik!');
                redirect('so/sales_order/','refresh');
        }
    }

    //Global Callback Column
    public function nominal($value,$row)
    {
        return number_format($value);
    }

    public function diskon()
    {
        $query = $this->db->query('select * from tb_diskon where deleted  = 0 and status_diskon = 0')->result();
        echo json_encode($query);
    }

}

/* End of file So.php */
