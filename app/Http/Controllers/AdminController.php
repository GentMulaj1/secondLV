<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    // Fetch all users
    public function getAllUsers()
    {
        try {
            $users = User::all();  // Fetch all users
            return response()->json($users, 200);
        } catch (\Exception $e) {
            // Log the error and return a 500 response
            Log::error("Error fetching users: " . $e->getMessage());
            return response()->json(['message' => 'Error fetching users'], 500);
        }
    }

    // Fetch all courses
    public function getAllCourses()
    {
        try {
            $courses = Course::all();  // Fetch all courses
            return response()->json($courses, 200);
        } catch (\Exception $e) {
            // Log the error and return a 500 response
            Log::error("Error fetching courses: " . $e->getMessage());
            return response()->json(['message' => 'Error fetching courses'], 500);
        }
    }

    // Delete a user by ID
    public function deleteUser($id)
    {
        try {
            $user = User::findOrFail($id);  // Find user by ID

            $user->delete();  // Delete the user
            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            // Log the error and return a 500 response
            Log::error("Error deleting user: " . $e->getMessage());
            return response()->json(['message' => 'Error deleting user'], 500);
        }
    }

    // Delete a course by ID
//     public function deleteCourse(Request $request, $courseId)
// {
//     try {
//         // Find the course by ID
//         $course = Course::findOrFail($courseId);

//         // Ensure the user is an admin (or the course instructor)
//         if (Auth::user()->role !== 'admin') {
//             return response()->json(['message' => 'Unauthorized'], 403);
//         }

//         // Delete the course
//         $course->delete();

//         // Return success message
//         return response()->json(['message' => 'Course deleted successfully'], 200);
//     } catch (\Exception $e) {
//         // Log the error
//         Log::error("Error deleting course: " . $e->getMessage());
//         return response()->json(['message' => 'Error deleting course'], 500);
//     }
// }


public function deleteCourse($id)
{
    try {
        $course = Course::findOrFail($id);  // Find user by ID
        $course->delete();  // Delete the user
        return response()->json(['message' => 'Course deleted successfully'], 200);
    } catch (\Exception $e) {
        // Log the error and return a 500 response
        Log::error("Error deleting user: " . $e->getMessage());
        return response()->json(['message' => 'Error deleting course'], 500);
    }
}





}
