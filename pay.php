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
 * @copyright 2022 Michael David <mikedh2612@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require_once(__DIR__ . '/../../../config.php');
require_login();

global $CFG, $USER, $DB;

$component   = required_param('component', PARAM_ALPHANUMEXT);
$paymentarea = required_param('paymentarea', PARAM_ALPHANUMEXT);
$itemid      = required_param('itemid', PARAM_INT);
$description = required_param('description', PARAM_TEXT);

$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'payanyway');
$payable = helper::get_payable($component, $paymentarea, $itemid);// Get currency and payment amount.
$currency = $payable->get_currency();
$surcharge = helper::get_gateway_surcharge('payanyway');// In case user uses surcharge.

// TODO: Check if currency is IDR. If not, then something went really wrong in config.
$cost = helper::get_rounded_cost($payable->get_amount(), $payable->get_currency(), $surcharge);

// write tx to db
$paygwdata = new stdClass();
$paygwdata->userid = $USER->id;
$paygwdata->component = $component;
$paygwdata->paymentarea = $paymentarea;
$paygwdata->itemid = $itemid;
$paygwdata->description = $description;
$paygwdata->timestamp = time();

echo serialize($paygwdata)."<br>";

die;

if (!$transaction_id = $DB->insert_record('paygw_payanyway', $paygwdata)) {
    print_error('error_txdatabase', 'paygw_payanyway');
}

// make hash
$mntsignature = md5($config->mntid.$transaction_id.$cost.$currency.$config->mnttestmode.$config->mntdataintegritycode);

$paymenturl = "https://".$config->paymentserver."/assistant.htm?";

$additionalparams = "";
foreach($_REQUEST as $key=>$value)
{
        if (strpos($key, "additionalParameters") !== false || strpos($key, "paymentSystem") !== false)
        {
                $key = str_replace("_", ".", $key);
                $additionalparams .= "&{$key}={$value}";
        }
}

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

redirect($paymenturl."
	MNT_ID={$config->mntid}&
	MNT_TRANSACTION_ID={$transaction_id}&
	MNT_CURRENCY_CODE={$currency}&
	MNT_AMOUNT={$cost}&
	MNT_SIGNATURE={$mntsignature}&
	MNT_SUCCESS_URL=".urlencode($CFG->wwwroot."/payment/gateway/payanyway/return.php?id=".$id)."&
	MNT_FAIL_URL=".urlencode($CFG->wwwroot."/payment/gateway/payanyway/return.php?id=".$id)."&
	MNT_CUSTOM1=".urlencode($course->shortname)."&
	MNT_CUSTOM2=".urlencode(fullname($USER))."&
	MNT_CUSTOM3=".urlencode($USER->email)."&
	MNT_DESCRIPTION=".urlencode($course->fullname)."&
	pawcmstype=moodle&
	followup=true&
	javascriptEnabled=true&
	id={$id}&
	paymentsystem={$paymentsystem[0]}
        {$paymentsystemparams}
        {$additionalparams}
");
