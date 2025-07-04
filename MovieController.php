<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // Mostra tutti i film
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            // Guest: non autenticato
            return response()->json(['message' => 'Devi essere autenticato!'], 401);
        }

        if ($user->hasRole('admin')) {
            // Admin: tutti i dati, anche film cancellati, esempio campo extra
            $movies = \App\Models\Movie::withTrashed()->get()->map(function ($movie) {
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'description' => $movie->description,
                    'year' => $movie->year,
                    'cover' => $movie->cover,
                    'category_id' => $movie->category_id,
                    'deleted_at' => $movie->deleted_at, // solo admin
                ];
            });
            return response()->json($movies);
        }

        // Utente normale: solo i dati base e solo i film non cancellati
        $movies = \App\Models\Movie::all()->map(function ($movie) {
            return [
                'id' => $movie->id,
                'title' => $movie->title,
                'description' => $movie->description,
                'year' => $movie->year,
                'cover' => $movie->cover,
                'category_id' => $movie->category_id,
            ];
        });
        return response()->json($movies);
    }


    // Mostra un film specifico
    public function show($id)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('admin')) {
            $movie = Movie::withTrashed()->find($id);
        } else {
            $movie = Movie::find($id);
        }
        if (!$movie) {
            return response()->json(['message' => 'Film non trovato'], 404);
        }
        return response()->json($movie);
    }


    // Crea un nuovo film (solo admin)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'year' => 'nullable|integer',
            'cover' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id'
        ]);

        $movie = Movie::create($request->all());
        return response()->json($movie, 201);
    }

    // Aggiorna un film (solo admin)
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['error' => 'Film non trovato'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            // altri campi
        ]);

        $movie->update($validated);

        return response()->json([
            'message' => 'Film aggiornato con successo',
            'movie' => $movie
        ]);
    }

    // Elimina un film (solo admin)
    public function destroy($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['error' => 'Film non trovato'], 404);
        }

        $movie->delete();

        return response()->json(['message' => 'Film eliminato con successo']);
    }
}
