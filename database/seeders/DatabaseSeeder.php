<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 */
	public function run(): void
	{
		// \App\Models\User::factory(10)->create();

		\App\Models\User::factory()->create([
			'name'     => 'GrCOTE7',
			'email'    => 'GrCOTE7@gmail.com',
			'password' => md5('123123123'),
		]);

		$this->call(ImportSeeder::class);
	}
}
