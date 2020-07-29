<?php

require_once 'cpclienthours.civix.php';
// phpcs:disable
use CRM_Cpclienthours_ExtensionUtil as E;
// phpcs:enable

/**
 * Implements hook_civicrm_links().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_links/
 */
function cpclienthours_civicrm_links($op, $objectName, $objectId, &$links, &$mask, &$values) {
  if (
    $op == 'view.contact.activity'
    && $objectName == 'Contact'
  ) {
    $contact = civicrm_api3('contact', 'getsingle', array(
      'id' => $objectId,
    ));

    if (in_array('Team', $contact['contact_sub_type'])) {
      $links[] = array(
        'name' => ts('Batch Client Hours'),
        'url' => CRM_Utils_System::url('civicrm/cpclienthours/addhours', 'reset=1&cid=' . $objectId, NULL, NULL, NULL, NULL, TRUE),
        'title' => 'Enter Client Hours',
        'class' => 'crm-i fa-clock-o',
      );
    }

  }
}

/**
 * Implements hook_civicrm_pageRun().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_pageRun/
 */
function cpclienthours_civicrm_pageRun(&$page) {
  $pageName = $page->getVar('_name');
  if (
    $pageName == 'CRM_Admin_Page_Options'
    && $page::$_gName == 'cpclienthours_hours_per_role'
  ) {
    // Add JS to manipulate the page.
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cpclienthours', 'js/CRM_Admin_Page_Options.js');
  }
}

/**
 * Implements hook_civicrm_buildForm().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_buildForm/
 */
function cpclienthours_civicrm_buildForm($formName, &$form) {
  if (
    $formName == 'CRM_Admin_Form_Options'
    && $form->getVar('_gName') == 'cpclienthours_hours_per_role'
    && (
      $form->_action == CRM_Core_Action::BROWSE
      || $form->_action == CRM_Core_Action::ADD
      || $form->_action == CRM_Core_Action::UPDATE
    )
  ) {

    // Replace the 'description' textarea with a 'description' text input,
    // labeled 'Hours', and required,
    $form->removeElement('description');
    $form->add('text', 'description', E::ts('Hours'), array(), TRUE);

    // Add js and css.
    CRM_Core_Resources::singleton()->addVars('cpclienthours', $vars);
    CRM_Core_Resources::singleton()->addStyleFile('com.joineryhq.cpclienthours', 'css/CRM_Admin_Form_Options.css');
    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cpclienthours', 'js/CRM_Admin_Form_Options.js');

    // Modify some elements directly.
    $form->getElement('is_active')->freeze();
    $form->getElement('weight')->freeze();
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_config/
 */
function cpclienthours_civicrm_config(&$config) {
  _cpclienthours_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_xmlMenu
 */
function cpclienthours_civicrm_xmlMenu(&$files) {
  _cpclienthours_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_install
 */
function cpclienthours_civicrm_install() {
  _cpclienthours_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_postInstall
 */
function cpclienthours_civicrm_postInstall() {
  _cpclienthours_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_uninstall
 */
function cpclienthours_civicrm_uninstall() {
  _cpclienthours_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_enable
 */
function cpclienthours_civicrm_enable() {
  _cpclienthours_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_disable
 */
function cpclienthours_civicrm_disable() {
  _cpclienthours_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_upgrade
 */
function cpclienthours_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _cpclienthours_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_managed
 */
function cpclienthours_civicrm_managed(&$entities) {
  _cpclienthours_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_caseTypes
 */
function cpclienthours_civicrm_caseTypes(&$caseTypes) {
  _cpclienthours_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_angularModules
 */
function cpclienthours_civicrm_angularModules(&$angularModules) {
  _cpclienthours_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_alterSettingsFolders
 */
function cpclienthours_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _cpclienthours_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Implements hook_civicrm_entityTypes().
 *
 * Declare entity types provided by this module.
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_entityTypes
 */
function cpclienthours_civicrm_entityTypes(&$entityTypes) {
  _cpclienthours_civix_civicrm_entityTypes($entityTypes);
}

/**
 * Implements hook_civicrm_thems().
 */
function cpclienthours_civicrm_themes(&$themes) {
  _cpclienthours_civix_civicrm_themes($themes);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_preProcess
 */
//function cpclienthours_civicrm_preProcess($formName, &$form) {
//
//}

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link https://docs.civicrm.org/dev/en/latest/hooks/hook_civicrm_navigationMenu
 */
//function cpclienthours_civicrm_navigationMenu(&$menu) {
//  _cpclienthours_civix_insert_navigation_menu($menu, 'Mailings', array(
//    'label' => E::ts('New subliminal message'),
//    'name' => 'mailing_subliminal_message',
//    'url' => 'civicrm/mailing/subliminal',
//    'permission' => 'access CiviMail',
//    'operator' => 'OR',
//    'separator' => 0,
//  ));
//  _cpclienthours_civix_navigationMenu($menu);
//}
