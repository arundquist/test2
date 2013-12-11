<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		// $this->call('UserTableSeeder');
		$this->call('TweetsTableSeeder');
		$this->call('CoursesTableSeeder');
		$this->call('TermsTableSeeder');
		$this->call('DeptsTableSeeder');
		$this->call('InstructorsTableSeeder');
		$this->call('TimesTableSeeder');
		$this->call('RoomsTableSeeder');
		$this->call('HpsTableSeeder');
		$this->call('BuildingsTableSeeder');
		$this->call('AreasTableSeeder');
	}

}