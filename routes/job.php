<?php 
use App\Http\Controllers\Job\CategoryController;
    Route::group(['namespace' => 'App\Http\Controllers\Job', 'prefix' => 'admin', 'middleware' => ['auth', 'admin', 'prevent-back-history']], function() {

    	Route::controller(JobController::class)->group(function () {
            Route::get('/job', 'index')->name('job');
            Route::get('/job/create', 'create')->name('job.create');
            Route::post('/job/add', 'store')->name('job.store');
            Route::get('/job/edit/{id}', 'edit')->name('job.edit');
            Route::post('/job/update/{id}', 'update')->name('job.update');
            Route::get('/job/destroy/{id}', 'destroy')->name('job.destroy');
            Route::post('/job/status', 'status')->name('job.status');
            
        });

    	Route::controller(JobApplicationController::class)->group(function () {
            Route::get('/job/application', 'index')->name('job.application');
            Route::get('/job/application/create', 'create')->name('job.application.create');
            Route::post('/job/application/add', 'store')->name('job.application.store');
            Route::get('/job/application/edit/{id}', 'edit')->name('job.application.edit');
            Route::get('/job/application/analytics/{id}', 'analytics')->name('job.application.analytics');
            Route::post('/job/application/update/{id}', 'update')->name('job.application.update');
            Route::get('/job/application/destroy/{id}', 'destroy')->name('job.application.destroy');
            Route::post('/job/application/status', 'status')->name('job.application.status');
            
        });
 

    	 Route::controller(JobCompanyController::class)->group(function () {
            Route::get('/job/company', 'index')->name('job.company');
            Route::get('/job/company/create', 'create')->name('job.company.create');

            Route::post('/job/company/add', 'store')->name('job.company.store');
            Route::get('/job/company/edit/{id}', 'edit')->name('job.company.edit');
            Route::get('/job/company/analytics/{id}', 'analytics')->name('job.company.analytics');
            Route::post('/job/company/update/{id}', 'update')->name('job.company.update');
            Route::get('/job/company/destroy/{id}', 'destroy')->name('job.company.destroy');
            Route::post('/job/company/status', 'status')->name('job.company.status');
            
        });
 
	
	    Route::controller(JobTypeController::class)->group(function () {
            Route::get('/job/type', 'index')->name('job.type');
            Route::post('/job/type/add', 'store')->name('job.type.store');
            Route::get('/job-type/edit/{id}', 'edit')->name('job.type.edit');
            Route::post('/job/type/update/{id}', 'update')->name('job.type.update');
            Route::get('/job-type/destroy/{id}', 'destroy')->name('job.type.destroy');
            Route::post('/job/type/status', 'status')->name('job.type.status');
            
        });

        Route::controller(JobSkillController::class)->group(function () {
            Route::get('/job/skill', 'index')->name('job.skill');
            Route::post('/job/skill/add', 'store')->name('job.skill.store');
            Route::get('/job-skill/edit/{id}', 'edit')->name('job.skill.edit');
            Route::post('/job/skill/update/{id}', 'update')->name('job.skill.update');
            Route::get('/job-skill/destroy/{id}', 'destroy')->name('job.skill.destroy');
            Route::post('/job/skill/status', 'status')->name('job.skill.status');
            
        });

        Route::controller(JobShiftController::class)->group(function () {
            Route::get('/job/shift', 'index')->name('job.shift');
            Route::post('/job/shift/add', 'store')->name('job.shift.store');
            Route::get('/job-shift/edit/{id}', 'edit')->name('job.shift.edit');
            Route::post('/job/shift/update/{id}', 'update')->name('job.shift.update');
            Route::get('/job-shift/destroy/{id}', 'destroy')->name('job.shift.destroy');
            Route::post('/job/shift/status', 'status')->name('job.shift.status');
            
        });

        Route::controller(JobExperienceController::class)->group(function () {
            Route::get('/job/experience', 'index')->name('job.experience');
            Route::post('/job/experience/add', 'store')->name('job.experience.store');
            Route::get('/job-experience/edit/{id}', 'edit')->name('job.experience.edit');
            Route::post('/job/experience/update/{id}', 'update')->name('job.experience.update');
            Route::get('/job-experience/destroy/{id}', 'destroy')->name('job.experience.destroy');
            Route::post('/job/experience/status', 'status')->name('job.experience.status');
            
        });

        Route::controller(JobLevelController::class)->group(function () {
            Route::get('/job/level', 'index')->name('job.level');
            Route::post('/job/level/add', 'store')->name('job.level.store');
            Route::get('/job-level/edit/{id}', 'edit')->name('job.level.edit');
            Route::post('/job/level/update/{id}', 'update')->name('job.level.update');
            Route::get('/job-level/destroy/{id}', 'destroy')->name('job.level.destroy');
            Route::post('/job/level/status', 'status')->name('job.level.status');
            
        });

        Route::controller(JobCareerLevelController::class)->group(function () {
            Route::get('/job/career-level', 'index')->name('job.career.level');
            Route::post('/job/career-level/add', 'store')->name('job.career.level.store');
            Route::get('/job/career-level/edit/{id}', 'edit')->name('job.career.level.edit');
            Route::post('/job/career-level/update/{id}', 'update')->name('job.career.level.update');
            Route::get('/job/career-level/destroy/{id}', 'destroy')->name('job.career.level.destroy');
            Route::post('/job/career-level/status', 'status')->name('job.career.level.status');
            
        });

        Route::controller(JobFunctionalAreaController::class)->group(function () {
            Route::get('/job/functional-area', 'index')->name('job.functional.area');
            Route::post('/job/functional-area/add', 'store')->name('job.functional.area.store');
            Route::get('/job/functional-area/edit/{id}', 'edit')->name('job.functional.area.edit');
            Route::post('/job/functional-area/update/{id}', 'update')->name('job.functional.area.update');
            Route::get('/job/functional-area/destroy/{id}', 'destroy')->name('job.functional.area.destroy');
            Route::post('/job/functional-area/status', 'status')->name('job.functional.area.status');
            
        });

        Route::controller(JobCategoryController::class)->group(function () {
            Route::get('/job/category', 'index')->name('job.category');
            Route::post('/job/category/add', 'store')->name('job.category.store');
            Route::get('/job/category/edit/{id}', 'edit')->name('job.category.edit');
            Route::post('/job/category/update/{id}', 'update')->name('job.category.update');
            Route::get('/job/category/destroy/{id}', 'destroy')->name('job.category.destroy');
            Route::post('/job/category/status', 'status')->name('job.category.status');
            
        });

        Route::controller(JobDegreeLevelController::class)->group(function () {
            Route::get('/job/degree-level', 'index')->name('job.degree.level');
            Route::post('/job/degree-level/add', 'store')->name('job.degree.level.store');
            Route::get('/job/degree-level/edit/{id}', 'edit')->name('job.degree.level.edit');
            Route::post('/job/degree-level/update/{id}', 'update')->name('job.degree.level.update');
            Route::get('/job/degree-level/destroy/{id}', 'destroy')->name('job.degree.level.destroy');
            Route::post('/job/degree-level/status', 'status')->name('job.degree.level.status');
            
        });

        Route::controller(JobDegreeTypeController::class)->group(function () {
            Route::get('/job/degree-type', 'index')->name('job.degree.type');
            Route::post('/job/degree-type/add', 'store')->name('job.degree.type.store');
            Route::get('/job/degree-type/edit/{id}', 'edit')->name('job.degree.type.edit');
            Route::post('/job/degree-type/update/{id}', 'update')->name('job.degree.type.update');
            Route::get('/job/degree-type/destroy/{id}', 'destroy')->name('job.degree.type.destroy');
            Route::post('/job/degree-type/status', 'status')->name('job.degree.type.status');
            
        });



  });


?>