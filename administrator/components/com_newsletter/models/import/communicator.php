<?php

/**
 * The Manager for acyMailer Component.
 *
 * @version	   $Id:  $
 * @copyright  Copyright (C) 2011 Migur Ltd. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE.txt
 */

JLoader::import('models.import.common', JPATH_COMPONENT_ADMINISTRATOR);

class NewsletterModelImportCommunicator extends NewsletterModelImportCommon
{

	public $name = 'Communicator';

	/**
	 * Fetch the lists from acyMailer component to array
	 *
	 * @return array - array of objects
	 * @since  1.0
	 */
	public function exportLists($offset = 0, $limit = 0)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('s.subscriber_email AS email, s.subscriber_name AS name, "" AS list_name, s.subscribe_date AS created');
		$query->from('#__communicator_subscribers AS s');
		$query->order('s.subscriber_email');
		// Set the query
		$db->setQuery($query, $offset, $limit);
		$objs = $db->loadAssocList();

		return (array) $objs;
	}

	/**
	 * Check the structure of a exported/imported tables
	 *
	 * @param array - the list to import
	 *
	 * @return bool
	 * @since  1.0
	 */
	public function isValid()
	{

		// Check the subscribers table
		return $this->validateTable(
			'#__communicator_subscribers',
			array('subscriber_email', 'subscriber_name', 'subscribe_date')
		);
	}

}
