<?php
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\OrganizationsController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\Admin\PhotosController;
use App\Http\Controllers\Admin\ActivityController;
use App\Http\Controllers\Admin\PhotoNotificationController;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Api\PlanController;
Route::get('/', function () {
    return redirect()->route('photo.search.form');
});

Route::get('/plans', function () {
    return redirect()->route('plans');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::middleware('auth:admin')->group(function () {
        Route::get('/dashboard', [LoginController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/profile', [LoginController::class, 'profile'])->name('admin.profile');
        Route::post('/profile/update', [LoginController::class, 'profileUpdate'])->name('admin.profile.update');
        
        Route::get('/admins', [AdminController::class, 'index'])->name('admin.users.data');
        Route::get('/users/data/list', [AdminController::class, 'list'])->name('admin.users.data.list');
        Route::get('/user/create', [AdminController::class, 'create'])->name('admin.users.create');
        Route::post('/users/add', [AdminController::class, 'addUser'])->name('admin.store.users');

        Route::get('/organization', [OrganizationController::class, 'index'])->name('admin.organization.data');
        Route::get('/organizations/list/', [OrganizationController::class, 'list'])->name('admin.organization.list');
        Route::get('/organizations/show/{organizationId}', [OrganizationController::class, 'showOrganization'])->name('admin.organization.show.data');
        Route::get('/organizations/edit/{organizationId}', [OrganizationController::class, 'editOrganization'])->name('admin.organization.edit.data');
        Route::post('/organization/update/{organizationId}', [OrganizationController::class, 'updateOrganization'])->name('admin.update.organization');
        Route::get('/update/organizations/status', [OrganizationController::class, 'updateStatus'])->name('admin.update.organizations.status');

        Route::get('/organization/create', [OrganizationController::class, 'create'])->name('admin.organization.create');
        Route::post('/organization/add', [OrganizationController::class, 'addOrganization'])->name('admin.store.organization');

        Route::get('/update/users/status', [AdminController::class, 'updateStatus'])->name('admin.update.users.status');
        Route::get('/edit/users/{userId}', [AdminController::class, 'editUsers'])->name('admin.users.edit.data');
        Route::post('/update/users/{userId}', [AdminController::class, 'updateUsers'])->name('admin.update.users.data');
        Route::get('/change/password', [AdminController::class, 'changePassword'])->name('admin.change.password');
        Route::post('/update/password', [AdminController::class, 'updatePassword'])->name('admin.update.password');
        Route::get('/settings', [AdminController::class, 'settings'])->name('admin.settings');
         Route::post('/update/settings', [AdminController::class, 'updateSettings'])->name('admin.setting.update');
        
        
        
        
        
        Route::get('/users', [UserController::class, 'index'])->name('admin.users');
        // Route::get('/users/list', [UserController::class, 'list'])->name('admin.users.list');
        Route::post('/users/update-status', [UserController::class, 'admin.user.updateStatus']);
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.user.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/user/edit/{userId}', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::post('/users/update/{userId}', [UserController::class, 'update'])->name('admin.users.update');
        Route::get('/user/photos/{userId}', [PhotoController::class, 'list']);
        
        Route::get('/users/list/two', [UserController::class, 'list'])->name('admin.users.list');
        Route::get('/users/update/data', [UserController::class, 'updateStatus'])->name('admin.users.update.data');
        Route::get('/users/show/imagedata/{id}', [UserController::class, 'showImagedatawithid'])->name('admin.users.show.imagedata');
        Route::get('/user/viewImages', [UserController::class, 'viewImages'])->name('admin.users.viewimages');  // Show the users and images
        Route::get('/fetch-users-images', [UserController::class, 'getUsersWithImages'])->name('admin.user.images');
        Route::get('/fetch/users/images/{userId}', [UserController::class, 'getUsersWithImageswithId'])->name('admin.user.images.by.id');

        Route::get('/photos', [PhotosController::class, 'index'])->name('admin.photos');
        Route::get('/photos/list', [PhotosController::class, 'list'])->name('admin.photos.list');
        Route::get('/deleted/photos', [PhotosController::class, 'deletedPhoto'])->name('admin.deleted.photos');
        Route::get('/deleted/photos/list', [PhotosController::class, 'deletedPhotoslist']);
        
        Route::get('/photos/show/{id}', [PhotosController::class, 'show'])->name('admin.photos.show');
        Route::get('/photos/showdata/{id}', [PhotosController::class, 'showdata'])->name('admin.photos.showdata');
        Route::get('/photos/update/data', [PhotosController::class, 'updateStatus'])->name('admin.photos.update.status');
        Route::post('/photos/update/{photId}', [PhotosController::class, 'update'])->name('admin.photo.update');
        Route::get('/photos/edit/{id}', [PhotosController::class, 'edit'])->name('admin.photos.edit');
        // Route::post('/photo/update/{id}', [PhotosController::class, 'update'])->name('admin.photo.update');
        Route::get('/reported/images', [PhotosController::class, 'reportedImages'])->name('admin.reported');
         Route::get('/reported/images/list', [PhotosController::class, 'reportedImagesList']);
          Route::get('/reported/{id}', [PhotoNotificationController::class, 'reportShow'])->name('reported.show');
        

        Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
        Route::get('/activity-logs', [ActivityController::class, 'index'])->name('admin.activity');
        Route::get('/activity/list', [ActivityController::class, 'list']);
       
        

        Route::get('/notifications/unread', [PhotoNotificationController::class, 'getUnreadNotifications']);
        Route::post('/notifications/read/{id}', [PhotoNotificationController::class, 'markAsRead']);
        Route::get('/notifications', [PhotoNotificationController::class,'notifications'])->name('notifications.index');
        Route::get('/notifications/{id}', [PhotoNotificationController::class, 'show'])->name('notifications.show');
        Route::get('/notificationList/list', [PhotoNotificationController::class, 'list']);
        Route::post('/notifications/unread-count/{id}', [PhotoNotificationController::class,'unreadCount']);
        

        
        
        
    });

});
    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('admin.forgot-password');
    Route::post('/send-password-reset-link', [LoginController::class, 'sendPasswordRestLink'])->name('admin.send-password-reset-link');
    Route::get('/password/reset/{token}', [LoginController::class, 'showResetForm'])->name('admin.password.reset');
    Route::post('/reset/password', [LoginController::class, 'resetPassword'])->name('admin.reset.password');
    Route::get('/link/expired', [LoginController::class, 'expireLink'])->name('admin.password.expired');
    Route::get('/reset-password', [LoginController::class, 'showUserResetForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPasswordUser'])->name('password.update');
    Route::get('/photo-search', [PhotoController::class, 'searchForm'])->name('photo.search.form');
    Route::get('/', [PhotoController::class, 'searchForm']);
    Route::post('/photo-search', [PhotoController::class, 'search'])->name('photo.search');
    Route::get('/photo/{random_id}', [PhotoController::class, 'show'])->name('photo.show');
     Route::get('/privacy-policy', [PhotoController::class, 'privacy_policy'])->name('privacy-policy');
     Route::get('/terms-conditions', [PhotoController::class, 'terms_conditions'])->name('terms-conditions');
      Route::get('/thank-you', [PhotoController::class, 'thank_you'])->name('thank-you');
      Route::get('/unsubscribe', [UserController::class, 'unsubscribe']);
     Route::get('/report/{random_id}', [PhotoController::class, 'report'])
    ->name('photo.report');
    Route::post('/report/{random_id}', [PhotoController::class, 'report_submit'])
    ->name('report.submit');
    Route::get('/photo/download/{id}', [PhotoController::class, 'download'])
        ->name('photo.download');
    Route::get('/plans', [PlanController::class, 'plans'])
        ->name('plans');
    Route::get('/organization', [OrganizationsController::class, 'index'])->name('organization');
    Route::post('/organization/store', [OrganizationsController::class, 'store'])->name('organization.store');

    Route::post('/contact-submit', [PhotoController::class, 'contact_submit'])->name('contact_submit');
    Route::get('/contact-thank-you', function () {
            return view('user.contact_thankyou');
        })->name('contact.thankyou');
    Route::get('/phpinfo', function() {
    phpinfo();
});

Route::middleware(['auth:web', 'role:owner'])->prefix('owner')->name('owner.')->group(function () {
    Route::get('/dashboard', [OwnerController::class, 'index'])->name('dashboard');
    Route::post('/logout', [OwnerController::class, 'ownerLogout'])->name('logout');
    Route::get('/login', [LoginController::class, 'showLogin'])->name('admin.login');
    Route::get('/profile', [OwnerController::class, 'profile'])->name('profile');
    Route::post('/profile/update', [OwnerController::class, 'profileUpdate'])->name('profile.update');
    Route::get('/change/password', [OwnerController::class, 'changePassword'])->name('change.password');
    Route::post('/update/password', [OwnerController::class, 'updatePassword'])->name('update.password');
    
    // Route::get('/admins', [AdminController::class, 'index'])->name('admin.users.data');
    Route::get('/employees', [OwnerController::class, 'employees'])->name('employee');
    Route::get('/employees/create', [OwnerController::class, 'create'])->name('employee.create');
    Route::post('/employees/store', [OwnerController::class, 'store'])->name('employee.store');
    Route::get('/employee/list/', [OwnerController::class, 'list'])->name('employees.list');
    Route::get('/edit/employee/{userId}', [OwnerController::class, 'editEmployee'])->name('employee.edit.data');
    Route::post('/update/employee/{userId}', [OwnerController::class, 'updateEmployee'])->name('update.employee.data');
     Route::get('/update/employee/status', [OwnerController::class, 'updateStatus'])->name('update.employee.status');

    

    
    
    


























});

