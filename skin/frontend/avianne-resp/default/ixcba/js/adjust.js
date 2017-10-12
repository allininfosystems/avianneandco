jQuery(function(){
	jQuery('.aside').css('position','relative').prependTo('.col-right').find('.title').html('Shopping Cart');
	jQuery('.payment_cart_totals .t4').html('Payment Method');
	jQuery('.aside .qty-price').each(function(){
		var qty = jQuery(this).find('input').val();
		jQuery(this).prepend('<span class="qty-item">Qty: '+qty+'</span><br />').find('input').remove();
		jQuery(this).find('.price').prepend('Price: ');
	});
	var gTotal = jQuery('.g-total .a-right span').html();
	jQuery('.see-all').prepend('<table><tr><td><span class="grand-total-green">Grand Total</span></td><td><span class="grand-total-green">'+gTotal+'</span></td></tr><tr><td><span class="forgot">forgot an item?</span></td><td id="btn-place"></td></tr></table>');
	jQuery('.see-all input').appendTo('#btn-place');
});