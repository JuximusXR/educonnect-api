<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants incorrects.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Déconnexion réussie']);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Non authentifié.'], 401);
        }

        // Récupération des devoirs avec leurs relations
        $submissions = Submission::where('user_id', $user->id)
            ->with(['lesson', 'lesson.course'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Formater l'historique pour s'assurer que React reçoive des objets propres
        $formattedHistory = $submissions->map(function ($submission) {
            return [
                'id' => $submission->id,
                'content' => $submission->content,
                'status' => $submission->status,
                'grade' => $submission->grade,
                'is_local' => false, // Puisque ça vient de Laravel, c'est forcément synchronisé !
                'created_at' => $submission->created_at ? $submission->created_at->format('d/m/Y H:i') : 'Date inconnue',
                'lesson' => $submission->lesson ? [
                    'id' => $submission->lesson->id,
                    'title' => $submission->lesson->title,
                    'course' => $submission->lesson->course ? [
                        'id' => $submission->lesson->course->id,
                        'title' => $submission->lesson->course->title,
                    ] : ['title' => 'Cours inconnu']
                ] : ['title' => 'Leçon inconnue']
            ];
        });

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at ? $user->created_at->format('d/m/Y') : 'Inconnue',
            ],
            'stats' => [
                'total_submissions' => $submissions->count(),
                'graded_submissions' => $submissions->where('status', 'graded')->count(),
            ],
            'history' => $formattedHistory // On passe notre historique parfaitement formaté
        ]);
    }
}
