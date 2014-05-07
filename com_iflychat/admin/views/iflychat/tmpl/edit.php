<?php

/**
 * @package iFlyChat
 * @version 1.0.0
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */
// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');


?>
<script type="text/javascript">
    window.addEvent('domready', function() {

        var span = document.getElementById('config-tabs-com_iflychat_configuration');
        if(document.id('jform_iflychat_show_admin_list').value == '1') span.getElementsByTagName('dt')[2].style.display="none";
        else span.getElementsByTagName('dt')[2].style.display="";
        document.id('jform_iflychat_show_admin_list').addEvent('change', function(){
            if(document.id('jform_iflychat_show_admin_list').value == '1') span.getElementsByTagName('dt')[2].style.display="none";
            else span.getElementsByTagName('dt')[2].style.display="";
        });
    });


    Joomla.submitbutton = function(task)
    {
        if (task == 'message.cancel' || document.formvalidator.isValid(document.id('iflychat-form'))) {
            Joomla.submitform(task, document.getElementById('iflychat-form'));
        }
        else {
            alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
        }
    }
</script>

<form action="<?php echo JRoute::_('index.php?option=com_iflychat&view=iflychat&layout=edit'); ?>" method="post" name="adminForm" id="iflychat-form" class="form-validate">
    <?php
    echo JHtml::_('tabs.start', 'config-tabs-'.$this->component->option.'_configuration', array('useCookie'=>1));
    $fieldSets = $this->form->getFieldsets();
    foreach ($fieldSets as $name => $fieldSet) :
        $label = empty($fieldSet->label) ? 'COM_IFLYCHAT_'.$name.'_FIELDSET_LABEL' : $fieldSet->label;
        echo JHtml::_('tabs.panel', JText::_($label), 'publishing-details');
        if (isset($fieldSet->description) && !empty($fieldSet->description)) :
            echo '<p class="tab-description">'.JText::_($fieldSet->description).'</p>';
        endif;
        ?>
        <ul class="config-option-list">
            <?php
            foreach ($this->form->getFieldset($name) as $field):
                ?>
                <li>
                    <?php if (!$field->hidden) : ?>
                        <?php echo $field->label; ?>
                    <?php endif; ?>
                    <?php echo $field->input; ?>
                </li>
            <?php
            endforeach;
            ?>
        </ul>


        <div class="clr"></div>
    <?php
    endforeach;
    echo JHtml::_('tabs.end');
    ?>
    <div>
        <input type="hidden" name="id" value="<?php echo $this->component->id;?>" />
        <input type="hidden" name="component" value="<?php echo $this->component->option;?>" />
        <input type="hidden" name="task" value="" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>



