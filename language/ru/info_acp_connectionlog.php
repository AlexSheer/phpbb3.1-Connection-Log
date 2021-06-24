<?php
/**
*
* info_acp_connectionlog [Russian]
*
* @package phpBB Extension - Connection Log
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine

$lang = array_merge($lang, array(
	'ACP_CONNECTION_LOGS'			=> 'Лог подключений',
	'ACP_CONNECTION_LOGS_EXPLAIN'	=> 'Это список всех подключений к конференции. Вы можете сортировать/фильтровать по имени пользователя, дате, IP-адресу или событию. Вы также можете очистить как отдельные, так и все записи лога.<br /><br /><strong>Подсказка</strong>: Информацию об IP-адресе (сервис Whois) можно получить, кликнув по значению IP (имени хоста) из списка.',

	'LOG_AUTH_SUCCESS'				=> '<strong>Успешное подключение</strong><br />» %s',
	'LOG_ADMIN_AUTH_SUCCESS'		=> '<strong>Успешное подключение к ACP</strong>',
	'LOG_AUTH_SUCCESS_AUTO'			=> '<strong>Успешное подключение (Автовход)</strong><br />» %s',

	'LOG_LOGIN_ERROR_USERNAME'		=> '<span style="color:#BC2A4D;"><strong>Отказ</strong> - несуществующий пользователь<br />» %s</span>',
	'LOG_LOGIN_ERROR_PASSWORD'		=> '<span style="color:#BC2A4D;"><strong>Отказ</strong> - неверный пароль<br />» %s</span>',
	'LOG_NO_PASSWORD_SUPPLIED'		=> '<span style="color:#BC2A4D;"><strong>Отказ</strong> - попытка входа без пароля<br />» %s</span>',
	'LOG_LOGIN_ERROR_ATTEMPTS'		=> '<span style="color:#BC2A4D;"><strong>Отказ</strong> - превышено максимально допустимое количество попыток входа<br />» %s</span>',
	'LOG_ACTIVE_ERROR'				=> '<span style="color:#BC2A4D;"><strong>Отказ</strong> - пользователь не активирован<br />» %s</span>',
	'LOG_USERNAME_FAIL_BAN'			=> '<span style="color:#BC2A4D;"><strong>Отказ</strong> - имя пользователя заблокировано по причине<br />» %s</span>',
	'LOG_ADMIN_AUTH_FAIL'			=> '<span style="color:#BC2A4D;"><strong>Отказ подключения к ACP</strong> - неверный пароль</span>',
	'LOG_FORM_INVALID'				=> 'Ошибка отправки формы<br />» %s',

	'LOG_CLEAR_CONNECTION_LOG'		=> 'Очищен лог подключений',
	'ACP_LOGS_SORT'					=> 'Фильтр',
	'ACP_LOGS_ALL'					=> 'Все',

	'LC_PRUNE_DAY'					=> 'Автоочистка лога',
	'LC_PRUNE_DAY_EXPLAIN'			=> 'Сколько дней хранить данные. Нулевое значение отключает эту возможность.',
));
