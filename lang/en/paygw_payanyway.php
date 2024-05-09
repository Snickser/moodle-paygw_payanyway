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
$string['paymore'] = 'If you want to donate more, simply enter your amount instead of the indicated amount.';
$string['skipmode'] = 'Can skip payment';
$string['skipmode_help'] = 'This setting allows a payment bypass button, which can be useful in public courses with optional payment.';
$string['skipmode_text'] = 'If you are not able to make a donation through the payment system, you can click on this button.';
$string['skippaymentbutton'] = 'Skip payment :(';
$string['suggest'] = 'Suggested cost';
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
$string['privacy:metadata'] = 'The PayAnyWay plugin does not store any personal data.';

$string['privacy:metadata'] = 'The payanyway plugin store some personal data.';
$string['privacy:metadata:paygw_payanyway:paygw_payanyway'] = 'Store some data';
$string['privacy:metadata:paygw_payanyway:shopid'] = 'Shopid';
$string['privacy:metadata:paygw_payanyway:apikey'] = 'ApiKey';
$string['privacy:metadata:paygw_payanyway:email'] = 'Email';
$string['privacy:metadata:paygw_payanyway:payanyway_plus'] = 'Send json data';
$string['privacy:metadata:paygw_payanyway:invoiceid'] = 'Invoice id';
$string['privacy:metadata:paygw_payanyway:courceid'] = 'Cource id';
$string['privacy:metadata:paygw_payanyway:groupnames'] = 'Group names';
$string['privacy:metadata:paygw_payanyway:success'] = 'Status';

$string['messagesubject'] = 'Payment notification';
$string['message_success_completed'] = 'Hello {$a->firstname},
You transaction of payment id {$a->orderid} with cost of {$a->fee} {$a->currency} is successfully completed.
If the item is not accessable please contact the administrator.';
$string['messageprovider:payment_receipt'] = 'Payment receipt';
