<?php 

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends CI_Controller {

    public function pembelian($id)
    {
        $parent = $this->db->query(
            "SELECT 
            A.*,NAMA_SUPPLIER,CONCAT(B.NAMA_BANK,'/',B.NOMOR_REKENING,'/',B.NAMA_PEMILIK_REKENING) BANK_ACCOUNT,
            CASE WHEN TIPE_ITEM = '01' THEN 'SPAREPART'
                   WHEN TIPE_ITEM = '02' THEN 'BAHAN BANGUNAN'
                     WHEN TIPE_ITEM = '03' THEN 'ASET TIDAK LANCAR'
         END TIPE_ITEM_PEMESANAN,C.NAMA_USER PEMBUAT,F.NAMA_USER APPROVAL1, G.NAMA_USER APPROVAL2
        FROM TB_PEMESANAN A 
                LEFT JOIN TB_BANK_ACCOUNT B ON A.ID_BANK_ACCOUNT = B.ID_BANK_ACCOUNT
                LEFT JOIN TB_USER C ON A.CREATOR = C.ID_USER
                LEFT JOIN TB_SUPPLIER D ON A.ID_SUPPLIER = D.ID_SUPPLIER
                LEFT JOIN TB_GUDANG E ON A.ID_GUDANG = E.ID_GUDANG
                LEFT JOIN TB_USER F ON A.APPROVED_1 = F.ID_USER
                LEFT JOIN TB_USER G ON A.APPROVED_2 = G.ID_USER
        WHERE ID_PEMESANAN = 17 $id")->row();

        $detail = $this->db->query(
            "SELECT A.*,NAMA_ITEM FROM TB_PEMESANAN_DETAIL A
                LEFT JOIN TB_ITEM ON A.ID_ITEM = B.ID_ITEM
            WHERE ID_PEMESANAN = $id")->result();
        
        
        $data = [
            'title' => 'Laporan Pemesanan '.$parent->no_po,
            'parent' => $parent,
            'detail' => $detail
        ];

        return view('report/laporan_pemesanan',$data);
    }

}

/* End of file Report.php */
