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
 * Strings for component 'paygw_payanyway', language 'en'
 *
 * @package     paygw_payanyway
 * @copyright   2024 Alex Orlov <snickser@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['abouttopay'] = 'You are about to pay for';
$string['callback'] = 'Callback URL:';
$string['callback_help'] = 'Copy this and put it in callback URLs at your PayAnyWay account.';
$string['fixdesc'] = 'Fixed payment comment';
$string['fixdesc_help'] = 'This setting sets a fixed comment for all payments.';
$string['gatewaydescription'] = 'PayAnyWay is an authorised payment gateway provider for processing credit card transactions.';
$string['gatewayname'] = 'PayAnyWay';
$string['maxcost'] = 'Maximium cost';
$string['mntdataintegritycode'] = 'Code of data integrity verification';
$string['mntid'] = 'Account number';
$string['mnttestmode'] = 'Test mode';
$string['password'] = 'Password';
$string['password_error'] = 'Invalid payment password';
$string['password_help'] = 'Using this password you can bypass the payback process. It can be useful when it is not possible to make a payment.';
$string['password_success'] = 'Payment password accepted';
$string['password_text'] = 'If you are unable to make a payment, then ask your curator for a password and enter it.';
$string['passwordmode'] = 'Password';
$string['payment'] = 'Donation';
$string['payment_error'] = 'Payment Error';
$string['payment_success'] = 'Payment Successful';
$string['paymentserver'] = 'Payment server URL';
$string['paymore'] = 'If you want to donate more, simply enter your amount instead of the indicated amount.';
$string['pluginname'] = 'PayAnyWay payment';
$string['pluginname_desc'] = 'The PayAnyWay plugin allows you to receive payments via PayAnyWay.';
$string['sendpaymentbutton'] = 'Send payment via PayAnyWay';
$string['skipmode'] = 'Can skip payment';
$string['skipmode_help'] = 'This setting allows a payment bypass button, which can be useful in public courses with optional payment.';
$string['skipmode_text'] = 'If you are not able to make a donation through the payment system, you can click on this button.';
$string['skippaymentbutton'] = 'Skip payment :(';
$string['suggest'] = 'Suggested cost';
$string['showduration'] = 'Show duration of training';
$string['usedetails'] = 'Make it collapsible';
$string['usedetails_help'] = 'Display a button or password in a collapsed block.';
$string['usedetails_text'] = 'Click here if you are unable to donate.';

/* Payment systems */
$string['paymentsystem'] = 'Payment system';
$string['payanyway'] = 'PayAnyWay';
$string['banktransfer'] = 'Bank transfer';
$string['ciberpay'] = 'CiberPay';
$string['comepay'] = 'Comepay';
$string['contact'] = 'Contact';
$string['elecsnet'] = 'Elecsnet';
$string['euroset'] = 'Euroset, Svyaznoi';
$string['forward'] = 'Forward Mobile';
$string['gorod'] = 'Federal System GOROD';
$string['mcb'] = 'MoscowCreditBank';
$string['moneta'] = 'Moneta.ru';
$string['moneymail'] = 'Money Mail';
$string['novoplat'] = 'NovoPlat';
$string['plastic'] = 'VISA, MasterCard, MIR';
$string['platika'] = 'PLATiKA';
$string['post'] = 'Russian Post Transfer';
$string['wallet'] = 'Wallet One';
$string['webmoney'] = 'WebMoney';
$string['yandex'] = 'Yandex.Money';
$string['additionalparameters'] = 'Additional parameters';
$string['eurosetrapidaphone'] = 'Phone number';
$string['moneymailemail'] = 'Email in Money Mail';
$string['mailofrussiasenderindex'] = 'Sender ZIP';
$string['mailofrussiasenderaddress'] = 'Sender address';
$string['mailofrussiasendername'] = 'Sender name';
$string['webmoneyaccountid'] = 'Payment method';
$string['sbp'] = 'SBP';

$string['internalerror'] = 'An internal error has occurred. Please contact us.';

$string['privacy:metadata'] = 'The PayAnyWay plugin store some personal data.';
$string['privacy:metadata:paygw_payanyway:paygw_payanyway'] = 'Store some data';
$string['privacy:metadata:paygw_payanyway:shopid'] = 'Shopid';
$string['privacy:metadata:paygw_payanyway:apikey'] = 'ApiKey';
$string['privacy:metadata:paygw_payanyway:email'] = 'Email';
$string['privacy:metadata:paygw_payanyway:payanyway_plus'] = 'Send json data';
$string['privacy:metadata:paygw_payanyway:invoiceid'] = 'Invoice id';
$string['privacy:metadata:paygw_payanyway:courseid'] = 'Course id';
$string['privacy:metadata:paygw_payanyway:groupnames'] = 'Group names';
$string['privacy:metadata:paygw_payanyway:success'] = 'Status';

$string['messagesubject'] = 'Payment notification';
$string['message_success_completed'] = 'Hello {$a->firstname},
You transaction of payment id {$a->orderid} with cost of {$a->fee} {$a->currency} is successfully completed.
If the item is not accessable please contact the administrator.';
$string['messageprovider:payment_receipt'] = 'Payment receipt';

$string['fixcost'] = 'Fixed price mode';
$string['fixcost_help'] = 'Disables the ability for students to pay with an arbitrary amount.';
$string['maxcosterror'] = 'The maximum price must be higher than the recommended price';

$string['message_invoice_created'] = 'Hello {$a->firstname}!
Your payment link {$a->orderid} to {$a->fee} {$a->currency} has been successfully created.
You can pay it within an hour.';

$string['donate'] = '<div>Plugin version: {$a->release} ({$a->versiondisk})<br>
You can find new versions of the plugin at <a href=https://github.com/Snickser/moodle-paygw_payanyway>GitHub.com</a>
<img src="https://img.shields.io/github/v/release/Snickser/moodle-paygw_payanyway.svg"><br>
Please send me some <a href="https://yoomoney.ru/fundraise/143H2JO3LLE.240720">donate</a>😊</div>
TRX TYEUMcRVMkaKwAGKENMvvN1YvtNvrkw5kh<br>
BTC bc1q9gfmeh33497daetpugp9tjl56mggg966sgqhl0<br>
EVM 0x4E2E41CD0F72095126f3d2945C545D069629b4d4<br>
<iframe src="https://yoomoney.ru/quickpay/fundraise/button?billNumber=143H2JO3LLE.240720"
width="330" height="50" frameborder="0" allowtransparency="true" scrolling="no"></iframe>';

$string['error_txdatabase'] = 'Error write TX data to database';
$string['error_notvalidtxid'] = 'FAIL. Not a valid transaction id';
$string['error_notvalidpayment'] = 'FAIL. Not a valid payment';

$string['uninterrupted_desc'] = 'The price for the course is formed taking into account the missed time of the period you have not paid for.';
