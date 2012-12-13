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

/**
 * Skyline Software - Twitter Helper Class.
 *
 * @package		Joomla.Site
 * @subpakage	Skyline.Twitter
 */
jimport( 'joomla.filesystem.path' );
jimport( 'joomla.filesystem.folder' );

class modSL_TwitterHelper {

	/**
	 * @param	$params
	 * @return	array
	 */
	public static function getList($params) {
		$username	= $params->get('username');
		$count		= $params->get('count', 5);

		if (!$username) {
			return null;
		}
		
		// START CACHING
		$cache_file = JPATH_BASE.'/cache/twitter_cache.xml';
	
		$feedUrl	= "http://twitter.com/statuses/user_timeline/$username.xml?count=$count";

		if (ini_get('allow_url_fopen')) {
			$content	= file_get_contents($feedUrl);
		} else if (function_exists('curl_init')) {
			$cUrl			= curl_init($feedUrl);
			curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, true);
			$content	= curl_exec($cUrl);
			curl_close($cUrl);
		} else {
			return array();
		}

		if (JFile::exists($cache_file)){
			$mtime = (strtotime("now") - filemtime($cache_file));

			if (($mtime > 600) && ($cache_xml = $content))
			{
			  //file_put_contents($cache_xml);
			  JFile::write($cache_file, $cache_xml); 
			  
			}
			echo "<!-- twitter cache generated ".date('Y-m-d h:i:s', filemtime($cache_file))." -->";
		} else {
			$cache_xml = $content;
			JFile::write($cache_file, $cache_xml); 
		}
		
		
		$xml		= JFactory::getXML($cache_file, true);
		$posts		= @$xml->status;
		if(!$posts) {
			echo @$xml->error;
		}

		foreach ($posts as $post) {
			$post->created_date	= self::_web20Format($post->created_at);
			$post->text			= self::_clean($post->text);
			$post->link			= "http://twitter.com/$username/status/$post->id";
		}

		return $posts;
	}

	private static function _web20Format($date_from, $date_to = -1) {
		// default and assume if 0 is passed in that its an error rather than the epoch

		if (!is_numeric($date_from)) {
			$date_from	= JFactory::getDate($date_from)->toUnix();
		}

		if ($date_from <= 0) {
			return JText::_('A long time ago');
		}

		if ($date_to == -1) {
			$date		= JFactory::getDate();
			$date_to	= $date->toUNIX();
		}

		// calculate the difference in seconds between the two timestamps
		$difference = $date_to - $date_from;

		if ($difference < 60) {
			$interval = 's';
		} else if ($difference < 60 * 60) {
			$interval = 'n';
		} else if ($difference < 60 * 60 * 24) {
			$interval = 'h';
		} else if ($difference < 60 * 60 * 24 * 7) {
			$interval = 'd';
		} else if ($difference < 60 * 60 * 24 * 30) {
			$interval = 'ww';
		} else if ($difference < 60 * 60 * 24 * 365) {
			$interval = 'm';
		} else {
			$interval = 'y';
		}

		// base on the interval, determine the number of units ebetween the two dates.
		switch ($interval) {
			case 'm':
				$months_diff = floor($difference / (60 * 60 * 24 * 29));
				while (mktime(date("H", $date_from), date("i", $date_from), date("s", $date_from), date("n", $date_from) + ($months_diff), date("j", $date_to), date("Y", $date_from)) < $date_to) {
					$months_diff++;
				}
				$date_diff = $months_diff;

				if ($date_diff == 12) {
					$date_diff--;
				}

				$res = ($date_diff == 1) ? JText::sprintf('%d month ago', $date_diff) : JText::sprintf('%d months ago', $date_diff);
				break;

			case 'y':
				$date_diff = floor($difference / (60 * 60 * 24 * 365));
				$res = ($date_diff == 1) ? JText::sprintf('%d year ago', $date_diff) : JText::sprintf('%d years ago', $date_diff);
				break;

			case 'd':
				$date_diff = floor($difference / (60 * 60 * 24));
				$res = ($date_diff == 1) ? JText::sprintf('%d day ago', $date_diff) : JText::sprintf('%d days ago', $date_diff);
				break;

			case 'ww':
				$date_diff = floor($difference / (60 * 60 * 24 * 7));
				$res = ($date_diff == 1) ? JText::sprintf('%d week ago', $date_diff) : JText::sprintf('%d weeks ago', $date_diff);
				break;

			case 'h':
				$date_diff = floor($difference / (60 * 60));
				$res = ($date_diff == 1) ? JText::sprintf('%d hour ago', $date_diff) : JText::sprintf('%d hours ago', $date_diff);
				break;

			case 'n':
				$date_diff = floor($difference / (60));
				$res = ($date_diff == 1) ? JText::sprintf('%d minute ago', $date_diff) : JText::sprintf('%d minutes ago', $date_diff);
				break;

			case 's':
				$date_diff = $difference;
				$res = ($date_diff == 1) ? JText::sprintf('%d second ago', $date_diff) : JText::sprintf('%d seconds ago', $date_diff);
				break;
		}

		return $res;
	}

	private static function _clean($content) {
		$content = preg_replace("#(^|[\n ])([\w]+?://[\w]+[^ \"\n\r\t< ]*)#", "\\1<a href=\"\\2\" >\\2</a>", $content);
		$content = preg_replace("#(^|[\n ])((www|ftp)\.[^ \"\t\n\r< ]*)#", "\\1<a href=\"http://\\2\" >\\2</a>", $content);
		$content = preg_replace("/@(\w+)/", "<a href=\"http://www.twitter.com/\\1\" >@\\1</a>", $content);
		$content = preg_replace("/#(\w+)/", "<a href=\"http://search.twitter.com/search?q=\\1\" >#\\1</a>", $content);

		return $content;
	}

}