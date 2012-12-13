<?php

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
//import the necessary class definition for formfield
jimport('joomla.html.html');
jimport('joomla.form.formfield');
class JFormFieldInfo extends JFormField {
	protected  $type = 'Info';
	protected function getInput()
	{
		return '';        
	}
	
	public function getLabel() 
	{
		return '<div class="infoField">'.JText::_($this->element['label']).'<div style="clear:both;"></div></div>';
	}
}
