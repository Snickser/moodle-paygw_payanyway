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
 * @copyright  2019 Shamim Rezaie <shamim@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace paygw_payanyway;

/**
 * The gateway class for payanyway payment gateway.
 *
 * @copyright  2019 Shamim Rezaie <shamim@moodle.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class gateway extends \core_payment\gateway {
    public static function get_supported_currencies(): array {
        // See https://developer.payanyway.com/docs/api/reference/currency-codes/,
        // 3-character ISO-4217: https://en.wikipedia.org/wiki/ISO_4217#Active_codes.
        return [
            'RUB', 'USD'
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

        $mform->addElement('text', 'mntid', get_string('mntid', 'paygw_payanyway'));
        $mform->setType('mntid', PARAM_TEXT);

        $mform->addElement('text', 'mntdataintegritycode', get_string('mntdataintegritycode', 'paygw_payanyway'));
        $mform->setType('mntdataintegritycode', PARAM_TEXT);

        $mform->addElement('checkbox', 'mnttestmode', get_string('mnttestmode', 'paygw_payanyway'));
        $mform->setType('mnttestmode', PARAM_TEXT);

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
