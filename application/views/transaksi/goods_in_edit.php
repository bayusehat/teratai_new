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
<form action="<?= base_url();?>gi/goods_in_update/<?= $gi->id_item_masuk;?>" method="POST">
    <div id="content-wrapper" class="group">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <a href="<?= base_url('gi/goods_in');?>" class="btn btn-danger mb-5"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-12 col-xl-6">
                            <div class="form-group">
                                <label for="id_supplier">Supplier</label>
                                <select name="id_supplier" id="id_supplier" class="form-control select2">
                                    <!--<option value="">Pilih Supplier</option>-->
                                    <?php
                                        foreach($supplier as $sp){ ?>
                                            <option value="<?= $sp->id_supplier;?>" <?php if($sp->id_supplier == $gi->id_supplier){echo 'selected';}else{echo '';}?>><?= $sp->nama_supplier;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_gudang">Gudang</label>
                                <select name="id_gudang" id="id_gudang" class="form-control input-sm select2">
                                    <option value="">Pilih Gudang</option>
                                    <?php
                                        foreach($gudang as $gd){ ?>
                                            <option value="<?= $gd->id_gudang;?>" <?php if($gd->id_gudang == $gi->id_gudang){echo 'selected';}else{echo '';}?>><?= $gd->nama_gudang;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xl-12">
                            <div class="card" style="border:1px solid lightgrey">
                                <h3>List Purchase Order Other</h3>
                                <hr>
                                <table class="table table-bordered table-hovered table-condensed" id="tableData">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Act</th>
                                            <th>No. PO</th>
                                            <th>Created</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan Item Masuk</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function(){
        loadData();
    })

    function loadData(){
        var id_sup = $('#id_supplier').val();
        var id_gi = '<?= $gi->id_item_masuk; ?>';
        $('#tableData').DataTable({
            asynchronous: true,
            processing: true, 
            destroy: true,
            ajax: {
                url: "<?= base_url('gi/check_po'); ?>/"+id_sup+"/"+id_gi,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'GET'
            },
            columns: [
                { name: 'id_pembelian', searchable: false, orderable: true, className: 'text-center' },
                { name: 'action', searchable: false, orderable: false, className: 'text-center' },
                { name: 'no_po' },
                { name: 'date' }
            ],
            order: [[0, 'asc']],
            iDisplayInLength: 10 
        });
    }
</script>
<script src="<?= base_url();?>assets/spada/js/transaksi.js"></script>
<?php $this->load->view('layout/foot');?>