<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ThreadController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('courses', [CourseController::class, 'getCourses']);  // Public route for active courses



Route::middleware('auth:api')->group(function () {
    // Instructor-related routes
    Route::post('courses', [CourseController::class, 'create']);
    // Instructor can edit only their course
    Route::put('courses/{course}', [CourseController::class, 'update']); 
    Route::post('threads/{thread_id}/replies', [ReplyController::class, 'create']);
    Route::put('users/details', [UserController::class, 'updateUserDetails']);
    Route::post('courses/{course_id}/threads', [ThreadController::class, 'create']);

    //     // Delete thread route
    Route::delete('threads/{thread_id}', [ThreadController::class, 'delete']);
    Route::delete('threads/{thread_id}', [ThreadController::class, 'delete'])->middleware('auth:api');

    // Delete reply route
    Route::delete('replies/{reply_id}', [ReplyController::class, 'delete']);

    Route::post('courses/{courseId}/enroll', [CourseController::class, 'enroll']);
    Route::post('/threads/{thread}/replies', [ThreadController::class, 'storeReply']);
    Route::put('courses/{courseId}', [CourseController::class, 'update']);
    
    // Instructor can edit and delete their courses
    Route::get('courses/my_courses', [CourseController::class, 'getCoursesByInstructor']);  // Instructor's courses
    Route::delete('courses/my_courses/{courseId}', [CourseController::class, 'delete']);  // Instructor's courses
    // Route::delete('courses/{courseId}', [CourseController::class, 'delete']);  // Instructor can delete their course


    Route::get('courses/enrolled', [CourseController::class, 'enrolledCourses']);  // enrolledCourses by students
    Route::post('courses/{courseId}/unenroll', [CourseController::class, 'unenroll']);
    Route::get('user/role', [UserController::class, 'getRole']); //take role 



    Route::get('courses/aaaaaa', [CourseController::class, 'myCourses']);  // enrolledCourses by students


});

