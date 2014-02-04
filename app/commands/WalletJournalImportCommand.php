<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Pheal\Pheal;

class WalletJournalImportCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'updateJournal';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Update Wallet Journals for all Active Characters';

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
		//Get a list of all Character IDs
		$chars = EveCharacter::select(array('characterID', 'characterName', 'api_key_id', 'api_key_vcode'))->where('status', '=', 1)->get();

		// loop through all characters and get download their latest wallet transactions
		foreach($chars as $char)
		{
			$wallet = $this->_getWallet($char->characterID, $char->api_key_id, $char->api_key_vcode);

			$data = $wallet->toArray()['result']['transactions'];
			$inserted = array();
			$whereIn = array();

			// build arrays to store ids
			foreach($data as $i => $row)
			{
				$inserted[$row['refID']] = $i;
				$whereIn[] = $row['refID'];
			}

			// find and remove redundant downloaded data
			$rows = WalletJournal::select('refID')->whereIn('refID', $whereIn)->get();
			foreach($rows as $row)
			{
				if(isset($inserted[$row->refID]))
				{
					unset($data[$inserted[$row->refID]]);
				}
			}

			// insert remaining data into the DB
			foreach($data as $row)
			{
				WalletJournal::create($row);
			}
			$this->info('Updated Character Wallet Journal ('.$char->characterID.'): '.$char->characterName);
		}
	}

	private function _getWallet($character_id, $key_id, $key_vcode, $from = '')
	{
		\Pheal\Core\Config::getInstance()->cache = new \Pheal\Cache\FileStorage('/www/brave-auth.com/data/phealng/');
		\Pheal\Core\Config::getInstance()->access = new \Pheal\Access\StaticCheck();

		$pheal = new Pheal($key_id, $key_vcode, "char");
		try
		{
			$data = array(
				"characterID" => (string)$character_id,
				'accountKey' => '1000',
				'rowCount' => '256'
			);

			// support walking
			if($from != '')
			{
				$data['fromID'] = $from;
			}

			$response = $pheal->WalletJournal($data);
			return $response;
		}
		catch (\Pheal\Exceptions\PhealException $e)
		{
			echo sprintf(
				"An exception was caught! Type: %s Message: %s",
				get_class($e),
				$e->getMessage()
			);
			return false;
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array();
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array();
	}

}