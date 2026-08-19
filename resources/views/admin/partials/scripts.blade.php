<script src="{{ asset('admin/js/jquery-3.7.1.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<!-- Bootstrap 5 -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE v4 -->
 <!-- <script
  src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/js/adminlte.min.js"
  crossorigin="anonymous"
></script> -->

<script src="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc3/dist/js/adminlte.min.js"></script>
<!-- <script src="{{ asset('admin/js/adminlte.js') }}"></script> -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="{{ asset('admin/js/common.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const bell = document.getElementById('notificationBell');
    const badge = document.getElementById('notificationCount');
    const modalElement = document.getElementById('notificationModal');
    const modalBody = document.getElementById('notificationModalBody');

    if (!bell || !badge || !modalElement || !modalBody) return;

    const notificationsUrl = '{{ url('/admin/notifications/unread') }}';

    function notificationItem(item) {
        const row = document.createElement('li');
        row.className = 'list-group-item notificationRow d-flex justify-content-between align-items-start bg-light';
        row.dataset.id = item.id;
        row.style.cursor = 'pointer';

        const content = document.createElement('div');
        content.className = 'notification-text';
        const name = document.createElement('strong');
        name.textContent = item.name || 'Notification';
        const type = document.createElement('small');
        type.className = 'd-block';
        type.textContent = item.type || 'Notification';
        const date = document.createElement('small');
        date.className = 'd-block text-muted';
        date.textContent = item.created_at_formatted || '';

        content.append(name, type, date);
        row.append(content);
        return row;
    }

    async function refreshNotifications() {
        try {
            const response = await fetch(notificationsUrl, {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            if (!response.ok) throw new Error('Unable to load notifications.');

            const notifications = await response.json();
            modalBody.classList.remove('text-danger');
            badge.textContent = notifications.length;
            badge.style.display = notifications.length ? 'inline-block' : 'none';
            modalBody.replaceChildren();

            if (!notifications.length) {
                modalBody.textContent = 'No new notifications.';
                return;
            }

            const list = document.createElement('ul');
            list.className = 'list-group';
            notifications.forEach(function (item) { list.append(notificationItem(item)); });
            modalBody.append(list);
        } catch (error) {
            badge.style.display = 'none';
            modalBody.textContent = 'Failed to load notifications.';
            modalBody.classList.add('text-danger');
        }
    }

    bell.addEventListener('click', function () {
        refreshNotifications();
        bootstrap.Modal.getOrCreateInstance(modalElement).show();
    });

    modalBody.addEventListener('click', function (event) {
        if (event.target.closest('.notificationRow')) {
            window.location.assign('{{ route('notifications.index') }}');
        }
    });

    refreshNotifications();
    window.setInterval(refreshNotifications, 15000);
});
</script>

<!-- <script src="{{ asset('admin/js/datatable.js') }}"></script> -->




