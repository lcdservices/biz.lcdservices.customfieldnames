<?php

// AUTO-GENERATED FILE -- Civix may overwrite any changes made to this file

/**
 * (Delegated) Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function _customfieldnames_civix_civicrm_config(&$config = NULL) {
  static $configured = FALSE;
  if ($configured) {
    return;
  }
  $configured = TRUE;

  $template =& CRM_Core_Smarty::singleton();

  $extRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
  $extDir = $extRoot . 'templates';

  if ( is_array( $template->template_dir ) ) {
      array_unshift( $template->template_dir, $extDir );
  }
  else {
      $template->template_dir = array( $extDir, $template->template_dir );
  }

  $include_path = $extRoot . PATH_SEPARATOR . get_include_path( );
  set_include_path($include_path);
}

/**
 * (Delegated) Implements hook_civicrm_xmlMenu().
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function _customfieldnames_civix_civicrm_xmlMenu(&$files) {
  foreach (_customfieldnames_civix_glob(__DIR__ . '/xml/Menu/*.xml') as $file) {
    $files[] = $file;
  }
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function _customfieldnames_civix_civicrm_install() {
  _customfieldnames_civix_civicrm_config();
  if ($upgrader = _customfieldnames_civix_upgrader()) {
    $upgrader->onInstall();
  }
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function _customfieldnames_civix_civicrm_uninstall() {
  _customfieldnames_civix_civicrm_config();
  if ($upgrader = _customfieldnames_civix_upgrader()) {
    $upgrader->onUninstall();
  }
}

/**
 * (Delegated) Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function _customfieldnames_civix_civicrm_enable() {
  _customfieldnames_civix_civicrm_config();
  if ($upgrader = _customfieldnames_civix_upgrader()) {
    if (is_callable(array($upgrader, 'onEnable'))) {
      $upgrader->onEnable();
    }
  }
}

/**
 * (Delegated) Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 * @return mixed
 */
function _customfieldnames_civix_civicrm_disable() {
  _customfieldnames_civix_civicrm_config();
  if ($upgrader = _customfieldnames_civix_upgrader()) {
    if (is_callable(array($upgrader, 'onDisable'))) {
      $upgrader->onDisable();
    }
  }
}

/**
 * (Delegated) Implements hook_civicrm_upgrade().
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function _customfieldnames_civix_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  if ($upgrader = _customfieldnames_civix_upgrader()) {
    return $upgrader->onUpgrade($op, $queue);
  }
}

/**
 * @return CRM_Customfieldnames_Upgrader
 */
function _customfieldnames_civix_upgrader() {
  if (!file_exists(__DIR__.'/CRM/Customfieldnames/Upgrader.php')) {
    return NULL;
  }
  else {
    return CRM_Customfieldnames_Upgrader_Base::instance();
  }
}

/**
 * Search directory tree for files which match a glob pattern
 *
 * Note: Dot-directories (like "..", ".git", or ".svn") will be ignored.
 * Note: In Civi 4.3+, delegate to CRM_Utils_File::findFiles()
 *
 * @param $dir string, base dir
 * @param $pattern string, glob pattern, eg "*.txt"
 * @return array(string)
 */
