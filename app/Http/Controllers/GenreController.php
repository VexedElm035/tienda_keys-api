<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class GenreController extends Controller
{
    public function index()
    {
        return response()->json(Genre::all(), 200);
    }

    public function show($id)
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json(['error' => 'Genero no encontrado'], 404);
        }
        return response()->json($genre, 200);
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255|unique:genres,name',
                'description' => 'nullable|string',
            ]);

            $genre = Genre::create($validatedData);

            return response()->json(['message' => 'Genero creado exitosamente', 'data' => $genre], 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Datos invalidos', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json(['error' => 'Genre not found'], 404);
        }

        try {
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255|unique:genres,name,' . $id,
                'description' => 'nullable|string',
            ]);

            $genre->update($validatedData);

            return response()->json(['message' => 'Genre updated successfully', 'data' => $genre], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => 'Invalid data', 'messages' => $e->errors()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        $genre = Genre::find($id);
        if (!$genre) {
            return response()->json(['error' => 'Genre not found'], 404);
        }

        $genre->delete();
        return response()->json(['message' => 'Genre deleted successfully'], 200);
    }
}
