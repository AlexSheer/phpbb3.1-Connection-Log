<?php
/**
*
* @package phpBB Extension - Connection Log
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\connectionlog\migrations;

class connectionlog_0_0_1 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return;
	}

	static public function depends_on()
	{
		return array('\phpbb\db\migration\data\v310\dev');
	}

	public function update_schema()
	{
		return array(
			'add_tables'		=> array(
				$this->table_prefix . 'connect_log'	=> array(
					'COLUMNS'		=> array(
						'log_id'			=> array('UINT', null, 'auto_increment'),
						'log_type'			=> array('TINT:4', 0),
						'user_id'			=> array('UINT', 0),
						'forum_id'			=> array('UINT', 0),
						'reportee_id'		=> array('UINT', 0),
						'topic_id'			=> array('UINT', 0),
						'log_ip'			=> array('VCHAR:40', ''),
						'log_time'			=> array('UINT:11', 0),
						'log_operation'		=> array('TEXT', ''),
						'log_data'			=> array('MTEXT_UNI', ''),
					),
					'PRIMARY_KEY'	=> 'log_id',
						'KEYS'			=> array(
							'log_type'		=> array('INDEX', 'log_type'),
							'forum_id'		=> array('INDEX', 'forum_id'),
							'topic_id'		=> array('INDEX', 'topic_id'),
							'reportee_id'	=> array('INDEX', 'reportee_id'),
							'user_id'		=> array('INDEX', 'user_id'),
						),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_tables'		=> array(
				$this->table_prefix . 'connect_log',
			),
		);
	}

	public function update_data()
	{
		return array(
			// Current version
			array('config.add', array('connectionlog_version', '0.0.1')),
			array('config.add', array('lc_expire_days', '7')),
			array('config.add', array('lc_prune_gc', '3600', '0')),
			array('config.add', array('lc_prune_last_gc', '0', '1')),
			// ACP
			array('module.add', array('acp', 'ACP_FORUM_LOGS', 'ACP_CONNECTION_LOGS')),
			array('module.add', array('acp', 'ACP_CONNECTION_LOGS', array(
				'module_basename'	=> '\sheer\connectionlog\acp\connectionlog_module',
				'module_langname'	=> 'ACP_CONNECTION_LOGS',
				'module_mode'		=> 'connection',
				'module_auth'		=> 'ext_sheer/connectionlog && acl_a_viewlogs',
				'module_enabled'	=> true,
			))),
		);
	}
}
