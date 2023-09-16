<div id="chat_box" class="" style="display: none">
    <div class="lg:flex sm:items-center justify-between py-3 border-b border-gray-200 p-3">
        <div class="flex items-center space-x-4">
            <img src="{{ asset('img/profile.jpg') }}" class="w-10 sm:w-12 h-10 sm:h-12 rounded-full cursor pointer"
                alt="">
            <div class="flex flex-col leading-tight">
                <div class="text-1xl mt-1 flex items-center">
                    <span class="chat-user text-green-700 mr-3"></span>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button
                class="inline-flex items-center justify-center rounded-full h-10 w-10 transition duration-500 ease-in-out text-gray-500 hover:bg-gray-300 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path
                        d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Message starts here --}}
    <div id="message"
        class="chat-area flex flex-col space-y-4 p-3 scrollbar-thumb-blue h-full scrollbar-thumb-rounded scrollbar-track-blue-lighter scrollbar-w-2">
        {{-- message will be here --}}
    </div>
    {{-- Message ends here --}}

    {{-- Chat Area Starts Here --}}
    <div class="border-t-2 border-gray-200 px-4 pt-4 mb-2 mb-16 justify-between">
        <div class="relative flex send-area">
            <input type="textarea" placeholder="Message"
                class="chat-input focus:ring-green-500 focus:border-green-500 w-full focus:placeholder-gray-400 text-gray-600 placeholder-gray-300 pl-12 bg-gray-100 rounded-full py-3 border-gray-200">

            <button data-to-user=""
                class="btn-chat inline-flex items-center justify-center rounded-full h-12 w-12 transition duration-500 ease-in-out hover:bg-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 -960 960 960" width="24">
                    <path
                        d="M120-160v-640l760 320-760 320Zm80-120 474-200-474-200v140l240 60-240 60v140Zm0 0v-400 400Z" />
                </svg>
            </button>
            <input type="text" id="to-user-id" value="" hidden>
        </div>
    </div>
    {{-- Chat Area Ends Here --}}
</div>