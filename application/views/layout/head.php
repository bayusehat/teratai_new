<!DOCTYPE HTML>
<!--[if lt IE 7]><html class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]><html class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]><html class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--><html class="no-js"><!--<![endif]-->
<head>
    <?php
    if($wogc == false){
        foreach($css_files as $file): ?>
            <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
    <?php endforeach;} ?>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>assets/spada/images/icon.png">
    <meta name="description" content="Backend UI" />
    <meta name="author" content="SPADA Digital Consulting" />
	<title><?php echo $title;?></title>
    <link rel="stylesheet" href="<?php echo base_url();?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/resources/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/spada/css/normalize.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/spada/css/grid.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/spada/css/style.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/spada/css/modal-center.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/spada/css/transaksi.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/spada/css/float-button.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/spada/font-awesome/css/font-awesome.min.css" type="text/css" media="screen">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/resources/jquery-ui.css" >
    <link rel="stylesheet" href="<?php echo base_url();?>assets/spada/css/add-css.css" type="text/css" media="screen">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.11.0/sweetalert2.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
    <script src="<?php echo base_url();?>assets/resources/sweetalert2.min.js"></script>
    <script src="<?php echo base_url();?>assets/resources/jquery.min.js"></script>
    <script src="<?php echo base_url();?>assets/resources/bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/resources/jquery-1.9.1.js"></script>
    <script src="<?php echo base_url();?>assets/resources/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>
    <script src="<?php echo base_url();?>assets/spada/js/notify.js"></script>
    <?php 
    if($wogc == false){
        foreach($js_files as $file): 
    ?>
            <script src="<?php echo $file; ?>"></script>
    <?php
     endforeach;
    } 
    ?>
    <style type="text/css">
        .gc-container{
            height:100px;
            padding: 0;
        }
        #berhasil,#gagal{
            display: none;
        }
        .text-white{
            color:white;
        }
        .mb-5{
            margin-bottom:10px;
        }
        .h-line{
            border-bottom: 1px solid lightgrey;
            padding-bottom: 5px;
            margin-bottom : 5px;
        }
    </style>
    <script>
        var base_url = '<?php echo base_url();?>';
        $(document).ready(function() {
             $('.select2').select2();
             $('.pickdate').datepicker({
                 dateFormat : 'yy-mm-dd'
             })

            //set real time
            var interval = setInterval(function() {
            var momentNow = moment();
                $('#tgl_real').val(momentNow.format('YYYY-MM-DD hh:mm:ss'))
            }, 100);
            
            $(window).keydown(function(event){
			    if(event.keyCode == 13) {
			      event.preventDefault();
			      return false;
			    }
			  });
        })

        function notif(title,text,type){
            swal({
                title: title,
                text: text,
                timer: 2500,
                showConfirmButton: false,
                type: type
            });
        }
    </script>
</head>
<body>
    <!-- Notif -->
    <?php if ($this->session->flashdata('success')): ?>
        <script>
            swal({
                title: "Success",
                text: "<?php echo $this->session->flashdata('success'); ?>",
                timer: 2500,
                showConfirmButton: false,
                type: 'success'
            });
        </script>
    <?php endif; ?>
    <?php if ($this->session->flashdata('failed')): ?>
        <script>
            swal({
                title: "Error",
                text: "<?php echo $this->session->flashdata('failed'); ?>",
                timer: 2500,
                showConfirmButton: false,
                type: 'error'
            });
        </script>
    <?php endif; ?>
	<div id="main" class="group">
        <div id="left-panel" class="col">
            <div id="logo">
                <img src="<?php echo base_url();?>assets/spada/images/teratai.png">
            </div>
            <div id="left-navigation">
                <ul class="main-menu">
                    <li class="menu-item">
                        <a href="<?php echo base_url();?>"><i class="fa fa-tachometer"></i>Dashboard</a>
                    </li>
                    <?php
	                $parentlist = $this->db->query('select menu_parent from tb_menu where deleted = 0 and id_menu in (select id_menu from tb_user_access where id_jabatan = '.$this->session->userdata('id_jabatan').')')->result();
	                    
                    $menuParent = $this->db->query('select * from tb_menu where menu_parent = 0')->result();
                    foreach($menuParent as $mp) {  
                        $checkAccesParent =  $this->db->query('select * from tb_user_access where id_menu = '.$mp->id_menu.' and id_jabatan = '.$this->session->userdata('id_jabatan'))->row();
                        if(!$checkAccesParent){
	                        foreach($parentlist as $pl){
		                        if($pl->menu_parent == $mp->id_menu)
		                        	$checkAccesParent = true;
	                        }
                        }
                        
                        if($checkAccesParent){
                    ?>
                    <li class="menu-item">
                        <a href="<?= $mp->url_menu;?>"><i class="<?= $mp->icon_menu;?>"></i><?= $mp->nama_menu;?></a>
                        <ul class="sub-menu">
                        <?php 
                        $menuChild = $this->db->query('select * from tb_menu where menu_parent = '.$mp->id_menu)->result();
                        foreach($menuChild as $mc){ 
                            $checkAccesChild =  $this->db->query('select * from tb_user_access where id_menu = '.$mc->id_menu.' and id_jabatan = '.$this->session->userdata('id_jabatan'))->row();
                            if($checkAccesChild){
                        ?>
                            <li class="sub-menu-item">
                                <a href="<?php echo base_url();?><?= $mc->url_menu;?>"><?= $mc->nama_menu;?></a>
                            </li>
                        <?php 
                        } 
                    } ?>
                        </ul>
                    </li>
                <?php 
                        }
                    } 
                ?>
                    <li class="menu-item">
                        <a href="<?php echo base_url();?>index.php/user/doLogout" style="color: red" onclick="return confirm('Apakah anda yakin ingin keluar?');"><i class="fa fa-sign-out"></i>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    <div id="content" class="group">
        <div id="top-panel">
            <div class="top-wrapper">
                <div id="page-title" class="left">
                    <h1><?php echo $title;?></h1>
                </div>
                <div id="user-account" class="right">
                    <a href="#"><span>
                         <?php echo $this->session->userdata('username');?></span><!-- <img src="<?php echo base_url();?>assets/uploads/files/<?php echo $this->session->userdata('photo');?>" class="round-image"></a> -->
                    </div>
                    <div id="notification" class="right">
                        <a href="#"><i class="fa fa-bell"></i></a>
                    </div>
                    <div id="search-panel" class="right">
                        <form>
                            <input type="text" name="search" placeholder="Search">
                            <span>
                                <input type="button" value="">
                                <i class="fa fa-search"></i>
                            </span>
                        </form>
                    </div>
                </div>
            </div>