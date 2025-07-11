<?php

namespace App\Http\Controllers;

use App\Models\Episode;
use Illuminate\Http\Request;

class EpisodeController extends Controller
{
    // Mostra tutti gli episodi
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Devi essere autenticato!'], 401);
        }

        if ($user->hasRole('admin')) {
            $episodes = \App\Models\Episode::withTrashed()->get()->map(function ($episode) {
                return [
                    'id' => $episode->id,
                    'title' => $episode->title,
                    'episode_number' => $episode->episode_number,
                    'season_number' => $episode->season_number,
                    'series_id' => $episode->series_id,
                    'deleted_at' => $episode->deleted_at,
                ];
            });
            return response()->json($episodes);
        }

        $episodes = \App\Models\Episode::all()->map(function ($episode) {
            return [
                'id' => $episode->id,
                'title' => $episode->title,
                'episode_number' => $episode->episode_number,
                'season_number' => $episode->season_number,
                'series_id' => $episode->series_id,
            ];
        });
        return response()->json($episodes);
    }


    // Mostra un episodio
    public function show($id)
    {
        $episode = Episode::find($id);

        if (!$episode) {
            return response()->json(['error' => 'Episodio non trovato'], 404);
        }

        return response()->json($episode);
    }

    // Crea un nuovo episodio (solo admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'episode_number' => 'required|integer',
            'season_number' => 'nullable|integer',
            'series_id' => 'required|integer|exists:series,id',
            'description' => 'nullable|string',
        ]);


        $episode = Episode::create($validated);

        return response()->json([
            'message' => 'Episodio creato con successo',
            'episode' => $episode
        ], 201);
    }

    // Aggiorna un episodio (solo admin)
    public function update(Request $request, $id)
    {
        $episode = Episode::find($id);

        if (!$episode) {
            return response()->json(['error' => 'Episodio non trovato'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'episode_number' => 'required|integer',
            'season_number' => 'nullable|integer',
            'series_id' => 'required|integer|exists:series,id',
            'description' => 'nullable|string',
        ]);


        $episode->update($validated);

        return response()->json([
            'message' => 'Episodio aggiornato con successo',
            'episode' => $episode
        ]);
    }

    // Elimina un episodio (solo admin)
    public function destroy($id)
    {
        $episode = Episode::find($id);

        if (!$episode) {
            return response()->json(['error' => 'Episodio non trovato'], 404);
        }

        $episode->delete();

        return response()->json(['message' => 'Episodio eliminato con successo']);
    }
}
