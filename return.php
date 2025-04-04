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
 * Redirects user to the original page
 *
 * @package   paygw_payanyway
 * @copyright 2024 Alex Orlov <snickser@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;

require("../../../config.php");
global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

require_login();

$id = required_param('MNT_TRANSACTION_ID', PARAM_INT);

if (!$payanywaytx = $DB->get_record('paygw_payanyway', ['paymentid' => $id])) {
    throw new \moodle_exception(get_string('error_notvalidtxid', 'paygw_payanyway'), 'paygw_payanyway');
}

if (!$payment = $DB->get_record('payments', ['id' => $payanywaytx->paymentid])) {
    throw new \moodle_exception(get_string('error_notvalidpayment', 'paygw_payanyway'), 'paygw_payanyway');
}

$paymentarea = $payment->paymentarea;
$component   = $payment->component;
$itemid      = $payment->itemid;

$url = helper::get_success_url($component, $paymentarea, $itemid);
if ($payanywaytx->success) {
    redirect($url, get_string('payment_success', 'paygw_payanyway'), 0, 'success');
} else {
    redirect($url, get_string('payment_error', 'paygw_payanyway'), 0, 'error');
}
