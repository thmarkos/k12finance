<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

function make_link($linktype, $link, $externallink, $articlelink ) {
	$final_link = '';
	switch($linktype) {
		case"internal":
			$menu =& JSite::getMenu();
			$menuitem = $menu->getItem($link);
			if($menuitem) switch($menuitem->type) {
				case 'component': 
					$final_url = JRoute::_($menuitem->link.'&Itemid='.$link);
					break;
				case 'url':
				case 'alias':
					$final_url = JRoute::_($menuitem->link);
					break;
			}
			
			$final_link = $final_url;
		break;
		
		case"external":
			$final_link = $externallink;
		break;
		
		case"article":
		$db = &JFactory::getDBO();
		$db->setQuery('SELECT id,catid FROM #__content WHERE id in ('.$articlelink.')');
		$rows = $db->loadObjectList();
		foreach ( $rows as $row ) {
		   $articleLink[$row->id] = $row->catid;
		}
		$flink = JRoute::_('index.php?option=com_content&view=article&id='.$articlelink);
		$final_link = $flink;
		break;
	}
	return $final_link;
}