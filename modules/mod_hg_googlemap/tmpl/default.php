<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$mid = $module->id;
$doc =& JFactory::getDocument();

$mod_path = "modules/mod_hg_googlemap/";

$width = $params->get('width');
$height = $params->get('height');
$imagepin = $params->get('imagepin'); 
$anchor = $params->get('anchor', 'bottom-center'); 
$centerMap = $params->get('centerMap'); 
$zoom = $params->get('zoom',13); 
$type = $params->get('type','ROADMAP'); 
$controll = $params->get('controll','DROPDOWN_MENU'); 
$draging = $params->get('draging','false'); 
$mousewheel = $params->get('mousewheel','false'); 
$marker_animation = $params->get('marker_animation',1); 
$animation_type = $params->get('animation_type','DROP'); 
$markers = $params->get('markers', $centerMap); 

// load scripts
$doc->addScript("http://maps.google.com/maps/api/js?sensor=false");

if(!strpos($width, '%')) $width = str_replace('%', '', $width).'px';
$css = '#hg_map { width : '.$width.'; height : '.$height.'px; }
#hg_map {border-top:1px solid #fff; border-bottom:1px solid #fff;}';

$doc->addStyleDeclaration($css);

if($imagepin == '') {
$imagepin = $mod_path.'assets/map_marker.png';
}

$imagepin_sizes = getimagesize($imagepin);
if($anchor == 'bottom-center') { $anchor_position = $imagepin_sizes[0]/2; }
else if($anchor == 'bottom-right') { $anchor_position = $imagepin_sizes[0];}
else { $anchor_position = 0; }

//marker animation
if($marker_animation == 1) { $animation = 'animation: google.maps.Animation.'.$animation_type;}
else { $animation = ''; }

// explode markers
$mk_array = explode("\n", $markers);

$script = '
(function($){ 
$.fn.ggMap = function(options) {
	var defaults = {
			mapTypeControl: false
        };
	var option = $.extend({}, defaults, options);
	
	return this.each(function(){
		var container = $(this).attr("id"),
			point = new google.maps.LatLng('.$centerMap.'),';
			$script .= '
			marker_points = [';
			foreach($mk_array as $key=>$value) {
				$script .= 'new google.maps.LatLng('.$value.')';
				if ($key < (count($mk_array) -1)) {
					$script .= ', ';
				}
			}
			$script .= '],
			markers = [],
			iterator = 0;

		var theMap = {
			initialize_eos_map: function(){

				
				var myMapOptions = {
					zoom: '.$zoom.',
					scrollwheel:'.$mousewheel.',
					draggable: '.$draging.',
					center: point,
					mapTypeId: google.maps.MapTypeId.'.$type.',
					mapTypeControl: option.mapTypeControl,
					mapTypeControlOptions: {
						style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
						position: google.maps.ControlPosition.RIGHT_TOP
					},
					navigationControl: true,
					navigationControlOptions: {style: google.maps.NavigationControlStyle.LARGE,
						position: google.maps.ControlPosition.LEFT_CENTER},
					zoomControlOptions: {
						style: google.maps.ZoomControlStyle.SMALL
					  }
				};
				var map = new google.maps.Map(document.getElementById(container),myMapOptions);

				theMap.drop(map, marker_points);
			},
			drop: function (map, marker_points) {
				for (var i = 0; i < marker_points.length; i++) {
					setTimeout(function() {
						theMap.addMarker(map, marker_points);
					}, i * 300);
				}
			},
			addMarker: function (map, marker_points) {
				var image = new google.maps.MarkerImage(
					  "'.JURI::base().$imagepin.'",
					  new google.maps.Size('.$imagepin_sizes[0].','.$imagepin_sizes[1].'),
					  new google.maps.Point(0,0),
					  new google.maps.Point('.round($anchor_position).','.$imagepin_sizes[1].')
				  );
				var shape = {
					  coord: [0, 0, '.$imagepin_sizes[0].','.$imagepin_sizes[1].'],
					  type: "rect"
				  };
				markers.push(new google.maps.Marker({
					position: marker_points[iterator],
					draggable: false,
					raiseOnDrag: false,
					icon: image,
					shape: shape,
					map: map,
					'.$animation.'
				}));
				iterator++;
			}
		}
		theMap.initialize_eos_map();
	});
}

	$(document).ready(function() {
		$("#hg_map").ggMap({mapTypeControl: '.$params->get('enable_control', 'true').'});
	});
	
})(jQuery);
';

$doc->addScriptDeclaration($script); 
?>

<div id="hg_map"></div>