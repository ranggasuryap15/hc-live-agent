@if ($message->from_user == \Auth::user()->nopeg)
<div class="message message_sent">
    <p data-message-id="{{ $message->id }}">
        {{ $message->content }}
    </p>
    <time datetime="{{ date(" Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{
        $message->created_at->diffForHumans() }}</time>
</div>


@else

<div class="message">
    <p data-message-id="{{ $message->id }}">
        {{ $message->content }}
    </p>
    <time datetime="{{ date(" Y-m-dTH:i", strtotime($message->created_at->toDateTimeString())) }}">{{
        $message->created_at->diffForHumans() }}</time>
</div>

@endif