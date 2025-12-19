<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      $items = Item::orderBy('item_name', 'asc')->get();
      return view('admin.item.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
    return view('admin.item.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          $request->validate([
        'item_name'   => 'required|string',
        'price'       => 'required|numeric',
        'category_id' => 'required',
        'image_path'  => 'nullable|image|mimes:jpg,png,jpeg',
    ]);

    if ($request->hasFile('image_path')) {
        $image = $request->file('image_path')->store('items', 'public');
    }

    Item::create([
        'item_name'   => $request->item_name,
        'description' => $request->description,
        'price'       => $request->price,
        'category_id' => $request->category_id,
        'image_path'  => $image ?? null,
        'is_active'   => $request->has('is_active') ? 1 : 0,
    ]);

    return redirect()->route('admin.item.index')
        ->with('success', 'Item berhasil ditambahkan');
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
            $item = Item::findOrFail($id);
            $categories = Category::all();

    return view('admin.item.edit', compact('item', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $item = Item::findOrFail($id);

    $request->validate([
        'item_name'   => 'required|string',
        'price'       => 'required|numeric',
        'category_id' => 'required',
        'image_path'  => 'nullable|image|mimes:jpg,png,jpeg',
    ]);

    // Jika upload gambar baru
  // Jika upload gambar baru
if ($request->hasFile('image_path')) {

    // Hapus gambar lama (jika ada)
    if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
        Storage::disk('public')->delete($item->image_path);
    }

    // Simpan gambar baru
    $image = $request->file('image_path')->store('items', 'public');
    $item->image_path = $image;
}


    // Update data lainnya
    $item->update([
        'item_name'   => $request->item_name,
        'description' => $request->description,
        'price'       => $request->price,
        'category_id' => $request->category_id,
        'is_active'   => $request->has('is_active') ? 1 : 0,
    ]);

    return redirect()->route('admin.item.index')
        ->with('success', 'Item berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          $item = Item::findOrFail($id);

    // Hapus gambar jika ada
    if ($item->image_path && Storage::disk('public')->exists($item->image_path)) {
        Storage::disk('public')->delete($item->image_path);
    }

    // Hapus data item
    $item->delete();

    return redirect()->route('admin.item.index')
        ->with('success', 'Item berhasil dihapus');
    }
}
