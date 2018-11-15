<?php
/**
*
* @package phpBB Extension - Connection Log
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\connectionlog\acp;

class connectionlog_module
{
	var $u_action;

	function main($id, $mode)
	{
		global $db, $template, $request, $table_prefix, $user, $phpbb_log, $phpbb_container, $config, $phpbb_root_path, $phpEx;
		$user->add_lang('mcp');

		$phpbb_log->set_log_table(CONNECTION_LOG_TABLE);

		$whois		= $request->variable('whois', false);

		$start		= $request->variable('start', 0);
		$deletemark	= $request->variable('delmarked', false, false, \phpbb\request\request_interface::POST);
		$deleteall	= $request->variable('delall', false, false, \phpbb\request\request_interface::POST);
		$marked		= $request->variable('mark', array(0));
		$ip 		= $request->variable('ip', '');
		$usearch	= utf8_normalize_nfc($request->variable('usearch', '', true));
		$isearch	= $request->variable('isearch', '');
		$asearch	= $request->variable('asearch', 'ACP_LOGS_ALL');

		// Sort keys
		$sort_days	= $request->variable('st', 0);
		$sort_key	= $request->variable('sk', 't');
		$sort_dir	= $request->variable('sd', 'd');

		$pagination = $phpbb_container->get('pagination');

		if ($whois)
		{
			include($phpbb_root_path . 'includes/functions_user.' . $phpEx);

			$user->add_lang('acp/users');
			$this->page_title = 'WHOIS';
			$this->tpl_name = 'simple_body';
			$domain = gethostbyaddr($ip);
			$ipwhois = user_ipwhois($ip);

			$template->assign_vars(array(
				'MESSAGE_TITLE'		=> sprintf($user->lang['IP_WHOIS_FOR'], $domain),
				'MESSAGE_TEXT'		=> nl2br($ipwhois))
			);

			return;
		}

		// Delete entries if requested and able
		if (($deletemark || $deleteall))
		{
			if (confirm_box(true))
			{
				$conditions = array();

				if ($deletemark && sizeof($marked))
				{
					$sql = 'DELETE FROM ' . CONNECTION_LOG_TABLE . '
						WHERE ' . $db->sql_in_set('log_id', $marked) . '';
				}

				if ($deleteall)
				{
					$sql = 'TRUNCATE TABLE ' . CONNECTION_LOG_TABLE;
				}

				$db->sql_query($sql);
				$phpbb_log->set_log_table(LOG_TABLE);
				$phpbb_log->add('admin', $user->data['user_id'], $user->data['session_ip'], 'LOG_CLEAR_CONNECTION_LOG', time());
				redirect($this->u_action);
			}
			else
			{
				confirm_box(false, $user->lang['CONFIRM_OPERATION'], build_hidden_fields(array(
					'start'		=> $start,
					'delmarked'	=> $deletemark,
					'delall'	=> $deleteall,
					'mark'		=> $marked,
					'st'		=> $sort_days,
					'sk'		=> $sort_key,
					'sd'		=> $sort_dir,
					'i'			=> $id,
					'mode'		=> $mode,
					))
				);
			}
		}

		// Sorting
		$limit_days = array(0 => $user->lang['ALL_ENTRIES'], 1 => $user->lang['1_DAY'], 7 => $user->lang['7_DAYS'], 14 => $user->lang['2_WEEKS'], 30 => $user->lang['1_MONTH'], 90 => $user->lang['3_MONTHS'], 180 => $user->lang['6_MONTHS'], 365 => $user->lang['1_YEAR']);
		$sort_by_text = array('u' => $user->lang['SORT_USERNAME'], 't' => $user->lang['SORT_DATE'], 'i' => $user->lang['SORT_IP'], 'o' => $user->lang['SORT_ACTION']);
		$sort_by_sql = array('u' => 'u.username_clean', 't' => 'l.log_time', 'i' => 'l.log_ip', 'o' => 'l.log_operation');

		$s_limit_days = $s_sort_key = $s_sort_dir = $u_sort_param = '';
		gen_sort_selects($limit_days, $sort_by_text, $sort_days, $sort_key, $sort_dir, $s_limit_days, $s_sort_key, $s_sort_dir, $u_sort_param);

		// Define where and sort sql for use in displaying logs
		$sql_where = ($sort_days) ? (time() - ($sort_days * 86400)) : 0;
		$sql_sort = $sort_by_sql[$sort_key] . ' ' . (($sort_dir == 'd') ? 'DESC' : 'ASC');

		$this->tpl_name = 'acp_connection_logs_body';
		$this->page_title = $user->lang('ACP_CONNECTION_LOGS');

		$log_data = array();
		$log_count = 0;
		$start = view_log('admin', $log_data, $log_count, $config['topics_per_page'], $start, 0, 0, 0, $sql_where, $sql_sort);

		$base_url = $this->u_action . "&amp;$u_sort_param";
		$pagination->generate_template_pagination($base_url, 'pagination', 'start', $log_count, $config['topics_per_page'], $start);

		foreach ($log_data as $row)
		{
			$template->assign_block_vars('log', array(
				'USERNAME'	=> $row['username_full'],
				'IP'		=> ($ip == 'hostname') ? gethostbyaddr($row['ip']) : $row['ip'],
				'DATE'		=> $user->format_date($row['time']),
				'ACTION'	=> $row['action'],
				'ID'		=> $row['id'],
				'U_IP'		=> (!empty($row['ip'])) ? $this->u_action . '&amp;whois=true&amp;ip=' . $row['ip'] . '' : '',
				)
			);
		}

		// Actions sorting
		$list_actions = array(
			'ACP_LOGS_ALL',
			'LOG_AUTH_SUCCESS',
			'LOG_ADMIN_AUTH_SUCCESS',
			'LOG_AUTH_SUCCESS_AUTO',

			'LOG_LOGIN_ERROR_USERNAME',
			'LOG_LOGIN_ERROR_PASSWORD',
			'LOG_NO_PASSWORD_SUPPLIED',
			'LOG_LOGIN_ERROR_ATTEMPTS',
			'LOG_ACTIVE_ERROR',
			'LOG_USERNAME_FAIL_BAN',
			'LOG_ADMIN_AUTH_FAIL',
		);

		$s_asearch = '';
		foreach ($list_actions as $key => $action)
		{
			$selected = ($action == $asearch) ? ' selected="selected"' : '';
			$s_asearch .= '<option value="' . $action . '"' . $selected . '>' . $user->lang[$action] . '</option>';
		}

		$template->assign_vars(array(
				'U_ACTION'		=> $this->u_action . "&amp;start=$start",
				'U_HOSTNAME'	=> $this->u_action . "&amp;usearch=$usearch&amp;isearch=$isearch&amp;asearch=$asearch&amp;start=$start&amp;ip=" . (($ip == 'ip') ? 'hostname' : 'ip'),
				'S_LIMIT_DAYS'	=> $s_limit_days,
				'S_SORT_KEY'	=> $s_sort_key,
				'S_SORT_DIR'	=> $s_sort_dir,
				'S_ASEARCH'		=> $s_asearch,
			)
		);
	}
}
