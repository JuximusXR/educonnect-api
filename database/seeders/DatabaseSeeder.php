<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Création d'un étudiant de test pour la connexion
        User::create([
            'name' => 'Idris Kapinga',
            'email' => 'etudiant@educonnect.cd',
            'password' => Hash::make('password123'),
        ]);

        // 2. COURS 1 : Algorithmique et Programmation
        $course1 = Course::create([
            'title' => 'Algorithmique et Structure de Données',
            'code' => 'INF201',
            'description' => 'Introduction aux structures de données fondamentales : piles, files, listes chaînées et arbres.',
            'professor_name' => 'Prof. Nsiala',
        ]);

        // Leçon 1 : Complète (Vidéo + Texte + PDF)
        Lesson::create([
            'course_id' => $course1->id,
            'title' => 'Les Listes Chaînées Simples',
            'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ', // Exemple de lien vidéo
            'intro_text' => 'Dans cette leçon, nous allons comprendre comment allouer dynamiquement de la mémoire en utilisant les listes chaînées.',
            'content_markdown' => "Une liste chaînée est une structure de données linéaire où les éléments ne sont pas stockés à des emplacements mémoire contigus. Chaque élément pointe vers le suivant.\n\n### Avantages :\n- Insertion rapide\n- Suppression rapide\n- Taille dynamique",
            'pdf_url' => 'supports/chapitre1_listes.pdf', // Faux chemin PDF à télécharger
            'order' => 1,
        ]);

        // Leçon 2 : Texte Seul (Pas de vidéo, pas de PDF - Comme vous l'avez demandé)
        Lesson::create([
            'course_id' => $course1->id,
            'title' => 'Les Piles et les Files (Principe LIFO/FIFO)',
            'video_url' => null, // Pas de vidéo
            'intro_text' => 'Découverte des structures de données à accès restreint indispensables pour la gestion des processus.',
            'content_markdown' => "### 1. Les Piles (LIFO - Last In First Out)\nLe dernier élément arrivé est le premier à sortir. Exemple : la pile d'assiettes.\n\n### 2. Les Files (FIFO - First In First Out)\nLe premier élément arrivé est le premier à sortir. Exemple : une file d'attente au guichet.",
            'pdf_url' => null, // Pas de PDF fourni par le prof
            'order' => 2,
        ]);

        // 3. COURS 2 : Réseaux Informatiques
        $course2 = Course::create([
            'title' => 'Réseaux et Transmissions de Données',
            'code' => 'INF202',
            'description' => 'Comprendre l’architecture des réseaux informatiques, le modèle OSI et les protocoles TCP/IP.',
            'professor_name' => 'Prof. Minsiensi',
        ]);

        // Leçon 1 du cours réseau (Avec Vidéo et Texte, mais SANS PDF)
        Lesson::create([
            'course_id' => $course2->id,
            'title' => 'Introduction au Modèle OSI',
            'video_url' => 'https://www.youtube.com/watch?v=abc123xyz',
            'intro_text' => 'Comprendre les 7 couches de communication indispensables pour interconnecter des systèmes hétérogènes.',
            'content_markdown' => "Le modèle OSI (Open Systems Interconnection) est une norme qui décrit comment les données sont transmises sur un réseau.\n\nLes 7 couches sont : Physique, Liaison, Réseau, Transport, Session, Présentation, Application.",
            'pdf_url' => null, // Pas de PDF
            'order' => 1,
        ]);
    }
}
