<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>HC Live Agent</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/chat-hc.css') }}">
</head>

<body>
    <div class="container chat_box" id="chat_box">
        {{-- <div id="chat_box" class="chat_box"> --}}
            <div class="header">
                <input action="action" onclick="window.history.back(); return false;" type="submit" value="Back"
                    class="back-navigation" />
                <h1>HC Live Agent</h1>
            </div>
            <div class="body chat-area">
                {{-- disini akan muncul chat berdasarkan pengguna login --}}
            </div>
            <div class="send-area">
                <input type="text" class="chat_input" id="chat_input" name="" placeholder="Type a message" autofocus>
                <button type="button" class="btn-chat" data-to-user="{{ \Auth::user()->admin_to_live }}"
                    disabled>Send</button>
            </div>
            <input type="hidden" id="to_user_nopeg" value="{{ \Auth::user()->admin_to_live }}" />
            <input type="hidden" id="current_user" value="{{ \Auth::user()->nopeg }}" />
            <input type="hidden" id="pusher_app_key" value="{{ env('PUSHER_APP_KEY') }}" />
            <input type="hidden" id="pusher_cluster" value="{{ env('PUSHER_APP_CLUSTER') }}" />
        </div>
        {{-- </div> --}}

    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
    <script type=" text/javascript" src="{{ asset('js/chat-hc.js') }}"></script>
</body>

</html>