<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Chat with {{ $receiver->name }}</title>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
</head>

<body>
    <div id="chat">
        <div id="messages">
            <!-- Messages will be displayed here -->
            @foreach ($messages as $message)
                <div class="message {{ $message->sender_id == Auth::user()->id ? 'sender' : 'receiver' }}">
                    <span class="message-sender">
                        {{ $message->sender->name }}
                        </span>
                    {{ $message->message }}
                    </div>
            @endforeach
            </div>
        <div id="message-form">
            <input type="text" id="message-input" placeholder="Type a message">
            <button id="send-button">Send</button>
            </div>
        </div>


    <script>
        Pusher.logToConsole = true;
        var pusher = new Pusher('a3647b96754ff79530ea', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('chat.{{ $receiver->id }}');
        channel.bind('App\\Events\\NewMessage', function(data) {
            $('#messages').append('<div class="message">' + data.message.message + '</div>');
        });

        pusher.subscribe('chat.{{ Auth::user()->id }}').bind('App\\Events\\NewMessage', function(data) {
            $('#messages').append('<div class="message">' + data.message.message + '</div>');
        });

        // Handle sending messages
        $('#send-button').click(function() {
            const message = $('#message-input').val();
            const receiverId = {{ $receiver->id }};

            // Send the message to the server via AJAX
            $.ajax({
                url: '{{ route('chat.send') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    receiver_id: receiverId,
                    message: message
                },
                success: function(response) {
                    // Clear the input field
                    $('#message-input').val('');
                }
            });
        });
    </script>

</body>

</html>
