/**
 * The main submit functionality for forms in newsletter view.
 *
 * @version	   $Id:  $
 * @copyright  Copyright (C) 2011 Migur Ltd. All rights reserved.
 * @license	   GNU General Public License version 2 or later; see LICENSE.txt
 */

/**
 * The implementation of main submit handler for the form.
 *
 * @return   void
 * @since    1.0
 * @modified Andrey
 */
Joomla.submitbutton = function(task)
{
	if (task == '')
	{
		return false;
	}
	else
	{
		var isValid=true;
		var action = task.split('.');
		if (action[1] != 'cancel' && action[1] != 'close')
		{
			var forms = $$('form.form-validate');
			for (var i=0;i<forms.length;i++)
			{
				if (!document.formvalidator.isValid(forms[i]))
				{
					isValid = false;
					break;
				}
			}

            Migur.validator.tabIndicator(
                '#tabs-sub-container',
                'span h3 a',
                'tab-invalid',
                '.invalid'
            );
		}
	
		if (isValid)
		{
			// To set jform[htmlTpl] and jform[plugins] fields
			autosaver.getter();
			Joomla.submitform(task, document.newsletterForm);
			return true;
		}
		else
		{
			//alert(Joomla.JText._('COM_NEWSLETTER_ERROR_UNACCEPTABLE','Some values are unacceptable'));
			return false;
		}
	}
}
