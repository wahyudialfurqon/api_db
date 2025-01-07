<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $items = Item::all();

        return response()->json([
            'success' => true,
            'message' => 'Items retrieved successfully',
            'data' => $items
        ], 200);
    }

    public function show($id)
    {
        $item = Item::find($id);

        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Item details retrieved successfully',
            'data' => $item
        ], 200);
    }

    public function store(Request $request)
{
    // Validasi data
    $validatedData = $request->validate([
        'image' => 'required|image|mimes:jpeg,jpg,png|max:5120', // Max 5MB
        'item_name' => 'required|string|max:255',
        'category' => 'required|in:otomotive,clothes,electronic,stationary,toys,sports,furniture',
        'item_description' => 'required|string',
        'uploaded_by' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15',
    ]);

    // Upload gambar ke Cloudinary
    $cloudinaryImage = $request->file('image')->storeOnCloudinary('items');
    $url = $cloudinaryImage->getSecurePath(); // URL aman dari Cloudinary
    $public_id = $cloudinaryImage->getPublicId(); // ID publik untuk penghapusan gambar

    // Simpan data ke database
    $item = Item::create([
        'image_url' => $url, // URL gambar dari Cloudinary
        'image_public_id' => $public_id, // ID publik untuk referensi
        'item_name' => $validatedData['item_name'],
        'category' => $validatedData['category'],
        'item_description' => $validatedData['item_description'],
        'uploaded_by' => $validatedData['uploaded_by'],
        'address' => $validatedData['address'],
        'phone_number' => $validatedData['phone_number'],
    ]);

    // Kirimkan respon berdasarkan hasil
    if ($item) {
        return response()->json([
            'success' => true,
            'message' => 'Item successfully uploaded',
            'data' => $item
        ], 201);
    }

    return response()->json([
        'success' => false,
        'message' => 'Failed to upload item'
    ], 500);
}

  public function update(Request $request, $id)
{
    $item = Item::findOrFail($id);

    // Validasi data
    $validatedData = $request->validate([
        'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // Optional
        'item_name' => 'required|string|max:255',
        'category' => 'required|in:otomotive,clothes,electronic,stationary,toys,sports,furniture',
        'item_description' => 'required|string',
        'uploaded_by' => 'required|string|max:255',
        'address' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15',
    ]);

    // Cek apakah ada gambar baru yang diunggah
    if ($request->hasFile('image')) {
        // Hapus gambar lama dari Cloudinary jika ada
        if ($item->image_public_id) {
            Cloudinary::destroy($item->image_public_id);
        }

        // Upload gambar baru ke Cloudinary
        $cloudinaryImage = $request->file('image')->storeOnCloudinary('items');
        $url = $cloudinaryImage->getSecurePath(); // URL gambar aman dari Cloudinary
        $public_id = $cloudinaryImage->getPublicId(); // ID publik gambar

        // Update data gambar
        $validatedData['image_url'] = $url;
        $validatedData['image_public_id'] = $public_id;
    } else {
        // Jika tidak ada gambar baru, simpan gambar yang sudah ada
        $validatedData['image_url'] = $item->image_url;
        $validatedData['image_public_id'] = $item->image_public_id;
    }

    // Update data item lainnya
    $item->update($validatedData);

    // Kirimkan respon sukses
    return response()->json([
        'success' => true,
        'message' => 'Item successfully updated',
        'data' => $item
    ], 200);
}


    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        // Menghapus gambar menggunakan Cloudinary
        if ($item->image_public_id) {
            Cloudinary::destroy($item->image_public_id);
        }

        // Menghapus item dari database
        $item->delete();

        // Mengembalikan respon JSON
        return response()->json([
            'success' => true,
            'message' => 'Item successfully deleted'
        ], 200);
    }
}
