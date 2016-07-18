<?php
/**
*
* @package phpBB Extension - Connection Log
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\connectionlog\event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
* Event listener
*/
class listener implements EventSubscriberInterface
{
/**
* Assign functions defined in this class to event listeners in the core
*
* @return array
* @static
* @access public
*/
	static public function getSubscribedEvents()
	{
		return array(
			'core.session_set_custom_ban'		=> 'add_log_banned',
			'core.login_box_redirect'			=> 'add_log_sucess',
			'core.login_box_failed'				=> 'add_log_failed',
			'core.get_logs_modify_type'			=> 'add_sql_where',
			'core.acp_board_config_edit_add'	=> 'add_acp_config',
			'core.user_setup'					=> 'add_log_autologin',
		);
	}

	/** @var \phpbb\user */
	protected $user;
	/** @var \phpbb\auth\auth */

	protected $auth;

	/** @var \phpbb\db\driver\driver_interface $db */
	protected $db;

	/** @var \phpbb\config\config $config */
	protected $config;

	/** @var \phpbb\template\template */
	protected $template;

	/** @var \phpbb\request\request_interface */
	protected $request;

	/** @var string phpbb_root_path */
	protected $phpbb_root_path;

	/** @var string phpEx */
	protected $php_ext;

	/**
	* Constructor
	*/
	public function __construct(
		\phpbb\template\template $template,
		\phpbb\request\request_interface $request,
		\phpbb\user $user,
		\phpbb\auth\auth $auth,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\config\config $config,
		\phpbb\log\log_interface $log,
		$table_prefix,
		$phpbb_root_path,
		$php_ext
	)
	{
		$this->template = $template;
		$this->request = $request;
		$this->user = $user;
		$this->auth = $auth;
		$this->db = $db;
		$this->config = $config;
		$this->phpbb_log = $log;
		$this->table_prefix = $table_prefix;
		$this->phpbb_root_path = $phpbb_root_path;
		$this->php_ext = $php_ext;

		if (!defined('CONNECTION_LOG_TABLE'))
		{
			define ('CONNECTION_LOG_TABLE', $table_prefix.'connect_log');
		}
	}

	public function add_log_banned($event)
	{
		$ban_row =  $event['ban_row'];
		if (!empty($ban_row) && $ban_row['ban_userid'] == $this->user->data['user_id'])
		{
			$this->phpbb_log->set_log_table(CONNECTION_LOG_TABLE);
			$this->phpbb_log->add('admin', $this->user->data['user_id'], '', 'LOG_USERNAME_FAIL_BAN', time(), array($ban_row['ban_give_reason']));
		}
	}

	public function add_log_sucess($event)
	{
		$redirect = $event['redirect'];
		$admin = $event['admin'];
		$return = $event['return'];

		$sql = 'SELECT *
			FROM '. CONNECTION_LOG_TABLE. '
			WHERE user_id = ' . $this->user->data['user_id'] . '
			AND log_time >= ' . (time() - 1) . '
			AND log_time <= ' . (time() + 1) . '
			AND log_operation = \'LOG_USERNAME_FAIL_BAN\'';
		$result = $this->db->sql_query_limit($sql, 1);
		$row = $this->db->sql_fetchrow($result);
		$this->db->sql_freeresult($result);
		if(empty($row))
		{
			$message = ($event['admin']) ? 'LOG_ADMIN_AUTH_SUCCESS' : 'LOG_AUTH_SUCCESS';
			$this->phpbb_log->set_log_table(CONNECTION_LOG_TABLE);
			$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], $message, time(), array($event['redirect']));
		}
		else
		{
			$log_operation = 'LOG_USERNAME_FAIL_BAN';
			$sql = 'UPDATE ' . CONNECTION_LOG_TABLE . '
				SET log_ip = \''. $this->user->data['session_ip']. '\'
				WHERE log_id = ' . $row['log_id'] . '';
			$this->db->sql_query($sql);
		}
		$this->db->sql_query($sql);
	}

	public function add_log_failed($event)
	{
		$result = $event['result'];
		$username = $event['username'];

		if($this->user->data['session_page'] == 'adm/index.php')
		{
			$message = 'LOG_ADMIN_AUTH_FAIL';
		}
		else
		{
			$message = 'LOG_'. $result['error_msg'].'';
		}

		$this->phpbb_log->set_log_table(CONNECTION_LOG_TABLE);
		$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], $message, time(), array($event['username']));
	}

	public function add_sql_where($event)
	{
		//$event['sql_additional'] = '';
		if($usearch = $this->request->variable('usearch', '', true))
		{
			$this->template->assign_var('USEARCH', $usearch);
			$event['sql_additional'] .= " AND u.username_clean " . $this->db->sql_like_expression(str_replace('*', $this->db->get_any_char(), utf8_clean_string($usearch))) . ' ';
		}

		if($isearch = $this->request->variable('isearch', ''))
		{
			$this->template->assign_var('ISEARCH', $isearch);
			$event['sql_additional'] .= " AND l.log_ip " . $this->db->sql_like_expression(str_replace('*', $this->db->get_any_char(), $isearch)) . ' ';
		}

		if ($asearch =  $this->request->variable('asearch', ''))
		{
			$event['sql_additional'] .= ($asearch != 'ACP_LOGS_ALL') ? ' AND l.log_operation LIKE \''. $asearch .'\'' : '';
			$this->template->assign_var('ASEARCH', $asearch);
		}
	}

	public function add_acp_config($event)
	{
		$mode = $event['mode'];
		$display_vars = $event['display_vars'];

		if ($mode == 'settings')
		{
			$count = 0;
			foreach($display_vars['vars'] as $key => $value)
			{
				if (strripos($key, 'legend') === 0)
				{
					$count++;
				}
			}
			$next = $count + 1;
			$display_vars['vars']['legend' . $count . ''] = 'ACP_CONNECTION_LOGS';
			$display_vars['vars']['lc_expire_days'] = array('lang' => 'LC_PRUNE_DAY', 'validate' => 'int:0:60', 'type' => 'number:0:9999', 'explain' => true, 'append' => ' ' . $this->user->lang['DAYS']);
			$display_vars['vars']['legend' . $next . ''] = 'ACP_SUBMIT_CHANGES';
			$event['display_vars'] = $display_vars;
		}
	}

	public function add_log_autologin($event)
	{
		if (isset($this->user->data['session_created']) && $this->user->data['session_created'] && $this->user->data['session_autologin'])
		{
			$this->phpbb_log->set_log_table(CONNECTION_LOG_TABLE);
			$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], 'LOG_AUTH_SUCCESS_AUTO', time(), array($this->user->data['session_page']));
		}
	}
}
