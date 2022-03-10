<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductsController extends Controller
{

    public function __construct()
    {
        //$this->middleware(['verified'])->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Gate::authorize('products.view');
        $this->authorize('view-any', Product::class);
        
        $products = Product::all();

        return view('dashboard.products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Gate::authorize('products.create');
        $this->authorize('create', Product::class);

        return view('dashboard.products.create', [
            'product' => new Product(),
            'availabilities' => Product::availabilities(),
            'status_options' => Product::statusOptions(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Gate::authorize('products.create');
        $this->authorize('create', Product::class);       
        
        $rules = $this->rules();
        $request->validate($rules);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $data['image'] = $this->upload($request->file('image'));
        }

        $product = Product::create($data);
        $this->uploadGallery($product, $request);

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', "Product ($product->name) created");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $this->authorize('view', $product);

        //$product = Product::findOrFail($id);
        return view('dashboard.products.show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //Gate::authorize('products.update');
        
        $product = Product::findOrFail($id);

        $this->authorize('update', $product);

        return view('dashboard.products.edit', [
            'product' => $product,
            'availabilities' => Product::availabilities(),
            'status_options' => Product::statusOptions(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //Gate::authorize('products.update');
        
        $product = Product::findOrFail($id);
        
        $this->authorize('update', $product);

        $rules = $this->rules($id);
        $request->validate($rules);

        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $this->upload($request->file('image'));
        }

        tap($product->image, function($old_image) use ($product, $data) {
            $product->update($data);

            if ($old_image && $old_image != $product->image) {
                Storage::disk('public')->delete($old_image);
            }
        });

        $this->uploadGallery($product, $request);

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', "Product ($product->name) updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::withTrashed()->findOrFail($id);

        if ($product->trashed()) {
            $this->authorize('force-delete', $product);
            $product->forceDelete();
        } else {
            $this->authorize('delete', $product);
            $product->delete();
        }

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', "Product ($product->name) deleted");
    }

    public function trash()
    {
        $products = Product::onlyTrashed()->get();
        return view('dashboard.products.trash', [
            'products' => $products,
        ]);
    }

    public function restore(Request $request, $id)
    {
        $product = Product::onlyTrashed()->findOrFail($id);

        $this->authorize('restore', $product);

        $product->restore();

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', "Product ($product->name) restored");
    }

    protected function rules($id = 0)
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|int|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price',
            'status' => 'in:active,draft,archived',
            'availability' => 'in:in-stock,out-of-stock,back-order',
            'quantity' => 'nullable|int|min:0',
            'sku' => "nullable|string|unique:products,sku,$id",
            'barcode' => "nullable|string|unique:products,barcode,$id",
            'image' => 'nullable|image',
            'delete_media' => 'array',
        ];
    }

    protected function upload(UploadedFile $file)
    {
        return $file->store('thumbnails', [
            'disk' => 'public'
        ]);
    }

    protected function uploadGallery(Product $product, Request $request)
    {
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $product->addMedia($file->path())
                        ->toMediaCollection('gallery');
            }
        }

        $delete = $request->post('delete_media');
        if ($delete) {
            Media::destroy($delete);
        }
    }
}
