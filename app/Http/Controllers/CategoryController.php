<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Session;
use App\Category;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		//echo "<pre>"; print_r($data); die;
    		$category = new Category;
    		$category->name = $data['category_name'];
    		$category->parent_id = $data['parent_id'];
    		$category->description = $data['description'];
    		$category->url = $data['url'];
    		$category->save();
    		return redirect('/admin/view-categories')->with('flash_message_success','Category Added Successfully!');
    	}

    	$levels = Category::where(['parent_id'=>0])->get();
    	return view('admin.categories.add_category')->with(compact('levels'));
    }

    public function editCategory(Request $request, $id = null){
    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		Category::where(['id'=>$id])->update(['name'=>$data['category_name'], 'parent_id'=>$data['parent_id'], 'description'=>$data['description'], 'url'=>$data['url']]);
    		return redirect('/admin/view-categories')->with('flash_message_success','Category Updated Successfully!');
    	}
    	$categoryDetails = Category::where(['id'=>$id])->first();
    	$levels = Category::where(['parent_id'=>0])->get();
    	return view('admin.categories.edit_category')->with(compact('categoryDetails','levels'));
    }

    public function deleteCategory(Request $request, $id = null){
    	if (!empty($id)) {
    		Category::where(['id'=>$id])->delete();
    		return redirect()->back()->with('flash_message_success','Category Deleted Successfully!');
    	}
    }

    public function viewCategories(){
    	$categories = Category::get();
    	//$categories = json_decode(json_encode($categories));
    	//echo "<pre>"; print_r($categories); die;
    	return view('admin.categories.view_categories')->with(compact('categories'));
    }
}
