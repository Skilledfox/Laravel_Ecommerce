<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;
use App\Category;

class IndexController extends Controller
{
    public function index(){

    	// In Ascending Order (By Default)
    	$productsAll = Product::get();

    	// In Descending Order
    	$productsAll = Product::orderBy('id', 'DESC')->get();

    	// In Random Order
    	$productsAll = Product::inRandomOrder()->where('status',1)->get();

    	// Get all Categories and Sub Categories
    	$categories = Category::with('categories')->where(['parent_id' => 0])->get();
    	/*$categories = json_decode(json_encode($categories));
    	echo "<pre>"; print_r($categories); die;*/

    	// Without Laravel Relationship
    	/*$categories_menu = "";
    	foreach ($categories as $cat) {
    		$categories_menu .= "<div class='panel-heading'>
									<h4 class='panel-title'>
										<a data-toggle='collapse' data-parent='#accordian' href='#".$cat->id."'>
											<span class='badge pull-right'><i class='fa fa-plus'></i></span>
											".$cat->name."
										</a>
									</h4>
								</div>
								<div id='".$cat->id."' class='panel-collapse collapse'>
									<div class='panel-body'>
										<ul>";
										$sub_categories = Category::where(['parent_id' => $cat->id ])->get();
							    		foreach ($sub_categories as $subcat) {
							    			$categories_menu .= "<li><a href='".$subcat->url."'>".$subcat->name." </a></li>";
							    		}
										$categories_menu .= "</ul>
									</div>
								</div>
								";
    	}*/
    	return view('index')->with(compact('productsAll', 'categories_menu', 'categories'));
    }
}
