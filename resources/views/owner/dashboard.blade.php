@extends('admin.layouts.master')

@section('title', 'Dashboard')

@section('content')

<style>

    .dashboard-container {
        padding: 5px 0 30px;
    }

    /* ==============================
       Summary Cards
    ============================== */

    .dashboard-stat-card {
        border: 0;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.25s ease;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.08);
        height: 100%;
    }

    .dashboard-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .dashboard-stat-card a {
        text-decoration: none;
        color: inherit;
    }

    .dashboard-stat-body {
        padding: 22px;
        min-height: 125px;
    }

    .dashboard-stat-title {
        font-size: 15px;
        margin-bottom: 8px;
        opacity: 0.9;
    }

    .dashboard-stat-number {
        font-size: 30px;
        font-weight: 700;
        margin: 0;
    }

    .stat-employees {
        background: linear-gradient(135deg, #2878e3, #4b9cff);
        color: #fff;
    }

    .stat-photos {
        background: linear-gradient(135deg, #16a36a, #36c98a);
        color: #fff;
    }

    .stat-monthly {
        background: linear-gradient(135deg, #7b4fe0, #a477f5);
        color: #fff;
    }


    /* ==============================
       Dashboard Cards
    ============================== */

    .dashboard-card {
        border: 0;
        border-radius: 12px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, 0.07);
        height: 100%;
    }

    .dashboard-card .card-header {
        background: #fff;
        border-bottom: 1px solid #eee;
        padding: 16px 20px;
        border-radius: 12px 12px 0 0;
    }

    .dashboard-card .card-header h5 {
        margin: 0;
        font-size: 17px;
        font-weight: 600;
        color: #222;
    }

    .dashboard-card .card-body {
        padding: 0;
    }


    /* ==============================
       Employee List
    ============================== */

    .dashboard-user-item {
        display: flex;
        align-items: center;
        padding: 13px 18px;
        border-bottom: 1px solid #f0f0f0;
    }

    .dashboard-user-item:last-child {
        border-bottom: 0;
    }

    .dashboard-user-avatar {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        background: #f1f3f5;
    }

    .dashboard-user-info {
        flex: 1;
        min-width: 0;
    }

    .dashboard-user-name {
        font-size: 14px;
        font-weight: 600;
        color: #222;
        margin-bottom: 2px;
    }

    .dashboard-user-email {
        font-size: 12px;
        color: #888;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .dashboard-user-date {
        font-size: 11px;
        color: #999;
    }


    /* ==============================
       Photo List
    ============================== */

    .dashboard-photo-item {
        display: flex;
        align-items: center;
        padding: 11px 18px;
        border-bottom: 1px solid #f0f0f0;
    }

    .dashboard-photo-item:last-child {
        border-bottom: 0;
    }

    .dashboard-photo {
        width: 52px;
        height: 52px;
        border-radius: 8px;
        object-fit: cover;
        margin-right: 13px;
        background: #f1f3f5;
    }

    .dashboard-photo-info {
        flex: 1;
        min-width: 0;
    }

    .dashboard-photo-user {
        font-size: 14px;
        font-weight: 600;
        color: #222;
    }

    .dashboard-photo-date {
        font-size: 12px;
        color: #888;
        margin-top: 2px;
    }


    /* ==============================
       Chart
    ============================== */

    .chart-wrapper {
        height: 360px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #monthlyPhotoChart {
        max-height: 320px;
    }


    /* ==============================
       Empty State
    ============================== */

.stat-employees {
    position: relative;
}

.stat-employees .dashboard-stat-body {
    padding-right: 150px; /* space for button */
}

.btn-add-employee {
    position: absolute;
    top: 12px;
    right: 15px;

    background: #f59e0b;
    border: 1px solid #f59e0b;
    color: #fff !important;

    font-weight: 600;
    border-radius: 6px;
    padding: 7px 14px;

    z-index: 2;
}

.btn-add-employee:hover {
    background: #d97706;
    border-color: #d97706;
    color: #fff !important;
}
</style>


<div class="container-fluid dashboard-container">

    {{-- =========================================================
         SUMMARY CARDS
    ========================================================== --}}

    <div class="row g-3 mb-4">

        {{-- Total Employees --}}
<div class="col-lg-4 col-md-6">
    <div class="dashboard-stat-card stat-employees">

        <a href="{{ route('owner.employee') }}"
           class="text-decoration-none">

            <div class="dashboard-stat-body">

                <div class="dashboard-stat-title">
                    Total Employees
                </div>

                <h3 class="dashboard-stat-number">
                    {{ $total_employees }}
                </h3>

            </div>

        </a>

        <a href="{{ url('/owner/employees/create') }}"
           class="btn btn-add-employee btn-sm">
            <i class="fas fa-plus"></i>
            Add Employee
        </a>

    </div>
</div>

        {{-- Total Photos --}}
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-stat-card stat-photos">

                <a href="{{ route('owner.photos') }}">

                    <div class="dashboard-stat-body">

                        <div class="dashboard-stat-title">
                            Total Photos Uploaded
                        </div>

                        <h3 class="dashboard-stat-number">
                            {{ $totalPhotos }}
                        </h3>

                    </div>

                </a>

            </div>
        </div>


        {{-- Monthly Usage --}}
        <div class="col-lg-4 col-md-6">
            <div class="dashboard-stat-card stat-monthly">

                <a href="{{ route('owner.photos') }}">

                    <div class="dashboard-stat-body">

                        <div class="dashboard-stat-title">
                            Monthly Photo Usage
                        </div>

                        <h3 class="dashboard-stat-number">
                            {{ $monthlyPhotos }} / {{ $monthlyPhotoLimit }}
                        </h3>

                    </div>

                </a>

            </div>
        </div>

    </div>


    {{-- =========================================================
         LATEST USERS + PIE CHART
    ========================================================== --}}

    <div class="row g-4 mb-4">

        {{-- Latest 10 Employees --}}
        <div class="col-lg-6">

            <div class="card dashboard-card">

                <div class="card-header justify-content-between align-items-center">

                    <h5>
                        <i class="fas fa-users me-2"></i>
                        Latest Employees
                    </h5>

                    <a href="{{ route('owner.employee') }}"
                       class="btn btn-sm btn-outline-primary" style="float:right;">
                        View All
                    </a>

                </div>


                <div class="card-body">

                    @forelse($latestUsers as $user)

                        @php
                            $avatar = !empty($user->profile_image)
                                ? asset('storage/' . $user->profile_image)
                                : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
                        @endphp

                        <div class="dashboard-user-item">

                            <img
                                src="{{ $avatar }}"
                                alt="{{ $user->name }}"
                                class="dashboard-user-avatar"
                            >

                            <div class="dashboard-user-info">

                                <div class="dashboard-user-name">
                                    {{ $user->name }}
                                </div>

                                <div class="dashboard-user-email">
                                    {{ $user->email }}
                                </div>

                            </div>

                            <div class="dashboard-user-date">
                                {{ $user->created_at->format('d M Y') }}
                            </div>

                        </div>

                    @empty

                        <div class="dashboard-empty">

                            <i class="fas fa-users"></i>

                            <div>
                                No employees found.
                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>


        {{-- Monthly Photo Pie Chart --}}
        <div class="col-lg-6">

            <div class="card dashboard-card">

                <div class="card-header">

                    <h5>
                        <i class="fas fa-chart-pie me-2"></i>
                        Photo Uploads This Month
                    </h5>

                </div>


                <div class="card-body">

                    @if(count($chartData) > 0)

                        <div class="chart-wrapper">

                            <canvas id="monthlyPhotoChart"></canvas>

                        </div>

                    @else

                        <div class="dashboard-empty">

                            <i class="fas fa-chart-pie"></i>

                            <div>
                                No photos have been uploaded this month.
                            </div>

                        </div>

                    @endif

                </div>

            </div>

        </div>

    </div>


    {{-- =========================================================
         LATEST 10 PHOTOS
    ========================================================== --}}

    <div class="row">

        <div class="col-12">

            <div class="card dashboard-card">

                <div class="card-header justify-content-between align-items-center">

                    <h5>
                        <i class="fas fa-images me-2"></i>
                        Latest Uploaded Photos
                    </h5>

                    <a href="{{ route('owner.photos') }}"
                       class="btn btn-sm btn-outline-primary" style="float:right;">
                        View All
                    </a>

                </div>


                <div class="card-body">

                    @forelse($latestPhotos as $photo)

                        @php

                            $photoImage = !empty($photo->thumbnail)
                                ? asset('storage/' . $photo->thumbnail)
                                : (
                                    !empty($photo->photo)
                                        ? asset('storage/' . $photo->photo)
                                        : 'https://cdn-icons-png.flaticon.com/512/685/685655.png'
                                );

                            $photoUserName = optional($photo->user)->name ?? 'Unknown User';

                        @endphp


                        <div class="dashboard-photo-item">

                            <img
                                src="{{ $photoImage }}"
                                alt="Photo"
                                class="dashboard-photo"
                            >


                            <div class="dashboard-photo-info">

                                <div class="dashboard-photo-user">

                                    {{ $photoUserName }}

                                    <span class="text-muted">
                                        uploaded a photo
                                    </span>

                                </div>

                                <div class="dashboard-photo-date">

                                    {{ $photo->created_at->format('d M Y, h:i A') }}

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="dashboard-empty">

                            <i class="fas fa-images"></i>

                            <div>
                                No photos uploaded yet.
                            </div>

                        </div>

                    @endforelse

                </div>

            </div>

        </div>

    </div>

</div>


{{-- =========================================================
     CHART.JS
========================================================== --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const chartElement = document.getElementById('monthlyPhotoChart');

    if (!chartElement) {
        return;
    }

    const labels = @json($chartLabels);
    const data = @json($chartData);


    new Chart(chartElement, {

        type: 'pie',

        data: {

            labels: labels,

            datasets: [{
                data: data,

                backgroundColor: [
                    '#2878e3',
                    '#16a36a',
                    '#f2a900',
                    '#7b4fe0',
                    '#e34f4f',
                    '#17a2b8',
                    '#fd7e14',
                    '#6f42c1',
                    '#20c997',
                    '#d63384', '#e9ecef'
                ],

                borderWidth: 2,
                borderColor: '#ffffff'
            }]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            plugins: {

                legend: {
                    position: 'right',

                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },

                tooltip: {

                    callbacks: {

                        label: function(context) {

                            const label = context.label || '';
                            const value = context.raw || 0;

                            return ' ' + label + ': ' + value + ' photos';

                        }

                    }

                }

            }

        }

    });

});

</script>

@endsection