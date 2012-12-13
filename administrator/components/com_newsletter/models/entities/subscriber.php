<?php

/**
 * The SMTP profile model. Implements the standard functional for SMTP profile view.
 *
 * @version	   $Id:  $
 * @copyright  Copyright (C) 2011 Migur Ltd. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

/**
 * Class of Subscriber model of the component.
 *
 * @since   1.0
 * @package Migur.Newsletter
 * @deprecated 12.05 
 */
class NewsletterModelEntitySubscriber extends MigurModel
{
	/**
	 * Load subscriber or juser.
	 * If you specify j[JUserId] as input data then you get 
	 * JUser "in a body of subscriber
	 * 
	 * @param arrray|int $data
	 * 
	 * @return boolean 
	 */
	public function load($data)
	{
		if (empty($data)) {
			return false;
		}

		if ((is_numeric($data) && $data < 0) || !parent::load($data)) {

			// If the J! user is being requested
			if ((is_numeric($data) && $data < 0) || !empty($data['email'])) {

				if (!$this->_checkOnTheFly($data)) {
					return false;
				}
			}
		}

		if (!isset($this->_data->params) || is_null($this->_data->params)) {
			$this->_data->params = new stdClass;
		}

		// If we have the user_id then we must ensure 
		// that this user exists
		if (!empty($this->_data->user_id)) {

			$jUser = JTable::getInstance('user');

			// If user absent let's decide what to do with subscriber row...
			if (!$jUser->load($this->_data->user_id)) {

				$this->_data->user_id = 0;

				// If required data is present....
				if (!empty($this->_data->email)) {
					// Convert it to Migur subscriber
					parent::save();
					return true;
				} else {
					// Delete it!
					$this->delete();
					return false;
				}
			}

			$this->_data->name = $jUser->name;
			$this->_data->email = $jUser->email;
			$this->_data->state = !$jUser->block;
			$this->_data->created_on = $jUser->registerDate;
		}

		return true;
	}

	/**
	 * Performs search of J!user with $data creteria.
	 * Creates SUBSCRIBER row for him if it does not exist.
	 * 
	 * @param type $data
	 * @return type 
	 */
	protected function _checkOnTheFly($data)
	{
		if (empty($data)) {
			return false;
		}


		// Tryin to load J! user with $data criteria
		$dbo = JFactory::getDbo();
		$query = $dbo->getQuery(true);
		$query->select('*')
			->from('#__users');

		if (is_numeric($data)) {
			$query->where('id = ' . abs((int) $data));
		} else {
			foreach ($data as $name => $item) {
				$query->where($name . '=' . $dbo->quote($item));
			}
		}
		$dbo->setQuery($query);
		$user = $dbo->loadAssoc();

		// Nothing to do if absent.
		if (empty($user['id'])) {
			return false;
		}

		// If we already has SUBSCRIBER row for this J! user 
		// then there is nothing to do.
		if (parent::load(array('user_id' => $user['id']))) {
			return true;
		}

		// In other case create row
		$data = array();
		$data['user_id'] = $user['id'];
		$data['created_on'] = date('Y-m-d H:i:s');
		$data['created_by'] = JFactory::getUser()->id;
		$data['modified_on'] = 0;
		$data['modified_by'] = 0;
		$data['locked_on'] = 0;
		$data['locked_by'] = 0;

		if (!parent::save($data)) {
			return false;
		}

		// Create subscription key. 
		// Activation depends on activation of J!user.
		$this->_createSubscriptionKey();
		$this->_data->confirmed = empty($user->activation) ? 1 : $this->_data->subscription_key;
		return parent::save();
	}

	/**
	 * Get type of newsletter user prefer to recieve
	 * @deprecated 12.05 Moved to subscriber model
	 */
	public function getType()
	{
		return ($this->html == 1) ? 'html' : 'plain';
	}

	/**
	 * Returns a reference to the a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 *
	 * @return	JTable	A database object
	 * @since	1.0.4
	 */
	public function getTable($type = 'Subscriber', $prefix = 'NewsletterTable')
	{
		return JTable::getInstance($type, $prefix);
	}

	/**
	 * Creates the subscription key. Use user id and random number
	 * Length of subscription key is 15 characters
	 *
	 * @param  $userId - integer. The user ID.
	 *
	 * @return string
	 * @since 1.0
	 */
	protected function _createSubscriptionKey()
	{
		if (!empty($this->_data->subscription_key)) {
			return;
		}

		$sid = $this->_data->subscriber_id;
		// to get the constant length
		$mask = '000000000';
		$id = substr($mask, 0, strlen($mask) - strlen($sid)) . $sid;
		$this->_data->subscription_key = rand(100000, 999999) . $id . time();
	}

	/**
	 * Shorthand for creation of free Migur subscriber (not bound to J! user).
	 * 
	 * @param type $data
	 * @return type 
	 */
	public function create($data)
	{
		$data = (array) $data;

		// Complement data...
		// Sohuld not allow create J!user-type subscriber
		$data['user_id'] = 0;
		$data['created_on'] = date('Y-m-d H:i:s');
		$data['created_by'] = JFactory::getUser()->id;
		$data['modified_on'] = 0;
		$data['modified_by'] = 0;
		$data['locked_on'] = 0;
		$data['locked_by'] = 0;

		if ($this->save($data)) {

			$this->_data->confirmed = (empty($data['confirmed']) || $data['confirmed'] != 1) ?
				$this->_data->subscription_key : 1;
			return parent::save();
		}

		return false;
	}

