$(document).ready(function(){
	
	$('#btn').click(function(e){
		$('#fancy, #btn').fadeOut('slow', function(){
			$('#bank, #btn-bk').fadeIn('slow');
		});
	}); 
	
	$('#btn-bk').click(function(e){
		$('#bank, #btn-bk').fadeOut('slow', function(){
			$('#fancy, #btn').fadeIn('slow');
		});
	});
	
	$("#Datenbank_id").change(function(){
		value = $(this).val();
		
		$(".box").hide();
		$("#andere_DBs").hide();
		$("#ot-total").hide();		
		
		if (value != '' && value != '0') {
			$('.'+value).show();
			
			var otTotal = $('.'+value).data('price') + $('#sub-total').data('price');
			$("#ot-total").text(text_ot_total+': '+otTotal.toFixed(2).replace(".", ",")+' EUR').show();
		
		} else {
			$("#andere_DBs").show();
		}
	});

});

function print_fax_order(select) {
	
	if ($("#Datenbank_id").val() == '' || $("#Datenbank_id").val() == '0') {
		alert(error_fax_order_select_shipping);
	} else {
		window.focus(); 
		window.print();
	}
}