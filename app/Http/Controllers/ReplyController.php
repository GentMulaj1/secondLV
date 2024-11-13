<?php

namespace App\Http\Controllers;

use App\Models\Reply;
use App\Models\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReplyController extends Controller
{

     public function __construct()
     {
         $this->middleware('auth:api');
     }

     public function create(Request $request, $thread_id)
     {
         $thread = Thread::findOrFail($thread_id);
         $course = $thread->course;
     
         // Check if user is enrolled in the course
         if (!$course->students()->where('user_id', $request->user()->id)->exists()) {
             return response()->json(['message' => 'You must be enrolled in the course to reply.'], 403);
         }
     
         $request->validate(['content' => 'required|string']);
     
         $reply = Reply::create([
             'thread_id' => $thread->id,
             'user_id' => $request->user()->id,
             'content' => $request->content,
         ]);
     
         return response()->json(['reply' => $reply], 201);
     }

     public function index($thread_id)
{
    $thread = Thread::findOrFail($thread_id);
    $replies = $thread->replies;

    return response()->json($replies);
}





public function store(Request $request, $threadId)
    {
        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $thread = Thread::findOrFail($threadId);
        
        // Assuming that 'user_id' is the ID of the authenticated student
        $reply = $thread->replies()->create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);

        return response()->json($reply, 201); // Return the created reply
    }



   
    public function delete($reply_id)
    {
        $reply = Reply::findOrFail($reply_id);
        $user = Auth::user();

        // Check if the user is either an admin or an instructor
        if ($user->role === 'admin' || $user->role === 'instructor') {
            $reply->delete();
            return response()->json([
                'message' => 'Reply deleted successfully.'
            ]);
        }

        return response()->json([
            'message' => 'You do not have permission to delete this reply.'
        ], 403);
    }
}
