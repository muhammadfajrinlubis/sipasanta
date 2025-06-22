document.addEventListener('DOMContentLoaded', function () {
    // Enable Pusher logging - Only for debugging (remove in production)
    Pusher.logToConsole = true;

    var pusher = new Pusher('27829950f08a0c84e275', {
        cluster: 'ap1'
    });

    var channel = pusher.subscribe('pengaduan-channel');
    var unreadCount = localStorage.getItem('unreadCount') ? parseInt(localStorage.getItem('unreadCount')) : 0;
    const mediaGroupElement = document.querySelector('.media-group.dropdown-menu');
    const notificationBadge = document.querySelector('.notification-count');

    if (!mediaGroupElement || !notificationBadge) {
        console.error("Required DOM elements are missing.");
        return;
    }

    function loadNotifications() {
        const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
        mediaGroupElement.innerHTML = '';
        notifications.forEach(notification => {
            const notificationItem = createNotificationItem(notification);
            mediaGroupElement.insertAdjacentHTML('afterbegin', notificationItem);
        });
        updateNotificationBadge();
    }

    function updateNotificationBadge() {
        notificationBadge.style.display = unreadCount > 0 ? 'inline-block' : 'none';
        notificationBadge.textContent = unreadCount;
    }

    function createNotificationItem(notification) {
        const readClass = notification.read ? 'read' : 'unread';
        return `
            <a href="/admin/pengaduan/detail/${notification.id}" class="media-group-item ${readClass}" data-id="${notification.id}">
                <div class="media">
                    <div class="media-left">
                        <div class="avatar avatar-xs avatar-circle">
                            <i class="status status-online"></i>
                        </div>
                    </div>
                    <div class="media-body">
                        <div><strong>Pengaduan Baru</strong></div>
                        <small>${notification.deskripsi}</small><br>
                        <small>${notification.tgl_pengaduan}</small>
                    </div>
                </div>
            </a>
        `;
    }

    function markAsRead(notificationId) {
        const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
        const updatedNotifications = notifications.filter(notification => {
            if (notification.id === notificationId) {
                unreadCount = Math.max(unreadCount - 1, 0);
                return false;
            }
            return true;
        });

        localStorage.setItem('notifications', JSON.stringify(updatedNotifications));
        localStorage.setItem('unreadCount', unreadCount);
        loadNotifications();
        updateNotificationBadge();
    }

    // Event Delegation: listen click in mediaGroupElement
    mediaGroupElement.addEventListener('click', function (event) {
        const target = event.target.closest('.media-group-item');
        if (target) {
            const notificationId = parseInt(target.getAttribute('data-id'));
            if (!isNaN(notificationId)) {
                markAsRead(notificationId);
            }
        }
    });

    // Handle Pusher event
    channel.bind('pengaduan-event', function (data) {
        console.log('Data pengaduan baru:', data);

        const notification = {
            id: data.pengaduan.id,
            deskripsi: data.pengaduan.deskripsi,
            tgl_pengaduan: data.pengaduan.tgl_pengaduan,
            read: false
        };

        const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
        notifications.push(notification);
        localStorage.setItem('notifications', JSON.stringify(notifications));

        unreadCount++;
        localStorage.setItem('unreadCount', unreadCount);

        const notificationItem = createNotificationItem(notification);
        mediaGroupElement.insertAdjacentHTML('afterbegin', notificationItem);

        updateNotificationBadge();

        // Play notification sound
        const audio = new Audio('/sound/notification.mp3');
        audio.play().catch(function (error) {
            console.error("Autoplay failed: ", error);
        });

        // Optional: Auto reload
        setTimeout(() => {
            location.reload();
        }, 3000);
    });

    loadNotifications();
});


