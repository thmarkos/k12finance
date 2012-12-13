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

$document	= JFactory::getDocument();
$document->addStyleSheet(JURI::base() . 'modules/mod_sl_twitter/assets/css/style.css');
$link		= 'http://twitter.com/' . $params->get('username');

?>

<div class="mod_sl_twitter">
	<ul class="sl_tweets">
		<?php foreach ($list as $item) : ?>
			<li>
				<p>
					<?php echo $item->text; ?>
				</p>
				<span>
					<a href="<?php echo $link; ?>" title="<?php echo JText::sprintf('MOD_SL_TWITTER_GOTOPAGE_TITLE', $item->user->name); ?>" target="_blank">
						<?php echo $item->user->name; ?>
					</a>
					·
					<span title="<?php echo JText::_('MOD_SL_TWITTER_RETWEET_COUNT'); ?>"><?php echo $item->retweet_count; ?></span>
					<a href="<?php echo $item->link; ?>" class="date" target="_blank"><?php echo $item->created_date; ?></a>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
</div>