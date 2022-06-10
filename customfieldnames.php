<?php

require_once 'customfieldnames.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function customfieldnames_civicrm_config(&$config) {
  _customfieldnames_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function customfieldnames_civicrm_install() {
  _customfieldnames_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function customfieldnames_civicrm_uninstall() {
  _customfieldnames_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function customfieldnames_civicrm_enable() {
  _customfieldnames_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function customfieldnames_civicrm_disable() {
  _customfieldnames_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed
 *   Based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function customfieldnames_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _customfieldnames_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function customfieldnames_civicrm_buildForm($formName, &$form) {
  if($formName == 'CRM_Custom_Form_Group' && $form->getAction() == CRM_Core_Action::ADD) {
    $form->add('text', 'table_name', ts('Table Name'), '', TRUE);
    $form->add('text', 'name', ts('Machine Name'), '', TRUE);
    
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => "CRM/LCD/customgroup.tpl"
    ));
    //Change label of title field
    if ($form->elementExists('title')) {
			$title_label = $form->getElement('title');
      $title_label->_label = 'Set Title';
	  }
    //Set table name field default value
    $defaults['table_name'] = 'civicrm_value_';
    $form->setDefaults($defaults);
  }
  if ($formName == 'CRM_Custom_Form_Field' && $form->getAction() == CRM_Core_Action::ADD) {
    $form->add('text', 'column_name', ts('Column Name'), '', TRUE);
    $form->add('text', 'name', ts('Field Machine Name'), '', TRUE);
    
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => "CRM/LCD/customgroupfield.tpl"
    ));
  }
}

/**
 * Implements hook_civicrm_validateForm().
 *
 * @param string $formName
 * @param array $fields
 * @param array $files
 * @param CRM_Core_Form $form
 * @param array $errors
 */
function customfieldnames_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  // Form validation for Custom Data Set
  if ($formName == 'CRM_Custom_Form_Group' && $form->getAction() == CRM_Core_Action::ADD) {
    //validation for Group table name
    $table_name = CRM_Utils_Array::value('table_name', $fields);
    if (CRM_Core_DAO::checkTableExists($table_name)) {
      $error_message= ts("Cannot create custom table because %1 already exists.", array('1' => $table_name));
      $form->setElementError('table_name', $error_message);
    }
    elseif (strpos($table_name, ' ') !== FALSE) {
      $error_message= ts("Invalid table name. Table name may not have spaces.");
      $form->setElementError('table_name', $error_message);
    }
    //validation for Group name
    $group_name = CRM_Utils_Array::value('name', $fields);
    $group_params['name'] = $group_name;
    $custom_group = civicrm_api3('CustomGroup', 'get', $group_params);
    if (isset($custom_group['id'])) {
      $error_message= ts("Cannot create custom group because %1 already exists.", array('1' => $group_name));
      $form->setElementError('name', $error_message);
    }
  }

  // Form validation for Custom fields for a Custom Data Set
  if($formName == 'CRM_Custom_Form_Field' && $form->getAction() == CRM_Core_Action::ADD) {
    $gid = $form->getVar('_gid');
    //validation for Field Column name
    $column_name = CRM_Utils_Array::value('column_name', $fields);
    $field_params = array(
      'custom_group_id' => $gid,
      'column_name' => $column_name,
    );
    $custom_field = civicrm_api3('CustomField', 'get', $field_params);
    if (isset($custom_field['id'])) {
      $error_message= ts("Cannot create custom column because %1 already exists.", array('1' => $column_name));
      $form->setElementError('column_name', $error_message);
    }
    elseif ( strpos($column_name, ' ') ) {
      $error_message= ts("Invalid column name. Column name should not have space in between words.");
      $form->setElementError('column_name', $error_message);
    }
    //validation for Field name
    $field_name = CRM_Utils_Array::value('name', $fields);
    $field_name_params = array(
      'custom_group_id' => $gid,
      'name' => $field_name,
    );
    $custom_name_field = civicrm_api3('CustomField', 'get', $field_name_params);
    if (isset($custom_name_field['id'])) {
      $error_message= ts("Cannot create custom field because %1 already exists.", array('1' => $field_name));
      $form->setElementError('name', $error_message);
    }
  }
  return;
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function customfieldnames_civicrm_postInstall() {
  _customfieldnames_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function customfieldnames_civicrm_entityTypes(&$entityTypes) {
  _customfieldnames_civix_civicrm_entityTypes($entityTypes);
}
