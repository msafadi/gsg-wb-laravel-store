<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
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
        $rules = $this->rules();
        $request->validate($rules);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            $data['image'] = $this->upload($request->file('image'));
        }

        $product = Product::create($data);

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
    public function show($id)
    {
        $product = Product::findOrFail($id);
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
        $product = Product::findOrFail($id);
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
        $product = Product::findOrFail($id);

        $rules = $this->rules();
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
            $product->forceDelete();
        } else {
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

        $product->restore();

        return redirect()
            ->route('dashboard.products.index')
            ->with('success', "Product ($product->name) restored");
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|int|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|gt:price',
            'status' => 'in:active,draft,archived',
            'availability' => 'in:in-stock,out-of-stock,back-order',
            'quantity' => 'nullable|int|min:0',
            'sku' => 'nullable|string|unique:products,sku',
            'barcode' => 'nullable|string|unique:products,barcode',
            'image' => 'nullable|image',
        ];
    }

    protected function upload(UploadedFile $file)
    {
        return $file->store('thumbnails', [
            'disk' => 'public'
        ]);
    }    
}
