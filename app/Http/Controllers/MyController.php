<?php

namespace App\Http\Controllers;

use App\Http\Tools\Gc7;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class MyController extends Controller
{
	public function index(mixed $data = null): View
	{
		$data = (new AdController())->index();

		// define('DATA', $this->getFrDate());
		// return view('test')->withDataSend(DATA);

		// $data = Gc7::affData($data ?? null);

		return view('pages.test', compact('data'));
	}

	public function users($id = null)
	{
		$users = User::all();

		return view('test', ['dataSend' => $users]);
	}

	private function getFrDate()
	{
		date_default_timezone_set('Europe/Paris');
		$date = Carbon::now()->locale('fr');

		return ucfirst($date->isoFormat('LLLL'));
	}
}
