<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    function index()
    {
        $users = User::where('nopeg', '!=', Auth::user()->nopeg)->get();
        $userLogin = DB::table('users')->where('nopeg', Auth::user()->nopeg)->first();
        return view('admin.index', ['users' => $users, 'userLogin' => $userLogin]);
    }
}
