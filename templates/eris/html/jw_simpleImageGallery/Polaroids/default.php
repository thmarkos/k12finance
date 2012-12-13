<?php 
/**
 * @version		2.2
 * @package		Simple Image Gallery (plugin)
 * @author    JoomlaWorks - http://www.joomlaworks.gr
 * @copyright	Copyright (c) 2006 - 2011 JoomlaWorks, a business unit of Nuevvo Webware Ltd. All rights reserved.
 * @license		GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

// We set the caption output here so the HTML structure below is a "bit" cleaner :)
$photoCaption = '';


?>

<ul id="sig<?php echo $galleryID; ?>" class="sig-container">
	<?php foreach($gallery as $photo): ?>
	<li class="sig-block">
		<span class="sig-link-wrapper">
			<span class="sig-link-innerwrapper">
				<a href="<?php echo $photo->sourceImageFilePath; ?>" class="sig-link" rel="prettyPhoto1[gallery<?php echo $galleryID; ?>]" title="<?php echo $photoCaption; ?>" target="_blank">
					<img class="sig-image" src="<?php echo $photo->thumbImageFilePath; ?>" alt="" style="width:<?php echo $thb_width; ?>px;height:<?php echo $thb_height; ?>px;" />
					<?php if($galleryMessages): ?>
					<span class="sig-pseudo-caption"><b><?php echo JText::_('CLICK_TO_ENLARGE'); ?></b></span>
					<span class="sig-caption"><?php echo JText::_('CLICK_TO_ENLARGE'); ?></span>
					<?php endif; ?>
				</a>
			</span>
		</span>
	</li>
	<?php endforeach; ?>
	<li class="sig-clr"></li>
</ul>
