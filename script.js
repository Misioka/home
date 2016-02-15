$(window).load(function(){
	$('.temperature1_img').css('margin-left','0px');
	$('.temperature1_img').css('margin-top', '0px');
	$('#temperature1_value').css('margin-top', '0px');
	$('.temperature2_img').css('margin-left', '0px');
	$('.temperature2_img').css('margin-top', '0px');
	$('#temperature2_value').css('margin-top', '0px');
	var imgOne = $('#temperature1_img').width();
	var imgOneH = $('#temperature1_img').height();
	var tdOne = $('#temperature1_td').width();
	var tdOneH = $('#temperature1_td').height();
	var temp1 = $('#temperature1_value').height();
	var marginImg = (tdOne - imgOne) / 2; 
	var marginImgT = (tdOneH - imgOneH) / 2;
	var marginVal = (tdOneH - temp1)/2;
	$('.temperature1_img').css('margin-left', marginImg+'px');
	$('.temperature1_img').css('margin-top', marginImgT+'px');
	$('#temperature1_value').css('margin-top', marginVal+'px');
	$('#temperature1_td').animate({
		opacity: 1
	}, 500);
	$('.temperature2_img').css('margin-left', marginImg+'px');
	$('.temperature2_img').css('margin-top', marginImgT+'px');
	$('#temperature2_value').css('margin-top', marginVal+'px');
	$('#temperature2_td').animate({
		opacity: 1
	}, 500);
});

function AnimateRotate(angle, value, id, valueId) {
    var $elem = $(id);
    $({deg: 0}).animate({deg: 100}, {
        duration: 2000,
        step: function(now) {
            $elem.css({
                transform: 'rotate(' + now*angle/100 + 'deg)'
            });
	    var newTemp = String(Math.round(now*value/10)/10);
	    $(valueId).html(newTemp.replace('.', '<span>,') + '</span> °C');
        }
    });
}
