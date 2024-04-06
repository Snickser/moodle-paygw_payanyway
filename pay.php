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
 * @package   paygw_payanyway
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require_once(__DIR__ . '/../../../config.php');
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

// TODO: Check if currency is IDR. If not, then something went really wrong in config.
$cost = helper::get_rounded_cost($payable->get_amount(), $payable->get_currency(), $surcharge);

// check self cost
if ( !empty($costself) ) {
    $cost = $costself;
}
// check maxcost
if ( $config->maxcost && $cost > $config->maxcost ) {
    $cost = $config->maxcost;
}
$cost = number_format($cost, 2, '.', '');

// get course and groups for user
if( $paymentarea == "fee" ){
    $cs = $DB->get_record('enrol', ['id' => $itemid]);
    $cs->course = $cs->courseid;
} else if( $paymentarea == "cmfee" ) {
    $cs = $DB->get_record('course_modules', ['id' => $itemid]);
} else if( $paymentarea == "sectionfee" ) {
    $cs = $DB->get_record('course_sections', ['id' => $itemid]);
} else if( $paymentarea == "unlockfee" ) {
    $cs = $DB->get_record('gwpayments', ['id' => $itemid]);
}
$group_names = '';
$courseid = '';
if( $cs->course ){
    $courseid = $cs->course;
    if ($gs = groups_get_all_groups($cs->course, $userid)){
        foreach($gs as $g){
	    $groups[] = $g->name;
        }
        $group_names = implode(',', $groups);
    }
}

// write tx to db
$paygwdata = new stdClass();
$paygwdata->userid = $userid;
$paygwdata->component = $component;
$paygwdata->paymentarea = $paymentarea;
$paygwdata->itemid = $itemid;
$paygwdata->cost = $cost;
$paygwdata->currency = $currency;
$paygwdata->date_created = date("Y-m-d H:i:s");
$paygwdata->courseid = $courseid;
$paygwdata->group_names = $group_names;

if (!$transaction_id = $DB->insert_record('paygw_payanyway', $paygwdata)) {
    print_error('error_txdatabase', 'paygw_payanyway');
}

// password mode
if ( !empty($password) || !empty($skipmode) ){
    // build redirect
    $url = helper::get_success_url($component, $paymentarea, $itemid);

    if(isset($skipmode)) $password = $config->password;
    // check password
    if($password === $config->password){
        // make fake pay
	$cost = 0;
        $paymentid = helper::save_payment($payable->get_account_id(), $component, $paymentarea, $itemid, $userid, $cost, $payable->get_currency(), 'robokassa');
        helper::deliver_order($component, $paymentarea, $itemid, $paymentid, $userid);

        // write to DB
        $data = new stdClass();
        $data->id = $transaction_id;
        $data->success = 2;
        $data->cost = 0;
        $DB->update_record('paygw_payanyway', $data);

	redirect($url, get_string('password_success', 'paygw_payanyway'), 0, 'success');
    } else {
	redirect($url, get_string('password_error', 'paygw_payanyway'), 0, 'error');
    }
    die; // never
}


// make signature
$mntsignature = md5($config->mntid.$transaction_id.$cost.$currency.$USER->username.$config->mnttestmode.$config->mntdataintegritycode);

$paymenturl = "https://".$config->paymentserver."/assistant.htm?";

$paymentsystem = explode('_', $config->paymentsystem);
$paymentsystemparams = "";
if (!empty($paymentsystem[2]))
{
    $paymentsystemparams .= "paymentSystem.unitId={$paymentsystem[2]}&";
}
if (isset($paymentsystem[3]) && !empty($paymentsystem[3]))
{
    $paymentsystemparams .= "paymentSystem.accountId={$paymentsystem[3]}&";
}

$ReturnURL = helper::get_success_url($component, $paymentarea, $itemid);
$SuccessURL = $CFG->wwwroot."/payment/gateway/payanyway/return.php";
$FailURL = $SuccessURL;

redirect($paymenturl."
	MNT_ID={$config->mntid}&
	MNT_TRANSACTION_ID={$transaction_id}&
	MNT_CURRENCY_CODE={$currency}&
	MNT_AMOUNT={$cost}&
	MNT_SUBSCRIBER_ID=".urlencode($USER->username)."&
	MNT_TEST_MODE={$config->mnttestmode}&
	MNT_SIGNATURE={$mntsignature}&
	MNT_SUCCESS_URL=".urlencode($SuccessURL)."&
	MNT_FAIL_URL=".urlencode($FailURL)."&
	MNT_RETURN_URL=".urlencode($ReturnURL)."&
	MNT_CUSTOM1=".urlencode($component.":".$paymentarea.":".$itemid)."&
	MNT_CUSTOM2=".urlencode(fullname($USER))."&
	MNT_CUSTOM3=".urlencode($USER->email)."&
	MNT_DESCRIPTION=".get_string('payment','paygw_payanyway')."&
	pawcmstype=moodle&
	moneta.locale=".current_language()."&
	followup=true&
	{$paymentsystemparams}
");
