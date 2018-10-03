<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class IndexController extends Controller
{
    public function index(){

    	// In Ascending Order (By Default)
    	$productsAll = Product::get();

    	// In Descending Order
    	$productsAll = Product::orderBy('id', 'DESC')->get();

    	// In Random Order
    	$productsAll = Product::inRandomOrder()->get();

    	return view('index')->with(compact('productsAll'));
    }
}
