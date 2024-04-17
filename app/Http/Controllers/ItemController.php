<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    public function index(Request $request)
    {

        DB::beginTransaction();

        try {

            $items = Item::with('category')
                ->sortingQuery()
                ->searchQuery()
                ->paginationQuery();

            DB::commit();

            return $this->success('items retrived successfully', $items);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function store(ItemStoreRequest $request)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        if ($request->hasFile('image')) {
            $path = Storage::putFile('public', $request->file('image'));
            $image_url = url('api/image/');
            $payload['image'] = $image_url.'/'.$path;
        }

        try {

            $product = Item::create($payload->toArray());
            DB::commit();

            return $this->success('product created successfully', $product);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function show($id)
    {
        DB::beginTransaction();

        try {

            $product = Item::with('category')->findOrFail($id);
            DB::commit();

            return $this->success('product retrived successfully by id', $product);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function update(ItemUpdateRequest $request, $id)
    {
        DB::beginTransaction();

        $payload = collect($request->validated());

        try {

            $product = Item::findOrFail($id);

            if ($request->hasFile('image')) {
                $path = Storage::putFile('public', $request->file('image'));
                $image_url = url('api/image/');
                $payload['image'] = $image_url.'/'.$path;

                /**
                 * remove old image
                 */
                $old_image_url = $product->image;
                $parsedUrl = parse_url($product->image);
                $old_image_path = substr($parsedUrl['path'], 11);

                if (Storage::exists($old_image_path)) {
                    $delete = Storage::delete($old_image_path);
                }

            }

            $product->update($payload->toArray());

            DB::commit();

            return $this->success('product updated successfully by id', $product);

        } catch (Exception $e) {
            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function destroy($id)
    {
        DB::beginTransaction();
        try {

            $product = Item::findOrFail($id);
            $product->delete($id);

            /****
             *
             * delete public image
             */
            // $old_image_url = $product->image;
            // $parsedUrl = parse_url($product->image);
            // $old_image_path = substr($parsedUrl['path'], 11);
            // Storage::delete($old_image_path);

            DB::commit();

            return $this->success('product deleted successfully by id', []);

        } catch (Exception $e) {

            DB::rollback();

            return $this->internalServerError();
        }
    }

    public function getImage($path)
    {
        $image = Storage::get($path);

        if ($image) {

            return response($image, 200)->header('Content-Type', Storage::mimeType($path));

        } else {

            return $this->notFound('Image Resource Not Found', []);

        }
    }
}
