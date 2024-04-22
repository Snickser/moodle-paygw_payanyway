<?php

use core_payment\helper;

require("../../../config.php");
global $CFG, $USER, $DB;

defined('MOODLE_INTERNAL') || die();

require_login();

// file_put_contents("/tmp/xxxx", serialize($_REQUEST)."\n", FILE_APPEND);

$id = required_param('MNT_TRANSACTION_ID', PARAM_INT);

if (!$payanywaytx = $DB->get_record('paygw_payanyway', ['id' => $id])) {
    die('FAIL. Not a valid transaction id');
}

$paymentarea = $payanywaytx->paymentarea;
$component   = $payanywaytx->component;
$itemid      = $payanywaytx->itemid;

$url = helper::get_success_url($component, $paymentarea, $itemid);
if ($payanywaytx->success) {
    redirect($url, get_string('payment_success', 'paygw_payanyway'), 0, 'success');
} else {
    redirect($url, get_string('payment_error', 'paygw_payanyway'), 0, 'error');
}
