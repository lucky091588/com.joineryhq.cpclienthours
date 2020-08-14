<?php

use CRM_Cpclienthours_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_Cpclienthours_Form_Addhours extends CRM_Core_Form {
  private $teamCid;
  private $clientCids = [];
  private $helpTypeCustomFieldId;

  public function buildQuickForm() {
    $this->teamCid = CRM_Utils_Request::retrieve('cid', 'Int', $this);

    // Build the list of options for '"Help Type" value field.n
    $this->helpTypeCustomFieldId = CRM_Core_BAO_CustomField::getCustomFieldId('Help_Type', 'Service_details');
    $helpTypeOptionGroupId = civicrm_api3('customField', 'getvalue', array(
      'id' => $this->helpTypeCustomFieldId,
      'return' => 'option_group_id',
    ));
    $helpTypeOptions = CRM_Core_BAO_OptionValue::getOptionValuesAssocArray($helpTypeOptionGroupId);

    // Get all individuals with current 'team/client' relationships, noting
    // Service Type for each.
    $relationshipTypeId = civicrm_api3('relationshipType', 'getvalue', array(
      'name_a_b' => 'Has_team_client',
      'return' => 'id',
    ));
    $relationships = civicrm_api3('relationship', 'get', array(
      'relationship_type_id' => $relationshipTypeId,
      'is_active' => 1,
      'contact_id_a' => $this->teamCid,
      'api.Contact.getSingle' => ['id' => "\$value.contact_id_b", 'return' => ["sort_name", "is_deceased"]],
      'options' => array(
        'limit' => 0,
      ),
    ));

    // For each relationship, add fields: "hours", "help type"
    // For each relationship, set default value for "hours" field based on Service Type.
    $rows = array();
    $sortRows = array();
    $defaultValues = array();

    // Adding default hours for certain teams
    $teamContact = civicrm_api3('contact', 'getSingle', array('id' => $this->teamCid));
    $teamNickName = $teamContact['nick_name'];
    $defaultHours = 0;
    $defaultHealthType = 'HC';

    // Default Hours and Help Type processing
    $firstChar = mb_substr($teamNickName, 0, 1);
    $firstTwoChar = mb_substr($teamNickName, 0, 2);
    if($firstChar === 'Z') {
      $defaultHours = 3.5;
    } else if ($firstTwoChar === 'CG') {
      $defaultHours = 1.5;
      $defaultHealthType = 'SE';
    }

    foreach ($relationships['values'] as $relationship) {
      if ($relationship['api.Contact.getSingle']['is_deceased']) {
        // Client is deceased; omit them.
        continue;
      }
      $clientCid = $relationship['api.Contact.getSingle']['id'];
      $this->clientCids[] = $clientCid;
      $row['clientCid'] = $clientCid;
      $row['sortName'] = $relationship['api.Contact.getSingle']['sort_name'];
      $row['hoursElementName'] = "hours_{$clientCid}";
      $row['helpTypeElementName'] = "helpType_{$clientCid}";
      $this->add(
        // field type
        'text',
        // field name
        $row['hoursElementName'] ,
        // field label
        ts('Hours'),
        // is required
        TRUE
      );
      $this->add(
        'select',
        $row['helpTypeElementName'],
        ts('Help Type'),
        $helpTypeOptions,
        TRUE
      );

      $defaultValues[$row['hoursElementName']] = $defaultHours;
      $defaultValues[$row['helpTypeElementName']] = $defaultHealthType;
      $sortRows[] = $row['sortName'];
      $rows[] = $row;

    }
    // Sort rows by sort_name.
    array_multisort($sortRows, $rows);
    $this->assign('rows', $rows);


    // Add "service date" field, defaulting to current date.
    $attributes = array(
      // this css class prevents the datepicker from being autofocused on popup load
      'class' => 'dateplugin'
    );
    $this->add('datepicker', 'service_date', ts('Service date'), $attributes, TRUE, ['time' => FALSE]);
    $defaultValues['service_date'] = CRM_Utils_Date::getToday();

    $this->addButtons(array(
      array(
        'type' => 'submit',
        'name' => E::ts('Submit'),
        'isDefault' => TRUE,
      ),
      array(
        'type' => 'cancel',
        'name' => E::ts('Cancel'),
      ),
    ));

    $this->setDefaults($defaultValues);

    $this->assign('team', $teamContact);

    CRM_Core_Resources::singleton()->addScriptFile('com.joineryhq.cpclienthours', 'js/CRM_Cpclienthours_Form_Addhours.js', 1000, 'page-footer');
    parent::buildQuickForm();
  }

  public function postProcess() {
    $values = $this->exportValues();
    $userCid = CRM_Core_Session::getLoggedInContactID();
    $isLegacyCustomFieldId = CRM_Core_BAO_CustomField::getCustomFieldId('Is_legacy', 'Service_details');
    foreach ($this->clientCids as $clientCid) {
      $hours = CRM_Utils_Array::value("hours_{$clientCid}", $values, 0);
      if (!$hours) {
        // No hours recorded, so skip it.
        continue;
      }
      // If we're still here, create a Service Hours activity.
      $apiParams = array(
        "duration" => (60 * $hours),
        "custom_{$this->helpTypeCustomFieldId}" => $values["helpType_{$clientCid}"],
        'source_contact_id' => $userCid,
        'target_id' => $this->teamCid,
        'assignee_id' => $clientCid,
        'activity_date_time' => $values['service_date'],
        'activity_type_id' => 'Service hours',
        'subject' => ts('Team service hours (batch entry)'),
        "custom_{$isLegacyCustomFieldId}" => 0,
      );
      $activity = civicrm_api3('activity', 'create', $apiParams);
    }
    parent::postProcess();
  }

  /**
   * Get the fields/elements defined in this form.
   *
   * @return array (string)
   */
  public function getRenderableElementNames() {
    // The _elements list includes some items which should not be
    // auto-rendered in the loop -- such as "qfKey" and "buttons".  These
    // items don't have labels.  We'll identify renderable by filtering on
    // the 'label'.
    $elementNames = array();
    foreach ($this->_elements as $element) {
      /** @var HTML_QuickForm_Element $element */
      $label = $element->getLabel();
      if (!empty($label)) {
        $elementNames[] = $element->getName();
      }
    }
    return $elementNames;
  }

}
