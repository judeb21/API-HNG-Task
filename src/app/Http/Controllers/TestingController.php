<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestingController extends Controller
{
	public function index(Request $request){
		echo $request->mytest;
	}
}
