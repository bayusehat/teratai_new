<?php $this->load->view('layout/head');?>
<form action="<?= base_url();?>item/doCreateMutasi" method="POST">
<div id="content-wrapper" class="group">
    <div id="page-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6 col-md-6 col-xl-6">
                        <?php
                            if($this->session->flashdata('success')){ ?>
                                <div class="alert alert-success"><?= $this->session->flashdata('success');?></div>
                        <?php  } ?>
                        <?php
                            if($this->session->flashdata('failed')){ ?>
                                <div class="alert alert-danger"><?= $this->session->flashdata('failed');?></div>
                        <?php  } ?>
                            <div class="form-group">
                                <label for="">Tanggal Mutasi</label>
                                <input type="text" class="form-control pickdate" name="tanggal_mutasi" id="tanggal_mutasi" placeholder="Tanggal Mutasi">
                            </div>
                            <div class="form-group">
                               <div class="row">
                                    <div class="col-md-6 col-sm-12 col-xl-12">
                                        <div class="form-group">
                                            <label for="">Gudang Asal</label>
                                            <select name="gudang_asal" id="gudang_asal" class="form-control">
                                                <option value="">-- Pilih Gudang --</option>
                                                <?php
                                                    foreach($gudang as $ga){ ?>
                                                    <option value="<?= $ga->id_gudang;?>"><?= $ga->nama_gudang;?></option>
                                                <?php    
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12 col-xl-12">
                                        <div class="form-group">
                                            <label for="">Gudang Tujuan</label>
                                            <select name="gudang_tujuan" id="gudang_tujuan" class="form-control">
                                                <option value="">-- Pilih Gudang --</option>
                                                <?php
                                                    foreach($gudang as $gt){ ?>
                                                    <option value="<?= $gt->id_gudang;?>"><?= $gt->nama_gudang;?></option>
                                                <?php    
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                               </div>
                            </div>
                            <div class="form-group">
                                <label for="">Catatan</label>
                                <textarea name="catatan" id="catatan" class="form-control" style="margin: 0px 548px 0px 0px; width: 519px; height: 68px;" cols="30" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-md-6">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12">
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
                            </div>
                        <hr>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xl-12">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Item</th>
                                                <th>Quantity</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listItem">
                                          <!-- List Item -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block"><i class="fa fa-save"></i> Simpan Mutasi</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
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

    function addToTable(id,nama){
        var row = '<tr>'+
                    '<td>'+
                        '<input type="hidden" name="id_item[]" value="'+id+'">'+nama+
                   '</td>'+
                    '<td>'+
                        '<input type="text" class="form-control input-sm" name="quantity[]" value="">'+
                    '</td>'+
                    '<td>'+
                        '<button type="button" class="btn btn-danger btn-sm btn-block delItem"><i class="fa fa-trash"></i></button>'+
                    '</td>'+
                '</tr>';
        $('#listItem').append(row);
        $('#suggestions').fadeOut();
        $('#search').val("").focus();
    }

   function delRow(e){
    $(e).closest('tr').remove();
   }
</script>
<?php $this->load->view('layout/foot');?>