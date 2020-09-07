<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $retur->no_retur_pembelian;?></title>
    <style>
        .border-black{
            border:1px solid black;
            width : 100%;
            padding:10px;
        }
        .center{
            text-align:center;
        }
        .kop-table{
            margin : 0 auto;
        }
        .table-detail{
            width : 100%;
            border: 1px solid black;
            border-collapse : collapse;
        }
    </style>
</head>
<body>
    <div class="border-black">
        <div class="center">
            <h1>RETUR PEMBELIAN</h1>
            <table id="kop-table">
                <tr>
                    <td>Nomor</td>
                    <td>:</td>
                    <td><?= $retur->no_retur_pembelian;?></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><?= $retur->created;?></td>
                </tr>
            </table>
        </div>
        <hr>
        <!--- Teruntuk -->
        <div>
            P E M B E L I
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>TERATAI</td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>Jl. Surabaya</td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>:</td>
                    <td></td>
                </tr>
            </table>
            <br>

            K E P A D A  P E N J U A L  
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><?= $retur->nama_supplier;?></td>
                </tr>
                <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td><?= $retur->alamat_supplier;?></td>
                </tr>
                <tr>
                    <td>NPWP</td>
                    <td>:</td>
                    <td></td>
                </tr>
            </table>
         </div>
         <!--- End Teruntuk -->
         <br>
         <!-- Detail -->
         <div>
            <table border="1" class="table-detail" cellpadding="10px">
                <tr>
                    <th>No</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>Catatan</th>
                    <th>Harga</th>
                </tr>
                <?php
                $total = 0;
                    foreach($detail as $i => $d){ ?>
                        <tr>
                            <td><?= ++$i;?></td>
                            <td><?= $d->nama_item;?></td>
                            <td><?= $d->quantity;?></td>
                            <td><?= $d->catatan;?></td>
                            <td>Rp <?= number_format($d->harga);?></td>
                        </tr>
                <?php
                    $total += $d->harga;    
                    }
                ?>
                <tr>
                    <td colspan="4">Total</td>
                    <td>Rp <?= number_format($total);?></td>
                </tr>
            </table>
         </div>
         <!-- End Detail -->
    </div>
</body>
</html>