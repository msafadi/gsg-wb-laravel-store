<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Scopes\MainCategoryScope;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;

class CategoriesController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:categories.view')->only('index');
    }
    
    public function index(Request $request)
    {
        $search = $request->query('search'); // ?search=watch

        // $categories = DB::table('categories')->whereNull('deleted_at')->get();
        $categories = Category::withoutGlobalScope(MainCategoryScope::class)
            ->search($search)
            //->parent(6)
            ->orderBy('name')
            ->get(); // Return a Collection
        
        $title = 'Categories';

        return view('dashboard.categories.index', compact('title', 'categories'));
    }

    public function trash()
    {
        $categories = Category::onlyTrashed()->latest('deleted_at')->get();
        return view('dashboard.categories.trash', compact('categories'));
    }

    public function create()
    {

        if (!Gate::allows('categories.create')) {
            abort(403); // Foribden
        }

        $categories = Category::orderBy('name')->get();

        return view('dashboard.categories.create', [
            'parents' => $categories,
            'category' => new Category(),
        ]);
    }


    public function store(Request $request)
    {
        if (Gate::denies('categories.create')) {
            abort(403);
        }

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

        $category = Category::create($data);

        // PRG: Post Redirect Get
        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category ($category->name) created");
    }

    public function edit($id)
    {
        Gate::authorize('categories.update');

        // $category = Category::where('id', '=', $id)->dd();
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
        Gate::authorize('categories.update');

        // $rules = $this->rules($id);
        // $request->validate($rules);

        // Method 1
        // $category = Category::find($id);
        // $category->name = $request->post('name');
        // $category->parent_id = $request->post('parent_id');
        // $category->description = $request->post('description');
        // $category->save();

        // Method 2
        $category = Category::findOrFail($id);
        
        $data = $request->except('image');
        
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
        session()->put('message', "Category ($category->name) updated");

        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category ($category->name) updated");
    }

    public function destroy($id)
    {
        if (!Gate::check('categories.delete')) {
            abort(403, __('You are not allowed to process action.'));
        }

        $category = Category::withTrashed()->findOrFail($id);
        if ($category->trashed()) {
            $category->forceDelete();
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
        } else {
            $category->delete();
        }

        // Category::where('id', '=', $id)->delete();
        
        // Category::destroy($id);

        // PRG
        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category ($category->name) deleted");
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

    public function restore(Request $request, $id)
    {
        $category = Category::onlyTrashed()->findOrFail($id);
        $category->restore();

        // PRG
        return redirect()
            ->route('dashboard.categories.index')
            ->with('success', "Category ($category->name) restored");
    }
}
