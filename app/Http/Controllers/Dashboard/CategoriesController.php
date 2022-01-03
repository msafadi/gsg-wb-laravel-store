<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::all(); // Return a Collection
        // $categories = DB::table('categories')->get();
        
        $title = 'Categories';

        return view('dashboard.categories.index', compact('title', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('dashboard.categories.create', [
            'parents' => $categories,
        ]);
    }


    public function store(Request $request)
    {
        /*
        $category = new Category();
        $category->name = $request->post('name');
        $category->slug = Str::slug($category->name);
        $category->parent_id = $request->input('parent_id');
        $category->description = $request->description;
        $category->save();

        $category = new Category([
            'name' => $request->post('name'),
            'slug' => Str::slug($request->post('name')),
            'parent_id' => $request->post('parent_id'),
            'description' => $request->post('description'),
        ]);
        $category->save();
        
        $category = new Category();
        $category->forceFill([
            'name' => $request->post('name'),
            'slug' => Str::slug($request->post('name')),
            'parent_id' => $request->post('parent_id'),
            'description' => $request->post('description'),
        ])->save();
        */

        // Mass assignment
        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);
        $category = Category::create($request->all());

        // PRG: Post Redirect Get
        return redirect()->route('dashboard.categories.index');
    }

    public function edit($id)
    {
        //$category = Category::where('id', '=', $id)->firstOrFail();
        $category = Category::findOrFail($id);
        // if ($category == null) {
        //     return abort(404);
        // }

        $parents = Category::where('id', '<>', $id)
            ->orderBy('name')
            ->get();

        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, $id)
    {
        // Method 1
        // $category = Category::find($id);
        // $category->name = $request->post('name');
        // $category->parent_id = $request->post('parent_id');
        // $category->description = $request->post('description');
        // $category->save();
        
        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);
        
        // Method 2
        $category = Category::find($id);
        $category->update($request->all());
        // $category->forceFill($request->all)->save();

        // Method 3
        // Category::where('id', '=', $id)->update($request->except('_token', '_method'));

        // PRG
        return redirect()->route('dashboard.categories.index');
    }

    public function destroy($id)
    {
        // $category = Category::find($id);
        // $category->delete();

        // Category::where('id', '=', $id)->delete();
        
        Category::destroy($id);

        // PRG
        return redirect()->route('dashboard.categories.index');
    }
}
