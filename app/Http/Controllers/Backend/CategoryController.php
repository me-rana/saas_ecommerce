<?php

namespace App\Http\Controllers\Backend;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use File;

class CategoryController extends Controller
{
    //
    public function index(){
        $perpage = 50;
        $categories = Category::paginate($perpage);
        return view('backend.category.index', compact('categories'))->with('i',(request()->input('page',1)-1)*$perpage);
    }
    public function create(){
        return view('backend.category.create');
    }
    public function store(Request $request):RedirectResponse{
        $validation = $request->validate([
            'name' => 'required',
            'image' => 'required|mimes:png,jpg,jpeg,webp,gif'
        ]);
        $image = null;

        $request_image = $request->file('image');
        if(!is_null($request_image)){
            $image_gen = hexdec(uniqid()) . '.' .$request_image->getClientOriginalExtension();
            $request_image->move(public_path('assets/images/category'), $image_gen);
            $image = 'assets/images/category/'.$image_gen;
        }
        $counter = Category::count();
        $is_sub = null;
        if(!is_null($request->parent_id)){
            $is_sub = Category::where('id',$request->parent_id)->first()->parent_id;
        }
        $category = Category::create([
            'name' => $request->name,
            'slug' => str_replace('_', ' ', strtolower($request->name))."-".$counter+1,
            'parent_id' => $request->parent_id ?? null,
            'master_id' => $is_sub,
            'image' => $image,
            'status' =>  $request->status,
        ]);
        if($category){
            noty()->info('Category created successfully.');
        }else{
            noty()->error("Category can't create!");
        }
        return redirect()->back();
    }

    public function edit($id){
        $category = Category::where('id',$id)->first();
        return view('backend.category.edit', compact('category'));
    }

    public function update(Request $request):RedirectResponse{
        $validation = $request->validate([
            'name' => 'required',
        ]);
        $category = Category::where('id',$request->hidden_id)->first();
        $is_sub = null;
        if(!is_null($request->parent_id)){
            $is_sub = Category::where('id',$request->parent_id)->first()->parent_id;
        }
        $request_image = $request->file('image');
        if(!is_null($request_image)){
            if(File::exists($category->image)){
                File::delete($category->image);
            }
            $image_gen = hexdec(uniqid()) . '.' .$request_image->getClientOriginalExtension();
            $request_image->move(public_path('assets/images/category'), $image_gen);
            $category->update([
                'image' => 'assets/images/category/'.$image_gen,
            ]);
        }

        $category->update([
            'name' => $request->name,
            'slug' => str_replace('_', ' ', strtolower($request->name))."-".$request->hidden_id,
            'parent_id' => $request->parent_id ?? null,
            'master_id' => $is_sub ?? null,
            'status' =>  $request->status,
        ]);
        if($category){
            noty()->info('Category updated successfully.');
        }else{
            noty()->error("Category can't update!");
        }
        return redirect()->back();
    }

    public function destroy($id){
        $category = Category::where('id',$id)->first();
        if(File::exists($category->image)){
            File::delete($category->image);
        }
        $category->delete();
        if($category){
            noty()->info('Category deleted successfully.');
        }else{
            noty()->error("Category can't delete!");
        }
        return redirect()->back();
    }

    public function status(Request $request){
        $category =  Category::where('id', $request->hidden_id)->first();
        $category->update([
            'status' => $request->status,
        ]);
        if($category){
            noty()->info('Category updated successfully.');
        }else{
            noty()->error("Category can't update!");
        }
        return redirect()->back();
    }
}
