<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//import the necessary class definition for formfield
jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldSeparator extends JFormField {
	protected  $type = 'Separator';
	protected function getInput()
	{
		return '<div style="font-size:12px; line-height:18px; color:#333; padding:2px; margin:10px 0; background: #FAF2B6; height: auto; min-width: 100% important!; max-width: 100% important!;"><div style="clear:both;"></div></div>';     
	}

}
