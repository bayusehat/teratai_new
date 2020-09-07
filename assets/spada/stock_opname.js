$(document).ready(function(){
    $("#sku_item").focus();
});
    function scan_data(){
            var input_data = $('#sku_item').val();

            if (input_data.length === 0)
            {
                $('#suggestions').hide();
            }
            else
            {
                var post_data = {
                    'sku_item': input_data,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                };

                $.ajax({
                    type: "POST",
                    url: base_url+"index.php/item/get_barang_stock_opname",
                    data: post_data,
                    success: function (data) {
                        if (data.length > 0) {
                            $('#suggestions').show();
                            $('#autoSuggestionsList').addClass('auto_list');
                            $('#autoSuggestionsList').html(data);
                        }
                    }
                });

            }
        }
function add_barang_stock_opname(e){
    var produk_id = $(e).data('produk-id');
    var produk_nama = $(e).data('produknama');
    var produk_kode = $(e).data('produkkode');
    var produk_stok = $(e).data('produkstok');
    var produk_gudang = $(e).data('produkgudang');

    $("#sku_item").val(produk_kode);
    $("#id_item").attr('value',produk_id);
    $("#nama_item").val(produk_nama);
    $("#stok").attr('value',produk_stok);
    $("#id_gudang").val(produk_gudang).change();
    $("#suggestions").hide();
    $("#stok_gudang").focus();
} 