function _customfieldnames_civix_find_files($dir, $pattern) {
  if (is_callable(array('CRM_Utils_File', 'findFiles'))) {
    return CRM_Utils_File::findFiles($dir, $pattern);
  }

  $todos = array($dir);
  $result = array();
  while (!empty($todos)) {
    $subdir = array_shift($todos);
    foreach (_customfieldnames_civix_glob("$subdir/$pattern") as $match) {
      if (!is_dir($match)) {
        $result[] = $match;
      }
    }
    if ($dh = opendir($subdir)) {
      while (FALSE !== ($entry = readdir($dh))) {
        $path = $subdir . DIRECTORY_SEPARATOR . $entry;
        if ($entry{0} == '.') {
        } elseif (is_dir($path)) {
          $todos[] = $path;
        }
      }
      closedir($dh);
    }
  }
  return $result;
}
/**
 * (Delegated) Implements hook_civicrm_managed().
 *
 * Find any *.mgd.php files, merge their content, and return.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function _customfieldnames_civix_civicrm_managed(&$entities) {
  $mgdFiles = _customfieldnames_civix_find_files(__DIR__, '*.mgd.php');
  foreach ($mgdFiles as $file) {
    $es = include $file;
    foreach ($es as $e) {
      if (empty($e['module'])) {
        $e['module'] = 'biz.lcdservices.customfieldnames';
      }
      $entities[] = $e;
    }
  }
}

/**
 * (Delegated) Implements hook_civicrm_caseTypes().
 *
 * Find any and return any files matching "xml/case/*.xml"
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function _customfieldnames_civix_civicrm_caseTypes(&$caseTypes) {
  if (!is_dir(__DIR__ . '/xml/case')) {
    return;
  }

  foreach (_customfieldnames_civix_glob(__DIR__ . '/xml/case/*.xml') as $file) {
    $name = preg_replace('/\.xml$/', '', basename($file));
    if ($name != CRM_Case_XMLProcessor::mungeCaseType($name)) {
      $errorMessage = sprintf("Case-type file name is malformed (%s vs %s)", $name, CRM_Case_XMLProcessor::mungeCaseType($name));
      CRM_Core_Error::fatal($errorMessage);
      // throw new CRM_Core_Exception($errorMessage);
    }
    $caseTypes[$name] = array(
      'module' => 'biz.lcdservices.customfieldnames',
      'name' => $name,
      'file' => $file,
    );
  }
}

/**
 * (Delegated) Implements hook_civicrm_angularModules().
 *
 * Find any and return any files matching "ang/*.ang.php"
 *
 * Note: This hook only runs in CiviCRM 4.5+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function _customfieldnames_civix_civicrm_angularModules(&$angularModules) {
  if (!is_dir(__DIR__ . '/ang')) {
    return;
  }

  $files = _customfieldnames_civix_glob(__DIR__ . '/ang/*.ang.php');
  foreach ($files as $file) {
    $name = preg_replace(':\.ang\.php$:', '', basename($file));
    $module = include $file;
    if (empty($module['ext'])) {
      $module['ext'] = 'biz.lcdservices.customfieldnames';
    }
    $angularModules[$name] = $module;
  }
}

/**
 * Glob wrapper which is guaranteed to return an array.
 *
 * The documentation for glob() says, "On some systems it is impossible to
 * distinguish between empty match and an error." Anecdotally, the return
 * result for an empty match is sometimes array() and sometimes FALSE.
 * This wrapper provides consistency.
 *
 * @link http://php.net/glob
 * @param string $pattern
 * @return array, possibly empty
 */
function _customfieldnames_civix_glob($pattern) {
  $result = glob($pattern);
  return is_array($result) ? $result : array();
}

/**
 * Inserts a navigation menu item at a given place in the hierarchy.
 *
 * @param array $menu - menu hierarchy
 * @param string $path - path where insertion should happen (ie. Administer/System Settings)
 * @param array $item - menu you need to insert (parent/child attributes will be filled for you)
 */
function _customfieldnames_civix_insert_navigation_menu(&$menu, $path, $item) {
  // If we are done going down the path, insert menu
  if (empty($path)) {
    $menu[] = array(
      'attributes' => array_merge(array(
        'label'      => CRM_Utils_Array::value('name', $item),
        'active'     => 1,
      ), $item),
    );
    return TRUE;
  }
  else {
    // Find an recurse into the next level down
    $found = false;
    $path = explode('/', $path);
    $first = array_shift($path);
    foreach ($menu as $key => &$entry) {
      if ($entry['attributes']['name'] == $first) {
        if (!$entry['child']) $entry['child'] = array();
        $found = _customfieldnames_civix_insert_navigation_menu($entry['child'], implode('/', $path), $item, $key);
      }
    }
    return $found;
  }
}

/**
 * (Delegated) Implements hook_civicrm_navigationMenu().
 */
function _customfieldnames_civix_navigationMenu(&$nodes) {
  if (!is_callable(array('CRM_Core_BAO_Navigation', 'fixNavigationMenu'))) {
    _customfieldnames_civix_fixNavigationMenu($nodes);
  }
}

/**
 * Given a navigation menu, generate navIDs for any items which are
 * missing them.
 */
function _customfieldnames_civix_fixNavigationMenu(&$nodes) {
  $maxNavID = 1;
  array_walk_recursive($nodes, function($item, $key) use (&$maxNavID) {
    if ($key === 'navID') {
      $maxNavID = max($maxNavID, $item);
    }
    });
  _customfieldnames_civix_fixNavigationMenuItems($nodes, $maxNavID, NULL);
}

function _customfieldnames_civix_fixNavigationMenuItems(&$nodes, &$maxNavID, $parentID) {
  $origKeys = array_keys($nodes);
  foreach ($origKeys as $origKey) {
    if (!isset($nodes[$origKey]['attributes']['parentID']) && $parentID !== NULL) {
      $nodes[$origKey]['attributes']['parentID'] = $parentID;
    }
    // If no navID, then assign navID and fix key.
    if (!isset($nodes[$origKey]['attributes']['navID'])) {
      $newKey = ++$maxNavID;
      $nodes[$origKey]['attributes']['navID'] = $newKey;
      $nodes[$newKey] = $nodes[$origKey];
      unset($nodes[$origKey]);
      $origKey = $newKey;
    }
    if (isset($nodes[$origKey]['child']) && is_array($nodes[$origKey]['child'])) {
      _customfieldnames_civix_fixNavigationMenuItems($nodes[$origKey]['child'], $maxNavID, $nodes[$origKey]['attributes']['navID']);
    }
  }
}

