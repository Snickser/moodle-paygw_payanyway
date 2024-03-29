<?php

use core_payment\helper;

require("../../../config.php");
//require_once("$CFG->dirroot/paygw/payanyway/lib.php");
global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

$transaction_id = required_param('MNT_TRANSACTION_ID', PARAM_INT);

if (!$payanywaytx = $DB->get_record('paygw_payanyway', array('id' => $transaction_id))) {
    die('FAIL. Not a valid transaction id');
}

if (! $userid = $DB->get_record("user", array("id"=>$payanywaytx->userid))) {
    die('FAIL. Not a valid user id.');
}

$component   = $payanywaytx->component;
$paymentarea = $payanywaytx->paymentarea;
$itemid      = $payanywaytx->itemid;
$userid      = $payanywaytx->userid;

$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'payanyway');
$payable = helper::get_payable($component, $paymentarea, $itemid);


$data = array();
foreach ($_REQUEST as $key => $value) {
    $data[$key] = $value;
}


if(isset($data['MNT_ID']) && isset($data['MNT_TRANSACTION_ID']) && isset($data['MNT_OPERATION_ID'])
	&& isset($data['MNT_AMOUNT']) && isset($data['MNT_CURRENCY_CODE']) && isset($data['MNT_TEST_MODE'])
	&& isset($data['MNT_SIGNATURE']))
{
	$MNT_SIGNATURE = md5("{$data['MNT_ID']}{$data['MNT_TRANSACTION_ID']}{$data['MNT_OPERATION_ID']}{$data['MNT_AMOUNT']}{$data['MNT_CURRENCY_CODE']}{$data['MNT_TEST_MODE']}".$config->mntdataintegritycode);

	if ($data['MNT_SIGNATURE'] !== $MNT_SIGNATURE) {
		die('FAIL. Signature does not match.');
	}

	// Check that amount paid is the correct amount
	if ( (float) $payanywaytx->cost <= 0 ) {
		$cost = (float) $payable->get_amount();
	} else {
		$cost = (float) $payanywaytx->cost;
	}
	// Use the same rounding of floats as on the paygw form.
	$cost = number_format($cost, 2, '.', '');

	if ($data['MNT_AMOUNT'] !== $cost) {
		die('FAIL. Amount does not match.');
	}

	// Deliver course
	$cost = helper::get_rounded_cost($payable->get_amount(), $payable->get_currency(), helper::get_gateway_surcharge('payanyway'));
	$paymentid = helper::save_payment($payable->get_account_id(), $component, $paymentarea, $itemid, $userid, $cost, $payable->get_currency(), 'payanyway');
	helper::deliver_order($component, $paymentarea, $itemid, $paymentid, $userid);

	// write to DB
	$payanywaytx->success = 1;
	if (!$DB->update_record('paygw_payanyway', $payanywaytx)) {
		die('FAIL. Update db error.');
	} else {
		die('SUCCESS');
	}
} else {
	die('FAIL');
}