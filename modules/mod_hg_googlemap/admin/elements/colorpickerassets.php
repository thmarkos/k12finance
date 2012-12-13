<?php

defined('JPATH_BASE') or die;

jimport('joomla.form.formfield');

class JFormFieldColorpickerassets extends JFormField {
        protected $type = 'Colorpickerassets';

        protected function getInput() {
            $doc = JFactory::getDocument();
			$doc->addScript(JURI::root().$this->element['path'].'jscolor.js');
			return null;
        }
}

?>