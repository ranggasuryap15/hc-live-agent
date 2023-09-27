<?php

namespace App\Http\Controllers;

use App\Conversation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ConversationController extends Controller
{
    function create(Request $request)
    {
        $conversation = Conversation::where('conversation_id', $request->conversation_id)->first();

        // jika ada maka update menjadi in_queue dan unresolved
        if ($conversation) {
            $conversation->update([
                'in_queue' => true,
                'is_resolved' => false,
            ]);
            $conversation->save();
            return response()->json([
                'state' => 0,
                'message' => ['Conversation updated'],
                'data' => $conversation
            ]);
        }

        // jika check table query tidak ada, maka buat baru
        $conversations = new Conversation;

        // insert data ke table conversations
        $conversations->conversation_id = $request->conversation_id;
        $conversations->sender_nopeg = $request->sender_nopeg;
        $conversations->admin = $request->admin;
        $conversations->save();

        return response()->json([
            'state' => 1,
            'data' => $conversation
        ]);
    }
}
