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
<form action="<?= base_url();?>po/purchase_order_insert" method="POST">
    <div id="content-wrapper" class="group">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <a href="<?= base_url('po/purchase_order');?>" class="btn btn-danger mb-5"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
            <?php
                if($this->session->flashdata('error')){ ?>
                    <div class="alert alert-danger">
                        <strong>Error!</strong> <?= $this->session->flashdata('error');?>
                    </div>
            <?php  
                }
            ?>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-12 col-xl-3">
                            <div class="form-group">
                                <label for="">Tipe Item</label>
                                <select name="tipe_item" id="tipe_item" class="form-control input-sm select2">
                                    <option value="">Pilih Tipe Item</option>
                                    <option value="01">Sparepart</option>
                                    <option value="02">Bahan Bangunan</option>
                                    <option value="03">Aset Tidak Lancar</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_supplier">Supplier</label>
                                <select name="id_supplier" id="id_supplier" class="form-control input-sm select2">
                                    <option value="">Pilih Supplier</option>
                                    <?php
                                        foreach($supplier as $sp){ ?>
                                            <option value="<?= $sp->id_supplier;?>"><?= $sp->nama_supplier;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="id_gudang">Gudang</label>
                                <select name="id_gudang" id="id_gudang" class="form-control input-sm select2">
                                    <option value="">Pilih Gudang</option>
                                    <?php
                                        foreach($gudang as $gd){ ?>
                                            <option value="<?= $gd->id_gudang;?>"><?= $gd->nama_gudang;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <label for="ppn">PPN (10%)</label>
                                <select name="ppn" id="ppn" class="form-control input-sm" class="form-control input-sm">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" onchange="status_change();" class="form-control input-sm">
                                    <option value="0">PENDING</option>
                                    <option value="1">LUNAS</option>
                                    <option value="2">BATAL</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_bank_account">Bank Account</label>
                                <select name="id_bank_account" id="id_bank_account" class="form-control input-sm select2">
                                    <option value="">-- Pilih Bank Account --</option>
                                    <?php
                                        foreach($bank as $bk){ ?>
                                            <option value="<?= $bk->id_bank_account;?>"><?= $bk->nama_bank.'-'.$bk->nomor_rekening.'-'.$bk->nama_pemilik_rekening;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="tgljt">
                                <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                                <input type="text" class="form-control input-sm pickdate" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo">
                            </div>
                            <div class="form-group" id="tgllns">
                                <label for="tanggal_jatuh_tempo">Tanggal Pelunasan</label>
                                <input type="text" class="form-control input-sm pickdate" name="tanggal_pelunasan" id="tanggal_pelunasan">
                            </div>
                            <div class="form-group">
                                <label for="dropship">Dropship</label>
                                <select name="dropship" id="dropship" class="form-control input-sm select2" onchange="dropship_change()">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                            <div class="form-group" id="customer_for_dropship">
                                <label for="id_customer">Customer Untuk Dropship</label>
                                <select name="id_customer" id="id_customer" class="form-control input-sm select2">
                                    <option value="0">Walk In</option>
                                    <?php
                                        foreach($customer as $ct){ ?>
                                            <option value="<?= $ct->id_customer;?>"><?= $ct->nama_customer;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="metode_for_dropship">
                                <label for="id_metode_pembayaran">Metode Pembayaran</label>
                                <select name="id_metode_pembayaran" id="id_metode_pembayaran" class="form-control input-sm select2">
                                    <option value="">Pilih Metode Pembayaran</option>
                                    <?php
                                        foreach($metode_pembayaran as $mp){ ?>
                                            <option value="<?= $mp->id_metode_pembayaran;?>"><?= $mp->nama_metode_pembayaran;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-12 col-xl-9">
                            <div class="form-group">
                                <label for="">Cari Item</label>
                                <div class="form-group has-feedback has-search">
                                    <span class="glyphicon glyphicon-search form-control-feedback"></span>
                                        <input name="search" onkeyup="search_item()" class="form-control" id="search" placeholder="Search / Scan Produk" type="text" onkeyup="scan_data_beli();">
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
                                                <th>Diskon(%)</th>
                                                <th>Catatan</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listItem">
                                            
                                        </tbody>
                                    </table>            
                                </div>
                            </div>
                            <div class="form-group">
                                
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <label for="total">SUBTOTAL</label><br>
                                        <input type="text" class="form-control" name="subtotalall" id="subtotal" value="0" style="display:none;">
                                        <span id="subtotalall-separator"></span><br><br>
                                        <label for="total">PPn</label><br>
                                        <input type="nominalppn" class="form-control" name="nominalppn" id="nominalppn" value="0" style="display:none;">
                                        <span id="nominalppn-separator"></span><br><br>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-6">
                                        <label for="total">TOTAL</label><br>
                                        <input type="text" class="form-control" name="total" id="total" value="0" style="display:none;">
                                        <span id="total-separator"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block" name="submitAndBack" value="1"><i class="fa fa-save"></i> Simpan Purchase Order dan Kembali ke Daftar Purchase Order</button>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" name="submitAndSelf" value="1"><i class="fa fa-save"></i> Simpan Purchase Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    var isSalesOrder = 0;
    $(document).ready(function(){
        status_change();
        dropship_change();
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
        var qtyItem = $('.qty'+id);
        if($("#listItem tr td input[value='"+id+"']").length == 0 && qtyItem.length == 0){
            var subtotal = harga * 1;
            var row = '<tr>'+
                        '<td>'+no+'</td>'+
                        '<td>'+
                            '<input type="hidden" value="'+id+'" name="id_item[]" class="id">'+
                            nama+
                        '</td>'+
                        '<td>'+
                            '<input type="text" value="'+harga+'" class="form-control input harga" name="harga[]" onkeyup="change_quantity()">'+
                            '<div class="h-line"></div>'+
                            '<span class="harga-separator'+id+'"></span>'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" value="1" class="form-control input mini-input quantity qty'+id+'" onkeyup="change_quantity(this)" name="quantity[]">'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" value="0" class="form-control input mini-input diskon" name="diskon[]" onkeyup="change_quantity()">'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" value="" class="form-control input catatan" name="catatan[]">'+
                        '</td>'+
                        '<td>'+
                            '<input type="text" value="'+subtotal+'" class="form-control input subtotal sub'+id+'" name="subtotal[]" style="display:none;">'+
                            '<span class="subtotal-separator'+id+'"></span>'+
                        '</td>'+
                        '<td>'+
                            '<button type="button" class="btn btn-sm btn-danger" onclick="delRow(this)"><i class="fa fa-trash"></i></button>'+
                        '</td>'+
                    '</tr>';
            no++
            $('#listItem').append(row); 
            $('.harga-separator'+id).html('Rp '+numberFormat(harga));
            $('.subtotal-separator'+id).html('Rp '+numberFormat(subtotal));
            $('#suggestions').fadeOut();
            $('#search').val("").focus();
            console.log
            total();
        }else{
            var currentVal = parseInt(qtyItem.val());
            if(!isNaN(currentVal) && qtyItem.length == 1){
                $(".qty"+id).val(parseInt(parseInt($(".qty"+id).val()) + 1));
            }
            change_quantity()
            $('.harga-separator'+id).html('Rp '+numberFormat(harga));
            $('.subtotal-separator'+id).html('Rp '+numberFormat($('.sub'+id).val()));
            total();
            $('#suggestions').fadeOut();
            $('#search').val("").focus();
        }
    }

    function dropship_change(){
        var dropship = $('#dropship').val();
        if(dropship == 0){
            $('#customer_for_dropship').hide();
            $('#metode_for_dropship').hide();
        }else{
            $('#customer_for_dropship').show();
            $('#metode_for_dropship').show();
        }
    }

	$("#ppn").change(function(){
		change_quantity();
	});

    $("#search").keyup(function () {
        var el = $(this);
            $.ajax({
                url: "<?= base_url('item/auto_add');?>",
                dataType: "json",
                type: "POST",
                data: {'search_data':el.val()},
                success:function(res){
                    if(res.status == 200){
                        var qtyItem = $('.qty'+res.id_item);
                        if(el.val().length == res.sku_item.length){
                            if($("#listItem tr td input[value='"+res.id_item+"']").length == 0 && qtyItem.length == 0){
                                var subtotal = res.harga_jual * 1;
                                var row = '<tr>'+
                                            '<td>'+no+'</td>'+
                                            '<td>'+
                                                '<input type="hidden" value="'+res.id_item+'" name="id_item[]" class="id">'+
                                                res.nama_item+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="'+res.harga_jual+'" class="form-control input harga" name="harga[]" onkeyup="change_quantity()">'+
                                                '<div class="h-line"></div>'+
                                                '<span class="harga-separator'+res.id_item+'"></span>'+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="1" class="form-control input mini-input quantity qty'+res.id_item+'" onkeyup="change_quantity(this)" name="quantity[]">'+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="0" class="form-control input mini-input diskon" name="diskon[]"  onkeyup="change_quantity()">'+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="" class="form-control input catatan" name="catatan[]">'+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="'+subtotal+'" class="form-control input subtotal sub'+res.id_item+'" name="subtotal[]" style="display:none;">'+
                                                '<span class="subtotal-separator'+res.id_item+'"></span>'+
                                            '</td>'+
                                            '<td>'+
                                                '<button type="button" class="btn btn-sm btn-danger" onclick="delRow(this)"><i class="fa fa-trash"></i></button>'+
                                            '</td>'+
                                        '</tr>';
                            
                                $('#listItem').append(row); 
                                $('.harga-separator'+res.id_item).html('Rp '+numberFormat(res.harga_jual));
                                $('.subtotal-separator'+res.id_item).html('Rp '+numberFormat(subtotal));
                                $('#suggestions').fadeOut();
                                $('#search').val("").focus();
                                total();
                            }else{
                                var currentVal = parseInt(qtyItem.val());
                                if(!isNaN(currentVal) && qtyItem.length == 1){
                                    $(".qty"+res.id_item).val(parseInt(parseInt($(".qty"+res.id_item).val()) + 1));
                                }
                                change_quantity()
                                $('.harga-separator'+res.id_item).html('Rp '+numberFormat(res.harga_jual));
                                $('.subtotal-separator'+res.id_item).html('Rp '+numberFormat($('.sub'+res.id_item).val()));
                                total();
                                $('#suggestions').fadeOut();
                                $('#search').val("").focus();
                            }
                        }
                    }
                }
            });
        });
</script>
<script src="<?= base_url();?>assets/spada/js/transaksi.js?v=20072401"></script>
<?php $this->load->view('layout/foot');?>