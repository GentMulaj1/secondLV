<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'description' => $request->description,
            'instructor_id' => $request->user()->id,
        ]);

        return response()->json(['course' => $course], 201);
    }

    public function getCoursesByInstructor(Request $request)
{
    // Assuming the logged-in user is an instructor
    $user = $request->user(); // Get the currently authenticated user

    // Retrieve the courses that belong to this user
    $courses = $user->courses; // Assuming `courses` relationship is defined on the User model

    return response()->json($courses);
}

public function getThreads($courseId)
{
    try {
        // Find the course by its ID
        $course = Course::findOrFail($courseId);

        // Retrieve all threads for the given course
        $threads = $course->threads;  // Using the hasMany relationship

        // Return the threads as a JSON response
        return response()->json($threads, 200);
    } catch (\Exception $e) {
        return response()->json(['error' => 'Course not found or error fetching threads.'], 404);
    }
}

public function update(Request $request, $courseId)
{
    $course = Course::findOrFail($courseId);
    
    // Check if the authenticated user is the owner of the course
    if ($course->instructor_id != Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403); // Return 403 if not the owner
    }

    // Validate the incoming request
    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    // Update course
    $course->title = $validated['title'];
    $course->description = $validated['description'];
    $course->save();

    return response()->json($course);
}

public function enroll(Request $request, $courseId)
{
    $user = $request->user(); // Get the authenticated user
    $course = Course::findOrFail($courseId); // Find the course by ID

    // Check if the user is already enrolled
    if ($user->courses->contains($course)) {
        return response()->json(['message' => 'You are already enrolled in this course.'], 400);
    }

    // Enroll the user
    $user->courses()->attach($course); // Assuming 'courses' is the relationship name
    return response()->json(['message' => 'Successfully enrolled in the course!'], 200);
}


public function show($courseId)
{
    $course = Course::find($courseId);
    
    if (!$course) {
        return response()->json(['message' => 'Course not found'], 404);
    }

    return response()->json($course);
}




    public function delete(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
    
        // Check if the logged-in user is the instructor who created the course
        if ($course->instructor_id !== $request->user()->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $course->delete();
    
        return response()->json(['message' => 'Course deleted successfully'], 200);
    }


   public function getCourses()
    {
        // Fetch courses for all users, no authentication needed
        $courses = Course::all();
        return response()->json($courses);
    }

    public function enrolledCourses(Request $request)
    {
        $user = $request->user();  // Get authenticated user
        $courses = $user->courses;  // Fetch the courses the user is enrolled in
        return response()->json($courses);
    }


    public function unenroll(Request $request, $courseId)
{
    $user = $request->user();  // Get the authenticated user
    $course = Course::find($courseId);  // Find the course by its ID

    // Check if the course exists and if the user is enrolled in it
    if (!$course) {
        return response()->json(['message' => 'Course not found'], 404);
    }

    if ($user->courses->contains($course)) {
        // Only allow unenrollment if the user is enrolled in the course
        $user->courses()->detach($courseId);  // Remove the relationship (many-to-many)
        return response()->json(['message' => 'Successfully unenrolled from the course']);
    } else {
        // The user is not enrolled in this course
        return response()->json(['message' => 'You are not enrolled in this course'], 403);
    }
}




    public function myCourses()
    {
        $user = Auth::user(); // Get the currently authenticated user
        $courses = Course::where('instructor_id', $user->id)->get(); // Get courses where instructor_id matches the user's ID
        return response()->json($courses);
    }

}
