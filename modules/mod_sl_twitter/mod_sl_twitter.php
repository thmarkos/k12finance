<?php
/**
 * @version		$Id$
 * @author		Pham Minh Tuan (admin@vnskyline.com)
 * @package		Joomla.Site
 * @subpakage	Skyline.Twitter
 * @copyright	Copyright (c) 2012 Skyline Software (http://www.vnskyline.com). All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die();

// include the syndicate functions only once
require_once(dirname(__FILE__).DS.'helper.php');

$list		= modSL_TwitterHelper::getList($params);
if (!$list) {
	return;
}

$class_sfx	= htmlspecialchars($params->get('class_sfx'));

require(JModuleHelper::getLayoutPath('mod_sl_twitter', $params->get('layout', 'default')));