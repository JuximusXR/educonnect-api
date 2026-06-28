<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::with('lessons')->get();
        return response()->json($courses);
    }

    public function show($id)
    {
        $course = Course::with('lessons')->find($id);

        if (!$course) {
            return response()->json(['message' => 'Cours non trouvé'], 404);
        }

        return response()->json($course);
    }

    public function storeCourse(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'code' => 'required|string|unique:courses',
            'description' => 'required|string',
            'professor_name' => 'required|string',
        ]);

        $course = Course::create($request->all());

        return response()->json(['message' => 'Cours créé avec succès', 'course' => $course], 201);
    }

    public function storeLesson(Request $request, $courseId)
    {
        $request->validate([
            'title' => 'required|string',
            'intro_text' => 'nullable|string',
            'content_markdown' => 'nullable|string',
            'video_url' => 'nullable|url',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        $pdfPath = null;

        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('supports', 'public');
        }

        $lesson = Lesson::create([
            'course_id' => $courseId,
            'title' => $request->title,
            'intro_text' => $request->intro_text,
            'content_markdown' => $request->content_markdown,
            'video_url' => $request->video_url,
            'pdf_url' => $pdfPath ? '/storage/' . $pdfPath : null,
            'order' => Lesson::where('course_id', $courseId)->count() + 1
        ]);

        return response()->json(['message' => 'Leçon et support téléversés avec succès', 'lesson' => $lesson], 201);
    }
}
