<?php
/**
 * @package   Template Overrides - RocketTheme
 * @version   3.2.19 April 2, 2012
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2012 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 *
 * Rockettheme Gantry Template uses the Joomla Framework (http://www.joomla.org), a GNU/GPLv2 content management system
 *
 */

// no direct access
defined('_JEXEC') or die;
$limit = 150;
?>
<ul class="latestnews">
<?php foreach ($list as $key=>$item) :
$date = explode(' ',$item->created);
?>

	<li class="<?php if(count($list)==$key+1) echo 'last'; ?>">
		<a href="<?php echo $item->link; ?>" class="title"><?php echo $item->title; ?></a>
        <span class="details"><?php echo $date[0]; ?> by <?php echo $item->author; ?></span>
        <?php echo substr(strip_tags($item->introtext),0, $limit).((strlen($item->introtext) > $limit) ? '...' : ''); ?>
	</li>
<?php endforeach; ?>
</ul>