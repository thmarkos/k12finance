<?php
/**
 * @package     gantry
 * @subpackage  features
 * @version		3.2.19 April 2, 2012
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
class GantryFeatureStyleDeclaration extends GantryFeature {
    var $_feature_name = 'styledeclaration';

    function isEnabled() {
        global $gantry;
        $menu_enabled = $this->get('enabled');

        if (1 == (int)$menu_enabled) return true;
        return false;
    }

	function init() {
        global $gantry;

        //inline css for dynamic stuff
        $css  = 'body {background:'.$gantry->get('bgcolor').'; color:'.$gantry->get('body_color').'; }';
        $css .= 'body a {color:'.$gantry->get('linkcolor').';}';
		$css .= '#rt-bottom {background:'.$gantry->get('bottomcolor').';}';
        $css .= '#rt-footer, .author_link {background:'.$gantry->get('footercolor').';}';
        $css .= '#rt-header {background: '.$gantry->get('header-gradient-from').';
background: -moz-linear-gradient(top, '.$gantry->get('header-gradient-from').' 0%, '.$gantry->get('header-gradient-to').' 100%);
background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,'.$gantry->get('header-gradient-from').'), color-stop(100%,'.$gantry->get('header-gradient-to').'));
background: -webkit-linear-gradient(top, '.$gantry->get('header-gradient-from').' 0%,'.$gantry->get('header-gradient-to').' 100%);
background: -o-linear-gradient(top, '.$gantry->get('header-gradient-from').' 0%,'.$gantry->get('header-gradient-to').' 100%);
background: -ms-linear-gradient(top, '.$gantry->get('header-gradient-from').' 0%,'.$gantry->get('header-gradient-to').' 100%);
background: linear-gradient(top, '.$gantry->get('header-gradient-from').' 0%,'.$gantry->get('header-gradient-to').' 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\''.$gantry->get('header-gradient-from').'\', endColorstr=\''.$gantry->get('header-gradient-to').'\',GradientType=0 );
}';
		 $css .= '.plusslider-pagination li.current {border-color:'.$gantry->get('elementscolor').';}';
		 $css .= '.learn-more, .rt-readon-surround .readon, input[type=submit], button {background-color:'.$gantry->get('elementscolor').';}';
		 $css .= '.featured_box, #rt-breadcrumbs  {background-color:'.$gantry->get('elementscolor').';}';
		 $css .= 'img.thumb:hover {border-color:'.$gantry->get('elementscolor').';}';
		 $css .= '#rt-main .sidebar {-webkit-box-shadow:0 2px 0 '.$gantry->get('elementscolor').'; -moz-box-shadow:0 2px 0 '.$gantry->get('elementscolor').'; box-shadow:0 2px 0 '.$gantry->get('elementscolor').'; }';
		 $css .= '';
        
        $gantry->addInlineStyle($css);

	}

}