<?php $this->load->view('layout/head');?>
<div id="content-wrapper" class="group">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
            </div>
        </div>
            <div>
                <div class="row">
	                <div class="col-md-4">
                        <div class="card">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-archive f-40"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2 class="count"></h2>
                                    <h4 class="m-b-0">Total Stok Barang</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-archive f-40"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2 class="count"></h2>
                                    <h4 class="m-b-0">Data Barang</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa fa-sign-out f-40"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2 class="count"></h2>
                                    <h4 class="m-b-0">Data Penjualan</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="row">
                        <div class="card">
                            <h2>Stok Redline Monitor</h2>
                            <table class="table table-bordered table-hovered" id="tableData">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Item</th>
                                        <th>Gudang</th>
                                        <th>Stok Saat Ini</th>
                                        <th>Minimum Stok</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        loadTable();
    });
    function loadTable(){
        $('#tableData').DataTable({
            asynchronous: true,
            processing: true, 
            destroy: true,
            ajax: {
                url: "<?= base_url('dashboard/cek_stok_habis');?>",
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                method: 'GET'
            },
            columns: [
                { name: 'no', searchable: false, orderable: true, className: 'text-center' },
                { name: 'item'},
                { name: 'gudang'},
                { name: 'stok'},
                { name: 'stok_minimum', className:'text-center'},
                { name: 'status', className:'text-center'},
            ],
            order: [[0, 'asc']],
            iDisplayInLength: 10,
        });
    }
</script>
<?php $this->load->view('layout/foot'); ?>