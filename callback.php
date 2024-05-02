<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     paygw_payanyway
 * @copyright   2024 Alex Orlov <snickser@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require("../../../config.php");

global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

$transactionid = required_param('MNT_TRANSACTION_ID', PARAM_TEXT);
$operationid   = required_param('MNT_OPERATION_ID', PARAM_TEXT);
$subscriberid  = required_param('MNT_SUBSCRIBER_ID', PARAM_TEXT);
$signature     = required_param('MNT_SIGNATURE', PARAM_TEXT);

if (!$payanywaytx = $DB->get_record('paygw_payanyway', ['paymentid' => $transactionid])) {
    die('FAIL. Not a valid transaction id');
}

if (!$payment = $DB->get_record('payments', ['id' => $payanywaytx->paymentid])) {
    die('FAIL. Not a valid payment.');
}
$component   = $payment->component;
$paymentarea = $payment->paymentarea;
$itemid      = $payment->itemid;
$paymentid   = $payment->id;
$userid      = $payment->userid;

// Get config.
$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'payanyway');
$payable = helper::get_payable($component, $paymentarea, $itemid);

// Use the same rounding of floats as on the paygw form.
$cost = number_format($payment->amount, 2, '.', '');

// Build crc.
$crc = md5($config->mntid . $paymentid . $operationid . $cost . $payment->currency . $subscriberid .
           $config->mnttestmode . $config->mntdataintegritycode);

if ($crc !== $signature) {
    die('FAIL. Signature does not match.');
}

// Deliver.
helper::deliver_order($component, $paymentarea, $itemid, $paymentid, $userid);

// Write to DB.
$payanywaytx->success = 1;
if (!$DB->update_record('paygw_payanyway', $payanywaytx)) {
    die('FAIL. Update db error.');
} else {
    die('SUCCESS');
}
