<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Dashboard</title>
</head>

<body>
    <div class="relative min-h-screen flex flex-col bg-gray-50">
        <nav class="flex-shrink-0 bg-green-600">
            <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">
                <div class="relative flex items-center justify-between h-14">
                    <div></div>
                    <div class="flex lg:hidden">
                        <button
                            class="bg-green-600 inline-flex items-center justify-center p-2 rounded-md text-white hover:text-white hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-600 focus:ring-white">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                                <path d="M120-240v-80h720v80H120Zm0-200v-80h720v80H120Zm0-200v-80h720v80H120Z" />
                            </svg>
                        </button>
                    </div>

                    <div class="hidden lg:block lg:w-80">
                        <div class="flex items-center justify-end">
                            <div class="flex">
                                <a href="" class="px-3 py-2 rounded-md text-sm font-medium text-white hover:text-white">
                                    Chat
                                </a>
                            </div>
                            <div class="ml-4 relative flex-shrink-0">
                                <div>
                                    <button
                                        class="bg-green-700 flex text-sm rounded-full text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset--700 focus:ring-white">
                                        <img class="h-8 w-8 rounded-full" src="{{ asset('img/profile.jpg') }}" alt="">
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
        {{-- NAV SECTION ENDS HERE --}

        {{-- Chat Layout Starts here --}}
        <div class="flex-grow w-full max-w-7xl mx-auto lg:flex">
            <div class="flex-1 min-w-0 bg-white lg:flex">
                <div
                    class="border-b border-gray-200 lg:border-b-0 lg:flex-shrink-0 lg:w-64 lg:border-r lg:border-gray-200 bg-gray-50">
                    <div class="h-full pl-4 pr-2 py-6 sm:pl-6 lg:pl-0 xl:pl-0">
                        <div class="h-full relative">
                            <div
                                class="relative rounded-lg px-2 py-2 flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500 mb-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full" src="{{ asset('img/profile.jpg') }}" />
                                </div>
                                <div class="flex-1 min-w-0">
                                    <a href="" class="focus:outline-none">
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        <p class="text-sm font-bold text-green-600">{{ $userLogin->name }}</p>
                                        <p class="text-sm text-gray-500 truncate">Project Manager</p>
                                    </a>
                                </div>
                            </div>

                            {{-- search box start --}}
                            <div class="mb-4">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960"
                                            width="24">
                                            <path
                                                d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" placeholder="Search"
                                        class="focus:ring-green-500 focus:border-green-500 block w-full pl-10 sm:text-sm border-gray-100 rounded-full p-2 border">
                                </div>
                            </div>
                            {{-- search box end --}}
                            <div>
                                Unread Messages
                            </div>
                            {{-- user lists --}}
                            @if($usersUnread->count() > 0)
                            @foreach($usersUnread as $user)
                            <a href="#" data-id="{{ $user->nopeg }}" data-user="{{ $user->name }}"
                                class="chat-toggle relative rounded-lg px-2 py-2 flex items-center space-x-3 hover:border-gray-400 focus-within:ring-2 mb-3 hover:bg-gray-200 hover:cursor-pointer">
                                <div class="flex-shrink-0">
                                    <img class="h-10 w-10 rounded-full" src="{{ asset('img/profile.jpg') }}" alt="">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="focus:outline-none">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-bold text-green-600">
                                                {{ $user->name }}
                                            </p>

                                            {{-- menampilkan jam terakhir pesan dikirim --}}
                                            <div class="text-gray-400 text-xs">
                                                {{ date('H:i', strtotime($user->time_latest_message)) }}
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            {{-- menampilkan isi pesan terakhir --}}
                                            <p class="text-sm text-gray-500 truncate">
                                                {{ $user->latest_message }}
                                            </p>

                                            {{-- menampilkan jumlah pesan yang belum terbaca --}}
                                            @if ($unreadCounts[$user->nopeg] > 0)
                                            <div id="unread_count_{{ $user->nopeg }}"
                                                class="text-white text-xs bg-green-400 rounded-full px-1 py-0">
                                                {{ $unreadCounts[$user->nopeg] }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </a>
                            @endforeach
                            @else
                            <p>No User</p>
                            @endif
                            {{-- user lists end --}}
                        </div>
                    </div>
                </div>

                {{-- Chat Box here --}}
                @include('admin.chat')

                <div id="chat-overlay" class="flex-1 p:2 sm:pb-2 flex flex-col h-full hidden lg:flex"></div>
            </div>
            {{-- Chat Layout Ends here --}}
        </div>
    </div>

    <input type="hidden" id="current_user" value="{{ \Auth::user()->nopeg }}" />
    <input type="hidden" id="pusher_app_key" value="{{ env('PUSHER_APP_KEY') }}" />
    <input type="hidden" id="pusher_cluster" value="{{ env('PUSHER_APP_CLUSTER') }}" />

    <script src="{{ asset('js/chat-admin.js') }}"></script>
    <script src="https://js.pusher.com/4.1/pusher.min.js"></script>
</body>

</html>