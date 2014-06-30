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

class JFormFieldCustom2 extends JFormField {

    protected $type = 'Custom2';

    // getLabel() left out

    public function getInput() {


        if(!file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {

            // Initialize variables.
            $html = array();

            // Initialize some field attributes.
            $class = $this->element['class'] ? ' class="radio ' . (string) $this->element['class'] . '"' : ' class="radio"';

            // Start the radio field output.
            $html[] = '<fieldset id="' . $this->id . '"' . $class . '>';

            // Get the field options.
            $options = $this->getOptions();

            // Build the radio field output.
            foreach ($options as $i => $option)
            {

                // Initialize some option attributes.
                $checked = ((string) $option->value == (string) $this->value) ? ' checked="checked"' : '';
                $class = !empty($option->class) ? ' class="' . $option->class . '"' : '';
                $disabled = !empty($option->disable) ? ' disabled="disabled"' : '';

                // Initialize some JavaScript option attributes.
                $onclick = !empty($option->onclick) ? ' onclick="' . $option->onclick . '"' : '';

                $html[] = '<input type="radio" id="' . $this->id . $i . '" name="' . $this->name . '"' . ' value="'
                    . htmlspecialchars($option->value, ENT_COMPAT, 'UTF-8') . '"' . $checked . $class . $onclick . $disabled . '/>';

                $html[] = '<label for="' . $this->id . $i . '"' . $class . '>'
                    . JText::alt($option->text, preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)) . '</label>';
            }

            // End the radio field output.
            $html[] = '</fieldset>';

            return implode($html);
        }
        else return '';


    }
    protected function getOptions()
    {
        if(!file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {
        // Initialize variables.
        // Initialize variables.
        $options = array();

        foreach ($this->element->children() as $option)
        {

            // Only add <option /> elements.
            if ($option->getName() != 'option')
            {
                continue;
            }

            // Create a new option object based on the <option /> element.
            $tmp = JHtml::_(
                'select.option', (string) $option['value'], trim((string) $option), 'value', 'text',
                ((string) $option['disabled'] == 'true')
            );

            // Set some option attributes.
            $tmp->class = (string) $option['class'];

            // Set some JavaScript option attributes.
            $tmp->onclick = (string) $option['onclick'];

            // Add the option object to the result set.
            $options[] = $tmp;
        }

        reset($options);

        return $options; }
    else {

        return '';

    }
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
            $text = $this->element['label2'] ? (string) $this->element['label2'] : (string) $this->element['name'];
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

            return $label; }
        else {

            return '';

        }
    }
}