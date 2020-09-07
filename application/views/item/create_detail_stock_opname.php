<?php $this->load->view('layout/head');?>
<form action="<?= base_url();?>item/insert_detail_stock_opname/<?= $this->uri->segment(3);?>" method="POST">
    <div id="content-wrapper" class="group">
        <div id="page-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                        <!-- <div class="panel panel-info"> -->
                            <!-- <div class="panel-heading">Input Stock Opname</div>
                                <div class="panel-body"> -->
                                    <div class="form-group">
                                        <p class="red">*Ketikan SKU / Nama barang pada input SKU</p>
                                    </div>
                                    <div class="form-group has-feedback">
                                        <label><i class="fa fa-search"></i> SKU </label>
                                        <input type="text" name="sku_item" class="form-control" placeholder="SKU" id="sku_item" onkeyup="scan_data();">
                                            <input type="hidden" name="id_item" id="id_item">
                                                <!--<input type="hidden" name="stok" id="stok">-->
                                            <div id="suggestions">
                                                <div id="autoSuggestionsList">
                                                </div>
                                            </div>
                                        </div>
                                    <div class="form-group">
                                        <label>Nama Item</label>
                                        <input type="text" class="form-control" name="nama_item" placeholder="Nama Barang" id="nama_item" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Gudang</label>
                                        <select name="id_gudang" id="id_gudang" class="form-control" readonly>
                                            <option value="">Gudang Item</option>
                                            <?php
                                                foreach($gudang as $gd){ ?>
                                                    <option value="<?= $gd->id_gudang;?>"><?= $gd->nama_gudang;?></option>
                                            <?php    }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Stok Saat Ini</label>
                                        <input type="text" class="form-control" name="stok" placeholder="Stok Saat Ini" id="stok" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label>Stok Baru</label>
                                        <input type="text" name="stok_gudang" id="stok_gudang" class="form-control" placeholder="Stok Baru">
                                    </div>
                                    <div class="form-group">
                                        <label>Catatan</label>
                                        <input type="text" name="catatan" id="catatan" class="form-control" placeholder="Catatan">
                                        <input type="hidden" name="id_user" value="<?= $this->session->userdata('id_user');?>">
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" name="submit" class="btn btn-success btn-block" value="Simpan SOP" onclick="return confirm('Simpan Stock Opname?'); ">
                                    </div>
                                <!-- </div> -->
                                <!-- <div class="panel-footer">
                                    
                                </div> -->
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<!-- <script>
    function search_item(){
        var item = $('#search').val();

        if(item != ''){
            $.ajax({
                url : '<?= base_url();?>item/search_item',
                method : 'POST',
                data : {
                    'item' : item
                },
                success:function(res){
                    if (res.length > 0) {
                        $('#suggestions').fadeIn();
                        $('#autoSuggestionsList').addClass('auto_list');
                        $('#autoSuggestionsList').html(res);
                    }
                }
            })
        }else{
            $('#suggestions').fadeOut();
        }
    }
</script> -->
<script src="<?= base_url();?>assets/spada/js/stock_opname.js"></script>
<?php $this->load->view('layout/foot');?>