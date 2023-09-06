@if ($message->from_user == \Auth::user()->nopeg)

<p class="message user_message" data-message-id="{{ $message->id }}">{{ $message->content }}</p>

@else

<p class="message" data-message-id="{{ $message->id }}">{{ $message->content }}</p>

@endif