<?php $this->load->view('layout/head');?>
<div id="content-wrapper" class="group">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xl-12">
                <a href="<?= base_url();?>master/jabatan" class="btn btn-danger right"><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    <br>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-12 col-xl-12">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <td>Nama Menu</td>
                                    <td>Access</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								foreach($menu as $mn) {
									if($mn->menu_parent == 0){
	                                    $check = $this->db->where('id_jabatan',$role->id_jabatan)->where('id_menu',$mn->id_menu)->get('tb_user_access');
	                                ?>
		                                <tr>
		                                    <td><?= $mn->nama_menu; ?></td>
		                                    <td>
		                                        <input type="checkbox" name="id_jabatan" onclick="haveAccess(<?= $mn->id_menu;?>,<?= $role->id_jabatan;?>)" <?php if($check->num_rows() > 0 ){echo 'checked';}else{echo '';};?>>
		                                    </td>
		                                </tr>
		                                
		                                <?php foreach($menu as $smn) { 
			                                	if($smn->menu_parent == $mn->id_menu){
				                                	$check = $this->db->where('id_jabatan',$role->id_jabatan)->where('id_menu',$smn->id_menu)->get('tb_user_access');
			                            ?>
		                                	<tr>
			                                    <td><?= "---".$smn->nama_menu; ?></td>
			                                    <td>
			                                        <input type="checkbox" name="id_jabatan" onclick="haveAccess(<?= $smn->id_menu;?>,<?= $role->id_jabatan;?>)" <?php if($check->num_rows() > 0 ){echo 'checked';}else{echo '';};?>>
			                                    </td>
			                                </tr>
		                                <?php 	} ?>
		                                <?php } ?>
									<?php } ?>
								<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('layout/foot');?>
<script>
    function haveAccess($menu,$role){
        $.ajax({
            url : '<?= base_url();?>user/haveAccess/'+$menu+'/'+$role,
            method : 'POST',
            dataType : 'JSON',
            success:function(res){
                alert(res.pesan);
            }
        });
    }
    
</script>
