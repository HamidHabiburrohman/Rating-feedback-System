<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\UnitTypeController;
use App\Http\Controllers\Admin\UnitDepartmentController;
use App\Http\Controllers\Admin\FacilityController;
use App\Http\Controllers\Admin\UnitPhotoController;
use App\Http\Controllers\Admin\RatingController;
use App\Http\Controllers\Admin\RatingCategoryController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\AdminReplyController;
use App\Http\Controllers\Admin\ModerationLogController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ExportController;
use App\Http\Controllers\Admin\StudentController;

use App\Http\Controllers\Student;
use App\Http\Controllers\Student\BrowseController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\RatingController as StudentRatingController;
use App\Http\Controllers\Student\ReportController as StudentReportController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use App\Http\Controllers\Student\StudentSessionController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;

use Illuminate\Support\Facades\Route;

// ============================================================================
// API ROUTES - UNIT RATING FEEDBACK SYSTEM
// ============================================================================

// API VERSION 1
Route::prefix('v1')->group(function () {
    
    // ------------------------------------------------------------------------
    // PUBLIC UNIT BROWSING (VISITOR)
    // ------------------------------------------------------------------------
    // Endpoint untuk browsing unit tanpa login
    Route::prefix('units')->name('visitor.api.')->group(function () {
        Route::get('/', [BrowseController::class, 'apiUnits'])->name('list');
        Route::get('/{unit}/detail', [BrowseController::class, 'apiUnitDetail'])->name('detail');
    });
    
    // ------------------------------------------------------------------------
    // VISITOR ENDPOINTS (ANONYMOUS)
    // ------------------------------------------------------------------------
    Route::prefix('visitor')->middleware(['throttle:60,1'])->group(function () {
        
        // Unit Information
        Route::get('/units', [BrowseController::class, 'index']);
        Route::get('/units/{unit:slug}', [BrowseController::class, 'show']);
        
        // Unit Ratings
        Route::get('/units/{unit}/ratings', [StudentRatingController::class, 'unitRatings']);
        Route::get('/rating-categories', [StudentRatingController::class, 'getCategories']);
        
        // Session Management for Anonymous Visitors
        Route::post('/session/init', [StudentSessionController::class, 'init']);
        Route::get('/session/check', [StudentSessionController::class, 'check']);
        Route::get('/session/current', [StudentSessionController::class, 'current']);
        
        // Visit Tracking
        Route::post('/units/{unit}/visits', [BrowseController::class, 'trackVisit']);
    });
    
    // ------------------------------------------------------------------------
    // STUDENT AUTHENTICATION ENDPOINTS
    // ------------------------------------------------------------------------
    Route::prefix('student')->group(function () {
        Route::post('/login', [StudentSessionController::class, 'login']);
        Route::post('/logout', [StudentSessionController::class, 'logout']);
        Route::get('/check', [StudentSessionController::class, 'check']);
        Route::get('/current', [StudentSessionController::class, 'current']);
        Route::get('/activity', [StudentSessionController::class, 'activity']);
    });
    
    // ------------------------------------------------------------------------
    // STUDENT PROTECTED ROUTES
    // ------------------------------------------------------------------------
    Route::middleware(['student.session'])->prefix('student')->group(function () {
        
        // Dashboard
        Route::prefix('dashboard')->group(function () {
            Route::get('/', [StudentDashboardController::class, 'index']);
            Route::get('/stats', [StudentDashboardController::class, 'stats']);
            Route::get('/activity', [StudentDashboardController::class, 'activity']);
            Route::get('/recent-activity', [StudentDashboardController::class, 'recentActivity']);
        });
        
        // Ratings
        Route::prefix('ratings')->group(function () {
            Route::get('/history', [StudentRatingController::class, 'history']);
            Route::get('/unit/{unit:slug}', [StudentRatingController::class, 'unitRatings']);
            Route::post('/', [StudentRatingController::class, 'store']);
            Route::get('/{trackingCode}', [StudentRatingController::class, 'show']);
            Route::get('/{trackingCode}/edit', [StudentRatingController::class, 'edit']);
            Route::put('/{trackingCode}', [StudentRatingController::class, 'update']);
            Route::get('/check/{unit}/can-rate', [StudentRatingController::class, 'checkCanRate']);
        });
        
        // Reports
        Route::prefix('reports')->group(function () {
            Route::get('/history', [StudentReportController::class, 'history']);
            Route::post('/', [StudentReportController::class, 'store']);
            Route::get('/{trackingCode}', [StudentReportController::class, 'show']);
            Route::get('/check/{rating}/can-report', [StudentReportController::class, 'checkCanReport']);
        });
        
        // Profile
        Route::prefix('profile')->group(function () {
            Route::get('/', [StudentProfileController::class, 'show']);
            Route::get('/edit', [StudentProfileController::class, 'edit']);
            Route::put('/', [StudentProfileController::class, 'update']);
            Route::get('/sessions', [StudentProfileController::class, 'sessions']);
            Route::delete('/sessions/{sessionId}', [StudentProfileController::class, 'terminateSession']);
            Route::delete('/sessions/all/terminate', [StudentProfileController::class, 'terminateAllSessions']);
        });
    });
    
    // ------------------------------------------------------------------------
    // ADMIN AUTHENTICATION ENDPOINTS
    // ------------------------------------------------------------------------
    Route::prefix('auth')->group(function () {
        Route::post('/login', [LoginController::class, 'login']);
        Route::get('/check', [LoginController::class, 'check']);
        
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('/logout', [LogoutController::class, 'logout']);
        });
    });
    
    // ------------------------------------------------------------------------
    // ADMIN PROTECTED ROUTES
    // ------------------------------------------------------------------------
    Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
        
        // DASHBOARD
        Route::prefix('dashboard')->group(function () {
            Route::get('/stats', [DashboardController::class, 'stats']);
            Route::get('/charts', [DashboardController::class, 'charts']);
            Route::get('/overview', [DashboardController::class, 'overview']);
            Route::get('/audit-logs', [DashboardController::class, 'auditLogs']);
            Route::get('/recent-rated', [DashboardController::class, 'recentRated']);
            Route::get('/top-units/{type?}', [DashboardController::class, 'topUnits']);
            Route::get('/attention-units', [DashboardController::class, 'attentionUnits']);
            Route::get('/filter/{filter}', [DashboardController::class, 'getUnitsByFilter']);
        });
        
        // UNIT TYPES
        Route::prefix('unit-types')->group(function () {
            Route::get('/', [UnitTypeController::class, 'index']);
            Route::post('/', [UnitTypeController::class, 'store']);
            Route::get('/create', [UnitTypeController::class, 'create']);
            Route::get('/{unitType}', [UnitTypeController::class, 'show']);
            Route::get('/{unitType}/edit', [UnitTypeController::class, 'edit']);
            Route::put('/{unitType}', [UnitTypeController::class, 'update']);
            Route::delete('/{unitType}', [UnitTypeController::class, 'api-destroy']);
            Route::patch('/{unitType}/toggle-status', [UnitTypeController::class, 'toggleStatus']);
            Route::post('/reorder', [UnitTypeController::class, 'reorder']);
            Route::get('/icons/list', [UnitTypeController::class, 'getIcons']);
            Route::get('/stats/data', [UnitTypeController::class, 'stats']);
        });
        
        // UNIT DEPARTMENTS
        Route::prefix('unit-departments')->group(function () {
            Route::get('/', [UnitDepartmentController::class, 'index']);
            Route::post('/', [UnitDepartmentController::class, 'store']);
            Route::get('/create', [UnitDepartmentController::class, 'create']);
            Route::get('/{department}', [UnitDepartmentController::class, 'show']);
            Route::get('/{department}/edit', [UnitDepartmentController::class, 'edit']);
            Route::put('/{department}', [UnitDepartmentController::class, 'update']);
            Route::delete('/{department}', [UnitDepartmentController::class, 'destroy']);
            Route::patch('/{department}/toggle-status', [UnitDepartmentController::class, 'toggleStatus']);
            Route::get('/stats/data', [UnitDepartmentController::class, 'stats']);
        });
        
        // FACILITIES
        Route::prefix('facilities')->group(function () {
            Route::get('/', [FacilityController::class, 'index']);
            Route::post('/', [FacilityController::class, 'store']);
            Route::get('/create', [FacilityController::class, 'create']);
            Route::get('/{facility}', [FacilityController::class, 'show']);
            Route::get('/{facility}/edit', [FacilityController::class, 'edit']);
            Route::put('/{facility}', [FacilityController::class, 'update']);
            Route::delete('/{facility}', [FacilityController::class, 'destroy']);
            Route::get('/icons/list', [FacilityController::class, 'getIcons']);
            Route::get('/stats/data', [FacilityController::class, 'stats']);
            Route::get('/{facility}/units', [FacilityController::class, 'units']);
            Route::get('/export/data', [FacilityController::class, 'export']);
        });
        
        // UNITS
        Route::prefix('units')->group(function () {
            Route::get('/', [UnitController::class, 'index']);
            Route::post('/', [UnitController::class, 'store']);
            Route::get('/create', [UnitController::class, 'create']);
            Route::get('/{unit}', [UnitController::class, 'show']);
            Route::get('/{unit}/edit', [UnitController::class, 'edit']);
            Route::put('/{unit}', [UnitController::class, 'update']);
            Route::delete('/{unit}', [UnitController::class, 'destroy']);
            
            // Bulk actions
            Route::post('/bulk/delete', [UnitController::class, 'bulkDelete']);
            Route::post('/bulk/activate', [UnitController::class, 'bulkActivate']);
            Route::patch('/{unit}/toggle-status', [UnitController::class, 'toggleStatus']);
            
            // Unit Photos
            Route::prefix('{unit}/photos')->group(function () {
                Route::get('/', [UnitPhotoController::class, 'index']);
                Route::post('/upload', [UnitPhotoController::class, 'upload']);
                Route::delete('/{photo}', [UnitPhotoController::class, 'destroy']);
                Route::post('/{photo}/primary', [UnitPhotoController::class, 'setPrimary']);
                Route::post('/reorder', [UnitPhotoController::class, 'reorder']);
            });
            
            // Facilities assignment
            Route::post('/{unit}/facilities/sync', [UnitController::class, 'syncFacilities']);
        });
        
        // RATING CATEGORIES
        Route::prefix('rating-categories')->group(function () {
            Route::get('/', [RatingCategoryController::class, 'index']);
            Route::post('/', [RatingCategoryController::class, 'store']);
            Route::get('/create', [RatingCategoryController::class, 'create']);
            Route::get('/{category}', [RatingCategoryController::class, 'show']);
            Route::get('/{category}/edit', [RatingCategoryController::class, 'edit']);
            Route::put('/{category}', [RatingCategoryController::class, 'update']);
            Route::delete('/{category}', [RatingCategoryController::class, 'destroy']);
            Route::patch('/{category}/toggle-active', [RatingCategoryController::class, 'toggleActive']);
            Route::post('/reorder', [RatingCategoryController::class, 'reorder']);
            Route::get('/active/list', [RatingCategoryController::class, 'getActive']);
            Route::get('/stats/data', [RatingCategoryController::class, 'stats']);
            Route::post('/seed-defaults', [RatingCategoryController::class, 'seedDefaults']);
            Route::post('/validate-for-rating', [RatingCategoryController::class, 'validateForRating']);
            Route::get('/export/data', [RatingCategoryController::class, 'export']);
        });
        
        // RATINGS
        Route::prefix('ratings')->group(function () {
            Route::get('/', [RatingController::class, 'index']);
            Route::get('/{rating}', [RatingController::class, 'show']);
            Route::patch('/{rating}/status', [RatingController::class, 'updateStatus']);
            Route::post('/{rating}/moderate', [RatingController::class, 'moderate']);
            Route::post('/bulk-action', [RatingController::class, 'bulkAction']);
            Route::get('/stats/overview', [RatingController::class, 'stats']);
            Route::get('/unit/{unitId}/stats', [RatingController::class, 'unitStats']);
            Route::get('/export/data', [RatingController::class, 'export']);
            Route::delete('/{rating}', [RatingController::class, 'destroy']);
        });
        
        // ADMIN REPLIES
        Route::prefix('admin-replies')->group(function () {
            Route::get('/', [AdminReplyController::class, 'index']);
            Route::post('/{rating}/reply', [AdminReplyController::class, 'store']);
            Route::get('/{rating}', [AdminReplyController::class, 'show']);
            Route::put('/{reply}', [AdminReplyController::class, 'update']);
            Route::delete('/{reply}', [AdminReplyController::class, 'destroy']);
            Route::get('/stats/data', [AdminReplyController::class, 'stats']);
            Route::get('/check/{rating}/can-reply', [AdminReplyController::class, 'checkCanReply']);
            Route::get('/unit/{unitId}/recent', [AdminReplyController::class, 'recentForUnit']);
            Route::get('/export/data', [AdminReplyController::class, 'export']);
        });
        
        // REPORTS
        Route::prefix('reports')->group(function () {
            Route::get('/', [ReportController::class, 'index']);
            Route::get('/{report}', [ReportController::class, 'show']);
            Route::get('/{report}/edit', [ReportController::class, 'edit']);
            Route::put('/{report}', [ReportController::class, 'update']);
            Route::put('/{report}/status', [ReportController::class, 'updateStatus']);
            Route::post('/{report}/reply', [ReportController::class, 'reply']);
            Route::post('/bulk-action', [ReportController::class, 'bulkAction']);
            Route::get('/stats/overview', [ReportController::class, 'stats']);
            Route::get('/unit/{unitId}/stats', [ReportController::class, 'unitStats']);
            Route::get('/export/data', [ReportController::class, 'export']);
            Route::delete('/{report}', [ReportController::class, 'destroy']);
        });
        
        // MODERATION LOGS
        Route::prefix('moderation-logs')->group(function () {
            Route::get('/', [ModerationLogController::class, 'index']);
            Route::get('/{log}', [ModerationLogController::class, 'show']);
            Route::get('/stats/overview', [ModerationLogController::class, 'stats']);
            Route::get('/by-target/{targetType}/{targetId}', [ModerationLogController::class, 'byTarget']);
            Route::get('/export/data', [ModerationLogController::class, 'export']);
            Route::post('/cleanup', [ModerationLogController::class, 'cleanup']);
            Route::get('/summary', [ModerationLogController::class, 'summary']);
            Route::get('/by-admin/{adminId}', [ModerationLogController::class, 'byAdmin']);
            Route::delete('/{log}', [ModerationLogController::class, 'destroy']);
            Route::post('/bulk-destroy', [ModerationLogController::class, 'bulkDestroy']);
        });
        
        // SETTINGS
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index']);
            Route::post('/', [SettingController::class, 'store']);
            Route::get('/create', [SettingController::class, 'create']);
            Route::get('/{setting}', [SettingController::class, 'show']);
            Route::get('/{setting}/edit', [SettingController::class, 'edit']);
            Route::put('/{setting}', [SettingController::class, 'update']);
            Route::delete('/{setting}', [SettingController::class, 'destroy']);
            
            // Bulk operations
            Route::post('/bulk-update', [SettingController::class, 'bulkUpdate']);
            
            // Group operations
            Route::get('/group/{group}', [SettingController::class, 'getByGroup']);
            
            // Public settings
            Route::get('/public/all', [SettingController::class, 'getPublic']);
            
            // Value operations
            Route::get('/value/{key}', [SettingController::class, 'getValue']);
            Route::post('/reset/{key}', [SettingController::class, 'resetToDefault']);
            
            // Import/Export
            Route::post('/import', [SettingController::class, 'import']);
            Route::get('/export/data', [SettingController::class, 'export']);
            
            // Stats
            Route::get('/stats/data', [SettingController::class, 'stats']);
        });
        
        // EXPORTS
        Route::prefix('exports')->group(function () {
            Route::get('/', [ExportController::class, 'index']);
            Route::post('/reports', [ExportController::class, 'exportReports']);
            Route::post('/ratings', [ExportController::class, 'exportRatings']);
            Route::post('/units', [ExportController::class, 'exportUnits']);
            Route::post('/unit-types', [ExportController::class, 'exportUnitTypes']);
            Route::get('/download/{id}', [ExportController::class, 'downloadExport']);
        });
        
    });
});

// ----------------------------------------------------------------------------
// API INFORMATION
// ----------------------------------------------------------------------------
Route::get('/', function () {
    return response()->json([
        'message' => 'Unit Rating Feedback System API',
        'version' => '1.0.0',
        'endpoints' => [
            'public' => '/api/v1/units',
            'visitor' => '/api/v1/visitor',
            'student' => '/api/v1/student',
            'admin' => '/api/v1/admin',
            'auth' => '/api/v1/auth',
        ]
    ]);
});