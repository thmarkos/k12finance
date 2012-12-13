<?php
/**
 * @package     gantry
 * @subpackage  features
 * @version		3.2.16 February 8, 2012
 * @author		RocketTheme http://www.rockettheme.com
 * @copyright 	Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

defined('JPATH_BASE') or die();

gantry_import('core.gantryfeature');

/**
 * @package     gantry
 * @subpackage  features
 */
class GantryFeaturelogo extends GantryFeature {
	var $_feature_name = 'logo';
    var $_autosize = false;


	function render($position="") {
        global $gantry;

		if($gantry->get("logo-logowidth")){
			$logo_width = 'width='.$gantry->get("logo-logowidth").'px';
		}
		else {
			$logo_width = '';
		}
		if($gantry->get("logo-logoheight")){
			$logo_height = 'height='.$gantry->get("logo-logoheight").'px';
		}
		else {
			$logo_height = '';
		}
		
        if ($gantry->get("logo-autosize")) {

            jimport ('joomla.filesystem.file');
            //$path = $gantry->templatePath.DS.'images'.DS.'logo';
            $logocss = $gantry->get('logo-css','body #rt-logo');
           

            // get proper path based on perstyle hidden param
            //$path = (intval($gantry->get("logo-perstyle",0))===1) ? $path.DS.$gantry->get("cssstyle").DS : $path.DS;
            // append logo file
            $path = trim($gantry->get('logo-znlogo'));
			

            // if the logo exists, get it's dimentions and add them inline
            if (JFile::exists($path)) {
                $logosize = getimagesize($path);
                if (isset($logosize[0]) && isset($logosize[1])) {
                    $gantry->addInlineStyle($logocss.' {background:url('.JURI::base().''.$gantry->get('logo-znlogo').') no-repeat center center;width:'.$logosize[0].'px;height:'.$logosize[1].'px; padding:'.$gantry->get("logo-logopadding").';}');
                }
            } 
         }
		 elseif(!$gantry->get("logo-autosize") && $gantry->get("logo-logowidth") && $gantry->get("logo-logoheight")){
			
			$logocss = $gantry->get('logo-css','body #rt-logo');
			$gantry->addInlineStyle($logocss.' {background:url('.JURI::base().''.$gantry->get('logo-znlogo').') no-repeat center center;width:'.$gantry->get("logo-logowidth").'px;height:'.$gantry->get("logo-logoheight").'px; padding:'.$gantry->get("logo-logopadding").';}');
		 }

		
	    ob_start();
	    ?>
        
			<a href="<?php echo $gantry->baseUrl; ?>" id="rt-logo"></a>

        <?php
	    return ob_get_clean();
	}
}