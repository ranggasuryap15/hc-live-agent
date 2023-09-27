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
        $usersUnread = DB::table('users as u')
            ->join('conversations as c', 'c.sender_nopeg', '=', 'u.nopeg')
            ->join(DB::raw('(select m.*, row_number() over(partition by m.conversation_id order by m.created_at desc) rn from messages m) as m'), function ($join) {
                $join->on('m.conversation_id', '=', 'c.conversation_id')
                    ->where('m.rn', 1);
            })
            ->select('u.*', 'm.created_at as time_latest_message', 'm.content as latest_message', 'c.conversation_id', 'c.in_queue', 'c.is_resolved')
            ->where('u.nopeg', '!=', Auth::user()->nopeg)
            ->orderBy('time_latest_message', 'DESC')
            ->where('c.in_queue', true)
            ->where('c.is_resolved', false)
            ->where('u.admin_to_live', Auth::user()->nopeg)
            ->get();

        // list user message yang udah terselesaikan
        $resolved = DB::table('users as u')
            ->join('conversations as c', 'c.sender_nopeg', '=', 'u.nopeg')
            ->join(DB::raw('(select m.*, row_number() over(partition by m.conversation_id order by m.created_at desc) rn from messages m) as m'), function ($join) {
                $join->on('m.conversation_id', '=', 'c.conversation_id')
                    ->where('m.rn', 1);
            })
            ->select('u.*', 'm.created_at as time_latest_message', 'm.content as latest_message', 'c.conversation_id', 'c.in_queue', 'c.is_resolved')
            ->where('u.nopeg', '!=', Auth::user()->nopeg)
            ->orderBy('time_latest_message', 'DESC')
            ->where('c.in_queue', false)
            ->where('c.is_resolved', true)
            ->where('u.admin_to_live', Auth::user()->nopeg)
            ->get();

        // siapakah user yang sedang login saat ini
        $userLogin = DB::table('users')->where('nopeg', Auth::user()->nopeg)->first();

        // mencari unread messages count
        $unreadCounts = [];

        foreach ($usersUnread as $userUnread) {
            $unreadCount = DB::table('messages as m')
                ->join('conversations as c', 'm.conversation_id', '=', 'c.conversation_id')
                ->where('c.sender_nopeg', $userUnread->nopeg)
                ->where('is_read', false)
                ->count();

            $unreadCounts[$userUnread->nopeg] = $unreadCount;
        }

        return view('admin.index', [
            'usersUnread' => $usersUnread,
            'userLogin' => $userLogin,
            'unreadCounts' => $unreadCounts,
            'resolved' => $resolved
        ]);
    }
}
