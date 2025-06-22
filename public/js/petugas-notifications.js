document.addEventListener('DOMContentLoaded', function () {
    Pusher.logToConsole = false;

    const userId = document.body.getAttribute('data-user-id');
    if (!userId) return;

    const pusher = new Pusher('27829950f08a0c84e275', { cluster: 'ap1' });

    const channel = pusher.subscribe(`pengaduan-updated.${userId}`);
    let unreadCount = parseInt(localStorage.getItem('unreadCount')) || 0;

    const mediaGroupElement = document.getElementById('notification-panel');
    const notificationBadge = document.getElementById('notification-badge');
    const toggleButton = document.getElementById('notification-toggle');

    if (!mediaGroupElement || !notificationBadge || !toggleButton) {
        console.error("Required notification DOM elements are missing.");
        return;
    }

    toggleButton.addEventListener('click', () => {
        mediaGroupElement.style.display = (mediaGroupElement.style.display === 'block') ? 'none' : 'block';
    });

    function loadNotifications() {
        const notifications = JSON.parse(localStorage.getItem('notifications')) || [];
        mediaGroupElement.innerHTML = ''; // Kosongkan elemen sebelum menambahkan notifikasi baru
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
            <a href="/petugas/pengaduan/detail/${notification.id}"
               class="media-group-item ${readClass}"
               data-id="${notification.id}"
               style="display: block; padding: 10px; border-bottom: 1px solid #eee;">
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
                return false; // hapus notifikasi yg di-klik
            }
            return true;
        });
        localStorage.setItem('notifications', JSON.stringify(updatedNotifications));
        localStorage.setItem('unreadCount', unreadCount);
        loadNotifications(); // Memperbarui tampilan setelah menghapus notifikasi
        updateNotificationBadge(); // Memperbarui badge notifikasi
    }

    mediaGroupElement.addEventListener('click', function (event) {
        const target = event.target.closest('.media-group-item');
        if (target) {
            event.preventDefault(); // cegah langsung pindah halaman
            const notificationId = parseInt(target.getAttribute('data-id'));
            if (!isNaN(notificationId)) {
                markAsRead(notificationId);
                setTimeout(() => {
                    window.location.href = target.href; // Pindah ke halaman detail setelah 100ms
                }, 100);
            }
        }
    });

    channel.bind('pengaduan-diperbarui', function (data) {
        const notification = {
            id: data.id,
            deskripsi: data.deskripsi || 'Anda telah ditugaskan untuk menangani pengaduan.',
            tgl_pengaduan: data.tgl_pengaduan || new Date().toLocaleString(),
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

        const audio = new Audio('/sound/notification.mp3');
        audio.play().catch(error => console.error("Autoplay failed:", error));
    });

    loadNotifications();
});
