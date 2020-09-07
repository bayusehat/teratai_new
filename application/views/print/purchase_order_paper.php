<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $po->no_po;?></title>
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
        table tfoot th:nth-child(odd){
            text-align: right;
        }
        table tfoot th:nth-child(even){
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="border-black">
        <div class="center">
            <h1>NOTA PEMBELIAN</h1>
        </div>
        <hr>
        <!--- Teruntuk -->
        <div>
            <table>
                <tr>
                    <td>No. Purchase Order</td>
                    <td>:</td>
                    <td><?= $po->no_po;?></td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td><?= date('d F Y H:i',strtotime($po->created));?></td>
                </tr>
                <tr>
                    <td>Supplier</td>
                    <td>:</td>
                    <td><?= $po->nama_supplier;?></td>
                </tr>
                <tr>
                    <td>Gudang</td>
                    <td>:</td>
                    <td><?= $po->nama_gudang;?></td>
                </tr>
            </table>
         </div>
         <!--- End Teruntuk -->
         <br>
         <!-- Detail -->
         <div>
            <table border="1" class="table-detail" cellpadding="10px">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Harga</th>
                        <th>Catatan</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($detail as $i => $d){ ?>
                            <tr>
                                <td><?= ++$i;?></td>
                                <td><?= $d->nama_item;?></td>
                                <td><?= $d->quantity;?></td>
                                <td><?= number_format($d->harga);?></td>
                                <td><?= $d->catatan;?></td>
                                <td><?= number_format($d->subtotal);?></td>
                            </tr>
                    <?php    
                        }
                    ?>
                    
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5">Subtotal</th>
                        <th><?= number_format($po->subtotal);?></th>
                    </tr>
                    <tr>
                        <th colspan="5">PPN (10%)</th>
                        <th><?= number_format($po->ppn_nominal);?></th>
                    </tr>
                    <tr>
                        <th colspan="5">Grand Total</th>
                        <th><?= number_format($po->grand_total);?></th>
                    </tr>
                </tfoot>
            </table>
         </div>
         <!-- End Detail -->
    </div>
</body>
</html>