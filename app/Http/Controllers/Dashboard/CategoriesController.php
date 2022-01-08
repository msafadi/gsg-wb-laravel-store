<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;

class CategoriesController extends Controller
{
    public function index()
    {
        // $categories = DB::table('categories')->get();
        $categories = Category::leftJoin('categories as parents', 'parents.id', '=', 'categories.parent_id')
            ->select([
                'categories.*',
                'parents.name as parent_name'
            ])
            // ->whereNull('categories.parent_id') // parent_id IS NULL
            ->orderBy('name')
            ->get(); // Return a Collection
        
        $title = 'Categories';

        return view('dashboard.categories.index', compact('title', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('dashboard.categories.create', [
            'parents' => $categories,
            'category' => new Category(),
        ]);
    }


    public function store(Request $request)
    {
        $rules = $this->rules();
        
        /*$validator = Validator::make($request->all(), $rules);
        // $validator->validate();
        if ($validator->fails()) {
            // dd ( $validator->errors() );
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }*/

        // Throw ValidationException
        // $this->validate($request, $rules);
        $request->validate($rules, [
            'name.required' => ':attribute required!!',
        ]);

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

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $data['image'] = $this->upload($file);
        }

        $data['slug'] = Str::slug($data['name']);

        $category = Category::create($data);

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

    public function update(CategoryRequest $request, $id)
    {
        // $rules = $this->rules($id);
        // $request->validate($rules);

        // Method 1
        // $category = Category::find($id);
        // $category->name = $request->post('name');
        // $category->parent_id = $request->post('parent_id');
        // $category->description = $request->post('description');
        // $category->save();

        // Method 2
        $category = Category::find($id);
        
        $data = $request->except('image');
        $data['slug'] = Str::slug($data['name']);

        $old_image = $category->image;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $data['image'] = $this->upload($file);
        }
        
        $category->update($data);
        
        if ($old_image && $old_image != $category->image) {
            Storage::disk('public')->delete($old_image);
        }

        // $category->forceFill($request->all)->save();

        // Method 3
        // Category::where('id', '=', $id)->update($request->except('_token', '_method'));

        // PRG
        return redirect()->route('dashboard.categories.index');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        // Category::where('id', '=', $id)->delete();
        
        // Category::destroy($id);

        // PRG
        return redirect()->route('dashboard.categories.index');
    }

    protected function rules($id = 0)
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                //'unique:categories,name,' . $id,
                Rule::unique('categories', 'name')->ignore($id, 'id'),
                //(new Unique('categories', 'name'))->ignore($id)
            ],
            'parent_id' => 'nullable|int|exists:categories,id',
            'description' => 'nullable|string|min:5',
            'image' => 'required|image', //'|max:50|dimensions:min_width=150,min_height=150,max_width=300,max_height=300', // 50KB
        ];
    }

    protected function upload(UploadedFile $file)
    {
        if ($file->isValid()) {
            return $file->store('thumbnails', [
                'disk' => 'public',
            ]);
        } else {
            throw ValidationException::withMessages([
                'image' => 'File corrupted!',
            ]);
        }
    }
}
