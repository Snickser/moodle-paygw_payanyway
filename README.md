[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/snickser) support me with a donation.

# PayAnyWay payment gateway plugin for Moodle.

Version 0.7

https://payanyway.ru

![alt text](https://github.com/Snickser/moodle-paygw_payanyway/blob/main/payanyway.png?raw=true)

## Рекомендации

+ Тестировалось с Moodle 4.3.4.
+ Для записи в курс подходит стандарный плагин "Зачисление за оплату" (enrol_fee).
+ Для контрольного задания используйте модуль "[Gateway Payments](https://moodle.org/plugins/mod_gwpayments)" (мои правки [mod_gwpayments](https://github.com/Snickser/moodle-mod_gwpayments/tree/dev)), он правда глючный, но других пока нет.
+ Для ограничения доступности используйте модуль "[PaymentS availability condition for paid access](https://moodle.org/plugins/availability_gwpayments)" (мои правки [availability_gwpayments](https://github.com/Snickser/moodle-availability_gwpayments/tree/dev)).

## Status

[![Build Status](https://github.com/Snickser/moodle-paygw_payanyway/actions/workflows/moodle-ci.yml/badge.svg)](https://github.com/Snickser/moodle-paygw_payanyway/actions/workflows/moodle-ci.yml)

## INSTALLATION

Download the latest paygw_payanyway.zip and unzip the contents into the /payment/gateway directory. Or upload it from Moodle plugins adminnistration interface.

1. Install the plugin
2. Enable the PayAnyWay payment gateway
3. Create a new payment account
4. Configure the payment account against the PayAnyWay gateway using your pay ID
5. Enable the 'Enrolment on Payment' enrolment method
6. Add the 'Enrolment on Payment' method to your chosen course
7. Set the payment account, enrolment fee, and currency

The plugin supports only basic functionality, but everything changes someday...
