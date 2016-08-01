<?php
/**
*
* info_acp_connectionlog [Français]
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
	'ACP_CONNECTION_LOGS'			=> 'Journal des connexions',
	'ACP_CONNECTION_LOGS_EXPLAIN'	=> 'Liste l’ensemble des connexions sur le forum. Vous pouvez trier/filtrer par nom d’utilisateur, date, adresse IP ou par action. Vous pouvez aussi supprimer certaines opérations ou même l’intégralité de la log. <br /><br /><strong>Astuce</strong> : Vous pouvez obtenir des informations plus détaillées sur chaque adresse IP en cliquant dessus pour afficher le <em>Whois</em>.',
	
	'LOG_AUTH_SUCCESS'				=> '<strong>Connexion réussie</strong><br />» %s',
	'LOG_ADMIN_AUTH_SUCCESS'		=> '<strong>Connexion réussie à l’ACP</strong>',
	'LOG_AUTH_SUCCESS_AUTO'			=> '<strong>Connexion réussie (Autologged)</strong><br />» %s',

	'LOG_LOGIN_ERROR_USERNAME'		=> '<span style="color:#BC2A4D;"><strong>Erreur</strong> - Utilisateur inexistant<br />» %s</span>',
	'LOG_LOGIN_ERROR_PASSWORD'		=> '<span style="color:#BC2A4D;"><strong>Erreur</strong> - Mot de passe incorrect<br />» %s</span>',
	'LOG_NO_PASSWORD_SUPPLIED'		=> '<span style="color:#BC2A4D;"><strong>Erreur</strong> - Tentative de connexion sans mot de passe<br />» %s</span>',
	'LOG_LOGIN_ERROR_ATTEMPTS'		=> '<span style="color:#BC2A4D;"><strong>Erreur</strong> - Nombre maximum de tentatives de connexions dépassé<br />» %s</span>',
	'LOG_ACTIVE_ERROR'				=> '<span style="color:#BC2A4D;"><strong>Erreur</strong> - Utilisateur inactif<br />» %s</span>',
	'LOG_USERNAME_FAIL_BAN'			=> '<span style="color:#BC2A4D;"><strong>Erreur</strong> - Utilisateur banni<br />» %s</span>',
	'LOG_ADMIN_AUTH_FAIL'			=> '<span style="color:#BC2A4D;"><strong>Erreur de connexion à l’ACP</strong> - Mot de passe incorrect</span>',

	'LOG_CLEAR_CONNECTION_LOG'		=> 'Log des connections purgée',
	'ACP_LOGS_SORT'					=> 'Trier',
	'ACP_LOGS_ALL'					=> 'Toutes',

	'LC_PRUNE_DAY'					=> 'Purge automatique de la log des connexions',
	'LC_PRUNE_DAY_EXPLAIN'			=> 'Nombre de jours de conservation des logs. Les logs plus anciennes seront automatiquement purgées. Mettre cette valeur à 0 pour désactiver la purge automatique des logs.',
));
