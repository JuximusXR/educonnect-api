<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;

class SubmissionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'content' => 'required|string',
        ]);

        $submission = Submission::create([
            'user_id' => $request->user()->id,
            'lesson_id' => $request->lesson_id,
            'content' => $request->content,
            'status' => 'pending'
        ]);

        return response()->json([
            'message' => 'Devoir enregistré avec succès sur le serveur.',
            'submission' => $submission
        ], 201);
    }

    public function mySubmissions(Request $request)
    {
        $submissions = Submission::where('user_id', $request->user()->id)->get();
        return response()->json($submissions);
    }
}