/**
 * (Delegated) Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function _customfieldnames_civix_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  static $configured = FALSE;
  if ($configured) {
    return;
  }
  $configured = TRUE;

  $settingsDir = __DIR__ . DIRECTORY_SEPARATOR . 'settings';
  if(is_dir($settingsDir) && !in_array($settingsDir, $metaDataFolders)) {
    $metaDataFolders[] = $settingsDir;
  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @param string $formName
 * @param CRM_Core_Form $form
 */
function _customfieldnames_civix_civicrm_buildForm($formName, &$form) {
  $templatePath = realpath(dirname(__FILE__)."/templates");
  if( ($formName == 'CRM_Custom_Form_Group') && ($form->getAction() == CRM_Core_Action::ADD) ) {
    $templatePath = realpath(dirname(__FILE__)."/templates");
    $form->add('text', 'table_name', ts('Set Table Name'), '', FALSE);
    $form->assign('table_name', TRUE);
    $form->add('text', 'name', ts('Set Group Name'), '', FALSE);
    $form->assign('name', TRUE);
    
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => "{$templatePath}/customgroup.tpl"
    ));
  }
  if ( ($formName == 'CRM_Custom_Form_Field') && ($form->getAction() == CRM_Core_Action::ADD) ) {
    $form->add('text', 'column_name', ts('Set Column Name'), '', FALSE);
    $form->assign('column_name', TRUE);
    
    $form->add('text', 'name', ts('Set Field Name'), '', FALSE);
    $form->assign('name', TRUE);
    
    CRM_Core_Region::instance('page-body')->add(array(
      'template' => "{$templatePath}/customgroupfield.tpl"
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
function _customfieldnames_civix_civicrm_validateForm($formName, &$fields, &$files, &$form, &$errors) {
  // Form validation for Custom Data Set
  if ($formName == 'CRM_Custom_Form_Group' && ($form->getAction() == CRM_Core_Action::ADD) ) {
    //validation for Group table name
    $table_name = CRM_Utils_Array::value( 'table_name', $fields );
    if (! $table_name ) {
      $error_message = ts( 'Set Table Name is a required field' );
      $form->setElementError('table_name', $error_message);
    }
    elseif (CRM_Core_DAO::checkTableExists($table_name)) {
      $error_message= ts("Cannot create custom table because %1 is already exists.", array('1' => $table_name));
      $form->setElementError('table_name', $error_message);
    }
    elseif (strpos($table_name, ' ')) {
      $error_message= ts("Invalid table name. Table name should not have space in between words.");
      $form->setElementError('table_name', $error_message);
    }
    //validation for Group name
    $group_name = CRM_Utils_Array::value( 'name', $fields );
    if (! $group_name ) {
      $error_message = ts( 'Set Group Name is a required field' );
      $form->setElementError('name', $error_message);
    }
  }
  // Form validation for Custom fields for a Custom Data Set
  if($formName == 'CRM_Custom_Form_Field' && ($form->getAction() == CRM_Core_Action::ADD) ) {
    $gid = $form->getVar( '_gid' );
    //validation for Field Column name
    $column_name = CRM_Utils_Array::value( 'column_name', $fields );
    if (! $column_name ) {
      $error_message = ts( 'Set Column Name is a required field' );
      $form->setElementError('column_name', $error_message);
    }
    elseif ( ($field_id = _customfieldnames_civix_civicrm_get_custom_field_id($gid, $column_name)) > 0 ) {
      $error_message= ts("Cannot create custom field because %1 is already exists.", array('1' => $column_name));
      $form->setElementError('column_name', $error_message);
    }
    //validation for Field name
    $field_name = CRM_Utils_Array::value( 'name', $fields );
    if (! $field_name ) {
      $error_message = ts( 'Set Group Name is a required field' );
      $form->setElementError('name', $error_message);
    }
  }
  return;
}
/**
 * See if a CiviCRM custom field exists
 *
 * @param integer $custom_group_id
 *   custom group id that the field is expected to belong to
 * @param string $column_name
 *   custom field name to look for, corresponds to field civicrm_custom_field.column_name
 * @return integer
 *   custom field id if it exists, else zero
 */
function _customfieldnames_civix_civicrm_get_custom_field_id($custom_group_id, $field_name) {
  $result = 0;
  $diff = array();
  $data = array(
    'custom_group_id' => $custom_group_id,
    'column_name' => $field_name,
  );
  $field_value = CRM_Core_BAO_CustomField::retrieve($data, $diff);
  if( isset($field_value) ) {
    $result = $field_value->id;
  }
  return $result;
}