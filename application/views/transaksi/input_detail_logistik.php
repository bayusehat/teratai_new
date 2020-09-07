<?php $this->load->view('layout/head');?>
<style>
    .mini-input{
        width:50px;
    }
    .table-item{
        width:100%;
        height:400px;
        overflow-y:scroll;
    }
    .tableFixHead{ overflow-y: auto; height: 400px; }
    .tableFixHead table thead th { position: sticky; top: 0}
</style>
<?php
    if($this->session->flashdata('success')){ ?>
        <div class="alert alert-success"><?= $this->session->flashdata('success');?></div>
<?php  } ?>
<?php
    if($this->session->flashdata('failed')){ ?>
        <div class="alert alert-danger"><?= $this->session->flashdata('failed');?></div>
<?php  } ?>
<form action="<?= base_url();?>so/input_detail_logistik_action/<?= $so->id_penjualan;?>" method="POST">
    <div id="content-wrapper" class="group">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <a href="<?= base_url('so/sales_order');?>" class="btn btn-danger mb-5"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xl-3">
                            <div class="form-group">
                                <label for="id_customer">Customer</label>
                                <select name="id_customer" id="id_customer" class="form-control input-sm select2" disabled>
                                    <option value="">Pilih Customer</option>
                                    <?php
                                        foreach($customer as $cs){ ?>
                                            <option value="<?= $cs->id_customer;?>" <?php if($so->id_customer == $cs->id_customer){echo 'selected';}else{echo '';}?>><?= $cs->nama_customer;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_gudang">Gudang</label>
                                <select name="id_gudang" id="id_gudang" class="form-control input-sm select2" disabled>
                                    <option value="">Pilih Gudang</option>
                                    <?php
                                        foreach($gudang as $gd){ ?>
                                            <option value="<?= $gd->id_gudang;?>" <?php if($so->id_gudang == $gd->id_gudang){echo 'selected';}else{echo '';}?>><?= $gd->nama_gudang;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_logistik">Logistik</label>
                                <select name="id_logistik" id="id_logistik" class="form-control input-sm select2">
                                    <option value="">Pilih Logistik</option>
                                    <?php
                                        foreach($logistik as $lg){ ?>
                                            <option value="<?= $lg->id_logistik;?>" <?php if($so->id_logistik == $lg->id_logistik){echo 'selected';}else{echo '';}?>><?= $lg->nama_perusahaan_logistik;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="conf_logistik">Jenis Logistik</label>
                                <select name="conf_logistik" id="conf_logistik" class="form-control input-sm select2">
                                    <option value="0" <?php if($so->conf_logistik == 0){echo 'selected';}else{echo '';}?>>Internal</option>
                                    <option value="1" <?php if($so->conf_logistik == 1){echo 'selected';}else{echo '';}?>>External</option>
                                    <option value="2" <?php if($so->conf_logistik == 2){echo 'selected';}else{echo '';}?>>Tanpa Logistik</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status_logistik">Status Logistik</label>
                                <select name="status_logistik" id="status_logistik" class="form-control input-sm select2">
                                    <option value="0" <?php if($so->status_logistik == 0){echo 'selected';}else{echo '';}?>>Pending</option>
                                    <option value="1" <?php if($so->status_logistik == 1){echo 'selected';}else{echo '';}?>>Approved</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dropship">Dropship</label>
                                <select name="dropship" id="dropship" class="form-control input-sm select2" disabled>
                                    <option value="0" <?php if($so->dropship == 0){echo 'selected';}else{echo '';}?>>No</option>
                                    <option value="1" <?php if($so->dropship == 1){echo 'selected';}else{echo '';}?>>Yes</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="ppn">PPN (10%)</label>
                                <select name="ppn" id="ppn" class="form-control input-sm" class="form-control input-sm" disabled>
                                    <option value="0" <?php if($so->ppn == 0){echo 'selected';}else{echo '';}?>>Tidak</option>
                                    <option value="1" <?php if($so->ppn == 1){echo 'selected';}else{echo '';}?>>Ya</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" onchange="status_change();" class="form-control input-sm" disabled>
                                    <option value="0" <?php if($so->status_penjualan == 0){echo 'selected';}else{echo '';}?>>PENDING</option>
                                    <option value="1" <?php if($so->status_penjualan == 1){echo 'selected';}else{echo '';}?>>LUNAS</option>
                                    <option value="2" <?php if($so->status_penjualan == 2){echo 'selected';}else{echo '';}?>>BATAL</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_metode_pembayaran">Metode Pembayaran</label>
                                <select name="id_metode_pembayaran" id="id_metode_pembayaran" class="form-control input-sm select2" disabled>
                                    <option value="">Pilih Metode Pemabayaran</option>
                                    <?php
                                        foreach($metode_pembayaran as $mp){ ?>
                                            <option value="<?= $mp->id_metode_pembayaran;?>" <?php if($so->id_metode_pembayaran == $mp->id_metode_pembayaran){echo 'selected';}else{echo '';}?>><?= $mp->nama_metode_pembayaran;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="tgljt">
                                <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                                <input type="text" class="form-control input-sm pickdate" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="<?= $so->tanggal_jatuh_tempo;?>" disabled>
                            </div>
                            <div class="form-group" id="tgllns">
                                <label for="tanggal_jatuh_tempo">Tanggal Pelunasan</label>
                                <input type="text" class="form-control input-sm pickdate" name="tanggal_pelunasan" id="tanggal_pelunasan" value="<?= $so->tanggal_pelunasan;?>" disabled>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-12 col-xl-9">
                            <div class="form-group">
                                <label for="">Cari Item</label>
                                <div class="form-group has-feedback has-search">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                        <input name="search" onkeyup="search_item()" class="form-control" id="search" placeholder="Search / Scan Produk" type="text" onkeyup="scan_data_beli();" disabled>
                                            <div id="suggestions">
                                            <div id="autoSuggestionsList">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="tableFixHead">
                                    <table class="table table-bordered table-striped" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Harga</th>
                                                <th>Quantity</th>
                                                <th>Biaya Logistik</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listItem">
                                            <?php
                                            foreach($detail_so as $i => $ds){ ?>
                                            <tr>
                                                <td><?= ++$i;?></td>
                                                <td>
                                                    <input type="hidden" value="<?= $ds->id_penjualan_detail;?>" name="id_penjualan_detail[]" class="id_penjualan_detail">
                                                    <input type="hidden" value="<?= $ds->id_item;?>" name="id_item[]" class="id">
                                                    <?= $ds->nama_item;?>
                                                </td>
                                                <td>
                                                    <input type="text" value="<?= $ds->harga;?>" class="form-control input harga" name="harga[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="<?= $ds->quantity;?>" class="form-control input mini-input quantity" onkeyup="change_quantity_so()" name="quantity[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="<?= $ds->biaya_logistik;?>" class="form-control input biaya_logistik" onkeyup="change_quantity_so()" name="biaya_logistik[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="<?= $ds->subtotal;?>" class="form-control input subtotal" name="subtotal[]">
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="delRow(this)"><i class="fa fa-trash"></i></button>
                                                </td>
                                            </tr>
                                        <?php    }
                                            ?>
                                        </tbody>
                                    </table>            
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="diskon">Diskon</label>
                                <select name="diskon" id="diskon" class="form-control input-sm">
                                    <option value="0">Tidak Ada</option>
                                    <?php
                                        foreach($diskon as $dk){ ?>
                                            <option value="<?= $dk->id_diskon;?>" <?php if($so->diskon == $dk->id_diskon){echo 'selected';}else{echo '';}?>><?= $dk->nama_diskon;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="total">TOTAL</label>
                                <input type="text" class="form-control" name="total" id="total" value="<?= $so->grand_total;?>">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" name="submit"><i class="fa fa-save"></i> Simpan Sales Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        status_change();
    })
    function search_item(){
        var item = $('#search').val();
        var gudang = $('#id_gudang').val();

        if(gudang != ''){
            if(item != ''){
                $.ajax({
                    url : '<?= base_url();?>po/search_item',
                    method : 'POST',
                    data : {
                        'item' : item,
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
        }else{
            alert('Gudang belum dipilih!');
        }
    }
    var no = 1;
    function addToTable(id,nama,harga){
        var subtotal = harga * 1;
        var row = '<tr>'+
                    '<td>'+no+'</td>'+
                    '<td>'+
                        '<input type="hidden" value="'+id+'" name="id_item[]" class="id">'+
                        nama+
                    '</td>'+
                    '<td>'+
                        '<input type="text" value="'+harga+'" class="form-control input harga" name="harga[]">'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" value="1" class="form-control input mini-input quantity" onkeyup="change_quantity_so()" name="quantity[]">'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" value="0" class="form-control input biaya_logistik" onkeyup="change_quantity_so()" name="biaya_logistik[]">'+
                    '</td>'+
                    '<td>'+
                        '<input type="text" value="'+subtotal+'" class="form-control input subtotal" name="subtotal[]">'+
                    '</td>'+
                    '<td>'+
                        '<button type="button" class="btn btn-sm btn-danger" onclick="delRow(this)"><i class="fa fa-trash"></i></button>'+
                    '</td>'+
                '</tr>';
        no++
        $('#listItem').append(row);
        $('#suggestions').fadeOut();
        $('#search').val("").focus();
        total();
    }
</script>
<script src="<?= base_url();?>assets/spada/js/transaksi.js"></script>
<?php $this->load->view('layout/foot');?>