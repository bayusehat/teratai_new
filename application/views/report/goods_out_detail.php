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
<form action="<?= base_url();?>gi/goods_in_insert" method="POST">
    <div id="content-wrapper" class="group">
        <div id="page-wrapper">
            <div class="card">
                <div class="card-body">
                <table width="50%">
                    <tr>
                        <th>No. GO</th>
                        <th>:</th>
                        <th><?= $go->no_go;?></th>
                    </tr>
                    <tr>
                        <th>Gudang</th>
                        <th>:</th>
                        <th><?= $go->nama_gudang;?></th>
                    </tr>
                </table>
                    <ul class="list-group">
                        <?php
                            foreach($detail as $i => $v){
                                echo '<li class="list-group-item">Purchase Order : <strong>'.$v->no_so.'</strong></li>';
                                echo '<table class="table table-striped table-bordered">';
                                echo '<tr>
                                        <th>Nama Item</th>
                                        <th>Quantity</th>
                                    </tr>';
                                foreach($v->detail as $x => $d){
                                    echo '<tr>
                                            <td>'.$d->nama_item.'</td>
                                            <td>'.$d->quantity.'</td>
                                        </tr>
                                    ';
                                }
                                echo '</table>';
                                echo '<hr>';
                            }
                        ?>
                        
                    </ul>
                </div>
            </div>
        </div>
    </div>
</form>
<script>
    function checkPo(){
        var supplier = $('#id_supplier').val();
        var gudang = $('#id_gudang').val();
        
        if(supplier != ''){
            if(gudang != ''){
                $.ajax({
                    url: '<?php base_url();?>check_po/'+supplier+'/'+gudang,
                    dataType : 'JSON',
                    method : 'GET',
                    success:function(res){
                        var row = '';
                        if(res.status == 200){
                            $.each(res.result,function(i,val){
                                row += '<li class="list-group-item"><input type="checkbox" value="'+val.id_pemesanan+'" name="id_pemesanan[]">'+val.no_po+'</li>';
                            });
                            $('#listPo').html(row);
                        }else{
                            row += '<li class="list-group-item">'+res.result+'</li>';
                            $('#listPo').html(row);
                        }
                    }
                })
            }else{
                alert('Gudang harus dipilih!');
                $('#id_gudang').focus();
            }
        }else{
            alert('Supplier harus dipilih!');
            $('#id_supplier').focus();
        }
    }
</script>
<script src="<?= base_url();?>assets/spada/js/transaksi.js"></script>
<?php $this->load->view('layout/foot');?>