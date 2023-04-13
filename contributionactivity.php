<?php
/*-------------------------------------------------------+
| Contribution Activity Coupler                          |
| Copyright (C) 2016 SYSTOPIA                            |
| Author: B. Endres (endres@systopia.de)                 |
+--------------------------------------------------------+
| This program is released as free software under the    |
| Affero GPL license. You can redistribute it and/or     |
| modify it under the terms of this license which you    |
| can read by viewing the included agpl.txt or online    |
| at www.gnu.org/licenses/agpl.html. Removal of this     |
| copyright header is strictly prohibited without        |
| written permission from the original author(s).        |
+--------------------------------------------------------*/

require_once 'contributionactivity.civix.php';


/**
 * POST hook implementation to adjust a contribution's activity date
 * upon receive_date changes.
 */
function contributionactivity_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($op == 'edit' && $objectName == 'Contribution') {
    // find relevant activities...
    $activity_type_id = (int) CRM_Core_OptionGroup::getValue('activity_type', 'Contribution', 'name');
    $activities = civicrm_api3('Activity', 'get', array('activity_type_id' => $activity_type_id, 'source_record_id' => $objectId));

    // ... and update the date of all of them 
    foreach ($activities['values'] as $activity_id => $activity) {
      $update = array(
        'id'                 => $activity['id'],
        'activity_date_time' => date('Ymdhis', strtotime($objectRef->receive_date))
      );
      civicrm_api3('Activity', 'create', $update);
    }
  }
}

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function contributionactivity_civicrm_config(&$config) {
  _contributionactivity_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function contributionactivity_civicrm_install() {
  _contributionactivity_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function contributionactivity_civicrm_enable() {
  _contributionactivity_civix_civicrm_enable();
}
