<?php
/**
 * @version   3.2.6 June 14, 2011
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2011 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Gantry uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */
defined('GANTRY_VERSION') or die();
/**
 * @package     gantry
 * @subpackage  admin.elements
 */
gantry_import('core.config.gantryformfield');


class GantryFormFieldZncss extends GantryFormField {

    protected $type = 'zncss';
    protected $basetype = 'none';
    
	public function getInput()
	{
		global $gantry;
		$gantry->addStyles(array('zncss.css' ));
    }
}
