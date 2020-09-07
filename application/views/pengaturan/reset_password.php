<?php $this->load->view('layout/head');?>
<div id="content-wrapper" class="group">
    <div id="page-wrapper">
        <div class="row">
                <div class="col-lg-6">
                    <a href="<?= base_url('master/user');?>" class="btn btn-danger mb-5"><i class="fa fa-arrow-left"></i> Kembali</a>
                </div>
            </div>
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
                        <form action="<?= base_url();?>master/doResetPassword/<?= $user->id_user;?>" method="POST">
                            <div class="form-group">
                                <label for="">Password Baru : </label>
                                <input type="password" name="password_baru" id="password_baru" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="">Konfirmasi Password Baru: </label>
                                <input type="password" name="c_password_baru" id="c_password_baru" class="form-control">
                            </div>
                            <div class="form-group">
                                <button class="btn btn-success"><i class="fa fa-sync"></i> Reset Password User <?= $user->nama_user; ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layout/foot');?>