<?php

namespace App\Http\Controllers;

use App\Conversation;
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
        if (!$request->conversation_id) {
            return response()->json(['state' => 0, 'message' => $request->conversation_id]);
        }

        $messages = DB::table('messages as m')
            ->select("m.id", "m.content", "m.is_read", "m.created_at", "m.updated_at", "c.conversation_id", "c.sender_nopeg", "c.admin", "c.in_queue", "c.is_resolved")
            ->join("conversations as c", "m.conversation_id", "=", "c.conversation_id")
            ->where('m.conversation_id', $request->conversation_id)
            ->where('c.in_queue', true)
            ->where('c.is_resolved', false)
            ->where('c.sender_nopeg', Auth::user()->nopeg)
            ->get();

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
        if (!$request->conversation_id || !$request->message) {
            return response()->json(['state' => 0, 'messages' => $request->message]);
        }

        $message = new Message();

        $message->content = $request->message;
        $message->conversation_id = $request->conversation_id;
        $message->save();

        // prepare some data to send with the response
        $message->dateTimeStr = date("Y-m-dTH:i", strtotime($message->created_at->toDateTimeString()));

        $message->dateHumanReadable = $message->created_at->diffForHumans();

        $message->from_user_nopeg = Auth::user()->nopeg;

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
        $message = DB::table('messages as m')
            ->join('conversations as c', 'c.conversation_id', '=', 'c.conversation_id')
            ->where('c.sender_nopeg', $user->nopeg)
            ->update(['is_read' => true]);

        // $message = Message::where('from_user', $user->nopeg)
        //     ->where('is_read', false)
        //     ->update(['is_read' => true]);

        return response()->json(['state' => 1, 'data' => $message]);
    }
}
