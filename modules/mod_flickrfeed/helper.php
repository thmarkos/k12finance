<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

function attr($s,$attrname) { // return html attribute
	preg_match_all('#\s*('.$attrname.')\s*=\s*["|\']([^"\']*)["|\']\s*#i', $s, $x);
	if (count($x)>=3) return $x[2][0]; else return "";
}

function url2_get_contents($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed! This module (Flickr Feed) needs CURL to be installed on your server. Please contact your hosting support to enable cURL on your account. If you dont want this module just go to Modules Manager and disable it.');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

// id = id of the feed
// n = number of thumbs
function parseFlickrFeed($id,$n,$perrow) {
	$url = "http://api.flickr.com/services/feeds/photos_public.gne?id={$id}&lang=it-it&format=rss_200";
	$s = url2_get_contents($url);
	preg_match_all('#<item>(.*)</item>#Us', $s, $items);
	$out = "";
	for($i=0;$i<count($items[1]);$i++) {
		if($i>=$n) return $out;
		$item = $items[1][$i];
		preg_match_all('#<link>(.*)</link>#Us', $item, $temp);
		$link = $temp[1][0];
		preg_match_all('#<title>(.*)</title>#Us', $item, $temp);
		$title = $temp[1][0];
		preg_match_all('#<media:content([^>]*)>#Us', $item, $temp);
		$imglink = attr($temp[0][0],"url");
		preg_match_all('#<media:thumbnail([^>]*)>#Us', $item, $temp);
		$thumb = attr($temp[0][0],"url");
		
		$out.="<li class=\"".(($i % $perrow == ($perrow-1)) ? 'last' : "")."\"><a href=\"".$imglink."\" target='_blank' data-rel=\"prettyPhoto[flickr]\" title=\"".str_replace('"','',$title)."\"><img src='$thumb'/></a></li>";
	}
	return $out;
}