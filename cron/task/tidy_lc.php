<?php
/**
*
* @package phpBB Extension - Connection Log
* @copyright (c) 2015 Sheer
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace sheer\connectionlog\cron\task;

/**
* Tidy connection log cron task.
*
*/

class tidy_lc extends \phpbb\cron\task\base
{
	protected $config;
	protected $db;

	/**
	* Constructor.
	*/
	public function __construct(
		\phpbb\config\config $config,
		\phpbb\db\driver\driver_interface $db,
		\phpbb\user $user,
		\phpbb\log\log $phpbb_log,
		$connect_log_table
	)
	{
		$this->config = $config;
		$this->db = $db;
		$this->user = $user;
		$this->phpbb_log = $phpbb_log;
		$this->connect_log_table = $connect_log_table;
	}

	/**
	* Runs this cron task.
	*
	* @return null
	*/
	public function run()
	{
		$this->cron_tidy_lc();
	}

	/**
	* Returns whether this cron task should run now, because enough time
	* has passed since it was last run and clear log enable.
	* @return bool
	*/
	public function should_run()
	{
		if ($this->config['lc_expire_days'])
		{
			return $this->config['lc_prune_last_gc'] < time() - $this->config['lc_prune_gc'];
		}
		else
		{
			return false;
		}
	}

	public function cron_tidy_lc()
	{
		$diff = time() - ($this->config['lc_expire_days'] * 86400);
		$sql = 'DELETE FROM ' . $this->connect_log_table . ' WHERE log_time < '. $diff . '';
		$this->db->sql_query($sql);
		$this->phpbb_log->add('admin', $this->user->data['user_id'], $this->user->data['session_ip'], 'LOG_CLEAR_CONNECTION_LOG', time(), false);
		$this->config->set('lc_prune_last_gc', time(), true);
	}
}
