<?php

namespace App\Http\Controllers;

use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Publisher::query();

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
        $publishers = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Publisher berhasil diambil',
            'data' => $publishers
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $publisher = Publisher::create($request->all());

        return response()->json(['success' => true, 'message' => 'Publisher berhasil ditambahkan', 'data' => $publisher], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['success' => false, 'message' => 'Publisher tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Detail Publisher', 'data' => $publisher]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['success' => false, 'message' => 'Publisher tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $publisher->update($request->all());

        return response()->json(['success' => true, 'message' => 'Publisher berhasil diupdate', 'data' => $publisher]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $publisher = Publisher::find($id);

        if (!$publisher) {
            return response()->json(['success' => false, 'message' => 'Publisher tidak ditemukan'], 404);
        }

        $publisher->delete();

        return response()->json(['success' => true, 'message' => 'Publisher berhasil dihapus']);
    }
}
