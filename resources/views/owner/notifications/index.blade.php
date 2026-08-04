@extends('admin.layouts.master')
@section('title', 'Notifications List')

@section('content')
<div class="container-fluid">
    <div class="admin-page-header">
        <h3 class="card-title"><b>Notifications List</b></h3>
    </div>
    <div class="card">
        <div class="card-body">
            <table id="ownernotificationList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Random Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Type</th>
                        <th>IP Address</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="ownerNotificationModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content notification-modal-content">

      <div class="modal-header notification-modal-header">
        <h5 class="modal-title"><b>Notification Details</b></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body notification-modal-body">

        <div class="notification-details-grid">

          <div id="row_name" class="detail-row">
            <span class="detail-label">Name</span>
            <span id="name" class="detail-value"></span>
          </div>

          <div id="row_email" class="detail-row">
            <span class="detail-label">Email</span>
            <span id="email" class="detail-value"></span>
          </div>

          <div id="row_message" class="detail-row detail-row-full">
            <span class="detail-label">Message</span>
            <span id="message" class="detail-value"></span>
          </div>

          <div id="row_browser" class="detail-row">
            <span class="detail-label">Browser</span>
            <span id="browser" class="detail-value"></span>
          </div>

          <div id="row_platform" class="detail-row">
            <span class="detail-label">Platform</span>
            <span id="platform" class="detail-value"></span>
          </div>

          <div id="row_device" class="detail-row">
            <span class="detail-label">Device</span>
            <span id="device" class="detail-value"></span>
          </div>

          <div id="row_ip" class="detail-row">
            <span class="detail-label">IP Address</span>
            <span id="ip" class="detail-value"></span>
          </div>

          <div id="row_date" class="detail-row">
            <span class="detail-label">Date</span>
            <span id="date" class="detail-value"></span>
          </div>

          <div id="row_type" class="detail-row">
            <span class="detail-label">Type</span>
            <span id="type" class="detail-value badge-type"></span>
          </div>

          <div id="row_location" class="detail-row detail-row-full">
            <span class="detail-label">Location</span>
            <span id="location" class="detail-value"></span>
          </div>

        </div>

        <div id="row_image" class="text-center mb-3 image-preview-wrapper">
          <div class="image-preview-card">
            <img id="reported_image" src="" alt="Reported Image" class="reported-image">
          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<style>
/* ===== Page header ===== */
.admin-page-header {
    margin-bottom: 1rem;
}

/* ===== Filter select ===== */
#typeFilter {
    border-radius: 8px;
    border: 1px solid #dcdfe4;
    padding: 0.5rem 0.75rem;
}
#typeFilter:focus {
    border-color: #6c8dfa;
    box-shadow: 0 0 0 0.2rem rgba(108, 141, 250, 0.15);
}

/* ===== Table polish ===== */
#notificationList {
    border-radius: 8px;
    overflow: hidden;
}
#notificationList thead th {
    background-color: #f8f9fb;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    color: #555;
    vertical-align: middle;
}
#notificationList tbody td {
    vertical-align: middle;
    font-size: 0.9rem;
}
#notificationList tbody tr:hover {
    background-color: #f5f8ff;
}

/* ===== Modal shell ===== */
.notification-modal-content {
    border: none;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}
.notification-modal-header {
    background: #f8f9fb;
    border-bottom: 1px solid #eceef1;
    padding: 1rem 1.5rem;
}
.notification-modal-body {
    padding: 1.5rem;
}

/* ===== Details grid ===== */
.notification-details-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem 1.5rem;
    margin-bottom: 1.5rem;
}
.detail-row {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
    padding: 0.5rem 0.75rem;
    background: #fafbfc;
    border: 1px solid #eef0f2;
    border-radius: 8px;
    transition: background 0.2s ease, border-color 0.2s ease;
}
.detail-row:hover {
    background: #f2f6ff;
    border-color: #dbe4ff;
}
.detail-row-full {
    grid-column: 1 / -1;
}
.detail-label {
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: #8a8f98;
}
.detail-value {
    font-size: 0.95rem;
    color: #22262b;
    word-break: break-word;
}
.detail-value:empty::before {
    content: "—";
    color: #c3c7cd;
}

/* Type value shown as a small badge */
.badge-type {
    display: inline-block;
    width: fit-content;
    padding: 0.15rem 0.6rem;
    background: #e8edff;
    color: #3455db;
    border-radius: 999px;
    font-size: 0.78rem;
    font-weight: 600;
}

/* Responsive: stack on small screens */
@media (max-width: 576px) {
    .notification-details-grid {
        grid-template-columns: 1fr;
    }
}

/* ===== Image preview ===== */
.image-preview-wrapper {
    display: flex;
    justify-content: center;
}
.image-preview-card {
    display: inline-block;
    padding: 8px;
    background: #fafafa;
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    transition: box-shadow 0.25s ease, transform 0.25s ease;
}
.image-preview-card:hover {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
}
.reported-image {
    display: block;
    max-width: 100%;
    max-height: 400px;
    object-fit: contain;
    border-radius: 6px;
    background: #f3f4f6;
    cursor: zoom-in;
    transition: transform 0.3s ease;
}
.reported-image:hover {
    transform: scale(1.02);
}
</style>
@endsection
