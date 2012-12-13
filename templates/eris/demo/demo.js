jQuery(document).ready(function($) {
	$("div.panel_button").click(function(){
		$("div#panel").animate({
			left: "0px"
		}, "fast");
		$(".panel_button").animate({
			left: "230px"
		}, "fast");
		$("div.panel_button").toggle();
	});	
   $("div.hide_button").click(function(){
		$("div#panel").animate({
			left: "-230px"
		}, "fast");
		$(".panel_button").animate({
			left: "0px"
		}, "fast");
   });

   
   setTimeout(function() {
        $("div#panel").animate({left: "-230px"}, "fast");
		$(".panel_button").animate({left: "0px"}, "fast");
   }, 2000);
   

	$('#content_color').change(function() {
		var color = $(this).val();
		$('style#content_color_style').text('.learn-more, .rt-readon-surround .readon, input[type=submit], button, .featured_box, #rt-breadcrumbs  {background-color:' + color + '; } '
		+ '.plusslider-pagination li.current, img.thumb:hover {border-color: ' + color + ';}'
		+ '#rt-main .sidebar {-moz-box-shadow: 0 0 5px ' + color + ';-webkit-box-shadow: 0 0 5px ' + color + '; box-shadow: 0 0 5px ' + color + '; ;}'
		+ 'div.pp_eos .pp_content, div.pp_eos .pp_close, div.pp_eos .pp_nav .pp_play, div.pp_eos .pp_nav .pp_pause, div.pp_eos a.pp_arrow_previous, div.pp_eos a.pp_arrow_next, div.pp_eos .pp_expand, div.pp_eos .pp_contract {border: 2px solid ' + color + ';}');
		$('style#color_suggestions_style').text('');
	});
	$('#body_text_color').change(function() {
		var color = $(this).val();
		$('style#body_text_color_style').text('body {color:' + color + ';');
	});
	$('#bottom_color').change(function() {
		var color = $(this).val();
		$('style#bottom_color_style').text('#rt-bottom {background-color:' + color + ';');
	});
	$('#footer_color').change(function() {
		var color = $(this).val();
		$('style#footer_color_style').text('#rt-footer, .author_link {background-color:' + color + ';');
	});
	$('#body_background').change(function() {
		var color = $(this).val();
		$('style#body_background_style').text('body {background-color:' + color + ';}');
	});
	$('#links_color').change(function() {
		var color = $(this).val();
		$('style#links_color_style').text('body a {color:' + color + ';}');
		$('style#color_suggestions_style').text('');
	});
	
function hex2rgb(hex, opacity) {
	var rgb = hex.replace('#', '').match(/(.{2})/g);
	
	var i = 3;
	while (i--) {
	  rgb[i] = parseInt(rgb[i], 16);
	}
	
	if (typeof opacity == 'undefined') {
	  return 'rgb(' + rgb.join(', ') + ')';
	}
	
	return 'rgba(' + rgb.join(', ') + ', ' + opacity + ')';
};
function ColorLuminance(hex, lum) {
	
	// validate hex string
	hex = String(hex).replace(/[^0-9a-f]/gi, '');
	if (hex.length < 6) {
		hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
	}
	lum = lum || 0;
	// convert to decimal and change luminosity
	var rgb = "#", c, i;
	for (i = 0; i < 3; i++) {
		c = parseInt(hex.substr(i*2,2), 16);
		c = Math.round(Math.min(Math.max(0, c + (c * lum)), 255)).toString(16);
		rgb += ("00"+c).substr(c.length);
	}
	return rgb;
}
function rgb2hex(rgb) {
 rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
 function hex(x) {
  return ("0" + parseInt(x).toString(16)).slice(-2);
 }
 //return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
 return hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
}


	$('#color_suggestions').change(function() {
		var color = $(this).val();
		var dColor = ColorLuminance(color, 0.25);
		$('style#color_suggestions_style').text('.learn-more, .rt-readon-surround .readon, input[type=submit], button, .featured_box,#rt-breadcrumbs  {background-color:' + color + '; } '
		+ '.plusslider-pagination li.current, img.thumb:hover {border-color: ' + color + ';}'
		+ '#rt-main .sidebar {-moz-box-shadow: 0 2px 0 ' + color + ';-webkit-box-shadow: 0 2px 0 ' + color + '; box-shadow: 0 2px 0 ' + color + '; ;}'
		+ 'div.pp_eos .pp_content, div.pp_eos .pp_close, div.pp_eos .pp_nav .pp_play, div.pp_eos .pp_nav .pp_pause, div.pp_eos a.pp_arrow_previous, div.pp_eos a.pp_arrow_next, div.pp_eos .pp_expand, div.pp_eos .pp_contract {border: 2px solid ' + color + ';}'
		+ 'body a {color:' + dColor + ';}');
		$('#links_color').val(dColor).attr('style', 'background-color:'+dColor+' !important;  color: #fff;');
		$('#content_color').val(color).attr('style', 'background-color:'+color+' !important;  color: #fff;');
		$('#color_suggestions').val(color).attr('style', 'background-color:'+color+' !important;  color: #fff;');
	});
	
	
	
});