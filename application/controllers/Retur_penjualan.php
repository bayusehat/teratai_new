<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Retur_penjualan extends CI_Controller {

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

    public function no_retur_penjualan()
    {
        $this->db->select('no_retur_penjualan', FALSE);
        $this->db->order_by('id_retur_penjualan','DESC');    
        $this->db->limit(1);    
        $query = $this->db->get('tb_retur_penjualan');   
        if($query->num_rows() <> 0){      
            $noso = $query->row();
            $length = strlen(str_replace(" ","",$noso->no_retur_penjualan));
            $get_subs = $length - 3;
            if(strlen(str_replace(" ","",$noso->no_retur_penjualan)) > 10){
                $num = substr($noso->no_retur_penjualan,$get_subs,$length);
                $kode = $num + 1;

                $cek_all_data = $this->db->query("SELECT COUNT(*) jumlah FROM tb_retur_penjualan")->row();
                if($cek_all_data->jumlah < 100){
                    $kode = intval(substr($kode,-2));
                }else if($cek_all_data->jumlah > 100){
                    $kode = intval(substr($kode,-3));
                }else{
                    $kode = intval(substr($kode,-4));
                }
            }else if(strlen(str_replace(" ","",$noso->no_retur_penjualan)) <= 10){
                $data = str_split($noso->no_retur_penjualan,8);      
                $kode = $data[1] + 1;
            }else{
                $data = str_split($noso->no_retur_penjualan,9);      
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
        $nomor_tampil = "RJ-".$nomor_jual_max;
       
        return $nomor_tampil;
    }

    public function list_retur()
    {
        // $so = $this->db->query('select no_so from tb_penjualan where id_penjualan = '.$this->uri->segment(3))->row();
        $c = new grocery_CRUD();
        
        $c->set_subject('Retur Penjualan');
        $c->set_table('tb_retur_penjualan');
        $c->where('tb_retur_penjualan.deleted',0);
        $c->order_by('tb_retur_penjualan.created','DESC');
        $c->display_as('id_customer','Customer')
          ->display_as('id_gudang','Gudang')
          ->display_as('no_retur_penjualan','No. Retur')
          ->display_as('tanggal_jatuh_tempo_retur','Jatuh Tempo')
          ->display_as('created','Tgl. Nota')
          ->display_as('id_penjualan','No. Sales Order')
          ->display_as('approved_1','Approved By')
          ->display_as('approval','Action');
        $c->set_relation('id_customer','tb_customer','nama_customer',['deleted' => 0])
          ->set_relation('id_gudang','tb_gudang','nama_gudang',['deleted' => 0])
          ->set_relation('id_penjualan','tb_penjualan','no_so');
        $c->columns('no_retur_penjualan','created','id_penjualan','tanggal_jatuh_tempo_retur','id_gudang','id_customer','status','approved_1','approval');
        $c->unset_columns('updated','deleted','status_lunas','creator');
        $c->unset_fields('created','updated','deleted');
        $c->unset_add();
        $c->unset_read();
        $c->unset_edit();
        // $c->unset_delete();
        
        if($this->session->userdata('id_user') == 2 || $this->session->userdata('id_user') == 1){
            $c->add_action('Edit','','retur_penjualan/edit','fa-pencil');
        }
        // $c->add_action('Delete','','retur_penjualan/delete_retur_penjualan','fa-trash text-danger');
        $c->callback_column('status',[$this,'status_approved'])
          ->callback_column('approval',[$this,'approval_rp'])
          ->callback_column('approved_1',[$this,'approved']);
        //   ->callback_column('approved_2',[$this,'approved']);
        $c->callback_delete([$this,'delete_retur_penjualan']);
        $title = 'Data Retur Penjualan';
        $this->load->vars( array('title' => $title,'wogc' => false));
        $output = $c->render();
        $this->load->view('gc/template_gc', $output);
    }

    public function delete_retur_penjualan($id)
    {
        $parent = $this->db->update('tb_retur_penjualan',['deleted' => 1],['id_retur_penjualan' => $id]);
        $appr = $this->db->query('select * from tb_retur_penjualan where id_retur_penjualan = '.$id)->row();

        if($appr->approved_1 != 0){
            $returDetail = $this->db->query('select * from tb_retur_penjualan_detail where id_retur_penjualan = '.$id)->result();
            foreach ($returDetail as $dr) {
                $this->db->query(
                "update tb_stok_gudang set stok = stok - $dr->quantity where id_item = $dr->id_item and id_gudang = $appr->id_gudang"
            );
            $mod = $dr->quantity;
            history_stok($dr->id_item,$mod,'Hapus Retur Penjualan Approved','DRPJ',$id);
            }
        }

        return $parent;
        
    }

    public function status_approved($value,$row)
    {
        if($row->approved_1 != 0){
            return '<div class="label label-success">Approved</div>';
        }else{
            return '<div class="label label-danger">Belum ada Approval</div>';
        }
    }

    public function approval_rp($value,$row)
    {
        if($row->approved_1 == ''){
            if($this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2 || $this->session->userdata('id_jabatan') == 6 || $row->approved_1 == 0){
                $link1 = $row->approved_1 != 0 ? '#' : 'retur_penjualan/approve_rp_action/1/'.$row->id_retur_penjualan;
                // $link2 = $row->approved_2 != 0 ? '#' : 'retur_penjualan/approve_rp_action/2/'.$row->id_retur_penjualan;
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
        //$query = $this->db->query('select * from tb_retur_penjualan where id_retur_penjualan = '.$id)->row();
        if($tipe == 1){
	        $checkApprovedStatus = $this->db->where('id_retur_penjualan', $id)
	        								->select('approved_1')
	        								->get('tb_retur_penjualan')
	        								->row();
			
			if($checkApprovedStatus->approved_1 == 0){
	            $query = $this->db->query('UPDATE tb_retur_penjualan SET approved_1 = '.$this->session->userdata('id_user').' where id_retur_penjualan =  '.$id);
	            $this->triggerStockSo($id);
	            history_action('Approve Retur Penjualan','ARPJ',$id);
			}
            redirect($_SERVER['HTTP_REFERER'],'refresh');
        }else{
            /*$query = $this->db->query('UPDATE tb_retur_penjualan SET approved_2 = '.$this->session->userdata('id_user').' where id_retur_penjualan =  '.$id);
            $this->triggerStockSo($id);
            history_action('Approve Retur Penjualan','ARPJ',$id);
            redirect($_SERVER['HTTP_REFERER'],'refresh');*/
        }
    }

    public function create($id)
    {
        $so = $this->db->query('select no_so, id_customer, id_gudang from tb_penjualan where id_penjualan = '.$id)->row();
        if($so->id_customer > 0)
        	$customer = $this->db->query('select id_customer,nama_customer from tb_customer where id_customer = '.$so->id_customer)->row();
        else
        	$customer = (object) array('id_customer' => 0, 'nama_customer' => 'WALK IN');
        	
        $data = [
            'title' => 'Retur Penjualan '.$so->no_so,
            'wogc' => true,
            //'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where id_gudang = '.$so->id_gudang)->row(),
            //'customer' => $this->db->query('select id_customer,nama_customer from tb_customer where deleted = 0')->result(),
            'customer' => $customer,
            'detail_so' => $this->db->query('select id_item,nama_item,id_penjualan, harga, quantity, sum(qty_retur), quantity - sum(qty_retur) available_qty
            from(
            select a.id_item, b.nama_item, a.id_penjualan, a.quantity, a.harga, case when d.quantity is null then 0 else d.quantity end qty_retur
                from tb_penjualan_detail a 
                        left join tb_item b on a.id_item = b.id_item 
                        left join tb_retur_penjualan c on a.id_penjualan = c.id_penjualan
                        left join tb_retur_penjualan_detail d on a.id_retur_penjualan = d.id_retur_penjualan
                where id_penjualan = '.$id.'
            ) a
            group by id_item')->result()
        ];

        $this->load->view('transaksi/retur_penjualan_create',$data);
    }

    public function insert($id)
    {
        $this->form_validation->set_rules('id_customer','Customer','required');
        $this->form_validation->set_rules('id_gudang','Gudang','required');
        $this->form_validation->set_rules('tanggal_jatuh_tempo_retur','Tanggal Jatuh Tempo','required');

        $data = [
            'no_retur_penjualan'        => $this->no_retur_penjualan(),
            'id_customer'               => $this->input->post('id_customer'),
            'id_gudang'                 => $this->input->post('id_gudang'),
            'status_retur'              => $this->input->post('status_retur'),
            'id_penjualan'              => $id,
            'tanggal_jatuh_tempo_retur' => $this->input->post('tanggal_jatuh_tempo_retur'),
            'creator'                   => $this->session->userdata('id_user')
        ];

        if($this->form_validation->run() != FALSE){
            $insert = $this->db->insert('tb_retur_penjualan',$data);
            $id_ret = $this->db->insert_id();

            if($insert){
                if(count($this->input->post('qty_retur')) > 0){
                    foreach ($this->input->post('qty_retur') as $i => $item) {
                        if($this->input->post('qty_retur')[$i] != 0){
                            $detail = [
                                'id_retur_penjualan'    => $id_ret,
                                'id_item'               => $this->input->post('id_item')[$i],
                                'quantity'              => $this->input->post('qty_retur')[$i],
                                'harga'                 => $this->input->post('harga')[$i],
                                'status_pengembalian'   => 0,
                                'approval_pengembalian' => 0
                            ];

                            $this->db->insert('tb_retur_penjualan_detail',$detail);
                        }
                    }
                }
                $this->session->set_flashdata('success','Berhasil menambah retur!');
                history_action('Membuat Retur Penjualan','CRPJ',$id_ret);
                redirect('retur_penjualan/create/'.$id,'refresh');
            }else{
                $this->session->set_flashdata('failed','Terjadi Masalah! gagal menambah retur');
                redirect('retur_penjualan/create/'.$id,'refresh');
            }
        }else{
            $this->session->set_flashdata('error',validation_errors());
            redirect('retur_penjualan/create/'.$id,'refresh');
        }
    }

    public function edit($id)
    {
        $retur = $this->db->query('select id_penjualan,no_retur_penjualan,a.id_gudang,nama_gudang,a.id_customer,nama_customer,tanggal_jatuh_tempo_retur,status_retur
                                    from tb_retur_penjualan a left join tb_gudang b on a.id_gudang = b.id_gudang 
                                        left join tb_customer c on a.id_customer = c.id_customer 
                                    where a.deleted = 0 and id_retur_penjualan = '.$id)->row();
        
        if($retur->id_customer > 0)
        	$customer = $this->db->query('select id_customer,nama_customer from tb_customer where id_customer = '.$retur->id_customer)->row();
        else
        	$customer = (object) array('id_customer' => 0, 'nama_customer' => 'WALK IN');                            
                                    
        $data = [
            'title' => 'Edit Retur Penjualan '.$retur->no_retur_penjualan,
            'wogc' => true,
            //'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where deleted = 0')->result(),
            //'customer' => $this->db->query('select id_customer,nama_customer from tb_customer where deleted = 0')->result(),
            'gudang' => $this->db->query('select id_gudang,nama_gudang from tb_gudang where id_gudang = '.$retur->id_gudang)->row(),
            'customer' => $customer,
            'retur' => $retur,
            'detail' => 
                $this->db->query('select a.id_item,a.harga,a.id_retur_penjualan,a.quantity quantity_retur,c.quantity,nama_item,a.catatan
                from tb_retur_penjualan_detail a 
                    left join tb_retur_penjualan b on a.id_retur_penjualan = b.id_retur_penjualan
                    left join tb_penjualan_detail c on a.id_item = c.id_item
                    left join tb_item d on c.id_item = d.id_item
                    where c.id_penjualan = b.id_penjualan and a.id_retur_penjualan ='.$id)->result()
        ];

        $this->load->view('transaksi/retur_penjualan_edit',$data);
    }

    public function update($id)
    {
        $this->form_validation->set_rules('id_customer','Customer','required');
        $this->form_validation->set_rules('id_gudang','Gudang','required');
        $this->form_validation->set_rules('tanggal_jatuh_tempo_retur','Tanggal Jatuh Tempo','required');

        $data = [
            'no_retur_penjualan'        => $this->no_retur_penjualan(),
            'id_customer'               => $this->input->post('id_customer'),
            'id_gudang'                 => $this->input->post('id_gudang'),
            'status_retur'              => $this->input->post('status_retur'),
            'tanggal_jatuh_tempo_retur' => $this->input->post('tanggal_jatuh_tempo_retur'),
            'creator'                   => $this->session->userdata('id_user')
        ];

        if($this->form_validation->run() != FALSE){
            $update = $this->db->update('tb_retur_penjualan',$data,['id_retur_penjualan' => $id]);
            $id_ret = $id;

            if($update){
                if(count($this->input->post('qty_retur')) > 0){
                    $this->db->query('DELETE FROM tb_retur_penjualan_detail where id_retur_penjualan = '.$id_ret);
                    foreach ($this->input->post('qty_retur') as $i => $item) {
                        if($this->input->post('qty_retur')[$i] != 0){
                            $detail = [
                                'id_retur_penjualan'    => $id_ret,
                                'id_item'               => $this->input->post('id_item')[$i],
                                'quantity'              => $this->input->post('qty_retur')[$i],
                                'harga'                 => $this->input->post('harga')[$i],
                                'status_pengembalian'   => 0,
                                'approval_pengembalian' => 0
                            ];

                            $this->db->insert('tb_retur_penjualan_detail',$detail);
                        }
                    }
                }
                $this->session->set_flashdata('success','Berhasil memperbarui retur!');
                history_action('Memperbarui Retur Penjualan','URPJ',$id_ret);
                redirect('retur_penjualan/edit/'.$id,'refresh');
            }else{
                $this->session->set_flashdata('failed','Terjadi Masalah! gagal memperbarui retur');
                redirect('retur_penjualan/edit/'.$id,'refresh');
            }
        }else{
            $this->session->set_flashdata('error',validation_errors());
            redirect('retur_penjualan/edit/'.$id,'refresh');
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

    public function triggerStockSo($id)
    {
        $appr = $this->db->query('select * from tb_retur_penjualan where id_retur_penjualan = '.$id)->row();
        if($appr->approved_1 != 0){
            $returDetail = $this->db->query('select * from tb_retur_penjualan_detail where id_retur_penjualan = '.$id)->result();
            foreach ($returDetail as $dr) {
                $this->db->query(
                "update tb_stok_gudang set stok = stok + $dr->quantity where id_item = $dr->id_item and id_gudang = $appr->id_gudang"
            );
            $mod = $dr->quantity;
            history_stok($dr->id_item,$mod,'Approval Retur Penjualan','RPJ',$id);
            }
        }
    }

}

/* End of file Retur_penjualan.php */
