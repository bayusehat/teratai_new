<?php $this->load->view('layout/head');?>
    <div id="content-wrapper" class="group">
        <div id="page-wrapper">
        <?php
            if($this->session->flashdata('success')){ ?>
                <div class="alert alert-success"><?= $this->session->flashdata('success');?></div>
        <?php  } ?>
        <?php
            if($this->session->flashdata('failed')){ ?>
                <div class="alert alert-danger"><?= $this->session->flashdata('failed');?></div>
        <?php  } ?>
            <div class="row">
                <div class="col-lg-6">
                    <?php 
                    if($this->uri->segment(2) == 'mutasi'){
                        echo '<a href="'.base_url().'item/create_mutasi" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Mutasi</a>';
                    }
                    if($this->uri->segment(2) == 'purchase_order'){
                        if($this->session->userdata('id_jabatan') == 5 || $this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2){
                            echo '<a href="'.base_url().'po/purchase_order_create" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Purchase Order</a>';
                        }
                    }
                    if($this->uri->segment(2) == 'goods_in'){
                        if($this->session->userdata('id_jabatan') == 3 || $this->session->userdata('id_jabatan') == 1  || $this->session->userdata('id_jabatan') == 2){
                            echo '<a href="'.base_url().'gi/goods_in_create" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Goods In</a>';
                        }
                    }
                    if($this->uri->segment(2) == 'sales_order'){
                        if($this->session->userdata('id_jabatan') == 5 || $this->session->userdata('id_jabatan') == 6 || $this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2){
                            echo '<a href="'.base_url().'so/sales_order_create" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Sales Order</a>';
                        }
                    }
                    if($this->uri->segment(2) == 'goods_out'){
                        if($this->session->userdata('id_jabatan') == 3 || $this->session->userdata('id_jabatan') == 1 || $this->session->userdata('id_jabatan') == 2){
                            echo '<a href="'.base_url().'go/goods_out_create" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Goods Out</a>';
                        }
                    }
                    // if($this->uri->segment(1) == 'retur_pembelian'){
                    //     echo '<a href="'.base_url().'retur_pembelian/create/'.$this->uri->segment(3)
                    //     .'" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Retur Pembelian</a>';
                    // }
                    // if($this->uri->segment(1) == 'retur_penjualan'){
                    //     echo '<a href="'.base_url().'retur_penjualan/create/'.$this->uri->segment(3)
                    //     .'" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Retur Penjualan</a>';
                    // }
                    if($this->uri->segment(2) == 'detail_stock_opname'){
                        echo '<a href="'.base_url().'item/create_detail_stock_opname/'.$this->uri->segment(3)
                        .'" class="btn btn-success"><i class="fa fa-plus"></i> Tambah Detail Stock Opname</a>';
                    }
                    ?><br>
                </div>
                <div class="col-lg-6">
                    <?php
                        if($this->uri->segment(2) == 'detail_stock_opname'){
                            echo '<a href="'.base_url().'item/stock_opname" class="btn btn-danger right"><i class="fa fa-arrow-left"></i> Kembali</a>';
                        }
                    ?>
                </div>
            </div>
            <div>
                <?php echo $output; ?>
            </div>
        </div>
    </div>
    
    <?php if($title == 'Tambah Data Komponen Harga Jual' || $title == 'Edit Data Komponen Harga Jual'){ ?>
    <script type="text/javascript">
		$("#field-total_hpp, #field-harga_jual, #field-margin_akhir").attr("readonly", true);
		$("input").keyup(function(){
			$totalhpp = (1 * $("#field-harga_modal").val()) + (1 * $("#field-biaya_kirim").val());
			$("#field-total_hpp").val($totalhpp);
			
			$hargajual = Math.round((1 * $("#field-total_hpp").val()) * ((100 + (1 * $("#field-margin").val())) / 100));
			$("#field-harga_jual").val($hargajual);
			
			$marginakhir = (1 * $("#field-harga_jual").val()) -  (1 * $("#field-total_hpp").val());
			$("#field-margin_akhir").val($marginakhir);
		});
	</script>    
	<?php } ?>
<?php $this->load->view('layout/foot');?>