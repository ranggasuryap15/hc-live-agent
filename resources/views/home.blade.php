@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                </div>
            </div>

            {{-- Floating button to live chat --}}
            <a href="{{ route('hc-live-agent') }}">
                <button class="btn-floating" data-to-user="{{ \Auth::user()->admin_to_live }}">
                    Chat
                </button>
            </a>
        </div>
    </div>
</div>
@endsection