	/**
	 * Save user and check if it has a subscription key
	 * 
	 * @param array|object $data
	 * @return boolean 
	 */
	public function save($data = array(), $isJUser = false)
	{
		// Load state if data not loaded yet and $data has sid but does NOT have uid. 
		// Need to determine if this is a J! user
		if (!$this->getId() && !empty($data['subscriber_id']) && !isset($data['user_id'])) {
			if (!$this->load($data['subscriber_id'])) {
				return false;
			}
		}

		// Check if this row relies or should rely to J!user.
		if (!empty($data['user_id'])) {
			$uid = $data['user_id'];
		} else {
			$uid = !empty($this->_data->user_id) ? $this->_data->user_id : null;
		}

		// If this row relates to J! user...
		if (!empty($uid)) {
			// Sanitize model
			$data['name'] = '';
			$data['email'] = '';
			$data['created_on'] = 0;
			$data['modified_on'] = date('Y-m-d H:i:s');
			$data['modified_by'] = JFactory::getUser()->id;
		}

		// Check if this is a new record 
		if (!$this->getId()) {
			$data['created_by'] = JFactory::getUser()->id;
			$data['modified_on'] = 0;
			$data['modified_by'] = 0;
			$data['locked_on'] = 0;
			$data['locked_by'] = 0;
		}

		// Save the rest or all
		if (!parent::save($data)) {
			return false;
		}

		$this->_createSubscriptionKey();
		if (empty($this->_data->confirmed) && empty($data['confirmed'])) {
			$this->_data->confirmed = $this->_data->subscription_key;
		}
		
		return parent::save();
	}

	/**
	 * Method to check if user is already binded to the list.
	 *
	 * @param	int|string $data The id a list.
	 *
	 * @return	object on success, false or null on fail
	 * @since	1.0
	 * @deprecated 12.05 Moved to list model
	 */
	public function isInList($lid)
	{
		return $this->getTable('sublist')
				->load(array(
					'subscriber_id' => (int) $this->_data->subscriber_id,
					'list_id' => (int) $lid));
	}

	
	/**
	 * Is subscriber finished with registration?
	 * 
	 * @return boolean
	 */
	public function isConfirmed()
	{
		return ($this->_data->confirmed == 1);
	}

	
	/**
	 * Is subscriber enabled?
	 * 
	 * @return type 
	 */
	public function isEnabled()
	{
		return ($this->_data->state == 1);
	}

	
	/**
	 * Bind current subscriber to list.
	 *
	 * @param	array	$data	The form data.
	 *
	 * @return	boolean	True on success.
	 * @since	1.0
	 * @deprecated 12.05 Moved to list model
	 */
	public function assignToList($lid, $options = array())
	{
		if (empty($lid)) {
			return false;
		}

		$confirmed = !empty($options['confirmed'])? (int) $options['confirmed'] : (int) $this->_data->confirmed;
		
		// Save and finish.
		return $this->getTable('sublist')
				->save(array(
					'subscriber_id' => (int) $this->_data->subscriber_id,
					'list_id' => (int) $lid,
					'confirmed' => $confirmed));
	}

	/**
	 * Bind current subscriber to list.
	 *
	 * @param	array	$data	The form data.
	 *
	 * @return	boolean	True on success.
	 * @since	1.0
	 */
	public function unbindFromList($lid)
	{
		if (empty($lid)) {
			return false;
		}

		// Load the row. If it exists then nothing to do
		if (!$this->isInList($lid)) {
			return true;
		}

		// Delete and finish.
		$table = $this->getTable('sublist');
		if (!$table->load(array(
				'subscriber_id' => (int) $this->_data->subscriber_id,
				'list_id' => (int) $lid))
		) {
			return false;
		}

		return $table->delete();
	}

	/**
	 * Confirm subscriber and all its subscriptions to lists
	 * 
	 * @return boolean true on success
	 */
	public function confirm()
	{
		$this->_data->confirmed = 1;

		return $this->save();
	}

	/**
	 * Used to get id for juser/subscriber.
	 * Return subscriber_id if this is a subscriber
	 * or 'j[#__users.id]' if this entity is the wrapper for J! user via subscriber entity
	 * 
	 * @return string
	 */
	public function getExtendedId()
	{
		$id = $this->getId();
		if (!empty($id)) {
			return $id;
		}

		return '-' . $this->_data->user_id;
	}

	/**
	 * Check if this entity is the wrapper for J! user type
	 * Covers with TRUE all unbound yet J! users and bound ones to subscriber's table
	 * 
	 * @return boolean 
	 */
	public function isJoomlaUserType()
	{
		return (!empty($this->_data->user_id));
	}

	
	/**
	 * Deletes subscriber's and/or user's row
	 * 
	 * @return type 
	 */
	public function delete()
	{
		if ($this->isJoomlaUserType()) {
			throw Exception('The deleting of Joomla users is not allowed');
		}

		if (!$this->getId() || !parent::delete($this->getId())) {
			return false;
		}

		return true;
	}

}
