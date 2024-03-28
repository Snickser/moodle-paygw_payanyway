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
 * Contains class for payanyway payment gateway.
 *
 * @package    paygw_payanyway
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace paygw_payanyway;

/**
 * The gateway class for payanyway payment gateway.
 *
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gateway extends \core_payment\gateway {
    public static function get_supported_currencies(): array {
        // 3-character ISO-4217: https://en.wikipedia.org/wiki/ISO_4217#Active_codes.
        return [
            'RUB', 'USD', 'EUR'
        ];
    }

    /**
     * Configuration form for the gateway instance
     *
     * Use $form->get_mform() to access the \MoodleQuickForm instance
     *
     * @param \core_payment\form\account_gateway $form
     */
    public static function add_configuration_to_gateway_form(\core_payment\form\account_gateway $form): void {
        $mform = $form->get_mform();

        $options = array('www.payanyway.ru'  => 'www.payanyway.ru',
                         'demo.moneta.ru' => 'demo.moneta.ru');
        $mform->addElement('select', 'paymentserver', get_string('paymentserver', 'paygw_payanyway'), $options);
        $mform->setType('paymentserver', PARAM_TEXT);

	$paymentsystems = array(
			'payanyway_0_0' => get_string('payanyway', 'paygw_payanyway'),
//			'moneta_0_1015' => get_string('moneta', 'paygw_payanyway'),
			'plastic_0_card' => get_string('plastic', 'paygw_payanyway'),
			'sbp_0_12299232' => get_string('sbp', 'paygw_payanyway'),
//			'webmoney_0_1017' => get_string('webmoney', 'paygw_payanyway'),
//			'yandex_0_1020' => get_string('yandex', 'paygw_payanyway'),
//			'moneymail_0_1038' => get_string('moneymail', 'paygw_payanyway'),
//			'wallet_0_310212' => get_string('wallet', 'paygw_payanyway'),
//			'banktransfer_1_705000_75983431' => get_string('banktransfer', 'paygw_payanyway'),
//			'ciberpay_1_489755_19357960' => get_string('ciberpay', 'paygw_payanyway'),
//			'comepay_1_228820_47654606' => get_string('comepay', 'paygw_payanyway'),
//			'contact_1_1028_26' => get_string('contact', 'paygw_payanyway'),
//			'elecsnet_1_232821_10496472' => get_string('elecsnet', 'paygw_payanyway'),
//			'euroset_1_248362_136' => get_string('euroset', 'paygw_payanyway'),
//			'forward_1_83046_116' => get_string('forward', 'paygw_payanyway'),
//			'gorod_1_426904_152' => get_string('gorod', 'paygw_payanyway'),
//			'mcb_1_295339_143' => get_string('mcb', 'paygw_payanyway'),
//			'novoplat_1_281129_80314912' => get_string('novoplat', 'paygw_payanyway'),
//			'platika_1_226272_15662295' => get_string('platika', 'paygw_payanyway'),
//			'post_1_1029_15' => get_string('post', 'paygw_payanyway'),
		);
        $mform->addElement('select', 'paymentsystem', get_string('paymentsystem', 'paygw_payanyway'), $paymentsystems);
        $mform->setDefault('paymentsystem', get_string('paymentsystem', 'paygw_payanyway'));

        $mform->addElement('text', 'mntid', get_string('mntid', 'paygw_payanyway'));
        $mform->setType('mntid', PARAM_TEXT);

        $mform->addElement('text', 'mntdataintegritycode', get_string('mntdataintegritycode', 'paygw_payanyway'));
        $mform->setType('mntdataintegritycode', PARAM_TEXT);

        $mform->addElement('advcheckbox', 'mnttestmode', get_string('mnttestmode', 'paygw_payanyway'), '0');
        $mform->setType('mnttestmode', PARAM_TEXT);

        $mform->addElement('advcheckbox', 'skipmode', get_string('skipmode', 'paygw_payanyway'), '0');
        $mform->setType('skipmode', PARAM_TEXT);
        $mform->addHelpButton('skipmode', 'skipmode', 'paygw_payanyway');

        $mform->addElement('advcheckbox', 'passwordmode', get_string('passwordmode', 'paygw_payanyway'), '0');
        $mform->setType('passwordmode', PARAM_TEXT);
        $mform->disabledIf('passwordmode', 'skipmode', "neq", 0);

        $mform->addElement('text', 'password', get_string('password', 'paygw_payanyway'));
        $mform->setType('password', PARAM_TEXT);
        $mform->disabledIf('password', 'passwordmode');
        $mform->disabledIf('password', 'skipmode', "neq", 0);
        $mform->addHelpButton('password', 'password', 'paygw_payanyway');

        $mform->addElement('float', 'suggest', get_string('suggest', 'paygw_payanyway'));
        $mform->setType('suggest', PARAM_FLOAT);

        $mform->addElement('float', 'maxcost', get_string('maxcost', 'paygw_payanyway'));
        $mform->setType('maxcost', PARAM_FLOAT);

        global $CFG;
        $mform->addElement('html', '<span class="label-callback">'.get_string('callback', 'paygw_payanyway').':</span><br>');
        $mform->addElement('html', '<span class="callback_url">'.$CFG->wwwroot.'/payment/gateway/payanyway/callback.php</span><br>');
        $mform->addElement('html', '<span class="label-callback">'.get_string('callback_help', 'paygw_payanyway').'</span><br><br>');

    }

    /**
     * Validates the gateway configuration form.
     *
     * @param \core_payment\form\account_gateway $form
     * @param \stdClass $data
     * @param array $files
     * @param array $errors form errors (passed by reference)
     */
    public static function validate_gateway_form(\core_payment\form\account_gateway $form,
                                                 \stdClass $data, array $files, array &$errors): void {
        if ($data->enabled &&
                (empty($data->mntid) || empty($data->mntdataintegritycode) || empty($data->paymentserver))) {
            $errors['enabled'] = get_string('gatewaycannotbeenabled', 'payment');
        }
    }
}
