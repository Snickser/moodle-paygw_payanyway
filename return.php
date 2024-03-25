<?php

use core_payment\helper;

require("../../../config.php");
global $CFG, $USER, $DB;

//require_login();

defined('MOODLE_INTERNAL') || die();

$id = required_param('MNT_TRANSACTION_ID', PARAM_INT);

if (!$payanywaytx = $DB->get_record('paygw_payanyway', array('id' => $id))) {
    die('FAIL. Not a valid transaction id');
}

$paymentarea = $payanywaytx->paymentarea;
$component   = $payanywaytx->component;
$itemid      = $payanywaytx->itemid;

$url = helper::get_success_url($component, $paymentarea, $itemid);
redirect($url, get_string('paymentsuccessful', 'paygw_payanyway'), 0, 'success');
