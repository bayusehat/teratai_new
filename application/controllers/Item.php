<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Item extends CI_Controller {
	
	public $arrIdGudang = array();
	public $arridx = 0;
		
    public function __construct()
    {
        parent::__construct();
        if(empty($this->session->userdata('token'))){
            redirect('dashboard','refresh');
        }
        date_default_timezone_set('Asia/Jakarta');
    }

    //Stok Gudang
    public function stok_gudang()
    {
	    $access_url = 'item/stok_gudang';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

        $c->set_subject('Stok Gudang');
        $c->set_table('tb_stok_gudang');
        $c->where('tb_stok_gudang.deleted',0);
        $c->order_by('id_stok_gudang','ASC');
        $c->required_fields(
           'stok','id_gudang','id_item'
        );
        $c->display_as('id_item','Item')
          ->display_as('id_gudang','Gudang');
        $c->set_relation('id_item','tb_item','nama_item',['deleted' => 0])
          ->set_relation('id_gudang','tb_gudang','nama_gudang',['deleted' => 0]);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        
        $c->unset_add();
        $c->unset_read();
        $c->unset_edit();
        
        $c->unset_delete();
        $c->callback_after_insert([$this,'add_history_stok']);
        $c->callback_update([$this,'edit_history_stok']);
        $c->callback_delete([$this,'delete_stok_gudang']);
        $title = 'Data Stok Per Gudang';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function add_history_stok($post_array,$id)
    {
        $getGudang = $this->db->query('select * from tb_gudang where id_gudang = '.$post_array['id_gudang'])->row();
        history_stok($post_array['id_item'],$post_array['stok'],'Tambah Stok Untuk Gudang '.$getGudang->nama_gudang,'SG',$id);

        return $post_array;
    }

    public function edit_history_stok($post_array,$id)
    {
        $getStok = $this->db->query('select * from tb_stok_gudang where id_stok_gudang = '.$id)->row();
        $getGudang = $this->db->query('select * from tb_gudang where id_gudang = '.$post_array['id_gudang'])->row();
        
        $mod = $post_array['stok'] - $getStok->stok;

        history_stok($post_array['id_item'],$mod,'Perubahan Stok untuk Gudang '.$getGudang->nama_gudang,'SG',$id);

        return $this->db->update('tb_stok_gudang',$post_array,['id_stok_gudang' => $id]);
    }

    public function delete_stok_gudang($id)
    {
        $this->db->update('tb_stok_gudang',['deleted' => 1],['id_stok_gudang' => $id]);
        return history_action('Menghapus stok gudang','DSGU',$id);
    }
    //End Stok Gudang
    
    //Mutasi Barang
    public function mutasi()
    {
	    $access_url = 'item/mutasi';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

        $c->set_subject('Mutasi');
        $c->set_table('tb_mutasi');
        $c->where('tb_mutasi.deleted',0);
        $c->order_by('id_mutasi','ASC');
        $c->unset_edit();
        $c->unset_add();
        $c->unset_read();
        $c->columns('tanggal_mutasi','gudang_asal','gudang_tujuan','creator','status','approval');
        $c->set_relation('creator','tb_user','nama_user');
        $c->callback_column('gudang_asal',[$this,'nama_gudang_asal'])
          ->callback_column('gudang_tujuan',[$this,'nama_gudang_tujuan'])
          ->callback_column('status',[$this,'status'])
          ->callback_column('approval',[$this,'approval_mutasi']);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->callback_delete([$this,'delete_mutasi']);
        $c->add_action('Edit','','item/edit_mutasi','fa fa-edit');
        $title = 'Data Mutasi';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function delete_mutasi($id)
    {
        $checkApproval = $this->db->query('SELECT * from tb_mutasi where approval <> 0 and id_mutasi = '.$id)->row();
        if($checkApproval){
            $getDetail = $this->db->query('SELECT * from tb_mutasi_detail where id_mutasi = '.$id)->result();
            foreach($getDetail as $gmd){
                //Reverse Stok
                $queSubstract = $this->db->query("
                    UPDATE tb_stok_gudang SET stok = stok - $gmd->quantity where id_gudang = $checkApproval->gudang_tujuan and id_item = $gmd->id_item
                ");
                $quePlus      = $this->db->query("
                    UPDATE tb_stok_gudang SET stok = stok + $gmd->quantity where id_gudang = $checkApproval->gudang_asal and id_item = $gmd->id_item
                ");
                history_stok($gmd->item,$gmd->quantity,'Hapus Mutasi Barang','MB',$id);
                history_action('Menghapus mutasi','DMU',$id);
            }
            return $this->db->update('tb_mutasi',['deleted' => 1],['id_mutasi' => $id]);
        }else{
            return $this->db->update('tb_mutasi',['deleted' => 1],['id_mutasi' => $id]);
        }
    }

    public function nama_gudang_asal($value,$row)
    {
        $nm = $this->db->query('SELECT nama_gudang FROM tb_gudang WHERE id_gudang='.$row->gudang_asal)->row();
        if($nm){
            return $nm->nama_gudang;
        }
    }

    public function nama_gudang_tujuan($value,$row)
    {
        $nm = $this->db->query('SELECT nama_gudang FROM tb_gudang WHERE id_gudang='.$row->gudang_tujuan)->row();
        if($nm){
            return $nm->nama_gudang;
        }
    }

    public function status($value,$row)
    {
        if($row->approval == 0){
            return '<div class="label label-danger">Belum ada Approval</div>';
        }else{
            return '<div class="label label-success">Approved</div>';
        }
    }

    public function create_mutasi()
    {
        $data = [
            'title'  => 'Tambah Mutasi Barang',
            'wogc'   => true,
            'gudang' => $this->db->query('SELECT id_gudang,nama_gudang FROM tb_gudang WHERE deleted = 0')->result(),
        ];

        $this->load->view('item/create_mutasi',$data);
    }

    public function doCreateMutasi()
    {
        $data = [
            'tanggal_mutasi' => $this->input->post('tanggal_mutasi'),
            'gudang_asal'    => $this->input->post('gudang_asal'),
            'gudang_tujuan'  => $this->input->post('gudang_tujuan'),
            'catatan'        => $this->input->post('catatan'),
            'creator'        => $this->session->userdata('id_user'),
        ];

        $insert = $this->db->insert('tb_mutasi',$data);
        $id     = $this->db->insert_id();

        if($insert){
            foreach ($this->input->post('id_item') as $i => $v) {
                $detail = [
                    'id_mutasi' => $id,
                    'id_item' => $this->input->post('id_item')[$i],
                    'quantity'  => $this->input->post('quantity')[$i]
                ];
                $this->db->insert('tb_mutasi_detail',$detail);
            }
            $this->session->set_flashdata('success','Mutasi berhasil dibuat!');
            history_action('Menambah mutasi barang','CMU',$id);
            redirect('item/create_mutasi');
        }else{
            $this->session->set_flashdata('failed','Terjadi kesalahan! mutasi tidak dapat dibuat');
            redirect('item/create_mutasi');
        }
    }

    public function edit_mutasi($id)
    {
        $data = [
            'title'  => 'Edit Mutasi',
            'wogc'   => true,
            'gudang' => $this->db->query('SELECT id_gudang,nama_gudang FROM tb_gudang WHERE deleted = 0')->result(),
            'mutasi' => $this->db->query('SELECT id_mutasi,tanggal_mutasi,catatan,gudang_asal,gudang_tujuan FROM tb_mutasi where deleted = 0 and id_mutasi = '.$id)->row(),
            'detail' => $this->db->query('SELECT a.id_item, nama_item,quantity FROM tb_mutasi_detail a LEFT JOIN tb_item b on a.id_item = b.id_item WHERE id_mutasi = '.$id)->result()
        ];

        $this->load->view('item/update_mutasi',$data);
    }

    public function doUpdateMutasi($id)
    {
        $data = [
            'tanggal_mutasi' => $this->input->post('tanggal_mutasi'),
            'gudang_asal'    => $this->input->post('gudang_asal'),
            'gudang_tujuan'  => $this->input->post('gudang_tujuan'),
            'catatan'        => $this->input->post('catatan'),
            'creator'        => $this->session->userdata('id_user'),
        ];

        $update = $this->db->update('tb_mutasi',$data,['id_mutasi' => $id]);

        if($update){
            $delRow = $this->db->query('DELETE FROM tb_mutasi_detail WHERE id_mutasi = '.$id);
            foreach ($this->input->post('id_item') as $i => $v) {
                $detail = [
                    'id_mutasi' => $id,
                    'id_item' => $this->input->post('id_item')[$i],
                    'quantity'  => $this->input->post('quantity')[$i]
                ];
                $this->db->insert('tb_mutasi_detail',$detail);
            }
            $this->session->set_flashdata('success','Mutasi berhasil diupdate!');
            history_action('Memperbarui mutasi barang','UMU',$id);
            redirect('item/mutasi');
        }else{
            $this->session->set_flashdata('failed','Terjadi kesalahan! mutasi tidak dapat diupdate');
            redirect('item/update_mutasi/'.$id);
        }
    }

    public function approval_mutasi($value,$row)
    {
        if($this->session->userdata('id_user') == 3){
            if($row->approved_by == 0){
                return '<a href="'.base_url('item/approve_mutasi/'.$row->id_mutasi).'"><i class="fa fa-check"></i> Approve KS</a>';
            }else{
                return 'No Action';   
            }
        }else{
            return 'No Action';
        }
    }

    public function approve_mutasi($id)
    {
        $getMutasi       = $this->db->query('SELECT gudang_asal,gudang_tujuan from tb_mutasi where id_mutasi = '.$id)->row();
        $getDetailMutasi = $this->db->query('SELECT * FROM tb_mutasi_detail WHERE id_mutasi                  = '.$id)->result();
        if(count($getDetailMutasi) > 0){
            foreach($getDetailMutasi as $gmd){
                $queSubstract = $this->db->query("
                    UPDATE tb_stok_gudang SET stok = stok - $gmd->quantity where id_gudang = $getMutasi->gudang_asal and id_item = $gmd->id_item
                ");
                $quePlus      = $this->db->query("
                    UPDATE tb_stok_gudang SET stok = stok + $gmd->quantity where id_gudang = $getMutasi->gudang_tujuan and id_item = $gmd->id_item
                ");
            }
            $this->db->query('UPDATE tb_mutasi SET approved_by = '.$this->session->userdata('id_user').' where id_mutasi='.$id);
            history_stok($gmd->id_item,$gmd->quantity,'Mutasi Barang','MB',$id);
            history_action('Approval mutasi barang','AMU',$id);
            $this->session->set_flashdata('success','Item berhasil dimutasi, cek Pencatatan log Stok untuk validasi!');
            redirect('item/mutasi');
        }else{
            $this->session->set_flashdata('failed','Tidak ada barang yang harus dimutasi!');
            redirect('item/mutasi');
        }
    }
    //End Mutasi Barang
    //Komponen Harga Jual
    public function komponen_harga_jual()
    {
	    $access_url = 'item/komponen_harga_jual';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();
		
        $c->set_subject('Komponen Harga Jual');
        $c->set_table('tb_komponen_harga_jual');
        $c->where('tb_komponen_harga_jual.deleted',0);
        $c->order_by('id_komponen_harga_jual','DESC');
        $c->set_relation('id_item','tb_item','nama_item');
        $c->display_as('id_item','Item');
        $c->display_as('margin','Margin (%)');
        $c->required_fields(
            'id_item','harga_modal',
            'biaya_kirim','margin'
        );
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->callback_column('margin',[$this,'percent'])
          ->callback_column('harga_modal',[$this,'nominal'])
          ->callback_column('harga_jual',[$this,'nominal'])
          ->callback_column('margin_akhir',[$this,'nominal'])
          ->callback_column('total_hpp',[$this,'nominal'])
          ->callback_column('biaya_kirim',[$this,'nominal']);
        $c->callback_after_insert([$this,'set_harga_jual']);
        $c->callback_after_update([$this,'set_harga_jual']);
        $c->callback_delete([$this,'delete_komponen_harga_jual']);
        
        $gcstate = $c->getState();
        $titleprefix = '';
        if($gcstate == 'add')
	        $titleprefix = 'Tambah ';
	    else if($gcstate == 'edit')
	        $titleprefix = 'Edit ';
        
        $title = $titleprefix.'Data Komponen Harga Jual';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function set_harga_jual($post_array,$id)
    {
        $harga_modal = $post_array['harga_modal'];
        $biaya_kirim = $post_array['biaya_kirim'];
        $margin      = $post_array['margin'];
        

        $harga_beli  = $harga_modal + $biaya_kirim;
        $getNominalMargin = ($margin / 100) * $harga_beli;
        
        $harga_jual = $harga_beli + $getNominalMargin;
        $this->db->query("UPDATE tb_komponen_harga_jual SET harga_jual = $harga_jual, margin_akhir = $getNominalMargin, total_hpp = $harga_beli WHERE id_komponen_harga_jual = $id");
        history_action('Menambah komponen harga jual','CHJ',$id);
        return $this->db->update('tb_item',['harga_jual' => $harga_jual,'harga_modal' => $harga_modal],['id_item' => $post_array['id_item']]);
    }

    public function delete_komponen_harga_jual($id)
    {
       $this->db->update('tb_komponen_harga_jual',['deleted' => 1],['id_komponen_harga_jual' => $id]);
       return history_action('Menghapus komponen harga jual','DHJ',$id);
    }
    //End Komponen Harga Jual

    //Search Item
    public function search_item()
    {
        $return = '';
        $item = $this->input->post('item');

        $query = $this->db->query("
            SELECT * FROM tb_item WHERE deleted <> 1 and nama_item like '%$item%' OR sku_item like '%$item%'
        ")->result();

        if(!empty($query)){
            foreach($query as $a){
                $return .= "<li>
                                <a href='javascript:void(0);' onclick='addToTable($a->id_item,\"".addslashes($a->nama_item)."\")'>$a->nama_item</a>
                            </li>";
            }
        }else{
            $return .= '<li>Item tidak ditemukan!</li>';
        }

        echo $return;
    }
    //End Search Item

    //History Stok
    public function history_stok()
    {
	    $access_url = 'item/history_stok';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

        $c->set_subject('History Stok');
        $c->set_table('tb_history_stok');
        $c->where('tb_history_stok.deleted',0);
        $c->order_by('tanggal','DESC');
        $c->display_as('id_item','Item')
          ->display_as('id','Source');
        $c->set_relation('id_item','tb_item','nama_item',['deleted' => 0]);
        $c->unset_columns('created','updated','deleted');
        $c->unset_fields('created','updated','deleted');
        $c->unset_delete();
        $c->unset_read();
        $c->unset_add();
        $c->unset_edit();
        $c->callback_column('id',[$this,'source']);
        $title = 'Data History Stok';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output); 
    }

    public function source($value,$row)
    {
        if($row->flag == 'GI'){
            $get = $this->db->query('select * from tb_item_masuk where id_item_masuk = '.$row->id)->row();
            if($get){
                return $get->no_gi;
            }else{
                return '';
            }
        }else if($row->flag == 'GO'){
            $get= $this->db->query('select * from tb_item_keluar where id_item_keluar = '.$row->id)->row();
            return $get->no_go;
        }else if($row->flag == 'SG'){
            $get = $this->db->query('select * from tb_stok_gudang where id_stok_gudang = '.$row->id)->row();
            return 'Stok Gudang ID '.$get->id_stok_gudang. ' - '.date('d/m/Y H:i',strtotime($get->created));
        }else if($row->flag == 'SOP'){
            $get = $this->db->query('select * from tb_stock_opname where id_stock_opname = '.$row->id)->row();
            return 'Stock Opname ID '.$get->id_stock_opname.' / '.$get->tanggal_stock_opname;
        }
    }
    //End History Stok

    //Stock Opname
    public function stock_opname()
    {
	    $access_url = 'item/stock_opname';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

			$c->set_subject('Stock Opname');
			$c->where('tb_stock_opname.deleted',0);
			$c->order_by('id_stock_opname','DESC');
			$c->set_table('tb_stock_opname');
			$c->unset_columns('created','updated','deleted');
			$c->unset_fields('created','updated','deleted');
			$c->required_fields('tanggal','status');
			$c->columns('tanggal_stock_opname','status','id_user','detail');
			$c->field_type('status','dropdown',array('Open' => 'Open', 'Close' => 'Close'));
			$c->callback_column('status',array($this,'status_label'))
              ->callback_column('detail',array($this,'detail_opname'));
			$c->set_relation('id_user','tb_user','nama_user');
			$c->display_as('id_user','User');
            $c->callback_field('id_user',array($this,'set_value_user'));
            $c->callback_after_insert([$this,'creator_stock_opname']);
            $c->callback_after_insert([$this,'editor_stock_opname']);
			$c->callback_delete(array($this,'delete_stock_opname'));
			$c->add_action('Detail', '', 'item/detail_stock_opname', 'fa-file');
			$title = 'Stock Opname';
			$this->load->vars( array('title' => $title,'wogc'=>false));
			$output = $c->render();
			$this->load->view('gc/template_gc', $output);
    }

    public function creator_stock_opname($post_array,$id)
    {
        return history_action('Menambah data stock opname baru','CSOP',$id);
    }

    public function editor_stock_opname($post_array,$id)
    {
        return history_action('Memperbarui data stock opname','USOP',$id);
    }
    
    public function stock_sheet()
    {
	    $access_url = 'item/stock_sheet';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
        $c = new grocery_CRUD();

			$c->set_subject('Stock Sheet (Klik Export atau Print)');
			$c->where('tb_item.deleted',0);
			$c->order_by('sku_item','DESC');
			$c->set_table('tb_item');
			$gudangs = $this->db->query('select * from tb_gudang where deleted = 0')->result();
			$cols = array('sku_item', 'nama_item');
			foreach($gudangs as $gudang){
				$cols[] = "G".$gudang->id_gudang.": ".$gudang->nama_gudang;
				$this->arrIdGudang[] = $gudang->id_gudang;
			}
			$c->columns($cols);
			foreach($gudangs as $gudang){
				$c->callback_column("G".$gudang->id_gudang.": ".$gudang->nama_gudang, function($value, $row){
						$id_gudang = $this->arrIdGudang[$this->arridx % count($this->arrIdGudang)];
						
						$this->arridx++;
						
						$getstock = $this->db->query("select ifnull((select stok from tb_stok_gudang where deleted = 0 and id_gudang = ".$id_gudang." and id_item = ".$row->id_item."), 0) as stokgudang")->row();
						return $getstock->stokgudang;
				});
			}
			$c->display_as('id_user','User');
			$c->unset_add();
			$c->unset_read();
			$c->unset_edit();
			$c->unset_delete();
			$title = 'Print Stock Sheet';
			$this->load->vars( array('title' => $title,'wogc'=>false));
			$output = $c->render();
			$this->load->view('gc/template_gc', $output);
    }
    
    public function import()
    {
	    $access_url = 'item/import';
	    $access_sql = "select id_access from tb_user_access where id_jabatan = ".$this->session->userdata('id_jabatan')." and id_menu in
	    				(select id_menu x from tb_menu x where x.url_menu = '$access_url' and x.deleted = 0)";
	    $access_check = $this->db->query($access_sql)->result();
	    if(count($access_check) < 1){
		    return redirect('dashboard');
	    }
	    
	    $successmsg = '';
	    $failmsg = '';
	    
	    if($_SERVER['REQUEST_METHOD'] == 'POST'){
		    if(isset($_FILES['invfile'])){
			    include FCPATH."application/third_party/SimpleXLSX.php";
			    
			    $itemupdate = 0;
			    $iteminsert = 0;
			    
			    $fileName = $_FILES["invfile"]["tmp_name"];
			    if ($_FILES["invfile"]["size"] > 0) {
					if ( $xlsx = SimpleXLSX::parse($fileName) ) {
						$firstline = true;
						
						foreach ($xlsx->rows(0) as $row) {
							if($firstline){
								$firstline = false;
							} else {
								$existingitem = $this->db->query("select id_item from tb_item where sku_item = '".$row[0]."' and deleted = 0")->row();
								if(!isset($existingitem)){
									if($this->db->query("insert into tb_item(sku_item, nama_item, harga_modal, harga_jual, satuan) values('".$row[0]."','".$row[1]."',".$row[2].",".$row[3].",'".$row[4]."')")){
										$iteminsert++;
									}
								}
								else{
									if($this->db->query("update tb_item set nama_item = '".$row[1]."', harga_modal = ".$row[2].", harga_jual = ".$row[3].", satuan = '".$row[4]."' where id_item = ".$existingitem->id_item)){
										$itemupdate++;
									}
								}
							}
						}
						
						if($iteminsert > 0 || $itemupdate > 0){
                            history_action('Import data stock sheet','CSS','');
							$successmsg = "Import Data Berhasil: $iteminsert Item Baru, $itemupdate Item Update";
						}
						else{
							$failmsg = 'Import Data Gagal: Tidak Ada Item Valid, Periksa Kembali Format Tabel';
						}
						
					} else {
						$failmsg = 'Import Data Gagal: Tidak Dapat Membaca File Excel, Periksa Kembali Format Tabel';
					}
				} else {
					$failmsg = 'Import Data Gagal: File Excel Tidak Memiliki Content';
				}
			}
		}
	    
	    $data = [
            'title' => 'Import List Item',
            'wogc' => true,
            'successmsg' => $successmsg,
            'failmsg' => $failmsg
        ];
        $this->load->view('item/import',$data);
    }
    
    public function delete_stock_opname($id)
    {
        $parent = $this->db->update('tb_stock_opname',['deleted' => 1],['id_stock_opname' =>  $id]);
        if($parent){
            $check = $this->db->query('select count(*) detail from tb_stock_opname_detail where id_stock_opname = '.$id);
            if($check->row()->detail > 0){
                //Pembatalan Stock Opname
                $rows = $this->db->query('select * from tb_stock_opname_detail where id_stock_opname = '.$id)->result();
                foreach ($rows as $x => $c) {
                    $this->db->query('update tb_stok_gudang set stok = '.$c->stok_database.' where id_item = '.$c->id_item.' and id_gudang = '.$c->id_gudang);
                    history_stok($c->id_item,$c->stok_database,'Pembatalan Stock Opname','SOP',$id);
                    history_action('Menghapus data stock opname','DSOP',$id);
                }
            }
            return $this->db->update('tb_stock_opname_detail',['deleted' => 1],['id_stock_opname' =>  $id]);
        }else{
            return $parent;
        }
    }

    public function detail_opname($value,$row)
	{
		return '
		<a href="'.base_url().'item/detail_stock_opname/'.$row->id_stock_opname.'" class="btn btn-default"><i class="fa fa-sign-in"></i> Detail Stock Opname</a>
		';
	}

    public function set_value_user($value,$id = null)
    {
        return '<input type="hidden" name="id_user" value="'.$this->session->userdata('id_user').'">'.$this->session->userdata('nama');
    }

    public function status_label($value,$row)
	{
		if($row->status == 'Open'){
			return '<div class="label label-success">'.$value.'</div>';
		}else{
			return '<div class="label label-danger">'.$value.'</div>';
		}
	}

    public function detail_stock_opname()
    {
        $c = new grocery_CRUD();

        $c->set_subject('Detail Stok Opname');
        $c->set_table('tb_stock_opname_detail');
        $c->where('tb_stock_opname_detail.deleted',0);
        $c->order_by('id_stock_opname_detail','DESC');
        $c->where('tb_stock_opname_detail.id_stock_opname',$this->uri->segment(3));
        $c->unset_add();
        $c->unset_columns('created','updated','deleted','id_stock_opname','id_item','id_gudang');
        $c->unset_fields('created','updated','deleted','id_barang','sku_item','nama_item','stok_database','stok_gudang','id_stock_opname_baru');
        $c->display_as('id_user','Username')
            ->display_as('stok_database','Stok Database')
            ->display_as('stok_gudang','Stok Gudang');
        $c->callback_column('status',array($this,'status_label'))
            ->callback_column('detail',array($this,'detail_opname'));
        $c->set_relation('id_user','tb_user','nama_user');
        $c->callback_field('id_user',array($this,'set_value_user'));
        $c->callback_delete(array($this,'delete_detail_stock_opname'));
        $title = 'Detail Stock Opname ';
        $this->load->vars( array('title' => $title,'wogc'=>false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    public function delete_detail_stock_opname($id)
    {
        $row = $this->db->update('tb_stock_opname_detail',['deleted' => 1],['id_stock_opname_detail' => $id]);
        if($row){
            $c = $this->db->query('select * from tb_stock_opname_detail where id_stock_opname_detail ='. $id)->row();
            $this->db->query('update tb_stok_gudang set stok = '.$c->stok_database.' where id_item = '.$c->id_item.' and id_gudang = '.$c->id_gudang);
            history_stok($c->id_item,$c->stok_database,'Pembatalan Detail Stock Opname','SOP',$c->id_stock_opname);
            history_action('Menghapus data detail stock opname','DDSOP',$id);
        }else{
            return $row;
        }
    }

    public function create_detail_stock_opname($id)
    {
        $data = [
            'title' => 'Detail Stock Opname Create ',
            'wogc' => true,
            'gudang' => $this->db->query('select * from tb_gudang where deleted = 0')->result()
        ];

        $this->load->view('item/create_detail_stock_opname',$data);
    }

    public function insert_detail_stock_opname($id)
    {
        $data = [
            'id_item' => $this->input->post('id_item'),
            'id_gudang' => $this->input->post('id_gudang'),
            'nama_item' => $this->input->post('nama_item'),
            'sku_item' => $this->input->post('sku_item'),
            'stok_database' => $this->input->post('stok'),
            'stok_gudang' => $this->input->post('stok_gudang'),
            'catatan' => $this->input->post('catatan'),
            'id_user' => $this->input->post('id_user'),
            'id_stock_opname' => $id
        ];

        $insert = $this->db->insert('tb_stock_opname_detail', $data);
        $id_detail = $this->db->insert_id();
        if($insert){
            //Update Stok 
            $row = $this->db->query('update tb_stok_gudang set stok = '.$this->input->post('stok_gudang').' where id_item = '.$this->input->post('id_item').' and id_gudang = '.$this->input->post('id_gudang'));
            history_stok($this->input->post('id_item'),$this->input->post('stok_gudang'),'Stock Opname baru','SOP',$id);
            history_action('Menambah data detail stock opname baru','CDSOP',$id);
            $this->session->set_flashdata('success', 'Berhasil menambahkan Stock Opname Detail');
            redirect('item/detail_stock_opname/'.$id,'refresh');
        }else{
            $this->session->set_flashdata('failed', 'Gagal menambahkan Stock Opname Detail');
            redirect('item/create_detail_stock_opname/'.$id,'refresh');
        }
    }
    //End Stock Opname

    //Global Callback Column
    public function percent($value,$row)
    {
        return $value.'%';
    }

    public function nominal($value,$row)
    {
        return number_format($value);
    }

    public function auto_add()
    {
        $search = $this->input->post('search_data');

        $query = $this->db->query("select * from tb_item where sku_item = '".$search."'")->row();
        if($query){
            $data = [
                'id_item'    => $query->id_item,
                'nama_item'  => $query->nama_item,
                'harga_jual' => $query->harga_jual,
                'sku_item'   => $query->sku_item,
                'status' => 200
            ];
        }else{
            $data = [
                'status' => 500,
                'message' => 'Barang tidak tersedia!'
            ];
        }

        echo json_encode($data);
    }

    public function get_barang_stock_opname()
	{
        $scan_data = $this->input->post('sku_item');
        
        $result = $this->db->query("select sku_item,b.id_item,nama_item,b.id_gudang,nama_gudang,stok from tb_item a 
        left join tb_stok_gudang b
            on a.id_item = b.id_item
        left join tb_gudang c
            on b.id_gudang = c.id_gudang
        where sku_item like '%$scan_data%' or nama_item like '%$scan_data%' and a.deleted = 0")->result();

        if(!empty($result)){
            foreach($result as $row){
                if($row->stok > 0){ //NO NEED TO CHECK STOCK?
                    echo '<li>
                            <a class="list" style="display:block;cursor:pointer" data-produk-id="'.$row->id_item.'" data-produkkode="'.$row->sku_item.'" data-produknama="'.$row->nama_item.'" data-produkgudang="'.$row->id_gudang.'" data-produkstok="'.$row->stok.'" onclick="add_barang_stock_opname(this);">
                            <div class="row">
                                <div class="col-sm-6">
                                ' . $row->nama_item . ' | Gudang : '.$row->nama_gudang.'
                                </div>
                                <div class="col-sm-6">
                                <button type="button" class="add_cart btn btn-success btn-sm" data-produk-id="'.$row->id_item.'" data-produkkode="'.$row->sku_item.'" data-produknama="'.$row->nama_item.'" data-produkgudang="'.$row->id_gudang.'" data-produkstok="'.$row->stok.'" onclick="add_barang_stock_opname(this);"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </a>
                    </li>';
                }else{
                    echo '';
                }
            }
        }else{
            echo '<li> Barang tidak ditemukan </li>';
        }
	}

}

/* End of file Item.php */
