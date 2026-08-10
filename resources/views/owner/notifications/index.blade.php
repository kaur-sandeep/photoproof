@extends('admin.layouts.master')
@section('title', 'Notifications')

@section('content')
<div class="container-fluid ">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Notifications </b></h3>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="ownernotificationList" class="table mb-0" aria-label="Owner notifications">
                <thead class="visually-hidden">
                    <tr><th>Notification</th></tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="ownerNotificationModal" tabindex="-1" aria-labelledby="ownerNotificationModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content notification-modal-content">
            <div class="modal-header notification-modal-header">
                <h5 class="modal-title" id="ownerNotificationModalTitle">Notification details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body notification-modal-body">
                <div class="notification-details-grid">
                    <div id="row_name" class="detail-row"><span class="detail-label">Name</span><span id="name" class="detail-value"></span></div>
                    <div id="row_email" class="detail-row"><span class="detail-label">Email</span><span id="email" class="detail-value"></span></div>
                    <div id="row_browser" class="detail-row"><span class="detail-label">Browser</span><span id="browser" class="detail-value"></span></div>
                    <div id="row_platform" class="detail-row"><span class="detail-label">Platform</span><span id="platform" class="detail-value"></span></div>
                    <div id="row_device" class="detail-row"><span class="detail-label">Device</span><span id="device" class="detail-value"></span></div>
                    <div id="row_ip" class="detail-row"><span class="detail-label">IP address</span><span id="ip" class="detail-value"></span></div>
                    <div id="row_date" class="detail-row"><span class="detail-label">Date &amp; time</span><span id="date" class="detail-value"></span></div>
                    <div id="row_type" class="detail-row"><span class="detail-label">Notification type</span><span id="type" class="detail-value badge-type"></span></div>
                    <div id="row_location" class="detail-row detail-row-full"><span class="detail-label">Location</span><span id="location" class="detail-value"></span></div>
                </div>
                <div id="row_image" class="image-preview-wrapper">
                    <div class="image-preview-card"><img id="reported_image" src="" alt="Related photo" class="reported-image"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>

.notification-page-heading { display:flex; justify-content:space-between; align-items:end; margin: .4rem 0 1.5rem; }
.notification-page-eyebrow { color:#5e72e4; font-size:.75rem; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
.notification-page-heading h3 { margin:.2rem 0; color:#202939; font-size:1.6rem; font-weight:700; }
.notification-page-heading p { margin:0; color:#768195; }

#ownernotificationList { width:100% !important; border-collapse:collapse; }
#ownernotificationList tbody td { padding:0; border:0; }
#ownernotificationList tbody tr + tr td { border-top:1px solid #edf0f4; }
.owner-notification-item { display:grid; grid-template-columns:48px minmax(0, 1fr) auto; gap:14px; align-items:center; padding:18px 22px; transition:background .2s ease; }
.owner-notification-item:hover { background:#f8faff; }
.owner-notification-item.is-unread { background:#f5f8ff; }
.notification-avatar, .notification-photo { width:48px; height:48px; object-fit:cover; }
.notification-avatar { border-radius:50%; border:2px solid #fff; box-shadow:0 2px 7px rgba(30,41,59,.14); }
.notification-copy { min-width:0; }
.notification-message { color:#273244; font-size:.98rem; font-weight:600; line-height:1.4; }
.notification-action { display:block; margin-top:3px; color:#68758a; font-size:.86rem; }
.notification-date { display:block; margin-top:7px; color:#8b95a5; font-size:.79rem; }
.notification-side { display:flex; align-items:center; gap:12px; }
.notification-photo { border-radius:8px; border:1px solid #e7eaf0; }
.notification-view-btn { border:1px solid #ccd6fb; border-radius:7px; background:#fff; color:#4058c9; font-size:.82rem; font-weight:700; padding:7px 12px; white-space:nowrap; transition:.2s ease; }
.notification-view-btn:hover { background:#4058c9; border-color:#4058c9; color:#fff; }
#ownernotificationList_wrapper { padding:16px 20px; }
#ownernotificationList_wrapper .dataTables_filter input, #ownernotificationList_wrapper .dataTables_length select { border:1px solid #dce1e8; border-radius:7px; padding:.35rem .6rem; outline:0; }
#ownernotificationList_wrapper .dataTables_filter input:focus { border-color:#7185e8; box-shadow:0 0 0 .18rem rgba(64,88,201,.12); }
#ownernotificationList_wrapper .dataTables_info, #ownernotificationList_wrapper .dataTables_paginate { color:#768195; font-size:.82rem; margin-top:14px; }
.notification-modal-content { border:0; border-radius:14px; overflow:hidden; box-shadow:0 12px 35px rgba(15,23,42,.18); }
.notification-modal-header { background:#f8f9fc; border-bottom:1px solid #e9edf3; padding:1rem 1.5rem; }
.notification-modal-body { padding:1.5rem; }
.notification-details-grid { display:grid; grid-template-columns:1fr 1fr; gap:.75rem 1rem; margin-bottom:1.25rem; }
.detail-row { display:flex; flex-direction:column; gap:.18rem; padding:.65rem .75rem; background:#fafbfc; border:1px solid #edf0f3; border-radius:8px; }
.detail-row-full { grid-column:1 / -1; }
.detail-label { color:#8a94a3; font-size:.69rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; }
.detail-value { color:#293344; font-size:.92rem; overflow-wrap:anywhere; }
.badge-type { width:fit-content; padding:.16rem .55rem; border-radius:999px; background:#e8edff; color:#4058c9; font-size:.78rem; font-weight:600; }
.image-preview-wrapper { display:flex; justify-content:center; }
.image-preview-card { max-width:100%; padding:7px; background:#fafafa; border:1px solid #e5e7eb; border-radius:10px; }
.reported-image { display:block; max-width:100%; max-height:400px; border-radius:6px; object-fit:contain; }
@media (max-width:576px) { .notification-page-heading { align-items:start; } .owner-notification-item { grid-template-columns:42px minmax(0,1fr); padding:15px; } .notification-avatar { width:42px; height:42px; } .notification-side { grid-column:2; justify-content:space-between; } .notification-photo { width:42px; height:42px; } .notification-details-grid { grid-template-columns:1fr; } }
</style>
@endsection
