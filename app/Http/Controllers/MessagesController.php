<?php

namespace App\Http\Controllers;

use App\Lib\PusherFactory;
use App\Message;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessagesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = DB::table('users')->select('*')->where('nopeg', '=', Auth::user()->nopeg)->first();
        return view('chat-hc', ['user' => $user]);
    }

    /**
     * getLoadLatestMessages
     *
     *
     * @param Request $request
     */
    public function getLoadLatestMessages(Request $request)
    {
        if (!$request->user_id) {
            return;
        }

        $messages = Message::where(function ($query) use ($request) {
            $query->where('from_user', Auth::user()->nopeg)->where('to_user', $request->user_id);
        })->orWhere(function ($query) use ($request) {
            $query->where('from_user', $request->user_id)->where('to_user', Auth::user()->nopeg);
        })->orderBy('created_at', 'ASC')->get();
        $result = [];

        foreach ($messages as $message) {
            $result[] = view('message-line')->with('message', $message)->render();
        }

        return response()->json(['state' => 1, 'messages' => $result]);
    }


    /**
     * postSendMessage
     *
     * @param Request $request
     */
    public function postSendMessage(Request $request)
    {
        if (!$request->to_user || !$request->message) {
            return;
        }

        $message = new Message();

        $message->from_user = Auth::user()->nopeg;

        $message->to_user = $request->to_user;

        $message->content = $request->message;

        // save to database
        $message->save();

        // prepare some data to send with the response
        $message->dateTimeStr = date("Y-m-dTH:i", strtotime($message->created_at->toDateTimeString()));

        $message->dateHumanReadable = $message->created_at->diffForHumans();

        $fromUser = DB::table('users')->select('*')->where('nopeg', '=', Auth::user()->nopeg)->first();
        $toUser = DB::table('users')->select('*')->where('nopeg', '=', $request->to_user)->first();

        $message->from_user_name = $fromUser->name;

        $message->from_user_nopeg = Auth::user()->nopeg;

        $message->to_user_name = $toUser->name;

        $message->to_user_nopeg = $request->to_user;

        PusherFactory::make()->trigger('chat', 'send', ['data' => $message]);

        return response()->json(['state' => 1, 'data' => $message]);
    }


    /**
     * getOldMessages
     *
     * we will fetch the old messages using the last sent id from the request
     * by querying the created at date
     *
     * @param Request $request
     */
    public function getOldMessages(Request $request)
    {
        if (!$request->old_message_id || !$request->to_user)
            return;

        $message = Message::find($request->old_message_id);

        $lastMessages = Message::where(function ($query) use ($request, $message) {
            $query->where('from_user', Auth::user()->nopeg)
                ->where('to_user', $request->to_user)
                ->where('created_at', '<', $message->created_at);
        })
            ->orWhere(function ($query) use ($request, $message) {
                $query->where('from_user', $request->to_user)
                    ->where('to_user', Auth::user()->nopeg)
                    ->where('created_at', '<', $message->created_at);
            })
            ->orderBy('created_at', 'ASC')->limit(10)->get();

        $result = [];

        if ($lastMessages->count() > 0) {
            foreach ($lastMessages as $message) {
                $result[] = view('message-line')->with('message', $message)->render();
            }

            PusherFactory::make()->trigger('chat', 'oldMsgs', ['to_user' => $request->to_user, 'data' => $result]);
        }

        return response()->json(['state' => 1, 'data' => $result]);
    }

    // function read messages
    public function markAsRead(Request $request)
    {
        $user = User::where('nopeg', $request->nopeg)->first();

        if (!$user) {
            return response()->json(['state' => 0, 'message' => "User not found", 'data' => $user]);
        }

        // tandai pesan sebagai sudah dibaca
        $message = Message::where('from_user', $user->nopeg)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['state' => 1, 'data' => $message]);
    }
}
