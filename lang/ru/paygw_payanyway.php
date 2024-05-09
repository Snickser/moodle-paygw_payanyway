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
 * Local language pack from https://study.bhuri.ru
 *
 * @package     paygw_payanyway
 * @copyright   2024 Alex Orlov <snickser@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['abouttopay'] = 'Вы собираетесь пожертвовать на';
$string['callback'] = 'Callback URL:';
$string['callback_help'] = 'Copy this and put it in callback URLs at your PayAnyWay account.';
$string['cost'] = 'Стоимость записи';
$string['currency'] = 'Валюта';
$string['fixdesc'] = 'Фиксированный комментарий платежа';
$string['fixdesc_help'] = 'Эта настройка устанавливает фиксированный комментарий для всех платежей, и отключает отображение описания комментария на странице платежа.';
$string['gatewaydescription'] = 'PayAnyWay — авторизованный платежный шлюз для обработки транзакций по кредитным картам.';
$string['maxcost'] = 'Максимальная цена';
$string['mntdataintegritycode'] = 'Код проверки целостности данных';
$string['mntid'] = 'Номер счета';
$string['mnttestmode'] = 'Тестовый режим';
$string['password'] = 'Резервный пароль';
$string['password_error'] = 'Введён неверный платёжный пароль';
$string['password_help'] = 'С помощью этого пароля можно обойти процесс отплаты. Может быть полезен когда нет возможности произвести оплату.';
$string['password_success'] = 'Платёжный пароль принят';
$string['password_text'] = 'Если у вас нет возможности сделать пожертвование, то попросите у вашего куратора пароль и введите его.';
$string['passwordmode'] = 'Разрешить ввод резервного пароля';
$string['payment'] = 'Пожертвование';
$string['payment_error'] = 'Ошибка оплаты';
$string['payment_success'] = 'Оплата успешно произведена';
$string['paymentserver'] = 'URL сервера оплаты';
$string['paymore'] = 'Если вы хотите пожертвовать больше, то просто впишите свою сумму вместо указанной.';
$string['pluginname'] = 'Платежи PayAnyWay';
$string['pluginname_desc'] = 'Плагин PayAnyWay позволяет получать платежи через PayAnyWay.';
$string['sendpaymentbutton'] = 'Пожертвовать!';
$string['skipmode'] = 'Показать кнопку обхода платежа';
$string['skipmode_help'] = 'Эта настройка разрешает кнопку обхода платежа, может быть полезна в публичных курсах с необязательной оплатой.';
$string['skipmode_text'] = 'Если вы не имеете возможности совершить пожертвование через платёжную систему то можете нажать на эту кнопку.';
$string['skippaymentbutton'] = 'Не имею :(';
$string['suggest'] = 'Рекомендуемая цена';
$string['usedetails'] = 'Показывать свёрнутым';
$string['usedetails_help'] = 'Прячет кнопку или пароль под сворачиваемый блок, если они включены.';
$string['usedetails_text'] = 'Нажмите тут если у вас нет возможности совершить пожертвование';

/* Payment systems */
$string['paymentsystem'] = 'Платежная система';
$string['payanyway'] = 'PayAnyWay';
$string['banktransfer'] = 'Банковский перевод';
$string['ciberpay'] = 'CiberPay';
$string['comepay'] = 'Comepay';
$string['contact'] = 'Contact';
$string['elecsnet'] = 'Элекснет';
$string['euroset'] = 'Евросеть, Связной';
$string['forward'] = 'Forward Mobile';
$string['gorod'] = 'Федеральная система ГОРОД';
$string['mcb'] = 'МосКредитБанк';
$string['moneta'] = 'Moneta.ru';
$string['moneymail'] = 'Money Mail';
$string['novoplat'] = 'NovoPlat';
$string['plastic'] = 'VISA, MasterCard, МИР';
$string['platika'] = 'PLATiKA';
$string['post'] = 'ФГУП Почта Росии';
$string['wallet'] = 'Wallet One';
$string['webmoney'] = 'WebMoney';
$string['yandex'] = 'Yandex.Money';
$string['additionalparameters'] = 'Дополнительные параметры';
$string['eurosetrapidaphone'] = 'Номер телефона';
$string['moneymailemail'] = 'Email в Money Mail';
$string['mailofrussiasenderindex'] = 'Индекс отправителя';
$string['mailofrussiasenderregion'] = 'Регион отправителя';
$string['mailofrussiasenderaddress'] = 'Адрес отправителя';
$string['mailofrussiasendername'] = 'Имя отправителя';
$string['webmoneyaccountid'] = 'Источник оплаты';
$string['sbp'] = 'СБП';
