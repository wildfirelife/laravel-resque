<?php namespace Awellis13\Resque\Console;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Config;
use Resque;
use Resque_Worker;
use ResqueScheduler_Worker;
use Resque_Log;

class SchedulerListenCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'resque:scheduler';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Run a resque scheduler worker';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
		// Read input
		$logLevel = $this->input->getOption('verbose') ? true : false;
		$interval = $this->input->getOption('interval');

		// Connect to redis
		Resque::setBackend(Config::get('database.redis.default.host').':'.Config::get('database.redis.default.port'));

		// Launch worker
		$worker = new ResqueScheduler_Worker();
		$worker->logLevel = $logLevel;

		fwrite(STDOUT, "*** Starting scheduler \n");
		$worker->work($interval);
	}
	
	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('interval', null, InputOption::VALUE_OPTIONAL, 'Amount of time to delay failed jobs', 5),
		);
	}

}
