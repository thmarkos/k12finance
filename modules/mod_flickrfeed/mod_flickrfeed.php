<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

require_once dirname(__FILE__).'/helper.php';

$limit = $params->get('limit');
$flickrid = $params->get('flickrid');
$perrow = $params->get('perrow');
$follow_text = $params->get('follow_text', '');
$follow_link = $params->get('follow_link', '');


$mid = $module->id;
$doc =& JFactory::getDocument();

// load inline styles
$css = '#flickr_container_'.$mid.' img {width:'.$params->get('width', 40).'px; height:'.$params->get('height', 40).'px;}';
$doc->addStyleDeclaration($css); 
?>

<div id="flickr_container_<?php echo $mid; ?>" class="flickr_container clearfix">
<ul class="flickr_feeds clearfix">
<?php

// usage example:
echo parseFlickrFeed($flickrid,$limit,$perrow);
// you have to use css to customize it

?>
</ul>
<?php
if($follow_text != '')
	echo '<a href="'.$follow_link.'" target="_blank" title="'.$follow_text.'" class="followUs">'.$follow_text.'</a>';
?>
</div>