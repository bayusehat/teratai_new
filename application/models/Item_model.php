<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model {

    public function cek_stok_habis()
    {
        $result = [];
        $query = $this->db->query('
        select a.id_item,a.id_gudang,nama_item item,nama_gudang gudang,stok,a.id_item idi,a.id_gudang idg
            from tb_stok_gudang a left join tb_item b on a.id_item = b.id_item
                left join tb_gudang c on a.id_gudang = c.id_gudang
            where a.deleted = 0'
        )->result();
        foreach ($query as $i => $sb) {
            $redline = $this->db->query('select * from tb_redline where id_item = '.$sb->id_item.' and id_gudang='.$sb->id_gudang)->row();
            if($redline){
                if($sb->stok > $redline->stok_minimum){
                    $status = '<div class="label label-success">Stok Aman</div>';
                }else{
                    $status = '<div class="label label-danger">Stok Hampir Habis / Habis</div>';
                }
                $stok_minimum = '<div class="label label-danger">'.$redline->stok_minimum.'</div>';
                $result[] = [
                    'item'         => $sb->item,
                    'gudang'       => $sb->gudang,
                    'stok'         => $sb->stok,
                    'stok_minimum' => $stok_minimum,
                    'status'       => $status
                ];
            }else{
                /*$result[] = [
                    'item'         => $sb->item,
                    'gudang'       => $sb->gudang,
                    'stok'         => $sb->stok,
                    'stok_minimum' => 'Belum ditambahkan',
                    'status'       => $status
                ];*/
            }
        }
        return $result;
    }

}

/* End of file Item_model.php */
