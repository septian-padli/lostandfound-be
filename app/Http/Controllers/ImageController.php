<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Image;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreImageRequest;
use App\Http\Requests\UpdateImageRequest;

class ImageController extends Controller
{
    public function store(StoreImageRequest $request, string $idItem)
    {
        $item = Item::findOrFail($idItem);

        $images = $request->file('images');

        $savedImages = [];
        foreach ($images as $imageFile) {
            $path = $imageFile->store('items', 'public');
            $image = Image::create([
                'id_item' => $item->id,
                'url' => asset('storage/' . $path),
            ]);
            $savedImages[] = [
                'id' => $image->id,
                'url' => $image->url,
            ];
        }

        return response()->json(['data' => $savedImages], 200);
    }
}
