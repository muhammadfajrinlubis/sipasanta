document.addEventListener('DOMContentLoaded', function () {
    // Enable Pusher logging - Only for debugging (remove in production)
    Pusher.logToConsole = true;

    // Initialize Pusher for laundry
    var pusherLaundry = new Pusher('27829950f08a0c84e275', {
        cluster: 'ap1'
    });
    var channelLaundry = pusherLaundry.subscribe('laundry-channel');

    // Unread count for laundry notifications
    var unreadCountLaundry = localStorage.getItem('unreadCountLaundry') ? parseInt(localStorage.getItem('unreadCountLaundry')) : 0;

    // Elements for laundry notifications
    const mediaGroupElementLaundry = document.querySelector('.dropdown-laundry');
    const notificationBadgeLaundry = document.querySelector('.notification-count-laundry');
    const notifIconLaundry = document.getElementById('notifLaundry');

    if (!mediaGroupElementLaundry || !notificationBadgeLaundry || !notifIconLaundry) {
        console.error("Elemen notifikasi laundry tidak ditemukan.");
        return;
    }

    // Load laundry notifications
    function loadLaundryNotifications() {
        const notifications = JSON.parse(localStorage.getItem('laundry_notifications')) || [];
        mediaGroupElementLaundry.innerHTML = '';
        notifications.forEach(notification => {
            const notificationItem = createLaundryNotificationItem(notification);
            mediaGroupElementLaundry.insertAdjacentHTML('afterbegin', notificationItem);
        });
        updateLaundryNotificationBadge();
    }

    // Update laundry notification badge
    function updateLaundryNotificationBadge() {
        notificationBadgeLaundry.style.display = unreadCountLaundry > 0 ? 'inline-block' : 'none';
        notificationBadgeLaundry.textContent = unreadCountLaundry;
    }

    // Create laundry notification item
    function createLaundryNotificationItem(notification) {
        return `
            <a href="/admin/laundry" class="media-group-item" data-id="${notification.id}">
                <div class="media">
                    <div class="media-left">
                        <div class="avatar avatar-xs avatar-circle bg-primary text-white">👕</div>
                    </div>
                    <div class="media-body">
                        <div><strong>Permintaan Laundry Baru</strong></div>
                        <small>${notification.deskripsi}</small><br>
                        <small>${notification.tgl_pengaduan}</small>
                    </div>
                </div>
            </a>
        `;
    }

    // Mark laundry notification as read
    function markLaundryAsRead(notificationId) {
        const notifications = JSON.parse(localStorage.getItem('laundry_notifications')) || [];
        const updatedNotifications = notifications.filter(notification => {
            if (notification.id === notificationId) {
                unreadCountLaundry = Math.max(unreadCountLaundry - 1, 0);
                return false; // Remove the notification
            }
            return true; // Keep the notification
        });

        localStorage.setItem('laundry_notifications', JSON.stringify(updatedNotifications));
        localStorage.setItem('unreadCountLaundry', unreadCountLaundry);
        loadLaundryNotifications();
        updateLaundryNotificationBadge();
    }

    // Event Delegation for laundry notifications
    mediaGroupElementLaundry.addEventListener('click', function (event) {
        const target = event.target.closest('.media-group-item');
        if (target) {
            const notificationId = parseInt(target.getAttribute('data-id'));
            if (!isNaN(notificationId)) {
                markLaundryAsRead(notificationId);
                // Redirect to the laundry page
                window.location.href = target.href;
            }
        }
    });

    // Handle Pusher event for new laundry requests
    channelLaundry.bind('laundry-requested', function (data) {
        const notification = {
            id: data.id,
            deskripsi: `Laundry untuk NORM ${data.nomr} di ruangan ${data.ruangan}`,
            tgl_pengaduan: data.tanggal,
            read: false // New notifications are unread
        };

        const notifications = JSON.parse(localStorage.getItem('laundry_notifications')) || [];
        notifications.push(notification);
        localStorage.setItem('laundry_notifications', JSON.stringify(notifications));

        unreadCountLaundry++;
        localStorage.setItem('unreadCountLaundry', unreadCountLaundry);

        const notificationItem = createLaundryNotificationItem(notification);
        mediaGroupElementLaundry.insertAdjacentHTML('afterbegin', notificationItem);

        updateLaundryNotificationBadge();

        // Play notification sound
        const audio = new Audio('/sound/notification.mp3');
        audio.play().catch(function (error) {
            console.error("Autoplay failed: ", error);
        });
    });

    // Load initial notifications
    loadLaundryNotifications();
});
