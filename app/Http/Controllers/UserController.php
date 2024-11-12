<?php

namespace App\Http\Controllers;

use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // Update user details
    public function updateUserDetails(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'address' => 'required|string',
            'phone_number' => 'required|string',
        ]);

        $user = Auth::user(); // Get the authenticated user
        
        // Check if user detail already exists
        $userDetail = $user->userDetail; 

        if (!$userDetail) {
            // If user detail doesn't exist, create new record
            $userDetail = new UserDetail();
            $userDetail->user_id = $user->id;
        }

        // Update the user details
        $userDetail->address = $request->address;
        $userDetail->phone_number = $request->phone_number;
        $userDetail->save();

        return response()->json([
            'message' => 'User details updated successfully',
            'data' => $userDetail
        ]);
    }

    public function getRole(Request $request)
    {
        // Assuming the user is authenticated via JWT or another method
        $user = $request->user();
        
        // Return the role of the user (e.g., student, admin, instructor)
        return response()->json(['role' => $user->role]);
    }
}
