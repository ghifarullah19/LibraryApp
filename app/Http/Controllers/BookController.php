<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Eager Loading: Ambil data buku sekalian dengan data author dan publisher-nya
        $query = Book::with(['author', 'publisher']);

        // 1. Filter Pencarian Teks (Judul)
        if ($request->has('search') && $request->search != '') {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 2. Filter Dropdown: Penulis
        if ($request->has('author_id') && $request->author_id != '') {
            $query->where('author_id', $request->author_id);
        }

        // 3. Filter Dropdown: Penerbit
        if ($request->has('publisher_id') && $request->publisher_id != '') {
            $query->where('publisher_id', $request->publisher_id);
        }

        // 4. Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // 5. Pagination
        $limit = $request->get('limit', 10);
        $books = $query->paginate($limit);

        return response()->json([
            'success' => true,
            'message' => 'Daftar Buku berhasil diambil',
            'data' => $books
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Validasi ini memastikan ID yang diinput benar-benar ada di database!
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'publish_year' => 'required|integer|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $book = Book::create($request->all());

        return response()->json(['success' => true, 'message' => 'Buku berhasil ditambahkan', 'data' => $book], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $book = Book::with(['author', 'publisher'])->find($id);

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan'], 404);
        }

        return response()->json(['success' => true, 'message' => 'Detail Buku', 'data' => $book]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'author_id' => 'required|exists:authors,id',
            'publisher_id' => 'required|exists:publishers,id',
            'publish_year' => 'required|integer|digits:4'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $book->update($request->all());

        return response()->json(['success' => true, 'message' => 'Buku berhasil diupdate', 'data' => $book]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['success' => false, 'message' => 'Buku tidak ditemukan'], 404);
        }

        $book->delete();

        return response()->json(['success' => true, 'message' => 'Buku berhasil dihapus']);
    }
}
