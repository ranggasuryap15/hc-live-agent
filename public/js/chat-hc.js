$(function () {
    // init the pusher
    let pusher = new Pusher($("#pusher_app_key").val(), {
        cluster: $("#pusher_cluster").val(),
        encrypted: true,
    });

    let channel = pusher.subscribe("chat");

    loadLatestMessages($("#chat_box"), $("#to_user_nopeg").val());

    $(".chat_input").on("keyup", function () {
        if ($(this).val() != "") {
            $(this)
                .parents(".send-area")
                .find(".btn-chat")
                .prop("disabled", false);
        } else {
            $(this)
                .parents(".send-area")
                .find(".btn-chat")
                .prop("disabled", true);
        }
    });

    // on click the btn send the message
    $(".btn-chat").on("click", function (e) {
        send(
            $(this).attr("data-to-user"),
            $(".send-area").find(".chat_input").val()
        );
    });

    // listen for the send event, this event will be triggered on click the send btn
    channel.bind("chat", function (data) {
        displayMessage(data.data);
    });

    // // handle the scroll top of any chat box
    // // the idea is to load the last messages by date depending of last message
    // // that's already loaded on the chat box
    // let lastScrollTop = 0;

    $(".chat-area").on("scroll", function (e) {
        let st = $(this).scrollTop();
        console.log(st);
        if (st < lastScrollTop) {
            fetchOldMessages(
                $(this).parents("#chat_box").find("#to_user_nopeg").val(),
                $(this).find(".message:first-child").attr("data-message-id")
            );
        }

        lastScrollTop = st;
    });

    // listen for the oldMsgs event, this event will be triggered on scroll top
    channel.bind("oldMsgs", function (data) {
        displayOldMessages(data);
    });
});

/**
 * loaderHtml
 * @returns {string}
 */
// function loaderHtml() {
//     return '<i class="glyphicon glyphicon-refresh loader"></i>=';
// }

/**
 * loadLatestMessages
 *
 */
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
        // beforeSend: function () {
        //     if (chat_area.find(".loader").length == 0) {
        //         chat_area.html(loaderHtml());
        //     }
        // },
        success: function (response) {
            if (response.state == 1) {
                console.log(response);
                response.messages.map(function (val, index) {
                    $(val).appendTo(chat_area);
                });
            }
        },
        complete: function () {
            // chat_area.find(".loader").remove();
        },
    });
}

/**
 * send
 */
function send(to_user, message) {
    let send_area = $(".send-area");

    $.ajax({
        url: "/send",
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
            to_user: to_user,
            message: message,
        },
        method: "POST",
        dataType: "json",
        // sebelum kirim pesan, tampilkan loader
        // beforeSend: function () {
        //     if (chat_area.find(".loader").length == 0) {
        //         chat_area.html(loaderHtml());
        //     }
        // },
        success: function (response) {
            console.log(response);
        },
        complete: function () {
            // send_area.find(".loader").remove();
            send_area.find(".btn-chat").prop("disabled", true);
            send_area.find(".chat_input").val("");
            // send_area.animate(
            //     {
            //         scrollTop:
            //             chat_area.offset().top + chat_area.outerHeight(true),
            //     },
            //     800,
            //     "swing"
            // );
        },
    });
}

/**
 * fetchOldMessages
 */
function fetchOldMessages(to_user, old_message_id) {
    let send_area = $(".send_area");

    $.ajax({
        url: "/fetch-old-messages",
        data: {
            to_user: to_user,
            old_message_id: old_message_id,
            _token: $("meta[name='csrf-token']").attr("content"),
        },
        method: "GET",
        dataType: "json",
        // beforeSend: function () {
        //     if (send_area.find(".loader").length == 0) {
        //         chat_area.prepend(loaderHtml());
        //     }
        // },
        success: function (response) {
            console.log(response);
        },
        complete: function () {
            // send_area.find(".loader").remove();
            console.log("complete");
        },
    });
}

function getMessageSenderHtml(message) {
    let content = `
        <p class="message user_message" data-message-id="${message.id}">
            ${message.content}
        </p>`;
    return content;
}

function getMessageReceiverHtml(message) {
    let content = `<p class="message" data-message-id="${message.id}">${message.content}</p>`;
    return content;
}

function displayMessage(message) {
    console.log(message.from_user_id);
    // if ($("#current_user").val() == message.from_user_id) {
    //     let messageLine = getMessageSenderHtml(message);
    //     $("#chat_box_" + messageLine.to_user_id)
    //         .find(".chat-area")
    //         .append(messageLine);
    // } else if ($("#current_user").val() == message.to_user_id) {
    //     cloneChatBox(message.from_user_id, message.fromUserName, function () {
    //         let chatBox = $("#chat_box_" + message.from_user_id);
    //         let messageLine = getMessageReceiverHtml(message);
    //         $("#chat_box_" + message.from_user_id)
    //             .find(".chat-area")
    //             .append(messageLine);
    //     });
    // }
}

function displayOldMessages(data) {
    if (data.data.length > 0) {
        data.data.map(function (val, index) {
            $("#chat_box_" + data.to_user)
                .find(".chat-area")
                .prepend(val);
        });
    }
}
