import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.private('Notifications.' + userId)
    .notification(function(message) {
        let c = Number($('#unread-count').text())
        c++
        $('#unread-count').text(c)

        $('#n-list').prepend(`<a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i>
            ${message.title}
            <span class="float-right text-muted text-sm">now</span>
        </a>
        <div class="dropdown-divider"></div>`);

        $(document).Toasts('create', {
            title: message.title,
            body: message.body,
            animation: true,
            autohide: true,
            delay: 2000
          });
    
    });