<?php

namespace App\Http\Controllers;

use App\Models\Series;
use Illuminate\Http\Request;

class SeriesController extends Controller
{
    // Mostra tutte le serie (GET /api/series)
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Devi essere autenticato!'], 401);
        }

        if ($user->hasRole('admin')) {
            $series = \App\Models\Series::withTrashed()->get()->map(function ($serie) {
                return [
                    'id' => $serie->id,
                    'title' => $serie->title,
                    'description' => $serie->description,
                    'year' => $serie->year,
                    'cover' => $serie->cover,
                    'category_id' => $serie->category_id,
                    'deleted_at' => $serie->deleted_at,
                ];
            });
            return response()->json($series);
        }

        $series = \App\Models\Series::all()->map(function ($serie) {
            return [
                'id' => $serie->id,
                'title' => $serie->title,
                'description' => $serie->description,
                'year' => $serie->year,
                'cover' => $serie->cover,
                'category_id' => $serie->category_id,
            ];
        });
        return response()->json($series);
    }


    // Mostra una singola serie (GET /api/series/{id})
    public function show($id)
    {
        $series = Series::find($id);

        if (!$series) {
            return response()->json(['error' => 'Serie non trovata'], 404);
        }

        return response()->json($series);
    }

    // Crea una nuova serie (POST /api/series) -- solo admin
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'year' => 'nullable|integer',
            'cover' => 'nullable|string',
            'category_id' => 'nullable|integer|exists:categories,id'
        ]);

        $series = Series::create($request->all());
        return response()->json($series, 201);
    }

    // Aggiorna una serie esistente (PUT /api/series/{id}) -- solo admin
    public function update(Request $request, $id)
    {
        $series = Series::find($id);

        if (!$series) {
            return response()->json(['error' => 'Serie non trovata'], 404);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            // altri campi qui
        ]);

        $series->update($validated);

        return response()->json([
            'message' => 'Serie aggiornata con successo',
            'series' => $series
        ]);
    }

    // Elimina una serie (DELETE /api/series/{id}) -- solo admin
    public function destroy($id)
    {
        $series = Series::find($id);

        if (!$series) {
            return response()->json(['error' => 'Serie non trovata'], 404);
        }

        $series->delete();

        return response()->json(['message' => 'Serie eliminata con successo']);
    }
}
