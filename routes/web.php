<?php
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ConsumerController;
use App\Http\Controllers\LineManController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ElectricMeterController;
use App\Http\Controllers\BrownoutScheduling;
use App\Http\Controllers\ReconnectionController;
use App\Http\Controllers\ConsumerMeterHistoryController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\ConsumerWelcome;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\PasswordController;
use App\Models\Consumer;
use App\Http\Controllers\Auth\CustomForgotPasswordController;
use App\Http\Controllers\Auth\CustomResetPasswordController;
use App\Http\Controllers\GroupNameController;
use App\Http\Controllers\Auth\OtpPasswordResetController;
use App\Http\Controllers\BillingController;




        Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login'])->name('login.perform');
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

        Route::post('/check-email', [ConsumerController::class, 'checkEmail'])->name('check.email');
        Route::get('/staff/check-email', [StaffController::class, 'checkEmail'])->name('staff.checkEmail');

        Route::get('/password/forgot', [OtpPasswordResetController::class, 'showForgot'])
        ->name('password.forgot');

        Route::get('/password/request-otp', function () {
        return redirect()->route('password.forgot');
        });


        Route::get('/reset-password', [OtpPasswordResetController::class, 'showReset'])
        ->name('password.reset.form');

                Route::post('/password/request-otp', [OtpPasswordResetController::class, 'requestOtp'])->name('password.request.otp');
                Route::post('/password/resend-otp', [OtpPasswordResetController::class, 'resendOtp'])->name('password.resend.otp');
                Route::post('/password/reset', [OtpPasswordResetController::class, 'resetPassword'])->name('password.reset');



                Route::get('/password/change', [PasswordController::class, 'showChangeForm'])->name('password.change');
                Route::post('/password/change', [PasswordController::class, 'update'])->name('password.change.update');
 
                Route::middleware(['auth', 'force.password.change'])->group(function () {

                Route::middleware(['check.permission'])->group(function () {
                                          Route::get('/dashboard',[DashboardController::class, 'index'])->name('dashboard.index');
                Route::get('/permissions/{role}/edit', [RolePermissionController::class, 'edit'])->name('permissions.edit');
                Route::put('/permissions/{role}/update', [RolePermissionController::class, 'update'])->name('permissions.update');
              
              
                Route::get('/consumerIndex',[ConsumerController::class, 'index'])->name('consumer.index');

                Route::get('/staffIndex',[StaffController::class, 'index'])->name('staff.index');

                Route::post('/addStaff',[StaffController::class,'storeStaff'])->name('staff.store');

                Route::put('/staffUpdate/{id}', [StaffController::class, 'update'])->name('staff.update');
                Route::get('/staff/search',[StaffController::class, 'search'])->name('staff.search');

      

                Route::patch('/staff/toggle-status/{id}', [StaffController::class, 'toggleStatus'])->name('staff.toggleStatus');
                Route::patch('/staff/archive/{id}', [StaffController::class, 'archive'])->name('staff.archive');


        




                Route::patch('/restore/{id}', [StaffController::class, 'restore'])->name('users.restore');
                Route::delete('/destroy/{id}', [StaffController::class, 'destroy'])->name('users.destroy');

                Route::post('/bulkRestore', [StaffController::class, 'bulkRestore'])->name('users.bulkRestore');
                Route::post('/bulkDelete', [StaffController::class, 'bulkDelete'])->name('users.bulkDelete');


                Route::post('/addConsumer',[ConsumerController::class,'store'])->name('consumers.store');
                Route::put('/consumer/{id}', [ConsumerController::class, 'update'])->name('consumer.update');
                Route::put('/consumer-archived/{id}', [ConsumerController::class, 'archived'])->name('consumer.archived');
                Route::put('/consumer-restore/{id}', [ConsumerController::class, 'unArchived'])->name('consumer.unArchived');
                Route::delete('/consumer/{id}', [ConsumerController::class, 'destroyConsumer'])->name('consumer.destroy');


                Route::get('/meters/{meter}/transfer', [ConsumerMeterHistoryController::class, 'transferForm'])->name('meters.transfer.form');
                Route::post('/meters/{meter}/transfer-or-replace', [ConsumerMeterHistoryController::class, 'transferOrReplace'])
                ->name('meters.transferOrReplace');
    

 
                Route::post('/consumers/{consumer}/assign-meter', [ConsumerMeterHistoryController::class, 'assignMeter'])->name('meters.assign');





                Route::get('/consumers/{consumer}/meter-history/recent', [ConsumerMeterHistoryController::class, 'recent']);
                Route::get('/meter-history', [ConsumerMeterHistoryController::class, 'index'])->name('meter-history.index');


        


   


                Route::get('/electricMeter', [ElectricMeterController::class, 'index'])->name('electricMeter.index');
                
                Route::post('/check-meter', [ElectricMeterController::class, 'checkMeter'])->name('check.meter');
                Route::post('/electricMeterStore', [ElectricMeterController::class, 'store'])->name('electricMeter.store');
                Route::put('/electricMeterUpdate/{id}', [ElectricMeterController::class, 'update'])->name('electricMeter.update');

                Route::put('/electric-meters/{id}/archive', [ElectricMeterController::class, 'archived'])->name('electric-meters.archive');

                Route::delete('/electricMeter/{id}', [ElectricMeterController::class, 'destroy'])->name('electricMeter.destroy');
                Route::delete('/electricMeterbulkDelete', [ElectricMeterController::class, 'bulkDelete'])->name('electricMeter.bulkDelete');
                Route::patch('/meters/toggle-status/{meter}', [ElectricMeterController::class, 'toggleStatus'])
               ->name('meters.statusToggle');

                Route::put('/meters/{meter}/update', [ElectricMeterController::class, 'updateMeter'])->name('meters.update');

                Route::get('/brownoutSched', [BrownoutScheduling::class, 'index'])->name('BrownoutScheduling.index');
                Route::post('/storeSchedule', [BrownoutScheduling::class, 'storeSchedule'])->name('store.schedule');
                Route::put('/brownoutSchedUpdate/{id}' , [BrownoutScheduling::class, 'updateSchedule'])->name('schedule.update');
                Route::put('/schedule/{id}/cancel', [BrownoutScheduling::class, 'cancel'])->name('schedule.cancel');
                Route::put('/schedule/{id}/archive', [BrownoutScheduling::class, 'archive'])->name('schedule.archive');

                Route::get('/reconnection', [ReconnectionController::class, 'index'])->name('reconnection.index');

                Route::post('/linemen', [LineManController::class, 'createLineMan'])->name('linemen.create');
                Route::put('/linemanUpdate/{id}', [LineManController::class, 'updateLineMan'])->name('lineman.update');
                Route::post('/linemen/{lineman}/deactivate', [LineManController::class, 'deactivate'])->name('linemen.deactivate');
                Route::post('/linemen/{lineman}/activate', [LineManController::class, 'activate'])->name('linemen.activate');
                
                Route::post('/linemen/{lineman}/on-leave', [LineManController::class, 'onLeave'])->name('linemen.onleave');
                Route::post('/linemen/{lineman}/back-from-leave', [LineManController::class, 'backFromLeave'])->name('linemen.back_from_leave');

                Route::post('/linemen/{lineman}/archive', [LineManController::class, 'archive'])->name('linemen.archive');
                Route::get('/linemen/{id}/profile', [LineManController::class, 'profile'])->name('linemen.profile');
                Route::post('/linemen/update-group-name', [LineManController::class, 'updateGroupName'])->name('linemen.updateGroupName');
                Route::post('/linemen/update-group-assignment', [LineManController::class, 'updateGroupAssignment'])->name('linemen.updateGroupAssignment');
                Route::put('/linemen/{id}/change-group', [LineManController::class, 'changeGroup'])->name('linemen.changeGroup');
                Route::get('/linemen/by-group/{groupId?}', [LineManController::class, 'getByGroup'])->name('linemen.byGroup');
                Route::post('/groups', [GroupNameController::class, 'storeGroup'])->name('groups.store');
                Route::put('/groups/{group}/update', [GroupNameController::class, 'updateGroup'])->name('groups.update');
                Route::delete('/groups/{group}', [GroupNameController::class, 'destroyGroup'])->name('groups.destroyGroup');
                
                Route::get('/billingManagement', [BillingController::class, 'index'])->name('billing.index');

        });
   });

