<?php


/**
 * @package iFlyChat
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */


// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldCustom3 extends JFormField {

    protected $type = 'Custom3';

    // getLabel() left out

    public function getInput() {

        if(!file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {
        // Initialize some field attributes.
        $size = $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
        $maxLength = $this->element['maxlength'] ? ' maxlength="' . (int) $this->element['maxlength'] . '"' : '';
        $class = $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';
        $readonly = ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
        $disabled = ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';

        // Initialize JavaScript field attributes.
        $onchange = $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

        return '<input type="text" name="' . $this->name . '" id="' . $this->id . '"' . ' value="'
        . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"' . $class . $size . $disabled . $readonly . $onchange . $maxLength . '/>';
        } else return '';

    }
    protected function getLabel()
    {

        if(!file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {
            // Initialise variables.
            $label = '';

            if ($this->hidden)
            {
                return $label;
            }

            // Get the label text from the XML element, defaulting to the element name.
            $text = $this->element['label3'] ? (string) $this->element['label3'] : (string) $this->element['name'];
            $text = $this->translateLabel ? JText::_($text) : $text;

            // Build the class for the label.
            $class = !empty($this->description) ? 'hasTip' : '';
            $class = $this->required == true ? $class . ' required' : $class;
            $class = !empty($this->labelClass) ? $class . ' ' . $this->labelClass : $class;

            // Add the opening label tag and main attributes attributes.
            $label .= '<label id="' . $this->id . '-lbl" for="' . $this->id . '" class="' . $class . '"';

            // If a description is specified, use it to build a tooltip.
            if (!empty($this->description))
            {
                $label .= ' title="'
                    . htmlspecialchars(
                        trim($text, ':') . '::' . ($this->translateDescription ? JText::_($this->description) : $this->description),
                        ENT_COMPAT, 'UTF-8'
                    ) . '"';
            }

            // Add the label text and closing tag.
            if ($this->required)
            {
                $label .= '>' . $text . '<span class="star">&#160;*</span></label>';
            }
            else
            {
                $label .= '>' . $text . '</label>';
            }

            return $label;
        } else return '';
    }
}