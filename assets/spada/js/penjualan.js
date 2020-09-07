	$(document).ready(function(){

		$(".del").on('click', function(){
            $(this).parent().parent().parent().parent().remove(); 
            total();
            
            });
		// $('#detail_cart').load("<?php echo base_url();?>index.php/cart/load_cart");

		$(document).on('click','.hapus_cart',function(){
			var row_id=$(this).attr("id");
			$.ajax({
				url : "<?php echo base_url();?>index.php/cart/hapus_cart",
				method : "POST",
				data : {row_id : row_id},
				success :function(data){
					$('#detail_cart').html(data);
				}
			});
		});
	});

	var i = 0;
	var no = 1;
	function add_produk(e){
			var produk_id    = $(e).data("produk-id");
			var produk_kode  = $(e).data("produkkode");
			var produk_nama  = $(e).data("produknama");
			var produk_harga = $(e).data("produkharga");
			var produk_modal = $(e).data("produkmodal");
			
			// var quantity     = $('#'+produk_id).val();
			var jml = 1;
			var upt = +$('#'+produk_id).val() + 1;
			var qtyItem = $("#qty"+produk_id);
			// var subtotal     = quantity * produk_harga;

			if ($("#isi tr td input[value='"+produk_id+"']").length == 0 && qtyItem.length == 0){
					$("#isi").append('<tr>'+
	                                '<td><input type="hidden" name="id_barang['+i+']" value="'+produk_id+'"><input type="text" name="sku_barang['+i+']" value="'+produk_kode+'" class="form-barang" required readonly></td>'+
	                                '<td><input type="text" name="nama_barang['+i+']" value="'+produk_nama+'" class="form-barang" required></td>'+
	                                '<td><input type="text" name="harga['+i+']" value="'+produk_harga+'" class="form-barang price" required><input type="hidden" name="modal['+i+']" value="'+produk_modal+'" class="form-barang price" required></td>'+
	                                '<td><input type="number" name="quantity['+i+']" min="1" value="'+jml+'" class="form-control qty" id="qty'+produk_id+'" onkeyup="update_qty();" required></td>'+
	                                '<td><input type="text" name="subtotal['+i+']" value="'+jml*produk_harga+'" class="form-barang subtotal" id="sub'+produk_id+'" required></td>'+
	                                '<td><button type="button" class="btn btn-danger btn-sm del" onclick="hapus_row(this);"><i class="fa fa-trash"></i></button> </td>'+
	                            '</tr>');
	            					i++;   
	                            total();
	                            $('tbody#isi tr:last td:first input').focus();
	                            $("#suggestions").hide();
	                            $("#search_data").val("");
	                            $("#search_data").focus();

				}else{
					var currentVal = parseInt(qtyItem.val());
					
					if(!isNaN(currentVal) && qtyItem.length == 1){
						//qtyItem.attr('value',currentVal+1);
						$("#qty"+produk_id).val(parseInt(parseInt($("#qty"+produk_id).val()) + 1));
					}
					
					$("#sub"+produk_id).val(qtyItem.val()*produk_harga);
					total();
	                $("#suggestions").hide();
	                $("#search_data").val("");
	                $("#search_data").focus();
				}
            }

            function hapus_row(e) {
            	$(e).parent().parent().remove();
            	total();
            }

			function ajaxSearch(){
				var input_data = $('#search_data').val();

                //if (input_data.length === 0)
                if (input_data.length < 1)
                {
                    $('#suggestions').hide();
                }
                else
                {

                    var post_data = {
                        'search_data': input_data,
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    };

                    $.ajax({
                        type: "POST",
                        url: "autocomplete/",
                        data: post_data,
                        success: function (data) {
                            // return success
                            if (data.length > 0) {
	                            var input_data = $('#search_data').val();

								if (input_data.length >= 1){

	                                $('#suggestions').show();
	                                $('#autoSuggestionsList').addClass('auto_list');
	                                $('#autoSuggestionsList').html(data);
                                }
                            }
                        }
                    });

                }
            }

            function update_qty() {
            	total();
            	update_amounts();
			    $('.qty').change(function() {
			        update_amounts();
			        total();
			    });
            }

            function total() {
            var sum = 0;

            $(".subtotal").each(function() {
                var value = $(this).val();
                
                if(!isNaN(value) && value.length != 0) {
                    sum += parseFloat(value);
                }
            });

            $("#total").val(sum);
        	}

        	function qty() {
        		$('#myTable > tbody  > tr').each(function() {
			        var qty = $(this).find('.qty').val(function(i, oldval) {
			        	return ++oldval;
    				});
			    });
        	}

        	function increment() {
        		update_amounts();
			    total();
			    var pp = qty();
			    var sum = 1;
			    $('#myTable > tbody  > tr').each(function() {
    				
    				var qtyn = $(this).find('.qty').val();
    				var price = $(this).find('.price').val();
			        var amount = (qtyn *price);
			        sum+=amount;
			        $(this).find('.subtotal').val(amount);
			        total();
			    });
        	}


			function update_amounts()
			{
			    var sum = 0;
			    $('#myTable > tbody  > tr').each(function() {
			        var qty = $(this).find('.qty').val();
			        var price = $(this).find('.price').val();
			        var amount = (qty*price)
			        sum+=amount;
			        $(this).find('.subtotal').val(amount);
			    });
			  
			}

			function kembali() {
				var bayar = $("#bayar").val();
				var total = $("#total").val();
				var kembali = bayar-total;

				$("#kembali").val(kembali);
			}

			$('#bayar').keyup(function() {
			        update_amounts();
			        total();
			        kembali();
			    });

			$('form select[name=id_payment]').change(function(){
                if ($(this).find(':selected').data('cash') == 'Yes'){
	                $('.nokartu').hide();
                    $('.bayar').show();
                    
                }else{
                    $('.bayar').hide();
                    $('.nokartu').show();
                }
            });

		$("#frmTransaksi").submit(function(){
			total();
		});

	    $("#search_data").keyup(function () {
			   var el = $(this);
			   
			        $.ajax({
			            url: "auto_add",
			            dataType: "json",
			            type: "POST",
			            data: {'search_data':el.val()},
			            success: function (result) {
			            	var subtotal = result.harga_jual*result.qty;
			            	var jml = 1;
			            	var qtyItem = $("#qty"+result.id_barang);
			            	if (el.val().length == result.sku_barang.length) {
							    if ($("#isi tr td input[value='"+result.id_barang+"']").length == 0 && qtyItem.length == 0){
									$("#isi").append('<tr>'+
					                                '<td><input type="hidden" name="id_barang['+i+']" value="'+result.id_barang+'"><input type="text" name="sku_barang['+i+']" value="'+result.sku_barang+'" class="form-barang" required readonly></td>'+
					                                '<td><input type="text" name="nama_barang['+i+']" value="'+result.nama_barang+'" class="form-barang" required></td>'+
					                                '<td><input type="text" name="harga['+i+']" value="'+result.harga_jual+'" class="form-barang price" required><input type="hidden" name="modal['+i+']" value="'+result.harga_modal+'" class="form-barang price" required></td>'+
					                                '<td><input type="number" name="quantity['+i+']" value="'+jml+'" class="form-control qty" id="qty'+result.id_barang+'" onkeyup="update_qty();" required></td>'+
					                                '<td><input type="text" name="subtotal['+i+']" value="'+jml*result.harga_jual+'" class="form-barang subtotal" id="sub'+result.id_barang+'" required></td>'+
					                                '<td><button type="button" class="btn btn-danger btn-sm del" onclick="hapus_row(this);"><i class="fa fa-trash"></i></button> </td>'+
					                            '</tr>');
					            					i += 1;
					                            total();
					                            $('tbody#isi tr:last td:first input').focus();
						                        $("#suggestions").hide();
						                        $("#search_data").val("");
						                        $("#suggestions").hide();
						                        $("#search_data").focus();
								}else{
									var currentVal = parseInt(qtyItem.val());
									if(!isNaN(currentVal) && qtyItem.length == 1){
										//qtyItem.attr('value',currentVal+1);
										$("#qty"+result.id_barang).val(parseInt(parseInt($("#qty"+result.id_barang).val()) + 1));
									}
									$("#sub"+result.id_barang).val(qtyItem.val()*result.harga_jual);
									total();
									$("#suggestions").hide();
					                $("#search_data").val("");
					                $("#suggestions").hide();
					                $("#search_data").focus();
								}
						}else{

			        	if(el.val().length > result.sku_barang.length){
			        		alert('Barang tidak tersedia');
			        	}
			        }
			            }
			            });
			            
			            
			    }); 