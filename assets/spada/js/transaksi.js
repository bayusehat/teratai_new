function status_change(){
    var status = $('#status').val();
    
    switch(status){
        case '0':
            $('#tgljt').show();
            $('#tgllns').hide();
            break;
        case '1':
            $('#tgljt').hide();
            $('#tgllns').show();
            break;
        case '2':
            $('#tgljt').hide();
            $('#tgllns').hide();
            break;
        default:
            $('#tgljt').show();
            $('#tgllns').hide();
    }
}

function total() {
    var sum = 0;
    $(".subtotal").each(function() {
        var value = $(this).val();
        
        if(!isNaN(value) && value.length != 0) {
            sum += parseFloat(value);
        }
    });
    $('#subtotal').val(sum);
    $("#subtotalall-separator").html('Rp '+numberFormat(sum));
    
    if(isSalesOrder == 1){ //execute apabila ini adalah SO, hitung PPn dan diskon
	    //var id_diskon = $("#diskon").val();
	    /*if(id_diskon > 0){
		    var tjenisdiskon = arrdiskon[id_diskon].jenis;
		    var tnominaldiskon = arrdiskon[id_diskon].nominal;
		    if(tjenisdiskon == 0){
			    var displaynominaldiskon = tnominaldiskon;
		    }
		    else{
			    var displaynominaldiskon = Math.round((tnominaldiskon * sum) / 100);
		    }
		    
		    $("#nominaldiskon").val(displaynominaldiskon);
			$("#nominaldiskon-separator").html('Rp '+numberFormat(displaynominaldiskon));
		    
		    sum = sum - displaynominaldiskon;
	    }else{
            $("#nominaldiskon").val(id_diskon);
			$("#nominaldiskon-separator").html('');
        }*/
        
        var sumdiskon = 0;
        $(".hidden_diskon").each(function() {
	        var value = $(this).val();
	        
	        if(!isNaN(value) && value.length != 0) {
	            sumdiskon += parseFloat(value);
	        }
	    });
		$("#nominaldiskon-separator").html('Rp '+numberFormat(sumdiskon));
	    
	    var ppn = Math.round(sum * 0.1); //PPn 10%
	    sum = sum + ppn;
	    
	    $("#nominalppn").val(ppn);
		$("#nominalppn-separator").html('Rp '+numberFormat(ppn));
    }else{
        if(!isNaN(sum)){
            var thisPpn = $('#ppn').val();
            if(thisPpn == 1){
                var ppn = Math.round(sum * 0.1); //PPn 10%
                nominal = ppn;
                $('#ppn-nominal').html('Rp '+numberFormat(ppn));
                $("#nominalppn").val(ppn);
                $("#nominalppn-separator").html('Rp '+numberFormat(ppn));
            }else{
                nominal = 0;
                $('#ppn-nominal').html('');
                $("#nominalppn").val(0);
                $("#nominalppn-separator").html('');
            }
        }else{
            $('#ppn-nominal').html('');
        }
        sum = sum + nominal;
    }
    
    $("#total").val(sum);
    $("#total-separator").html('Rp '+numberFormat(sum));
}

function setPpn(sum){
    if(!isNaN(sum)){
        var thisPpn = $('#ppn').val();
        if(thisPpn == 1){
            var ppn = Math.round(sum * 0.1); //PPn 10%
            nominal = ppn;
            $('#ppn-nominal').html('Rp '+numberFormat(ppn));
        }else{
            nominal = 0;
            $('#ppn-nominal').html('');
        }
    }else{
        $('#ppn-nominal').html('');
    }
}

function change_quantity_so(){
    // var sum = 0;
    $('#myTable > tbody  > tr').each(function() {
        var id = $(this).find('.id').val();
        var qty = $(this).find('.quantity').val();
        
        var price = $(this).find('.harga').val();
        var biaya = $(this).find('.biaya_logistik').val();
        var amount = (parseInt(qty)*parseInt(price)) + parseInt(biaya);
        
        if(!isNaN(amount) && amount.length != 0) {
            sum = amount;
        }else{
            sum = 0;
        }
        
        var diskon = 0;
        var id_diskon = $(this).find('.diskon').val();
        if(id_diskon > 0){
	        var jenis_diskon = $(this).find('.diskon').find('option:selected').data('jenis');
	        var nominal_diskon = $(this).find('.diskon').find('option:selected').data('nominal');
	        if(jenis_diskon == 1){
		        diskon = Math.round(((nominal_diskon * 1.0) / 100) * (price * 1.0) * (qty * 1));
	        }
	        else{
		        diskon = Math.round(nominal_diskon * 1);
	        }
        }
        
        $(this).find('.hidden_diskon').val(diskon);
        $(this).find('.display_diskon').html('Rp '+numberFormat(diskon));
        
        sum = sum - diskon;
        
        $(this).find('.subtotal').val(sum);
        $(this).find('.subtotal-separator'+id).html('Rp '+numberFormat(sum));
        total();
    });
}

function change_quantity(){
    var sum = 0;
    $('#myTable > tbody  > tr').each(function() {
        var id = $(this).find('.id').val();
        var qty = $(this).find('.quantity').val();
        var disc = $(this).find('.diskon').val();
        var price = $(this).find('.harga').val();
            if(disc != 0){
                var amount = change_diskon();
            }else{
                var amount = (qty*price)
            }
        sum+=amount;
        $(this).find('.harga-separator'+id).html('Rp '+numberFormat(price));
        $(this).find('.subtotal').val(amount);
        $(this).find('.subtotal-separator'+id).html('Rp '+numberFormat(amount));
        total();
    });
}

function change_diskon(){
    var sum = 0;
    $('#myTable > tbody  > tr').each(function() {
        var id = $(this).find('.id').val();
        var qty = $(this).find('.quantity').val();
        var disc = $(this).find('.diskon').val();
        var price = $(this).find('.harga').val();
        var valueDiskon = (price*disc) / 100;
        var afterDisc = (price - valueDiskon);
        var amount = afterDisc * qty;
        sum+=amount;
        $(this).find('.subtotal').val(amount);
        $(this).find('.subtotal-separator'+id).html('Rp '+numberFormat(amount));
        total();
    });
}

function delRow(e){
    e.closest('tr').remove();
    total();
    cashValue();
}

function numberFormat(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}