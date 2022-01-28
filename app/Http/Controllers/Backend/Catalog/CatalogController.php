<?php

namespace App\Http\Controllers\Backend\Catalog;

use Illuminate\Http\Request;
use App\Models\Data\Catalog;
use App\Http\Controllers\Controller;
use App\Models\Catalog as CatalogModel;
use App\Models\Repositories\CatalogRepository;

class CatalogController extends Controller
{
    


    public function list()
    {
        $view_data = [
            'page_title' => 'Catalog list',
        ];
        return view('backend.pages.catalog.listing', $view_data);
    }

    /**
     * Get catalog data and display to datatable
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function dataTable(Request $request, CatalogRepository $catalogRepository)
    {
        
        $catalogs = $catalogRepository->setDataTableLimit($request->length)
                                      ->setDataTableOffset($request->start)
                                      ->setDataTableOrder($request->columns[$request->order[0]['column']]['data'], $request->order[0]['dir'])
                                      ->setDataTableSearch($request->search['value'])
                                      ->setRequest($request)
                                      ->getDataTableData();
        
        return $catalogs;
    }

    /**
     * Display form
     * 
     * @return \Illuminate\View\View
     */
    public function create()
    {

        $catalog = new \stdClass();
        $catalog->_id = null;
        $catalog->name = null;
        $catalog->sku = null;
        $catalog->description = null;
        $catalog->price = null;
        $catalog->discount_price = null;

        $view_data = [
            'page_title' => 'Add new catalog',
            'catalog' => $catalog,
            'image_count' => 0
        ];
        return view('backend.pages.catalog.form', $view_data);
    }


    public function edit(Request $request, CatalogModel $model, string $id)
    {
        $request->session()->forget(self::IMAGE_DIR);
        $catalog = $model->findOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
        $image_count = count(explode(';', $catalog->image_urls));
        $view_data = [
            'page_title' => 'Update catalog',
            'catalog' => $catalog,
            'image_count' => $image_count
        ];
        return view('backend.pages.catalog.form', $view_data);
    }

    /**
     * Create catalog
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Repositories\CatalogRepository $catalogRepository
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function postCreate(Request $request, CatalogRepository $catalogRepository)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'sku' => 'required',
            'price' => 'required|numeric',
        ]);

        try{
            $catalog = $catalogRepository->save($this->extractData($request));
            $request->session()->forget(self::IMAGE_DIR);
            return response()->json('Catalog successfully saved!', 200);
        }catch(\App\Exceptions\CatalogImageRequiredException $e){
            return response()->json($e->getMessage(), 400);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json('Oops! Something went wrong! Please try again!', 400);
        }

    }

    /**
     * Delete catalog
     * 
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function postDelete(Request $request)
    {
        $request->validate([
            '_id' => 'required'
        ]);

        try{
            $catalog = new \App\Models\Catalog();
            $catalog->deleteOne([
                '_id' => new \MongoDB\BSON\ObjectId($request->_id)
            ]);
            return response()->json('Catalog has been deleted!', 200);
        }catch(\Exception $e){
            \Log::error($e->getMessage());
            return response()->json('Oops! Something went wrong! Please try again!', 400);
        }
    }

    /**
     * Activate/Deactivate catalog
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleActivate(Request $request)
    {
        try{
            $id = new \MongoDB\BSON\ObjectId($request->_id);
            $catalog = new CatalogModel();
            $result = $catalog->findOne(['_id' => $id]);
            $message = $result->status == CatalogModel::ACTIVE ? 'Catalog successfully deactivated!' : 'Catalog is now active';
            $error_message = $result->status == CatalogModel::ACTIVE ? 'Oops! Something went wrong while deactivating catalog. Please try again' : 'Oops! Something went wrong while activating catalog. Please try again';

            $catalog->updateOne([
                '_id' => $id
            ], [
                '$set' => [
                    'status' => $result->status == CatalogModel::ACTIVE ? CatalogModel::INACTIVE : CatalogModel::ACTIVE,
                    'updated_at' => now()->__toString(),
                ]
            ]);
            return response()->json($message, 200);
        }catch(\Exception $e){
            return response()->json($error_message, 400);
        }
    }

    private function extractData(Request $request)
    {
        $catalog = new Catalog();
        $catalog->setName($request->name);
        $catalog->setDescription($request->description);
        $catalog->setSku($request->sku);
        $catalog->setPrice($request->price);
        $catalog->setDiscountPrice($request->has('discount_price') ? $request->discount_price : 0);
        $catalog->setImageUrls($this->getUploadImages($request));
        $catalog->setStatus($request->has('status') && $request->status ? $request->status : \App\Models\Catalog::ACTIVE);

        if ($request->has('_id') && $request->_id) {

            $uploadedImages = $request->session()->get(self::IMAGE_DIR);
            $images = is_array($request->catalog_images) && count($request->catalog_images) > 0 ? array_filter($request->catalog_images) : [];
            if ($uploadedImages) {

                $images = array_unique(array_merge($uploadedImages, $images));
            }
            
            $catalog->setId($request->_id)
                    ->setImageUrls(implode(';', $images))
                    ->setOnUpdateGuardField([
                        'updated_at'
                    ]);
        }

        return $catalog;
    }


/**
     * Upload image
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function uploadImage(Request $request)
    {

        if ($request->hasFile('catalog_image')) {
            
            $thumbnail_path = str_replace('public', self::IMAGE_DIR, $request->file('catalog_image')->store('public/catalog'));

            
            if ($request->session()->has(self::IMAGE_DIR)) {
                
                $thumbnails = $request->session()->get(self::IMAGE_DIR);
                $thumbnails[] = $thumbnail_path;

            } else {
                $thumbnails[] = $thumbnail_path;
            }

            $request->session()->put(self::IMAGE_DIR, $thumbnails);

            return response()->json('Image successfully uploaded');

        }

        return response()->json('Unable to save image on the server. Please try again', 422);
        
    }

    /**
     * Delete image when admin click on delete button
     * to an image in catalog form
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\JsonResponse
     * 
     * @todo Clean up deleted image from session in server
     */
    public function ajaxDeleteImage(Request $request)
    {
        $images = $request->session()->get(self::IMAGE_DIR);
        unset($images[$request->image_index - 1]);
        $images = array_values($images);
        $request->session()->put(self::IMAGE_DIR, $images);

        return response()->json('Image successfully deleted', 200);
    }


    /**
     * Get uploaded image from session here.
     * 
     * @todo Implement dropzone here
     *
     * @return string
     */
    private function getUploadImages(Request $request)
    {

        if ($request->has('_id') && $request->_id) {
            return null;
        }
        // get image from session upload using dropzone
        $uploadedImages = $request->session()->get(self::IMAGE_DIR);

        if ($uploadedImages) {
            return implode(';', $uploadedImages);
        }


        throw new \App\Exceptions\CatalogImageRequiredException();
    }

}
