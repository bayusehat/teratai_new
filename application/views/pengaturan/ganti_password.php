<?php $this->load->view('layout/head');?>
<div id="content-wrapper" class="group">
    <div id="page-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-12">
                    <?php
                        if($this->session->flashdata('success')){ ?>
                            <div class="alert alert-success"><?= $this->session->flashdata('success');?></div>
                    <?php  } ?>
                    <?php
                        if($this->session->flashdata('failed')){ ?>
                            <div class="alert alert-danger"><?= $this->session->flashdata('failed');?></div>
                    <?php  } ?>
                        <form action="<?= base_url();?>user/doChangePassword" method="POST">
                            <div class="form-group">
                                <label for="">Password Lama : </label>
                                <input type="password" name="pass_lama" id="pass_lama" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Konfirmasi Password Lama : </label>
                                <input type="password" name="c_pass_lama" id="c_pass_lama" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Password Baru : </label>
                                <input type="password" name="pass_baru" id="pass_baru" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success"><i class="fa fa-sync"></i> Ganti Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layout/foot');?>