<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

$fblike = $params->get('fblike', 0);
$tweet = $params->get('tweet', 0);
$text = $params->get('text', '');
$via = $params->get('via', '');
$url = $params->get('url', '');
$gplus = $params->get('gplus', 0);
$follow = $params->get('follow', 0);

$doc =& JFactory::getDocument();
$siteurl = JURI::base();

?>
<style type="text/css">
.social-connect {margin:0; padding:0; list-style:none; float:right;}
.social-connect li {float:left;}
</style>
<div id="fb-root"></div>
		<script>(function(d, s, id) {
          var js, fjs = d.getElementsByTagName(s)[0];
          if (d.getElementById(id)) return;
          js = d.createElement(s); js.id = id;
          js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
          fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
        
<ul class="social-connect clearfix">

<?php if($fblike == 0) { ?>
	<li class="fb_like_button">

        <div class="fb-like" data-href="<?php echo $siteurl; ?>" data-send="false" data-layout="button_count" data-width="80" data-show-faces="false" data-font="arial"></div>
        
	</li>
<?php } ?>

<?php if($tweet == 0) {
		
$twitter_vars = '';
if($text != '') 
	$twitter_vars .= ' data-text="'.$text.'" ';
if($via != '')
	$twitter_vars .= ' data-via="'.$via.'" ';
if($url != '') 
	$twitter_vars .= ' data-url="'.$url.'" ';
?>
    <li class="tweetme">
        <a href="http://twitter.com/share" class="twitter-share-button" <?php echo $twitter_vars; ?>  data-count="horizontal" >Tweet</a>
        <script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
    </li>
<?php } ?>


      
<?php if($gplus == 0) { ?>
	<li class="gplus">
		<script type="text/javascript">
		  (function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
			po.src = 'https://apis.google.com/js/plusone.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
		  })();
		</script>
		<div class="g-plusone" data-size="medium"></div>
	</li>
<?php } ?>
<?php if($follow == 0) { ?>
	<li class="follow">
    <a href="https://twitter.com/<?php echo $via; ?>" class="twitter-follow-button" data-show-count="false">Follow @<?php echo $via; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
	</li>
<?php } ?>
</ul>