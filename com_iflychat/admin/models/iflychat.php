<?php
/**
 * @package iFlyChat
 * @copyright Copyright (C) 2014 iFlyChat. All rights reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @author iFlyChat Team
 * @link https://iflychat.com
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.modelform');


class IflychatModelIflychat extends JModelForm  {


    protected $event_before_save = 'onConfigurationBeforeSave';

    protected $event_after_save = 'onConfigurationAfterSave';


    public function getForm($data = array(), $loadData = true){
        // Get the form.
        $form = $this->loadForm('com_iflychat.iflychat', 'config',
            array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)){
            return false;
        }
        return $form;
    }


    public function save($data)
    {
        $dispatcher = JDispatcher::getInstance();
        $table	= JTable::getInstance('extension');
        $isNew = true;

        // Save the rules.
        if (isset($data['params']) && isset($data['params']['rules']))
        {
            $rules	= new JAccessRules($data['params']['rules']);
            $asset	= JTable::getInstance('asset');

            if (!$asset->loadByName($data['option']))
            {
                $root	= JTable::getInstance('asset');
                $root->loadByName('root.1');
                $asset->name = $data['option'];
                $asset->title = $data['option'];
                $asset->setLocation($root->id, 'last-child');
            }
            $asset->rules = (string) $rules;

            if (!$asset->check() || !$asset->store())
            {
                $this->setError($asset->getError());
                return false;
            }

            // We don't need this anymore
            unset($data['option']);
            unset($data['params']['rules']);
        }

        // Load the previous Data
        if (!$table->load($data['id']))
        {
            $this->setError($table->getError());
            return false;
        }

        unset($data['id']);

        // Bind the data.
        if (!$table->bind($data))
        {
            $this->setError($table->getError());
            return false;
        }

        // Check the data.
        if (!$table->check())
        {
            $this->setError($table->getError());
            return false;
        }

        // Trigger the oonConfigurationBeforeSave event.
        $result = $dispatcher->trigger($this->event_before_save, array($this->option . '.' . $this->name, $table, $isNew));

        if (in_array(false, $result, true))
        {
            $this->setError($table->getError());
            return false;
        }

        // Store the data.
        if (!$table->store())
        {
            $this->setError($table->getError());
            return false;
        }

        // Clean the component cache.
        $this->cleanCache('_system');

        // Trigger the onConfigurationAfterSave event.
        $dispatcher->trigger($this->event_after_save, array($this->option . '.' . $this->name, $table, $isNew));




        return true;
    }
    function getComponent()
    {
        $result = JComponentHelper::getComponent('com_iflychat');

        return $result;
    }




}