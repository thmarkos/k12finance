<?php

/**
 * The model to configure the component.
 *
 * @version	   $Id:  $
 * @copyright  Copyright (C) 2011 Migur Ltd. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE.txt
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');

/**
 * Class of configuration model of the component
 *
 * @since   1.0
 * @package Migur.Newsletter
 */
class NewsletterModelConfiguration extends JModelForm
{

	/**
	 * Method to auto-populate the model state.
	 * Note. Calling getState in this method will result in recursion.
	 *
	 * @return	void
	 * @since	1.0
	 */
	protected function populateState()
	{
		// Set the component (option) we are dealing with.
		$component = 'com_newsletter';
		$this->setState('component.option', $component);
	}

	/**
	 * Method to get a form object.
	 *
	 * @param	array	$data		Data for the form.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 *
	 * @return	mixed	A JForm object on success, false on failure
	 * @since	1.0
	 */
	public function getForm($data = array(), $loadData = true)
	{
		jimport('joomla.form.form');

		if ($path = $this->getState('component.path')) {
			// Add the search path for the admin component config.xml file.
			JForm::addFormPath($path);
		} else {
			// Add the search path for the admin component config.xml file.
			JForm::addFormPath(JPATH_ADMINISTRATOR . '/components/' . $this->getState('component.option'));
		}

		// Get the form.
		$form = $this->loadForm(
				'com_newsletter.config',
				'config',
				array('control' => 'jform', 'load_data' => $loadData),
				false,
				'/config'
		);

		if (empty($form)) {
			return false;
		}

		return $form;
	}

	public function loadFormData()
	{
		$newsletter = JModel::getInstance('Newsletter', 'NewsletterModelEntity');
		$newsletter->loadFallBackNewsletter();
		$result = $this->getComponent()->params->toArray();
		$result['confirm_mail_subject'] = $newsletter->subject;
		$result['confirm_mail_body'] = $newsletter->plain;
		return $result;
	}

	/**
	 * Get the component information.
	 *
	 * @return	object
	 * @since	1.0
	 */
	function getComponent()
	{
		// Initialise variables.
		$option = $this->getState('component.option');
		// Load common and local language files.
		$lang = JFactory::getLanguage();
		$lang->load($option, JPATH_BASE, null, false, false)
			|| $lang->load($option, JPATH_BASE . "/components/$option", null, false, false)
			|| $lang->load($option, JPATH_BASE, $lang->getDefault(), false, false)
			|| $lang->load($option, JPATH_BASE . "/components/$option", $lang->getDefault(), false, false);

		$result = JComponentHelper::getComponent($option);
		return $result;
	}

	/**
	 * Method to save the configuration data.
	 *
	 * @param	array	An array containing all global config data.
	 *
	 * @return	bool	True on success, false on failure.
	 * @since	1.0
	 */
	public function save($data)
	{
		$table = JTable::getInstance('JExtension', 'NewsletterTable');
		// Save the rules.
		if (isset($data['params']) && isset($data['params']['rules'])) {
			jimport('joomla.access.rules');
			$rules = new JRules($data['params']['rules']);
			$asset = JTable::getInstance('asset');

			if (!$asset->loadByName($data['option'])) {
				$root = JTable::getInstance('asset');
				$root->loadByName('root.1');
				$asset->name = $data['option'];
				$asset->title = $data['option'];
				$asset->setLocation($root->id, 'last-child');
			}
			
			$asset->rules = (string) $rules;

			if (!$asset->check() || !$asset->store()) {
				$this->setError($asset->getError());
				return false;
			}

			// We don't need this anymore
			unset($data['option']);
			unset($data['params']['rules']);
		}

		// Load the previous Data
		if (!$table->load(array('name' => 'com_newsletter'))) {
			$this->setError($table->getError());
			return false;
		}

		unset($data['id']);

		// Bind the data.
		if (!$table->bind($data)) {
			$this->setError($table->getError());
			return false;
		}

		// Check the data.
		if (!$table->check()) {
			$this->setError($table->getError());
			return false;
		}

		// Store the data.
		if (!$table->store()) {
			$this->setError($table->getError());
			return false;
		}

		// Clean the cache.
		$cache = JFactory::getCache('_system');
		$cache->clean();

		return true;
	}

}