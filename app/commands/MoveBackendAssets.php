<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class MoveBackendAssets extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'backend:assets';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Move backend compiled assets to themes folder.';

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
	 * @return mixed
	 */
	public function fire()
	{
		$sourceDir      = base_path() . '/vendor/subbly/backend/public/assets';
		$destinationDir = public_path() . '/backend/assets';

		if( !File::exists( $sourceDir ) )
			return;

		$success = File::copyDirectory($sourceDir, $destinationDir);

		if( $success )
			$this->info('Backend assets copied');
		else
			$this->error('Can not copy Backend assets files');
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			// array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			// array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
