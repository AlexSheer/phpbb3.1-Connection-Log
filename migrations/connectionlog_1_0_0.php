<?php
/**
*
* @package phpBB Extension - Connection Log
* @copyright (c) 2017 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\connectionlog\migrations;

class connectionlog_1_0_0 extends \phpbb\db\migration\migration
{
	public function effectively_installed()
	{
		return isset($this->config['connectionlog_version']) && version_compare($this->config['connectionlog_version'], '1.0.0', '>=');
	}

	static public function depends_on()
	{
		return array('\sheer\connectionlog\migrations\connectionlog_0_0_1');
	}

	public function update_schema()
	{
		return array(
			'add_columns' => array(
				$this->table_prefix . 'connect_log' => array(
						'post_id'			=> array('UINT', 0),
				),
			),
		);
	}

	public function revert_schema()
	{
		return array(
			'drop_columns' => array(
				$this->table_prefix . 'connect_log' => array(
					'post_id',
				),
			),
		);
	}

	public function update_data()
	{
		return array(
			// Update configs
			array('config.update', array('connectionlog_version', '1.0.0')),
		);
	}
}
