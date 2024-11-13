<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Thread;
use App\Models\Course;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    use \Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Ensure authorize is recognized

    public function __construct()
    {
        // Add the middleware explicitly in the constructor
        $this->middleware('auth:api');
    }

    public function create(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);

        if ($course->instructor_id !== $request->user()->id) {
            return response()->json(['message' => 'Only the instructor can create threads'], 403);
        }

        $request->validate([
            'content' => 'required|string',
        ]);

        $thread = Thread::create([
            'course_id' => $course->id,
            'instructor_id' => $request->user()->id,
            'content' => $request->content,
        ]);

        return response()->json(['thread' => $thread], 201);
    }


    public function index($courseId)
    {
        $course = Course::find($courseId);
        if ($course) {
            $threads = $course->threads; // Get threads for the course
            return response()->json($threads);
        }
    
        return response()->json(['message' => 'Course not found'], 404);
    }


public function store(Request $request, $courseId)
{
    $course = Course::findOrFail($courseId);

    // Validate that the user is the instructor or has permission to create threads
    $this->authorize('create', Thread::class); // Custom policy check (optional)

    $request->validate([
        'content' => 'required|string',
    ]);

    $thread = Thread::create([
        'course_id' => $courseId,
        'instructor_id' => Auth::id(), // Assuming authenticated user is the instructor
        'content' => $request->content,
    ]);

    return response()->json($thread, 201);
}



    public function storeReply(Request $request, $threadId)
    {
        $user = Auth::user();
    
        // Check if the user is enrolled in the course associated with the thread
        $thread = Thread::findOrFail($threadId);
        if (!$user->courses->contains($thread->course_id)) {
            return response()->json(['message' => 'You must be enrolled in the course to reply.'], 403);
        }
    
        // Create a new reply
        $reply = Reply::create([
            'thread_id' => $threadId,
            'user_id' => $user->id,
            'content' => $request->content,
        ]);
    
        return response()->json(['reply' => $reply], 201);
    }
    


    public function delete($thread_id)
    {
        $thread = Thread::findOrFail($thread_id);

        // Use policy authorization
        $this->authorize('delete', $thread);

        $thread->delete();

        return response()->json(['message' => 'Thread deleted successfully.']);
    }


}

