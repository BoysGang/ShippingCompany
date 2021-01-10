<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
	public function requests()
	{
		return view('client/requests');
	}
}
