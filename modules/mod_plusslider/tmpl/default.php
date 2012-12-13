<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// load cluster style
$doc->addStyleSheet($mod_path."assets/css/plusslider.css");
$doc->addScript($mod_path."assets/js/jquery.plusslider.js");

$script = '
(function($){
	$(window).load(function(){
		$(".plusslider'.$mid.'").show().plusSlider({
			width: '.$width.',
			height: '.$height.',
			sliderType: "'.$sliderType.'",
			sliderEasing: "'.$sliderEasing.'",
			displayTime: '.$displayTime.',
			speed: '.$speed.',
			defaultSlide: '.$defaultSlide.',
			autoPlay: '.$autoPlay.',
			keyboardNavigation: '.$keyboardNavigation.',
			pauseOnHover: '.$pauseOnHover.',
			createArrows: '.$createArrows.',
			createPagination: '.$createPagination.'
		});
	});
})(jQuery);
';
$doc->addScriptDeclaration($script);
//$css = '.plusslider'.$mid.' {display:none;}';

$ipad = 0;

if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== FALSE) $ipad = 1;

$css = '
#slideshow {border-bottom: 3px solid #e4e4e4;}
#slideshow > .plusslider {left:50%; margin-left:-'.(($width/2)+2).'px; height:'.$height.'px; width:'.$width.'px; position: relative;}';

if($ipad == 1 ) {
	$css .= '
.browserIpad .plusslider {left:50%; margin-left:-'.(($width/2)+2).'px; }
.browserIpad .plusslider-arrows-wrapper {width:960px; left:50%; margin-left:-480px;}
';
}
$doc->addStyleDeclaration($css); 
?>

<div id="slider" class="plusslider<?php echo $mid; ?> ">
<?php
$keys = array_keys($active,0); 
foreach ($keys as $k)
	unset($active[$k]);

for($i=0;$i < count($active);$i++) {
?>
<div class="slide<?php echo $i; ?> slide">
	
    <?php
	switch($type[$i]) {
		case"image":
		if($url[$i] != '') 
		    echo '<a href="'.$url[$i].'" target="'.$target[$i].'" class="full-img">';

		echo '<img src="'.JURI::base().$image[$i].'" alt="'.$title[$i].'" />';
	    if($url[$i] != '') 
		    echo '</a>';
		break;
		
		case "vimeo":
			echo '<object width="'.$width.'" height="'.$height.'" wmode="transparent"><param name="allowfullscreen" value="true" /><param name="wmode" value="transparent"></param><param name="allowscriptaccess" value="always" /><param name="movie" value="http://vimeo.com/moogaloop.swf?clip_id='.$video[$i].'&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=#00ADEF&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" /><embed src="http://vimeo.com/moogaloop.swf?clip_id='.$video[$i].'&amp;server=vimeo.com&amp;show_title=0&amp;show_byline=0&amp;show_portrait=0&amp;color=#00ADEF&amp;fullscreen=1&amp;autoplay=0&amp;loop=0" type="application/x-shockwave-flash" allowfullscreen="true" allowscriptaccess="always" wmode="transparent" width="'.$width.'" height="'.$height.'"></embed></object>';
		break;
		
		case "youtube":
			echo '<object width="'.$width.'" height="'.$height.'" wmode="transparent"><param name="movie" value="http://www.youtube.com/v/'.$video[$i].'?version=3&amp;hl=en_US&amp;rel=0&amp;hd=1&amp;autoplay=0"></param><param name="allowFullScreen" value="true"><param name="wmode" value="transparent"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/'.$video[$i].'?version=3&amp;hl=en_US&amp;rel=0&amp;hd=1&amp;autoplay=0" type="application/x-shockwave-flash" wmode="transparent" width="'.$width.'" height="'.$height.'" allowscriptaccess="always" allowfullscreen="true"></embed></object>';
		break;
		
		case "flv":
			$template_path = JURI::base().'templates/'.JFactory::getApplication()->getTemplate();
			$doc->addScript($template_path."/js/swfobject.js");
			$flvimg = "";
			if($flv_image[$i] != '') {
				$flvimg .= "&preview=".JURI::base().$flv_image[$i];
			}
			echo '
			<div id="player'.$i.'"></div>
			<script type="text/javascript">
				var params = {};
				params.play = "true";
				params.menu = "false";
				params.scale = "showall";
				params.wmode = "transparent";
				params.allowfullscreen = "true";
				params.allowscriptaccess = "always";
				params.allownetworking = "all";
					  
				swfobject.embedSWF("'.$mod_path.'assets/flvplayer.swf?movie='.JURI::base().'images/flv/'.$flvfile[$i].'&controls=show'.$flvimg.'", "player'.$i.'", "'.$width.'", "'.$height.'", "8", null, null, params, null);
				//so.write("player");
			</script>
			';
		break;
	}
	
	
	?>
    
    <?php if($title[$i] != '' || $title_small[$i] != '') { ?>
    <div class="titles-wrapper">
        <div class="titles">
            <?php if($title[$i] != '') { ?>
                <h2 class="title_big"><?php echo $title[$i]; ?></h2>
            <?php } ?>
            <?php if($title_small[$i] != '') { ?>
                <h3 class="title_small"><?php echo $title_small[$i]; ?></h3>
            <?php } ?>
        </div>
    </div>
    <?php } ?>
    
</div>
<?php } ?>
</div>