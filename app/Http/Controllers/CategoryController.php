<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Mostra tutte le categorie
    public function index()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Devi essere autenticato!'], 401);
        }

        if ($user->hasRole('admin')) {
            $categories = \App\Models\Category::withTrashed()->get()->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'deleted_at' => $category->deleted_at,
                ];
            });
            return response()->json($categories);
        }

        $categories = \App\Models\Category::all()->map(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
            ];
        });
        return response()->json($categories);
    }


    // Mostra una categoria
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Categoria non trovata'], 404);
        }

        return response()->json($category);
    }

    // Crea una nuova categoria (solo admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // aggiungi altri campi se necessario
        ]);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Categoria creata con successo',
            'category' => $category
        ], 201);
    }

    // Aggiorna una categoria (solo admin)
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Categoria non trovata'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            // altri campi
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Categoria aggiornata con successo',
            'category' => $category
        ]);
    }

    // Elimina una categoria (solo admin)
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['error' => 'Categoria non trovata'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Categoria eliminata con successo']);
    }
}
