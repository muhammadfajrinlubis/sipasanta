document.addEventListener('DOMContentLoaded', function () {
    Pusher.logToConsole = true;

    var pusherLaundry = new Pusher('27829950f08a0c84e275', {
        cluster: 'ap1'
    });

    var channelLaundry = pusherLaundry.subscribe('laundry-channel');

    var unreadCountLaundry = localStorage.getItem('unreadCountLaundry') ? parseInt(localStorage.getItem('unreadCountLaundry')) : 0;

    const mediaGroupElementLaundry = document.querySelector('.dropdown-laundry');
    const notificationBadgeLaundry = document.querySelector('.notification-count-laundry');
    const notifIconLaundry = document.getElementById('notifLaundry');

    if (!mediaGroupElementLaundry || !notificationBadgeLaundry || !notifIconLaundry) {
        console.error("Elemen notifikasi laundry tidak ditemukan.");
        return;
    }

    function loadLaundryNotifications() {
        const notifications = JSON.parse(localStorage.getItem('laundry_notifications')) || [];
        mediaGroupElementLaundry.innerHTML = '';
        notifications.forEach(notification => {
            const notificationItem = createLaundryNotificationItem(notification);
            mediaGroupElementLaundry.insertAdjacentHTML('afterbegin', notificationItem);
        });
        updateLaundryNotificationBadge();
    }

    function updateLaundryNotificationBadge() {
        notificationBadgeLaundry.style.display = unreadCountLaundry > 0 ? 'inline-block' : 'none';
        notificationBadgeLaundry.textContent = unreadCountLaundry;
    }

    function createLaundryNotificationItem(notification) {
        return `
            <a href="/petugaslaundry/laundry" class="media-group-item" data-id="${notification.id}">
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

    function markLaundryAsRead(notificationId) {
        const notifications = JSON.parse(localStorage.getItem('laundry_notifications')) || [];
        const updatedNotifications = notifications.filter(notification => {
            if (notification.id === notificationId) {
                unreadCountLaundry = Math.max(unreadCountLaundry - 1, 0);
                return false;
            }
            return true;
        });

        localStorage.setItem('laundry_notifications', JSON.stringify(updatedNotifications));
        localStorage.setItem('unreadCountLaundry', unreadCountLaundry);
        loadLaundryNotifications();
        updateLaundryNotificationBadge();
    }

    mediaGroupElementLaundry.addEventListener('click', function (event) {
        const target = event.target.closest('.media-group-item');
        if (target) {
            const notificationId = parseInt(target.getAttribute('data-id'));
            if (!isNaN(notificationId)) {
                markLaundryAsRead(notificationId);
                window.location.href = target.href;
            }
        }
    });

    channelLaundry.bind('laundry-dikirim', function (data) {
        const notification = {
            id: data.id,
            deskripsi: `Laundry untuk NORM ${data.nomr} di ruangan ${data.ruangan}`,
            tgl_pengaduan: data.tanggal,
            read: false
        };

        const notifications = JSON.parse(localStorage.getItem('laundry_notifications')) || [];
        notifications.push(notification);
        localStorage.setItem('laundry_notifications', JSON.stringify(notifications));

        unreadCountLaundry++;
        localStorage.setItem('unreadCountLaundry', unreadCountLaundry);

        const notificationItem = createLaundryNotificationItem(notification);
        mediaGroupElementLaundry.insertAdjacentHTML('afterbegin', notificationItem);

        updateLaundryNotificationBadge();

        const audio = new Audio('/sound/notification.mp3');
        audio.play().catch(function (error) {
            console.error("Autoplay failed: ", error);
        });
    });

    loadLaundryNotifications();
});
