<?php
/**
*
* @package phpBB Extension - Connection Log
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\connectionlog\acp;

class connectionlog_info
{
	function module()
	{
		return array(
			'filename'	=> '\sheer\connectionlog\acp\connectionlog_module',
			'version'	=> '1.0.0',
			'title' => 'ACP_CONNECTION_LOGS',
			'modes'		=> array(
				'settings'	=> array(
					'title' => 'ACP_CONNECTION_LOGS',
					'auth' => 'ext_sheer/connectionlog && acl_a_viewlogs',
					'cat' => array('ACP_FORUM_LOGS')
				),
			),
		);
	}
}
