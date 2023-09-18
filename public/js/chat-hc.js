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
    channel.bind("send", function (data) {
        displayMessage(data.data);
    });

    // // handle the scroll top of any chat box
    // // the idea is to load the last messages by date depending of last message
    // // that's already loaded on the chat box

    // let lastScrollTop = 0;

    // $(".chat_box").on("scroll", function (e) {
    //     let st = $(this).scrollTop();
    //     console.log(st);
    //     // if (st < lastScrollTop) {
    //     //     fetchOldMessages(
    //     //         $(this).find("#to_user_nopeg").val(),
    //     //         $(this).find(".message:first-child").attr("data-message-id")
    //     //     );
    //     // }

    //     lastScrollTop = st;
    // });

    // listen for the oldMsgs event, this event will be triggered on scroll top
    // channel.bind("oldMsgs", function (data) {
    //     displayOldMessages(data);
    // });
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
                response.messages.map(function (val) {
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
            send_area.animate(
                {
                    scrollTop:
                        send_area.offset().top + send_area.outerHeight(true),
                },
                800,
                "swing"
            );
        },
    });
}

function getMessageSenderHtml(message) {
    let content = `
        <div class="message message_sent">
            <p data-message-id="${message.id}">${message.content}</p>
            <time datetime="${message.dateTimeStr}">${message.dateHumanReadable}</time>
        </div>`;
    return content;
}

function getMessageReceiverHtml(message) {
    let content = `
    <div class="message">
        <p data-message-id="${message.id}">${message.content}</p>
        <time datetime="${message.dateTimeStr}">${message.dateHumanReadable}</time>
    </div>
    `;
    return content;
}

function displayMessage(message) {
    if ($("#current_user").val() == message.from_user_nopeg) {
        let senderMessageLine = getMessageSenderHtml(message);
        $("#chat_box").find(".chat-area").append(senderMessageLine);
    } else if ($("#current_user").val() == message.to_user_nopeg) {
        let receiverMessageLine = getMessageReceiverHtml(message);
        $("#chat_box").find(".chat-area").append(receiverMessageLine);
    }
}

// sepertinya ini tidak perlu digunakan dulu, logicnya adalah mungkin hanya menampilkan chat di rentang waktu sekian sampai sekian (24 jam)
// function displayOldMessages(data) {
//     if (data.data.length > 0) {
//         data.data.map(function (val) {
//             $("#chat_box").find(".chat-area").prepend(val);
//         });
//     }
// }
/**
 * fetchOldMessages
 */
// function fetchOldMessages(to_user, old_message_id) {
//     let send_area = $(".send_area");

//     $.ajax({
//         url: "/fetch-old-messages",
//         data: {
//             to_user: to_user,
//             old_message_id: old_message_id,
//             _token: $("meta[name='csrf-token']").attr("content"),
//         },
//         method: "GET",
//         dataType: "json",
//         // beforeSend: function () {
//         //     if (send_area.find(".loader").length == 0) {
//         //         chat_area.prepend(loaderHtml());
//         //     }
//         // },
//         success: function (response) {
//             console.log(response);
//         },
//         complete: function () {
//             // send_area.find(".loader").remove();
//             console.log("complete");
//         },
//     });
// }
