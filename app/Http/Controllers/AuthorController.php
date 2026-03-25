<?php

namespace App\Http\Controllers;

use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Author::query();

        // 1. Filtering: Cari berdasarkan nama
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // 2. Sorting: Urutkan data
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // 3. Pagination: Batasi jumlah data per halaman
        $limit = $request->get('limit', 10); // Default 10 data
        $authors = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Author berhasil diambil',
            'data' => $authors
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $author = Author::create($request->all());

        return response()->json(['success' => true, 'message' => 'Author berhasil ditambahkan', 'data' => $author], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['success' => false, 'message' => 'Author tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Detail Author', 'data' => $author]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['success' => false, 'message' => 'Author tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $author->update($request->all());

        return response()->json(['success' => true, 'message' => 'Author berhasil diupdate', 'data' => $author]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $author = Author::find($id);

        if (!$author) {
            return response()->json(['success' => false, 'message' => 'Author tidak ditemukan'], 404);
        }

        $author->delete();

        return response()->json(['success' => true, 'message' => 'Author berhasil dihapus']);
    }
}
