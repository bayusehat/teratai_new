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
<form action="<?= base_url();?>retur_penjualan/insert/<?= $this->uri->segment(3);?>" method="POST">
    <div id="content-wrapper" class="group">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-6">
                    <a href="<?= base_url('retur_penjualan/list_retur/'.$this->uri->segment(3));?>" class="btn btn-danger mb-5"><i class="fa fa-arrow-left"></i> Kembali</a>
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
                                <label for="id_customer">Customer</label>
                                <select name="id_customer" id="id_customer" class="form-control input-sm select2">
                                    <option value="">Pilih Customer</option>
                                    <?php
                                        // foreach($customer as $sp){ ?>
                                            <option value="<?= $customer->id_customer;?>" selected><?= $customer->nama_customer;?></option>
                                    <?php    
                                    // }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="id_gudang">Gudang</label>
                                <select name="id_gudang" id="id_gudang" class="form-control input-sm select2">
                                    <option value="">Pilih Gudang</option>
                                    <?php
                                        // foreach($gudang as $gd){ ?>
                                            <option value="<?= $gudang->id_gudang;?>" selected><?= $gudang->nama_gudang;?></option>
                                    <?php    
                                    // }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select name="status_retur" id="status_retur" onchange="status_change();" class="form-control input-sm">
                                    <option value="0">PENDING</option>
                                    <option value="1">APPROVED</option>
                                    <option value="2">BATAL</option>
                                </select>
                            </div>
                            <div class="form-group" id="tgljt">
                                <label for="tanggal_jatuh_tempo_retur">Tanggal Jatuh Tempo</label>
                                <input type="text" class="form-control input-sm pickdate" name="tanggal_jatuh_tempo_retur" id="tanggal_jatuh_tempo_retur">
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
                                                foreach($detail_so as $dp){ ?>
                                                    <tr>
                                                        <td><?= ++$no; ?></td>
                                                        <td>
                                                            <?= $dp->nama_item;?>
                                                            <input type="hidden" name="id_item[]" value="<?= $dp->id_item;?>">
                                                        </td>
                                                       <td>
                                                            Rp <?= number_format($dp->harga);?>
                                                            <input type="hidden" name="harga[]" value="<?= $dp->harga;?>    ">
                                                        </td>
                                                        <td><?= $dp->available_qty;?></td>
                                                        <td><input type="text" class="form-control" name="qty_retur[]" value="0"></td>
                                                        <td><input type="text" class="form-control" name="catatan[]"></td>
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
                                <button type="submit" class="btn btn-success btn-block" name="submit"><i class="fa fa-save"></i> Simpan Retur Penjualan</button>
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