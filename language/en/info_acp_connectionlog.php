<?php
/**
*
* info_acp_connectionlog [English]
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
	'ACP_CONNECTION_LOGS'			=> 'Connection Log',
	'ACP_CONNECTION_LOGS_EXPLAIN'	=> 'This lists all the connections done on board. You can sort/filter by username, date, IP or action. You can also clear individual operations or the log as a whole.<br /><br /><strong>Trick</strong>: You can look up all IPs by clicking on the IP in the column and display <em>Whois</em>.',

	'LOG_AUTH_SUCCESS'				=> '<strong>Connected successfully</strong><br />» %s',
	'LOG_ADMIN_AUTH_SUCCESS'		=> '<strong>Connected successfully to ACP</strong>',
	'LOG_AUTH_SUCCESS_AUTO'			=> '<strong>Connected successfully (Autologged)</strong><br />» %s',

	'LOG_LOGIN_ERROR_USERNAME'		=> '<span style="color:#BC2A4D;"><strong>Failure</strong> - non-existent user<br />» %s</span>',
	'LOG_LOGIN_ERROR_PASSWORD'		=> '<span style="color:#BC2A4D;"><strong>Failure</strong> - incorrect password<br />» %s</span>',
	'LOG_NO_PASSWORD_SUPPLIED'		=> '<span style="color:#BC2A4D;"><strong>Failure</strong> - login attempt without a password<br />» %s</span>',
	'LOG_LOGIN_ERROR_ATTEMPTS'		=> '<span style="color:#BC2A4D;"><strong>Failure</strong> - exceeded the maximum number of login attempts<br />» %s</span>',
	'LOG_ACTIVE_ERROR'				=> '<span style="color:#BC2A4D;"><strong>Failure</strong> - inactive user<br />» %s</span>',
	'LOG_USERNAME_FAIL_BAN'			=> '<span style="color:#BC2A4D;"><strong>Failure</strong> - username banned by reason<br />» %s</span>',
	'LOG_ADMIN_AUTH_FAIL'			=> '<span style="color:#BC2A4D;"><strong>Failure connect to ACP</strong> - incorrect password</span>',

	'LOG_CLEAR_CONNECTION_LOG'		=> 'Connection log cleared',
	'ACP_LOGS_SORT'					=> 'Sort',
	'ACP_LOGS_ALL'					=> 'All',

	'LC_PRUNE_DAY'					=> 'Auto-pruning of connection log',
	'LC_PRUNE_DAY_EXPLAIN'			=> 'Set in days age of connection logs. Setting this value to 0 disables this behaviour.',
));
