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
 * @copyright  2024 Alex Orlov <snicker@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace paygw_payanyway;

/**
 * The gateway class for payanyway payment gateway.
 *
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gateway extends \core_payment\gateway {
    /**
     * Configuration form for currency
     */
    public static function get_supported_currencies(): array {
        // 3-character ISO-4217: https://en.wikipedia.org/wiki/ISO_4217#Active_codes.
        return [
            'RUB', 'USD', 'EUR',
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

        $options = ['www.payanyway.ru'  => 'www.payanyway.ru',
                         'demo.moneta.ru' => 'demo.moneta.ru'];
        $mform->addElement('select', 'paymentserver', get_string('paymentserver', 'paygw_payanyway'), $options);
        $mform->setType('paymentserver', PARAM_TEXT);

        $mform->addElement('text', 'mntid', get_string('mntid', 'paygw_payanyway'));
        $mform->setType('mntid', PARAM_TEXT);
        $mform->addRule('mntid', get_string('required'), 'required', null, 'client');

        $mform->addElement('passwordunmask', 'mntdataintegritycode', get_string('mntdataintegritycode', 'paygw_payanyway'));
        $mform->setType('mntdataintegritycode', PARAM_TEXT);
        $mform->addRule('mntdataintegritycode', get_string('required'), 'required', null, 'client');

        $mform->addElement('advcheckbox', 'mnttestmode', get_string('mnttestmode', 'paygw_payanyway'), '0');
        $mform->setType('mnttestmode', PARAM_INT);

        $paymentsystems = [
            '0' => get_string('payanyway', 'paygw_payanyway'),
            'card' => get_string('plastic', 'paygw_payanyway'),
            '1015' => get_string('moneta', 'paygw_payanyway'),
            '12299232' => get_string('sbp', 'paygw_payanyway'),
        ];
        $mform->addElement('select', 'paymentsystem', get_string('paymentsystem', 'paygw_payanyway'), $paymentsystems);
        $mform->setDefault('paymentsystem', get_string('paymentsystem', 'paygw_payanyway'));

        $mform->addElement('text', 'fixdesc', get_string('fixdesc', 'paygw_payanyway'), ['size' => 50]);
        $mform->setType('fixdesc', PARAM_TEXT);
        $mform->addHelpButton('fixdesc', 'fixdesc', 'paygw_payanyway');

        $mform->addElement('advcheckbox', 'skipmode', get_string('skipmode', 'paygw_payanyway'), '0');
        $mform->setType('skipmode', PARAM_TEXT);
        $mform->addHelpButton('skipmode', 'skipmode', 'paygw_payanyway');

        $mform->addElement('advcheckbox', 'passwordmode', get_string('passwordmode', 'paygw_payanyway'), '0');
        $mform->setType('passwordmode', PARAM_TEXT);
        $mform->disabledIf('passwordmode', 'skipmode', "neq", 0);

        $mform->addElement('passwordunmask', 'password', get_string('password', 'paygw_payanyway'));
        $mform->setType('password', PARAM_TEXT);
        $mform->addHelpButton('password', 'password', 'paygw_payanyway');

        $mform->addElement(
            'advcheckbox',
            'usedetails',
            get_string('usedetails', 'paygw_payanyway')
        );
        $mform->setType('usedetails', PARAM_INT);
        $mform->addHelpButton('usedetails', 'usedetails', 'paygw_payanyway');

        $mform->addElement(
            'advcheckbox',
            'showduration',
            get_string('showduration', 'paygw_payanyway')
        );
        $mform->setType('showduration', PARAM_INT);

        $mform->addElement(
            'advcheckbox',
            'fixcost',
            get_string('fixcost', 'paygw_payanyway')
        );
        $mform->setType('fixcost', PARAM_INT);
        $mform->addHelpButton('fixcost', 'fixcost', 'paygw_payanyway');

        $mform->addElement('float', 'suggest', get_string('suggest', 'paygw_payanyway'), ['size' => 10]);
        $mform->setType('suggest', PARAM_FLOAT);
        $mform->disabledIf('suggest', 'fixcost', "neq", 0);

        $mform->addElement('float', 'maxcost', get_string('maxcost', 'paygw_payanyway'), ['size' => 10]);
        $mform->setType('maxcost', PARAM_FLOAT);
        $mform->disabledIf('maxcost', 'fixcost', "neq", 0);

        global $CFG;
        $mform->addElement('html', '<div class="label-callback" style="background: pink; padding: 15px;">' .
                                    get_string('callback', 'paygw_payanyway') . '<br>');
        $mform->addElement('html', $CFG->wwwroot . '/payment/gateway/payanyway/callback.php<br>');
        $mform->addElement('html', get_string('callback_help', 'paygw_payanyway') . '</div><br>');

        $plugininfo = \core_plugin_manager::instance()->get_plugin_info('paygw_yookassa');
        $header = "<div>Версия плагина: $plugininfo->release ($plugininfo->versiondisk)<br>" .
        'Новые версии плагина вы можете найти на
 <a href=https://github.com/Snickser/moodle-paygw_payanyway>GitHub.com</a>
 <img src="https://img.shields.io/github/v/release/Snickser/moodle-paygw_payanyway.svg"><br>
 Пожалуйста, отправьте мне немножко <a href="https://yoomoney.ru/fundraise/143H2JO3LLE.240720">доната</a>😊</div>
 <iframe src="https://yoomoney.ru/quickpay/fundraise/button?billNumber=143H2JO3LLE.240720"
 width="330" height="50" frameborder="0" allowtransparency="true" scrolling="no"></iframe>';
        $mform->addElement('html', $header);
    }

    /**
     * Validates the gateway configuration form.
     *
     * @param \core_payment\form\account_gateway $form
     * @param \stdClass $data
     * @param array $files
     * @param array $errors form errors (passed by reference)
     */
    public static function validate_gateway_form(
        \core_payment\form\account_gateway $form,
        \stdClass $data,
        array $files,
        array &$errors
    ): void {
        if (
            $data->enabled &&
                (empty($data->mntid) || empty($data->mntdataintegritycode) || empty($data->paymentserver))
        ) {
            $errors['enabled'] = get_string('gatewaycannotbeenabled', 'payment');
        }
        if ($data->maxcost && $data->maxcost < $data->suggest) {
            $errors['maxcost'] = get_string('maxcosterror', 'paygw_payanyway');
        }
    }
}
