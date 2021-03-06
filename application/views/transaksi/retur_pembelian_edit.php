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
<form action="<?= base_url();?>retur_pembelian/update/<?= $this->uri->segment(3);?>" method="POST">
    <div id="content-wrapper" class="group">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <a href="<?= base_url('retur_pembelian/list_retur/'.$retur->id_pemesanan);?>" class="btn btn-danger mb-5"><i class="fa fa-arrow-left"></i> Kembali</a>
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
                                <label for="id_supplier">Supplier</label>
                                <select name="id_supplier" id="id_supplier" class="form-control input-sm select2">
                                    <option value="">Pilih Supplier</option>
                                    <!-- <?php
                                        foreach($supplier as $sp){ ?>
                                            <option value="<?= $sp->id_supplier;?>" <?php if($retur->id_supplier == $sp->id_supplier){echo 'selected';}else{echo '';}?>><?= $sp->nama_supplier;?></option>
                                    <?php    }
                                    ?> -->
                                    <?php
                                        // foreach($supplier as $sp){ ?>
                                            <option value="<?= $supplier->id_supplier;?>" selected><?= $supplier->nama_supplier;?></option>
                                    <?php    
                                        // }
                                    ?>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="id_gudang">Gudang</label>
                                <select name="id_gudang" id="id_gudang" class="form-control input-sm select2">
                                    <option value="">Pilih Gudang</option>
                                    <?php
                                        foreach($gudang as $gd){ ?>
                                            <option value="<?= $gd->id_gudang;?>" <?php if($retur->id_gudang == $gd->id_gudang){echo 'selected';}else{echo '';}?>><?= $gd->nama_gudang;?></option>
                                    <?php    }
                                    ?>
                                </select>
                            </div> -->
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status_retur" id="status_retur" onchange="status_change();" class="form-control input-sm">
                                    <option value="0" <?php if($retur->status_retur == '0'){echo 'selected';}else{echo '';}?>>PENDING</option>
                                    <option value="1" <?php if($retur->status_retur == '1'){echo 'selected';}else{echo '';}?>>APPROVED</option>
                                    <option value="2" <?php if($retur->status_retur == '2'){echo 'selected';}else{echo '';}?>>BATAL</option>
                                </select>
                            </div>
                            <div class="form-group" id="tgljt">
                                <label for="tanggal_jatuh_tempo_retur">Tanggal Jatuh Tempo</label>
                                <input type="text" class="form-control input-sm pickdate" name="tanggal_jatuh_tempo_retur" id="tanggal_jatuh_tempo_retur" value="<?= $retur->tanggal_jatuh_tempo_retur;?>">
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-12 col-xl-9">
                            <div class="form-group">
                                <div class="tableFixHead">
                                    <table class="table table-bordered table-striped" id="myTable">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Item</th>
                                                <th>Harga</th>
                                                <th>Qty (saat ini)</th>
                                                <th>Qty (untuk retur)</th>
                                                <th>Catatan</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="listItem">
                                            <?php
                                                $no = 0;
                                                foreach($detail as $dp){ ?>
                                                    <tr>
                                                        <td><?= ++$no; ?></td>
                                                        <td>
                                                            <?= $dp->nama_item;?>
                                                            <input type="hidden" name="id_item[]" value="<?= $dp->id_item;?>">
                                                            <input type="hidden" name="id_retur_pembelian_detail[]" value="<?= $dp->id_retur_pembelian_detail;?>">
                                                        </td> 
                                                        <td>
                                                            Rp <?= number_format($dp->harga);?>
                                                            <input type="hidden" name="harga[]" value="<?= $dp->harga;?>">
                                                        </td>
                                                        <td><?= $dp->quantity;?></td>
                                                        <td><input type="text" class="form-control" name="qty_retur[]" value="<?= $dp->quantity_retur;?>"></td>
                                                       
                                                        <td><input type="text" class="form-control" name="catatan[]" value="<?= $dp->catatan;?>"></td>
                                                        <td>
                                                            <button type="button" class="btn btn-danger del" onclick="delRow()"><i class="fa fa-trash"></i></button>
                                                        </td>
                                                    </tr>
                                            <?php  } ?>
                                        </tbody>
                                    </table>            
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success btn-block" name="submit"><i class="fa fa-save"></i> Simpan Retur Pembelian</button>
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
    })
</script>
<script src="<?= base_url();?>assets/spada/js/transaksi.js"></script>
<?php $this->load->view('layout/foot');?>