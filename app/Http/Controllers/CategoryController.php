<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::all();
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    return view('admin.category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
        'category_name'   => 'required|string',
        'description'       => 'required|string',
    ]);

    Category::create([
        'category_name'   => $request->category_name,
        'description' => $request->description,
    ]);

    return redirect()->route('admin.category.index')
        ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
          $category = Category::findOrFail($id);
            // $categories = Category::all();

    return view('admin.category.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       $category = Category::findOrFail($id);

    $request->validate([
        'category_name'   => 'required|string',
        'description'       => 'required|string',
    ]);


    // Update data lainnya
    $category->update([
        'category_name'   => $request->category_name,
        'description' => $request->description
    ]);

    return redirect()->route('admin.category.index')
        ->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
            $category = Category::findOrFail($id);


    // Hapus data item
    $category->delete();

    return redirect()->route('admin.category.index')
        ->with('success', 'kategori berhasil dihapus');
    }
}
