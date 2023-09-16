$(function () {
    let pusher = new Pusher($("#pusher_app_key").val(), {
        cluster: $("#pusher_cluster").val(),
        encrypted: true,
    });

    let channel = pusher.subscribe("chat");

    // on click name of chat message
    $(".chat-toggle").on("click", function (e) {
        e.preventDefault();

        let clickThis = $(this);

        let user_id = clickThis.attr("data-id");
        let userName = clickThis.attr("data-user");

        cloneChatBox(user_id, userName, function () {
            let chatBox = $("#chat_box_" + user_id);

            if (!chatBox.hasClass("chat-opened")) {
                chatBox.addClass("chat-opened").slideDown("fast");

                loadLatestMessages(chatBox, user_id);

                // chatbox auto scroll to bottom with swing animation
                chatBox.find(".chat-area").animate(
                    {
                        scrollTop:
                            chatBox.find(".chat-area").offset().top +
                            chatBox.find(".chat-area").outerHeight(true),
                    },
                    800,
                    "swing"
                );
            } else {
                // close chat
                chatBox.removeClass("chat-opened").slideUp("fast");
            }
        });
    });

    // enable button send when chat-input is typing
    $(".chat-input").on("change keyup", function () {
        if ($(this).val() != "") {
            $(this)
                .parents(".send-area")
                .find("#btn-chat")
                .prop("disabled", true);
        } else {
            $(this)
                .parents(".send-area")
                .find("#btn-chat")
                .prop("disabled", false);
        }
    });

    // mengirim pesan ketika tombol send di klik
    $(".btn-chat").on("click", function (e) {
        // parameter pertama adalah id, dan parameter kedua adalah value dari input chat
        send(
            $(this).attr("data-to-user"),
            $("#chat_box_" + $(this).attr("data-to-user"))
                .find(".chat-input")
                .val()
        );
    });

    // listen for the sedn event, this event wil be triggered on click the send btn
    channel.bind("send", function (data) {
        displayMessage(data.data);
    });
});

function cloneChatBox(user_id, username, callback) {
    if ($("#chat_box_" + user_id).length == 0) {
        let cloned = $("#chat_box").clone(true);

        // change cloned box id
        cloned.attr("id", "chat_box_" + user_id);
        cloned.find(".chat-user").text(username);
        cloned.find(".btn-chat").attr("data-to-user", user_id);
        cloned.find("#to-user-id").val(user_id);
        $("#chat-overlay").append(cloned);
    }

    callback();
}

// loadLatestMessages adalah ketika pengguna melakukan chat, maka dia akan me-load messages yang baru saja terkirim
function loadLatestMessages(container, user_id) {
    let chat_area = container.find(".chat-area");

    chat_area.html("");

    $.ajax({
        url: "/load-latest-messages",
        data: {
            user_id: user_id,
            _token: $("meta[name='csrf-token']").attr("content"),
        },
        method: "GET",
        dataType: "json",
        beforeSend: function () {
            console.log("before send");
        },
        success: function (response) {
            if (response.state == 1) {
                response.messages.map(function (val, index) {
                    $(val).appendTo(chat_area);
                });
            }
        },
        complete: function () {
            console.log("complete: remove loader");
        },
    });
}

// function send adalah untuk mengirim pesan dan masuk ke dalam database
function send(to_user, message) {
    let chat_box = $("#chat_box_" + to_user);
    let chat_area = chat_box.find(".chat-area");
    console.log(chat_box);
    $.ajax({
        url: "/send",
        data: {
            to_user: to_user,
            message: message,
            _token: $("meta[name='csrf-token']").attr("content"),
        },
        method: "POST",
        dataType: "json",
        beforeSend: function () {
            console.log("before send");
        },
        success: function (response) {
            console.log(response);
        },
        complete: function () {
            chat_box.find(".btn-chat").prop("disabled", true);
            chat_box.find(".chat-input").val("");
            // auto swing scroll to bottom
        },
    });
}

// menampilkan html dari pesan yang baru saja dikirim
function getMessageSenderHtml() {
    return `
        <div>
            pesan pengirim
        </div>
    `;
}

// menampilkan html dari pesan yang baru saja diterima
function getMessageReceiverHtml() {
    return `pesan penerima`;
}

// displayMessage adalah menampilkan pesan dari function getMessageSenderHtml() dan getMessageReceiverHtml()
function displayMessage(message) {
    // let alert_sound

    if ($("#current_user").val() == message.from_user_id) {
        let sender = getMessageSenderHtml(message);
        $("#chat_box_" + message.to_user_id)
            .find(".chat-area")
            .append(sender);
    } else if ($("#current_user").val() == message.to_user_id) {
        // play sound alert dan tampilkan angka di user tersebut

        let receiver = getMessageReceiverHtml(message);
        $("#chat_box_" + message.from_user_id)
            .find(".chat-area")
            .append(receiver);
    }
}
