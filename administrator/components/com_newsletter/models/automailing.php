<?php

/**
 * The newsletter model. Implements the standard functional for newsletter view.
 *
 * @version	   $Id:  $
 * @copyright  Copyright (C) 2011 Migur Ltd. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.modeladmin');

/**
 * Class of newsletter model of the component.
 *
 * @since   1.0
 * @package Migur.Newsletter
 */
class NewsletterModelAutomailing extends JModelAdmin
{

	protected $_context;

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @param	array	Configuration array for model. Optional.
	 *
	 * @return	JTable	A database object
	 * @since	1.0
	 */
	public function getTable($type = 'Automailing', $prefix = 'NewsletterTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Get the form.
		$form = $this->loadForm('com_newsletter.automailing', 'automailing', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return	mixed	The data for the form.
	 * @since	1.0
	 */
	protected function loadFormData()
	{
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_newsletter.edit.automailing.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}
	
	public function save($data) {
		
		if (empty($data['automailing_type']) && !empty($data['automailing_event'])) {
			
			switch($data['automailing_event']) {
				
				case 'date':
					$data['automailing_type'] = 'scheduled';
					break;

				case 'subscription':
					$data['automailing_type'] = 'eventbased';
					break;
			}
		} 
		
		return parent::save($data);
	}
	
	
	/**
	 * Method to delete one or more records.
	 *
	 * @param   array  &$pks  An array of record primary keys.
	 *
	 * @return  boolean  True if successful, false if an error occurs.
	 *
	 * @since   11.1
	 */
	public function delete(&$pks)
	{
		if (!parent::delete($pks)) {
			return false;
		}
		
		// Deletes all related threads as well
		$dbo = JFactory::getDbo();
		$dbo->setQuery(
			'DELETE FROM #__newsletter_threads WHERE ' .
			'type="automail" AND parent_id IN(' . implode(',', $pks) . ')');
		return $dbo->query();
	}
}
