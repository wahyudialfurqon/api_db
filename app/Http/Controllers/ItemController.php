<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png|max:5120', // Max 5MB
            'item_name' => 'required|string|max:255',
            'category' => 'required|in:otomotive,clothes,electronic,stationary,toys,sports,furniture',
            'item_description' => 'required|string',
            'uploaded_by' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        // Upload image to Cloudinary
        $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
            'folder' => 'uploads/items',
        ]);

        $imageUrl = $uploadedFile->getSecurePath(); // Get secure URL

        $item = Item::create([
            'image_path' => $imageUrl,
            'item_name' => $validatedData['item_name'],
            'category' => $validatedData['category'],
            'item_description' => $validatedData['item_description'],
            'uploaded_by' => $validatedData['uploaded_by'],
            'address' => $validatedData['address'],
            'phone_number' => $validatedData['phone_number'],
        ]);

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

        $validatedData = $request->validate([
            'image' => 'nullable|image|mimes:jpeg,jpg,png|max:5120', // Optional
            'item_name' => 'required|string|max:255',
            'category' => 'required|in:otomotive,clothes,electronic,stationary,toys,sports,furniture',
            'item_description' => 'required|string',
            'uploaded_by' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image from Cloudinary if exists
            if ($item->image_path) {
                $fileUrl = $item->image_path;
                $publicId = substr($fileUrl, strpos($fileUrl, 'uploads/items/'), strrpos($fileUrl, '.') - strpos($fileUrl, 'uploads/items/'));

                Cloudinary::destroy($publicId);
            }

            // Upload new image to Cloudinary
            $uploadedFile = Cloudinary::upload($request->file('image')->getRealPath(), [
                'folder' => 'uploads/items',
            ]);

            $validatedData['image_path'] = $uploadedFile->getSecurePath();
        } else {
            $validatedData['image_path'] = $item->image_path;
        }

        $item->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Item successfully updated',
            'data' => $item
        ], 200);
    }

    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        if ($item->image_path) {
            $fileUrl = $item->image_path;
            $publicId = substr($fileUrl, strpos($fileUrl, 'uploads/items/'), strrpos($fileUrl, '.') - strpos($fileUrl, 'uploads/items/'));

            Cloudinary::destroy($publicId);
        }

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item successfully deleted'
        ], 200);
    }
}
