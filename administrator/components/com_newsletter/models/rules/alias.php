<?php

/**
 * @version	   $Id:  $
 * @copyright  Copyright (C) 2011 Migur Ltd. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('JPATH_BASE') or die;

jimport('joomla.form.formrule');

/**
 * Form Rule class for the form.
 *
 * @since   1.0
 * @package Migur.Newsletter
 */
class JFormRuleAlias extends JFormRule
{

	/**
	 * The regular expression to use in testing a form field value.
	 *
	 * @var		string
	 * @since	1.0
	 */
	protected $regex = '^[a-zA-Z0-9\-]+$';
	
	/**
	 * Method to test the email address and optionally check for uniqueness.
	 *
	 * @param	object	$element	The JXMLElement object representing the <field /> tag for the
	 * 								form field object.
	 * @param	mixed	$value		The form field value to validate.
	 * @param	string	$group		The field name group control value. This acts as as an array
	 * 								container for the field. For example if the field has name="foo"
	 * 								and the group value is set to "bar" then the full field name
	 * 								would end up being "bar[foo]".
	 * @param	object	$input		An optional JRegistry object with the entire data set to validate
	 * 								against the entire form.
	 * @param	object	$form		The form object for which the field is being tested.
	 *
	 * @return	boolean	True if the value is valid, false otherwise.
	 * @since	1.0
	 */
	public function test(&$element, $value, $group = null, & $input = null, & $form = null)
	{
		// If the field is empty and not required, the field is valid.
		$required = ((string) $element['required'] == 'true' || (string) $element['required'] == 'required');
		if (!$required && empty($value)) {
			return true;
		}

		// Test the value against the regular expression.
		if (!parent::test($element, $value, $group, $input, $form)) {
			return false;
		}

		// Check if we should test for uniqueness.
		$unique = ((string) $element['unique'] == 'true' || (string) $element['unique'] == 'unique');
		if ($unique) {

			// Get the database object and a new query object.
			$db = JFactory::getDBO();
			$query = $db->getQuery(true);

			// Build the query.
			$query->select('COUNT(*)');
			$query->from('#__newsletter_newsletters');
			$query->where('alias = ' . $db->quote($value));

			// Get the extra field check attribute.
			$nid = ($input instanceof JRegistry) ? $input->get('newsletter_id') : '';
			$query->where($db->quoteName('newsletter_id') . ' <> ' . (int) $nid);

			// Set and query the database.
			$db->setQuery($query);
			$duplicate = (bool)$db->loadResult();

			// Check for a database error.
			
			// TODO: deprecated since 12.1
			if ($db->getErrorNum()) {
				// TODO deprecated since 12.1 Use PHP Exception
				JError::raiseWarning(500, $db->getErrorMsg());
			}

			if ($duplicate) {
				return false;
			}
		}

		return true;
	}

}