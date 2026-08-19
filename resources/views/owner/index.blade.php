@extends('admin.layouts.master')

@section('title', 'My Employees')

@section('content')

<div class="container-fluid">
    <div class="admin-page-header">

<h3 class="card-title"><b>My Employees </b></h3>
             <a href="{{ route('owner.employee.create') }}" class="btn btn-primary mb-3" style="float:right">Add Employee</a>

    </div>
      
    <div class="card">
        
        <div class="row mb-4">

    <!-- Monthly Limit -->
    <div class="col-md-4">
        <div class="photo-stat-card stat-limit">
            <div class="stat-icon">
                <i class="fas fa-images"></i>
            </div>

            <div class="stat-content">
                <div class="stat-title">Photo Capacity</div>
                <div class="stat-number">{{ $totalPhotoLimit }}</div>
                <div class="stat-subtitle">{{ $monthlyPhotoLimit }} monthly + {{ $topupPhotoLimit }} top-up photos</div>
            </div>
        </div>
    </div>

    <!-- Total Used -->
    <div class="col-md-4">
        <div class="photo-stat-card stat-used">
            <div class="stat-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>

            <div class="stat-content">
                <div class="stat-title">Total Photos Uploaded</div>
                <div class="stat-number">{{ $usedPhotos }}</div>
                <div class="stat-subtitle">Photos uploaded this month</div>
            </div>
        </div>
    </div>

    <!-- Remaining -->
    <div class="col-md-4">
        <div class="photo-stat-card stat-remaining">
            <div class="stat-icon">
                <i class="fas fa-chart-pie"></i>
            </div>

            <div class="stat-content">
                <div class="stat-title">Remaining</div>
                <div class="stat-number">{{ $remainingPhotos }}</div>
                <div class="stat-subtitle">Photos remaining this month</div>
            </div>
        </div>
    </div>

</div>
       
        <div class="card-body">
            <table id="employeesList" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Role</th>
                        <th>Photos</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
<style>
    .photo-stat-card {
    position: relative;
    min-height: 125px;
    border-radius: 10px;
    padding: 22px 25px;
    display: flex;
    align-items: center;
    overflow: hidden;
    transition: all 0.25s ease;
    border: 1px solid transparent;
}

.photo-stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.10);
}

/* Monthly Limit */
.stat-limit {
    background: #eaf3ff;
    border-color: #cfe3ff;
}

.stat-limit .stat-icon {
    background: #2878e3;
    color: #fff;
}

/* Used */
.stat-used {
    background: #eafaf3;
    border-color: #ccefe0;
}

.stat-used .stat-icon {
    background: #16a36a;
    color: #fff;
}

/* Remaining */
.stat-remaining {
    background: #fff6e5;
    border-color: #ffe3ad;
}

.stat-remaining .stat-icon {
    background: #f2a900;
    color: #fff;
}

.stat-icon {
    width: 58px;
    height: 58px;
    min-width: 58px;
    border-radius: 12px;

    display: flex;
    align-items: center;
    justify-content: center;

    font-size: 25px;
    margin-right: 20px;
}

.stat-content {
    flex: 1;
}

.stat-title {
    font-size: 15px;
    font-weight: 500;
    color: #555;
    margin-bottom: 4px;
}

.stat-number {
    font-size: 30px;
    line-height: 1.1;
    font-weight: 700;
    color: #1f1f1f;
}

.stat-subtitle {
    font-size: 12px;
    color: #777;
    margin-top: 5px;
}
</style>
@endsection
