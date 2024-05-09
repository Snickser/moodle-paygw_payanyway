<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Redirects user to the payment page
 *
 * @package     paygw_payanyway
 * @copyright   2024 Alex Orlov <snickser@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require_once(__DIR__ . '/../../../config.php');
require_once($CFG->libdir . '/filelib.php');

require_login();

global $CFG, $USER, $DB;

$userid = $USER->id;


$component   = required_param('component', PARAM_ALPHANUMEXT);
$paymentarea = required_param('paymentarea', PARAM_ALPHANUMEXT);
$itemid      = required_param('itemid', PARAM_INT);
$description = required_param('description', PARAM_TEXT);

$password    = optional_param('password', null, PARAM_TEXT);
$skipmode    = optional_param('skipmode', null, PARAM_TEXT);
$costself    = optional_param('costself', null, PARAM_TEXT);

$description = json_decode("\"$description\"");

$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'payanyway');
$payable = helper::get_payable($component, $paymentarea, $itemid);// Get currency and payment amount.
$currency = $payable->get_currency();
$surcharge = helper::get_gateway_surcharge('payanyway');// In case user uses surcharge.

$cost = helper::get_rounded_cost($payable->get_amount(), $payable->get_currency(), $surcharge);

// Check self cost.
if (!empty($costself)) {
    $cost = $costself;
}
// Check maxcost.
if ($config->maxcost && $cost > $config->maxcost) {
    $cost = $config->maxcost;
}
$cost = number_format($cost, 2, '.', '');

// Get course and groups for user.
if ($component == "enrol_fee") {
    $cs = $DB->get_record('enrol', ['id' => $itemid]);
    $cs->course = $cs->courseid;
} else if ($paymentarea == "cmfee") {
    $cs = $DB->get_record('course_modules', ['id' => $itemid]);
} else if ($paymentarea == "sectionfee") {
    $cs = $DB->get_record('course_sections', ['id' => $itemid]);
} else if ($component == "mod_gwpayments") {
    $cs = $DB->get_record('gwpayments', ['id' => $itemid]);
}
$groupnames = '';
if (!empty($cs->course)) {
    $courseid = $cs->course;
    if ($gs = groups_get_user_groups($courseid, $userid, true)) {
        foreach ($gs as $gr) {
            foreach ($gr as $g) {
                $groups[] = groups_get_group_name($g);
            }
        }
        if (isset($groups)) {
            $groupnames = implode(',', $groups);
        }
    }
} else {
    $courseid = '';
}

// Write tx to DB.
$paygwdata = new stdClass();
$paygwdata->courseid = $courseid;
$paygwdata->groupnames = $groupnames;

if (!$transactionid = $DB->insert_record('paygw_payanyway', $paygwdata)) {
    die(get_string('error_txdatabase', 'paygw_robokassa'));
}
$paygwdata->id = $transactionid;

// Build redirect.
$url = helper::get_success_url($component, $paymentarea, $itemid);

// Check passwordmode or skipmode.
if (!empty($password) || $skipmode) {
    $success = false;
    if ($config->skipmode) {
        $success = true;
    } else if (isset($cs->password) && !empty($cs->password)) {
        // Check module password.
        if ($password === $cs->password) {
            $success = true;
        }
    } else if ($config->passwordmode && !empty($config->password)) {
        // Check payment password.
        if ($password === $config->password) {
            $success = true;
        }
    }

    if ($success) {
        // Make fake pay.
        $paymentid = helper::save_payment(
            $payable->get_account_id(),
            $component,
            $paymentarea,
            $itemid,
            $userid,
            0,
            $payable->get_currency(),
            'payanyway'
        );
        helper::deliver_order($component, $paymentarea, $itemid, $paymentid, $userid);

        // Write to DB.
        $paygwdata->success = 2;
        $paygwdata->paymentid = $paymentid;
        $DB->update_record('paygw_payanyway', $paygwdata);

        redirect($url, get_string('password_success', 'paygw_payanyway'), 0, 'success');
    } else {
        redirect($url, get_string('password_error', 'paygw_payanyway'), 0, 'error');
    }
    die; // Never.
}


// Save payment.
$paymentid = helper::save_payment(
    $payable->get_account_id(),
    $component,
    $paymentarea,
    $itemid,
    $userid,
    $cost,
    $payable->get_currency(),
    'payanyway'
);

// Make signature.
$mntsignature = md5($config->mntid . $paymentid . $cost . $currency . $USER->username .
                    $config->mnttestmode . $config->mntdataintegritycode);

if (!empty($config->paymentsystem)) {
    $paymentsystem = '&paymentSystem.unitId=' . $config->paymentsystem;
} else {
    $paymentsystem = '';
}

$successurl = $CFG->wwwroot . "/payment/gateway/payanyway/return.php";

// Write to DB.
$paygwdata->paymentid = $paymentid;
$DB->update_record('paygw_payanyway', $paygwdata);

$paymenturl = "https://" . $config->paymentserver . "/assistant.htm?";

redirect($paymenturl .
"MNT_ID=$config->mntid" .
"&MNT_TRANSACTION_ID=$paymentid" .
"&MNT_AMOUNT=$cost" .
"&MNT_CURRENCY_CODE=$currency" .
"&MNT_SUBSCRIBER_ID=" . urlencode($USER->username) .
"&MNT_TEST_MODE=$config->mnttestmode" .
"&MNT_SIGNATURE=$mntsignature" .
"&MNT_SUCCESS_URL=" . urlencode($successurl) .
"&MNT_FAIL_URL=" . urlencode($successurl) .
"&MNT_RETURN_URL=" . urlencode($url) .
"&MNT_DESCRIPTION=" . urlencode($description) .
"&moneta.locale=" . current_language() .
"&followup=true" . $paymentsystem);
