<?php $this->load->view('layout/head');?>
<div id="content-wrapper" class="group">
    <div id="page-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-12">
                    <?php
                        if(strlen($successmsg) > 0){ ?>
                            <div class="alert alert-success"><?= $successmsg;?></div>
                    <?php  } ?>
                    <?php
                        if(strlen($failmsg) > 0){ ?>
                            <div class="alert alert-danger"><?= $failmsg;?></div>
                    <?php  } ?>
                    	<h3>Warning: Jika SKU item sudah ada dalam database, maka data yang sudah ada akan di-overwrite</h3>
                        <form action="<?= base_url();?>item/import" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="invfile">Pilih File Excel List Item Teratai</label>
                                <input type="file" name="invfile" id="invfile" accept=".xlsx">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success"><i class="fa fa-sync"></i> Import List Item</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layout/foot');?>