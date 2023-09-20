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
        // List user yang pesannya belum terbaca sama sekali atau sedang menunggu.
        $usersUnread = DB::table('users')
            ->join('messages', 'users.nopeg', '=', 'messages.from_user')
            ->select('users.*', 'messages.created_at as time_latest_message', 'messages.content as latest_message')->limit(1)
            ->where('users.nopeg', '!=', Auth::user()->nopeg)
            ->where('messages.is_read', '=', 'false')
            ->orderBy('messages.created_at', 'DESC')
            ->get();

        // siapakah user yang sedang login saat ini
        $userLogin = DB::table('users')->where('nopeg', Auth::user()->nopeg)->first();

        // mencari unread messages count
        $unreadCounts = [];

        foreach ($usersUnread as $userUnread) {
            $unreadCount = Message::where('from_user', $userUnread->nopeg)
                ->where('is_read', false)
                ->count();

            $unreadCounts[$userUnread->nopeg] = $unreadCount;
        }

        return view('admin.index', ['usersUnread' => $usersUnread, 'userLogin' => $userLogin, 'unreadCounts' => $unreadCounts]);
    }
}
