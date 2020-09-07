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
<form action="<?= base_url();?>so/sales_order_update/<?= $so->id_penjualan;?>" method="POST">
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
                                <select name="id_customer" id="id_customer" class="form-control input-sm select2">
                                    <option value="0">Walk In</option>
                                    <?php
                                        foreach($customer as $cs){ ?>
                                            <option value="<?= $cs->id_customer;?>" <?php if($so->id_customer == $cs->id_customer){echo 'selected';}else{echo '';}?>><?= $cs->nama_customer;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_gudang">Gudang</label>
                                <select name="id_gudang" id="id_gudang" class="form-control input-sm select2">
                                    <?php
                                        foreach($gudang as $gd){ ?>
                                            <option value="<?= $gd->id_gudang;?>" <?php if($so->id_gudang == $gd->id_gudang){echo 'selected';}else{echo '';}?>><?= $gd->nama_gudang;?></option>
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
                            <div class="form-group logistikinput logistikeksternal">
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
                            <div class="form-group logistikinput">
                                <label for="status_logistik">Status Logistik</label>
                                <select name="status_logistik" id="status_logistik" class="form-control input-sm select2">
                                    <option value="0" <?php if($so->status_logistik == 0){echo 'selected';}else{echo '';}?>>Pending</option>
                                    <option value="1" <?php if($so->status_logistik == 1){echo 'selected';}else{echo '';}?>>Approved</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="dropship">Dropship</label>
                                <select name="dropship" id="dropship" class="form-control input-sm select2">
                                    <option value="0" <?php if($so->dropship == 0){echo 'selected';}else{echo '';}?>>No</option>
                                    <option value="1" <?php if($so->dropship == 1){echo 'selected';}else{echo '';}?>>Yes</option>
                                </select>
                            </div>
                            <div class="form-group" style="display:none;">
                                <label for="ppn">PPN (10%)</label>
                                <select name="ppn" id="ppn" class="form-control input-sm" class="form-control input-sm">
                                    <option value="1" <?php if($so->ppn == 1){echo 'selected';}else{echo '';}?>>Ya
                                    </option><option value="0" <?php if($so->ppn == 0){echo 'selected';}else{echo '';}?>>Tidak</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status" id="status" onchange="status_change();" class="form-control input-sm">
                                    <option value="0" <?php if($so->status_penjualan == 0){echo 'selected';}else{echo '';}?>>PENDING</option>
                                    <option value="1" <?php if($so->status_penjualan == 1){echo 'selected';}else{echo '';}?>>LUNAS</option>
                                    <option value="2" <?php if($so->status_penjualan == 2){echo 'selected';}else{echo '';}?>>BATAL</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_metode_pembayaran">Metode Pembayaran</label>
                                <select name="id_metode_pembayaran" id="id_metode_pembayaran" class="form-control input-sm select2">
                                    <?php
                                        foreach($metode_pembayaran as $mp){ ?>
                                            <option value="<?= $mp->id_metode_pembayaran;?>" <?php if($so->id_metode_pembayaran == $mp->id_metode_pembayaran){echo 'selected';}else{echo '';}?>><?= $mp->nama_metode_pembayaran;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group" id="tgljt">
                                <label for="tanggal_jatuh_tempo">Tanggal Jatuh Tempo</label>
                                <input type="text" class="form-control input-sm pickdate" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="<?= $so->tanggal_jatuh_tempo;?>">
                            </div>
                            <div class="form-group" id="tgllns">
                                <label for="tanggal_jatuh_tempo">Tanggal Pelunasan</label>
                                <input type="text" class="form-control input-sm pickdate" name="tanggal_pelunasan" id="tanggal_pelunasan" value="<?= $so->tanggal_pelunasan;?>">
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
                                                <th>Diskon</th>
                                                <th>Biaya Logistik</th>
                                                <th>Catatan</th>
                                                <th>Subtotal</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listItem">
                                            <?php
                                            foreach($detail_so as $i => $ds){ 
                                            ?>
                                            <tr>
                                                <td><?= ++$i;?></td>
                                                <td>
                                                    <input type="hidden" value="<?= $ds->id_item;?>" name="id_item[]" class="id">
                                                    <?= $ds->nama_item;?>
                                                </td>
                                                <td>
                                                    <span class="harga-separator<?=$ds->id_item;?>"><?= number_format($ds->harga,0,'.','.');?></span>
                                                    <input type="hidden" value="<?= $ds->harga;?>" class="form-control input harga" name="harga[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="<?= $ds->quantity;?>" class="form-control input mini-input quantity qty<?= $ds->id_item;?>" onkeyup="change_quantity_so()" name="quantity[]">
                                                </td>
                                                <td>
                                                <select name="diskon[]" id="diskon<?php echo $ds->id_item; ?>" class="form-control input diskon">
                                                    <option value="0">No Diskon</option>
                                                    <?php
                                                        foreach($diskon as $dk){ ?>
                                                            <option data-jenis="<?= $dk->jenis_diskon; ?>" data-nominal="<?= $dk->nominal_diskon; ?>" value="<?= $dk->id_diskon;?>" <?php if($ds->id_diskon == $dk->id_diskon){echo 'selected';}else{echo '';}?>><?= $dk->nama_diskon;?></option>
                                                    <?php    
                                                    }
                                                    ?>
                                                </select>
                                                <input type="hidden" name="hidden_diskon" id="hidden_diskon<?php echo $ds->id_item; ?>" class="hidden_diskon" value="<?= $ds->nominal_diskon; ?>">
                                                <span id="display_diskon<?php echo $ds->id_item; ?>" class="display_diskon">Rp <?= number_format($ds->nominal_diskon,0,'.','.');?></span>
                                                </td>
                                                <td>
                                                    <input type="text" value="<?= $ds->biaya_logistik;?>" class="form-control input biaya_logistik" onkeyup="change_quantity_so()" name="biaya_logistik[]" style="display:none;">
                                                </td>
                                                <td>
                                                    <input type="text" value="" class="form-control input catatan" value="<?= $ds->catatan;?>" name="catatan[]">
                                                </td>
                                                <td>
                                                    <input type="text" value="<?= $ds->subtotal;?>" class="form-control input subtotal sub<?= $ds->id_item;?>" name="subtotal[]" style="display:none;">
                                                    <div class="h-line" style="display:none"></div>
                                                    <span class="subtotal-separator<?=$ds->id_item;?>"><?= number_format($ds->subtotal,0,'.','.');?></span>
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
                            <!-- <div class="form-group">
                                <label for="diskon">Diskon</label>
                                <select name="diskon" id="diskon" class="form-control input-sm">
                                    <option value="0">Tidak Ada</option>
                                    <?php
                                        foreach($diskon as $dk){ ?>
                                            <option value="<?= $dk->id_diskon;?>" <?php if($so->diskon == $dk->id_diskon){echo 'selected';}else{echo '';}?>><?= $dk->nama_diskon;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div> -->
                            <!-- <div class="form-group">
                                <label for="total">SUBTOTAL</label>
                                <input type="text" class="form-control" name="subtotalall" id="subtotal" value="0">
                            </div> -->
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xl-12">
                                        <label for="total">SUBTOTAL (INCLUDE DISKON)</label><br>
                                        <input type="text" class="form-control" name="subtotalall" id="subtotal" value="0" style="display:none;">
                                        <span id="subtotalall-separator"></span><br><br>
                                        <!-- <label for="nominaldiskon">Diskon</label><br>
                                        <input type="text" class="form-control" name="nominaldiskon" id="nominaldiskon" value="0" style="display:none;">
                                        <span id="nominaldiskon-separator"></span><br><br> -->
                                        <label for="total">PPn</label><br>
                                        <input type="nominalppn" class="form-control" name="nominalppn" id="nominalppn" value="0" style="display:none;">
                                        <span id="nominalppn-separator"></span><br><br>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-12">
                                        <label for="total">TOTAL</label><br>
                                        <input type="text" class="form-control" name="total" id="total" value="<?= $so->grand_total;?>" style="display:none;">
                                        <span id="total-separator">Rp <?= number_format($so->grand_total,0,'.','.');?></span><br><br>
                                        <label for="nominaldiskon">TOTAL DISKON</label><br>
                                        <input type="text" class="form-control" name="nominaldiskon" id="nominaldiskon" value="0" style="display:none;">
                                        <span id="nominaldiskon-separator"></span><br><br>
                                    </div>
                                </div>
                            </div>
                            <?php if($check == 0){ ?>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block" name="submitAndBack"><i class="fa fa-save"></i> Simpan Sales Order dan Kembali ke Daftar Sales Order</button>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" name="submitAndSelf"><i class="fa fa-save"></i> Simpan Sales Order</button>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
	<?php if($check != 0){ ?>
				$("input").attr("disabled", "disabled");
				$("select").attr("disabled", "disabled");
	<?php			
			}
	?>
	
	var isSalesOrder = 1;
	var arrdiskon = [];
	
	<?php
	if(count($diskon) > 0){ ?>
			<?php foreach($diskon as $dk){
					echo "arrdiskon[".$dk->id_diskon."] = {jenis: ".$dk->jenis_diskon.", nominal: ".(int)$dk->nominal_diskon."}; ";
			 } ?>
	<?php } ?>
	
    $(document).ready(function(){
        status_change();
        total();
        toggle_logistik();
        
        $("#conf_logistik").change(function(){
	        toggle_logistik();
        });
        
        $(".diskon").change(function(){ //recount diskon
	        change_quantity_so();
	        total();
        });
    })
    
    function toggle_logistik(){
	   	var conf_logistik = $("#conf_logistik").val();
		if(conf_logistik == 2){ //hide form logistik dan status logistik apabila jenis logistik = Tanpa Logistik
			$(".logistikinput").hide();
		}
		else if(conf_logistik == 1){
			$(".logistikinput").show();
			$(".logistikeksternal").show();
		}
		else{
			$(".logistikinput").show();
			$(".logistikeksternal").hide();
		}
    }
    
    function search_item(){
        var item = $('#search').val();
        var gudang = $('#id_gudang').val();

        // if(gudang != ''){
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
        // }else{
        //     alert('Gudang belum dipilih!');
        // }
    }
    var no = 1;
    function addToTable(id,nama,harga){
        $.getJSON('<?php echo base_url();?>so/diskon',function(data){
            html = '';
            html += '<select class="form-control input diskon" id="diskon' + id + '" name="diskon[]">'+
                    '<option value="0">No Diskon</option>';
            $.each(data,function(i,val){
                html += '<option value="'+val.id_diskon+'" data-jenis='+val.jenis_diskon+' data-nominal='+val.nominal_diskon+'>'+val.nama_diskon+'</option>';
            })
            html += '</select>';
            var qtyItem = $('.qty'+id);
            if($("#listItem tr td input[value='"+id+"']").length == 0 && qtyItem.length == 0){
                var subtotal = harga * 1;
                var row = '<tr>'+
                            '<td>'+no+'</td>'+
                            '<td width="100">'+
                                '<input type="hidden" value="'+id+'" name="id_item[]" class="id">'+
                                nama+
                            '</td>'+
                            '<td width="150">'+
                                '<span class="harga-separator'+id+'">'+harga+'</span>'+
                                '<input type="hidden" value="'+harga+'" class="form-control input harga" name="harga[]">'+
                            '</td>'+
                            '<td>'+
                                '<input type="text" value="1" class="form-control input mini-input quantity qty'+id+'" onkeyup="change_quantity_so()" name="quantity[]">'+
                            '</td>'+
                            '<td>'+
                                html +
                            '<input type="hidden" name="hidden_diskon" id="hidden_diskon'+id+'" class="hidden_diskon" value="0">' +
                            '<span id="display_diskon' + id +'" class="display_diskon"></span></td>'+
                            '</td>'+
                            '<td>'+
                                '<input type="text" value="0" class="form-control input biaya_logistik" onkeyup="change_quantity_so()" name="biaya_logistik[]" style="display:none;">'+
                            '</td>'+
                            '<td>'+
                                '<input type="text" value="" class="form-control input catatan" name="catatan[]">'+
                            '</td>'+
                            '<td>'+
                                '<input type="text" value="'+subtotal+'" class="form-control input subtotal sub'+id+'" name="subtotal[]" style="display:none;">'+
                                '<div class="h-line" style="display:none;"></div>'+
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
                total();
                
                //tambahkan agar bisa fire untuk diskon yang baru masuk element / DOM
		        $("#diskon" + id).change(function(){ //recount diskon
			        change_quantity_so();
			        total();
		        });
            }else{
                var currentVal = parseInt(qtyItem.val());
                if(!isNaN(currentVal) && qtyItem.length == 1){
                    $(".qty"+id).val(parseInt(parseInt($(".qty"+id).val()) + 1));
                }
                change_quantity_so()
                $('.harga-separator'+id).html('Rp '+numberFormat(harga));
                $('.subtotal-separator'+id).html('Rp '+numberFormat($('.sub'+id).val()));
                total();
                $('#suggestions').fadeOut();
                $('#search').val("").focus();
            }
        })
    }

    $("#search").keyup(function () {
        var el = $(this);
            $.ajax({
                url: "<?= base_url('item/auto_add');?>",
                dataType: "json",
                type: "POST",
                data: {'search_data':el.val()},
                success:function(res){
                    if(res.status == 200){
                        $.getJSON('<?php echo base_url();?>so/diskon',function(data){
                        html = '';
			            html += '<select class="form-control input diskon" id="diskon' + id + '" name="diskon[]">'+
			                    '<option value="0">No Diskon</option>';
			            $.each(data,function(i,val){
			                html += '<option value="'+val.id_diskon+'" data-jenis='+val.jenis_diskon+' data-nominal='+val.nominal_diskon+'>'+val.nama_diskon+'</option>';
			            })
                        html += '</select>';
                        var qtyItem = $('.qty'+res.id_item);
                        if(el.val().length == res.sku_item.length && res.sku_item.length > 5){
                            if($("#listItem tr td input[value='"+res.id_item+"']").length == 0 && qtyItem.length == 0){
                                var subtotal = res.harga_jual * 1;
                                var row = '<tr>'+
                                            '<td>'+no+'</td>'+
                                            '<td width="100">'+
                                                '<input type="hidden" value="'+res.id_item+'" name="id_item[]" class="id">'+
                                                res.nama_item+
                                            '</td>'+
                                            '<td>'+
                                                '<span class="harga-separator'+res.id_item+'">'+res.harga_jual+'</span>'+
                                                '<input type="hidden" value="'+res.harga_jual+'" class="form-control input harga" name="harga[]">'+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="1" class="form-control input mini-input quantity qty'+res.id_item+'" onkeyup="change_quantity_so()" name="quantity[]">'+
                                            '</td>'+
                                            '<td>'+
                                                html+
                                            '<input type="hidden" name="hidden_diskon" id="hidden_diskon'+id+'" class="hidden_diskon" value="0">' +
											'<span id="display_diskon' + id +'" class="display_diskon"></span></td>'+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="0" class="form-control input biaya_logistik" onkeyup="change_quantity_so()" name="biaya_logistik[]" style="display:none;">'+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="" class="form-control input catatan" name="catatan[]">'+
                                            '</td>'+
                                            '<td>'+
                                                '<input type="text" value="'+subtotal+'" class="form-control input subtotal sub'+res.id_item+'" name="subtotal[]" style="display:none;">'+
                                                '<div class="h-line" style="display:none;"></div>'+
                                                '<span class="subtotal-separator'+res.id_item+'"></span>'+
                                            '</td>'+
                                            '<td>'+
                                                '<button type="button" class="btn btn-sm btn-danger" onclick="delRow(this)"><i class="fa fa-trash"></i></button>'+
                                            '</td>'+
                                        '</tr>';
                                    no++
                                    $('#listItem').append(row);
                                    $('.harga-separator'+res.id_item).html('Rp '+numberFormat(res.harga));
                                    $('.subtotal-separator'+res.id_item).html('Rp '+numberFormat(subtotal));
                                    $('#suggestions').fadeOut();
                                    $('#search').val("").focus();
                                    total();
                                    
                                    //tambahkan agar bisa fire untuk diskon yang baru masuk element / DOM
							        $("#diskon" + id).change(function(){ //recount diskon
								        change_quantity_so();
								        total();
							        });
                                }else{
                                    var currentVal = parseInt(qtyItem.val());
                                    if(!isNaN(currentVal) && qtyItem.length == 1){
                                        $(".qty"+res.id_item).val(parseInt(parseInt($(".qty"+res.id_item).val()) + 1));
                                    }
                                    change_quantity_so()
                                    $('.harga-separator'+res.id_item).html('Rp '+numberFormat(res.harga_jual));
                                    $('.subtotal-separator'+res.id_item).html('Rp '+numberFormat($('.sub'+res.id_item).val()));
                                    total();
                                    $('#suggestions').fadeOut();
                                    $('#search').val("").focus();
                                }
                            }
                        })
                    }
                }
            });
        });
</script>
<script src="<?= base_url();?>assets/spada/js/transaksi.js?v=20050609"></script>
<?php $this->load->view('layout/foot');?>