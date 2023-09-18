@if ($message->from_user == \Auth::user()->nopeg)
{{-- <div class="message message_sent">
    <p data-message-id="{{ $message->id }}">
        {{ $message->content }}
    </p>
    <time datetime="{{ date(" Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{
        $message->created_at->diffForHumans() }}</time>
</div> --}}
<div class="chat-message message message_sent">
    <div class="flex items-end">
        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
            <div>
                <p data-message-id=""
                    class="px-2 py-2 rounded-lg inline-block rounded-br-none bg-green-200 bg-200 text-black-600">
                    {{ $message->content }}
                </p>
                <time datetime="{{ date(" Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{
                    $message->created_at->diffForHumans() }}</time>
            </div>
        </div>
        {{-- <img src="{{ asset('img/profile.jpg') }}" class="w-6 h-6 rounded-full order-1" alt=""> --}}
    </div>
</div>


@else

{{-- <div class="message">
    <p data-message-id="{{ $message->id }}">
        {{ $message->content }}
    </p>
    <time datetime="{{ date(" Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{
        $message->created_at->diffForHumans() }}</time>
</div> --}}
<div class="chat-message message">
    <div class="flex items-end">
        <div class="flex flex-col space-y-2 text-xs max-w-xs mx-2 order-2 items-start">
            <div>
                <p data-message-id=""
                    class="px-2 py-2 rounded-lg inline-block rounded-bl-none bg-gray-200 bg-200 text-gray-600">
                    {{ $message->content }}
                </p>
                <time datetime="{{ date(" Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{
                    $message->created_at->diffForHumans() }}</time>
            </div>
        </div>
        {{-- <img src="{{ asset('img/profile.jpg') }}" class="w-6 h-6 rounded-full order-1" alt=""> --}}
    </div>
</div>
@endif