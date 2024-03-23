<?php


require("../../config.php");
require_once("$CFG->dirroot/enrol/payanyway/lib.php");


$data = array();

foreach ($_REQUEST as $key => $value) {
	$data[$key] = $value;
}

if (!$payanywaytx = $DB->get_record('enrol_payanyway_transactions', array('id' => $data['MNT_TRANSACTION_ID']))) {
	die('FAIL. Not a valid transaction id');
}

if (! $user = $DB->get_record("user", array("id"=>$payanywaytx->userid))) {
	die('FAIL. Not a valid user id.');
}

if (! $course = $DB->get_record("course", array("id"=>$payanywaytx->courseid))) {
	die('FAIL. Not a valid course id.');
}

if (! $context = context_course::instance($course->id, IGNORE_MISSING)) {
	die('FAIL. Not a valid context id.');
}

if (! $plugin_instance = $DB->get_record("enrol", array("id"=>$payanywaytx->instanceid, "status"=>0))) {
	die('FAIL. Not a valid instance id.');
}

$plugin = enrol_get_plugin('payanyway');

if(isset($data['MNT_ID']) && isset($data['MNT_TRANSACTION_ID']) && isset($data['MNT_OPERATION_ID'])
	&& isset($data['MNT_AMOUNT']) && isset($data['MNT_CURRENCY_CODE']) && isset($data['MNT_TEST_MODE'])
	&& isset($data['MNT_SIGNATURE']))
{
	$MNT_SIGNATURE = md5("{$data['MNT_ID']}{$data['MNT_TRANSACTION_ID']}{$data['MNT_OPERATION_ID']}{$data['MNT_AMOUNT']}{$data['MNT_CURRENCY_CODE']}{$data['MNT_TEST_MODE']}".$plugin->get_config('mntdataintegritycode'));

	if ($data['MNT_SIGNATURE'] !== $MNT_SIGNATURE) {
		die('FAIL. Signature does not match.');
	}

	// Check that amount paid is the correct amount
	if ( (float) $payanywaytx->cost <= 0 ) {
		$cost = (float) $plugin->get_config('cost');
	} else {
		$cost = (float) $payanywaytx->cost;
	}

	// Use the same rounding of floats as on the enrol form.
	$cost = number_format($cost, 2, '.', '');

	if ($data['MNT_AMOUNT'] !== $cost) {
		die('FAIL. Amount does not match.');
	}

	if ($plugin_instance->enrolperiod) {
		$timestart = time();
		$timeend   = $timestart + $plugin_instance->enrolperiod;
	} else {
		$timestart = 0;
		$timeend   = 0;
	}

	// Enrol the user!
	$plugin->enrol_user($plugin_instance, $payanywaytx->userid, $plugin_instance->roleid, $timestart, $timeend);

	$payanywaytx->success = 1;
	if (!$DB->update_record('enrol_payanyway_transactions', $payanywaytx)) {
		die('FAIL');
	} else {
		die('SUCCESS');
	}
} else {
	die('FAIL');
}