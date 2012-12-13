<?php
/**
 * @package   gantry
 * @subpackage html.layouts
 * @version   3.2.16 February 8, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('GANTRY_VERSION') or die();

gantry_import('core.gantrylayout');

/**
 *
 * @package gantry
 * @subpackage html.layouts
 */
class GantryLayoutDoc_Body extends GantryLayout {
    var $render_params = array(
        'classes'       =>  null,
        'id'            =>  null
    );
    function render($params = array()){
        global $gantry;

        $fparams = $this-> _getParams($params);

    ob_start();
	$browser = '';
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox') !== FALSE) $browser .= 'browserFirefox';
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome') !== FALSE) $browser .= 'browserChrome';
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Opera') !== FALSE) $browser .= 'browserOpera';
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== FALSE) { $browser .= 'browserSafari';
		if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== FALSE) $browser .= ' browserIpad';
	}
	elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== FALSE){
	$browser .= 'browserIe';
	if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.') !== FALSE) $browser .= ' ie7 ie_old';
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') !== FALSE) $browser .= ' ie8 ie_old';
	elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.') !== FALSE) $browser .= ' ie9'; 
	} 
	
	$lang = JFactory::getLanguage();
	//XHTML LAYOUT
?><?php if(null != $fparams->id):?>id="<?php echo $fparams->id;?>"<?php endif;?> <?php if(strlen($fparams->classes) > 0):?>class="<?php echo $browser; ?> <?php echo $lang->getTag(); ?> <?php echo $fparams->classes; ?>"<?php endif;?><?php
	return ob_get_clean();
    }
}