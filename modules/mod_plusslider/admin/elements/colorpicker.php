<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldColorpicker extends JFormField {
        protected $type = 'Colorpicker';

        protected function getInput() {
			$output = '';
			$output .= "<input class=\"color {pickerPosition:'right', required:false, hash:true}\" autocomplete=\"off\" id=\"".$this->name."\" name=\"".$this->name."\" type=\"text\" size=\"7\" maxlength=\"11\" value=\"".$this->value."\" />";
			
			return $output;
        }
}

?>