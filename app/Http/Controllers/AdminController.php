<?php

namespace App\Http\Controllers;

use App\Message;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    //
    function index()
    {
        $users = User::where('nopeg', '!=', Auth::user()->nopeg)->get();

        $unreadCounts = [];

        foreach ($users as $user) {
            $unreadCount = Message::where('from_user', $user->nopeg)
                ->where('is_read', false)
                ->count();

            $unreadCounts[$user->nopeg] = $unreadCount;
        }


        $userLogin = DB::table('users')->where('nopeg', Auth::user()->nopeg)->first();
        return view('admin.index', ['users' => $users, 'userLogin' => $userLogin, 'unreadCounts' => $unreadCounts]);
    }
}
