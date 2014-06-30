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

class JFormFieldCustom4 extends JFormField {

    protected $type = 'Custom4';

    // getLabel() left out

    public function getInput() {


        if(!file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {

            // Initialize variables.
            $html = array();
            $attr = '';

            // Initialize some field attributes.
            $attr .= $this->element['class'] ? ' class="' . (string) $this->element['class'] . '"' : '';

            // To avoid user's confusion, readonly="true" should imply disabled="true".
            if ((string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true')
            {
                $attr .= ' disabled="disabled"';
            }

            $attr .= $this->element['size'] ? ' size="' . (int) $this->element['size'] . '"' : '';
            $attr .= $this->multiple ? ' multiple="multiple"' : '';

            // Initialize JavaScript field attributes.
            $attr .= $this->element['onchange'] ? ' onchange="' . (string) $this->element['onchange'] . '"' : '';

            // Get the field options.
            $options = (array) $this->getOptions();

            // Create a read-only list (no name) with a hidden input to store the value.
            if ((string) $this->element['readonly'] == 'true')
            {
                $html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
                $html[] = '<input type="hidden" name="' . $this->name . '" value="' . htmlspecialchars($this->value, ENT_COMPAT, 'UTF-8') . '"/>';
            }
            // Create a regular list.
            else
            {
                $html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
            }

            return implode($html); }

        else {

            return '';

        }
    }
    protected function getOptions()
    {
        if(!file_exists(JPATH_ROOT .'/components/com_community/libraries/core.php')) {
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
                'select.option', (string) $option['value'],
                JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $this->fieldname)), 'value', 'text',
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
            $text = $this->element['label4'] ? (string) $this->element['label4'] : (string) $this->element['name'];
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
    }}