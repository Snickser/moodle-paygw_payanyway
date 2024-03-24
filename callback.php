<?php

use core_payment\helper;

require("../../../config.php");
//require_once("$CFG->dirroot/paygw/payanyway/lib.php");
global $CFG, $USER, $DB;

$data = array();
foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
}

if (!$payanywaytx = $DB->get_record('paygw_payanyway', array('id' => $data['MNT_TRANSACTION_ID']))) {
	die('FAIL. Not a valid transaction id');
}

if (! $userid = $DB->get_record("user", array("id"=>$payanywaytx->userid))) {
	die('FAIL. Not a valid user id.');
}

$component   = $payanywaytx->component;
$paymentarea = $payanywaytx->paymentarea;
$itemid      = $payanywaytx->itemid;

$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'payanyway');
$payable = helper::get_payable($component, $paymentarea, $itemid);// Get currency and payment amount.
$surcharge = helper::get_gateway_surcharge('payanyway');// In case user uses surcharge.


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
		$cost = (float) $config->cost;
	} else {
		$cost = (float) $payanywaytx->cost;
	}
	// Use the same rounding of floats as on the paygw form.
	$cost = number_format($cost, 2, '.', '');

	if ($data['MNT_AMOUNT'] !== $cost) {
		die('FAIL. Amount does not match.');
	}

	$payanywaytx->success = 1;
	if (!$DB->update_record('paygw_payanyway', $payanywaytx)) {
		die('FAIL. Update db error.');
	} else {
		die('SUCCESS');
	}
} else {
	die('FAIL');
}