<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Auth;
use Session;
use Image;
use App\Category;
use App\Product;
use App\ProductsAttribute;

class ProductsController extends Controller
{
    public function addProduct(Request $request){
        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;
            if (empty($data['category_id'])) {
                return redirect()->back()->with('flash_message_error', '"Under Category" is Missing!');
            }
            $product = new Product;
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            if (!empty($data['description'])) {
                $product->description = $data['description'];
            }else{
                $product->description = '';
            }
            $product->price = $data['price'];
            // $product->image = '';

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Change Image file name before uploading
                    $filename = time(rand(111,99999)).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                    // Store Image name in products table
                    $product->image = $filename;
                }
            }

            $product->save();
            // return redirect()->back()->with('flash_message_success', 'Product has been added succesfully!');
            return redirect('/admin/view-products')->with('flash_message_success', 'Product has been added succesfully!');
        }

        // Categories Dropdown Start
    	$categories = Category::where(['parent_id'=>0])->get();
    	$categories_dropdown = "<option value='' selected disabled>Select</option>";
    	foreach ($categories as $cat) {
    		$categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>";
    		$sub_categories = Category::where(['parent_id'=>$cat->id])->get();
    		foreach ($sub_categories as $sub_cat) {
    			$categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp; --&nbsp;".$sub_cat->name."</option>";
    		}
    	}
        // Categories Dropdown End

    	return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    public function editProduct(Request $request, $id = null){

        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Change Image file name before uploading
                    $filename = time(rand(111,99999)).'.'.$extension;
                    $large_image_path = 'images/backend_images/products/large/'.$filename;
                    $medium_image_path = 'images/backend_images/products/medium/'.$filename;
                    $small_image_path = 'images/backend_images/products/small/'.$filename;
                    // Resize Images
                    Image::make($image_tmp)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                }
            }else{
                $filename = $data['current_image'];
            }

            if (empty($data['description'])) {
                $data['description'] = '';
            }


            Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'], 'product_name'=>$data['product_name'], 'product_code'=>$data['product_code'], 'product_color'=>$data['product_color'], 'description'=>$data['description'], 'price'=>$data['price'], 'image'=>$filename]);
            return redirect()->back()->with('flash_message_success', 'Product has been updated succesfully!');
        }

        // Get Product Details
        $productDetails = Product::Where(['id'=>$id])->first();

        // Categories Dropdown Start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach ($categories as $cat) {
            if ($cat->id==$productDetails->category_id) {
                $selected = "selected";
            }else{
                $selected = "";
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>";
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach ($sub_categories as $sub_cat) {
                if ($sub_cat->id==$productDetails->category_id) {
                $selected = "selected";
            }else{
                $selected = "";
            }
                $categories_dropdown .= "<option value = '".$sub_cat->id."' ".$selected.">&nbsp; --&nbsp;".$sub_cat->name."</option>";
            }
        }
        // Categories Dropdown End

        return view('admin.products.edit_product')->with(compact('productDetails', 'categories_dropdown'));
    }

    public function viewProducts(){
        $products = Product::get();
        // $products = json_decode(json_encode($products));
        foreach ($products as $key => $val) {
            $category_name = Category::where(['id'=>$val->category_id])->first();
            $products[$key]->category_name = $category_name->name;
        }
        
        // echo "<pre>"; print_r($products); die;
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function deleteProduct($id = null){
        Product::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Product has been deleted succesfully!');
    }

    public function deleteProductImage($id = null){

        // Get Product Image Name
        $productImage = Product::where(['id'=>$id])->first();
        // echo $productImage->image; die;

        // Get Product Image Paths
        $large_image_path = 'images/backend_images/products/large/';
        $medium_image_path = 'images/backend_images/products/medium/';
        $small_image_path = 'images/backend_images/products/small/';

        // Delete Large Image if  not exists in Folder
        if(file_exists($large_image_path.$productImage->image)){
            unlink($large_image_path.$productImage->image);
        }

        // Delete Medium Image if  not exists in Folder
        if(file_exists($medium_image_path.$productImage->image)){
            unlink($medium_image_path.$productImage->image);
        }

        // Delete Small Image if  not exists in Folder
        if(file_exists($small_image_path.$productImage->image)){
            unlink($small_image_path.$productImage->image);
        }

        //Delete Image from Product table
        Product::where(['id' => $id])->update(['image' => '']);

        return redirect()->back()->with('flash_message_success', 'Product Image has been deleted succesfully!');
    }

    public function addAttributes(Request $request, $id = null){
        $productDetails = Product::with('attributes')->where(['id'=>$id])->first();
        // $productDetails = json_decode(json_encode($productDetails));
        // echo "<pre>"; print_r($productDetails); die;


        if ($request->isMethod('post')) {
            $data = $request->all();
            // echo "<pre>"; print_r($data); die;

            foreach ($data['sku'] as $key => $val) {
                if (!empty($val)) {
                    $attribute = new ProductsAttribute;
                    $attribute->product_id =$id;
                    $attribute->sku = $val;
                    $attribute->size = $data['size'][$key];
                    $attribute->price = $data['price'][$key];
                    $attribute->stock = $data['stock'][$key];
                    $attribute->save();
                }
            }

            return redirect('/admin/add-attributes/'.$id)->with('flash_message_success', 'Product Attributes Has Been Added Successfully!');
        }

        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }

    public function deleteAttribute($id = null){
        ProductsAttribute::where(['id' => $id])->delete();
        return redirect()->back()->with('flash_message_success', 'Attribute has been deleted succesfully!');
    }

    public function products($url = null){

        // Show 404 page if Category URL does not exist
        $countCategory = Category::where(['url' => $url, 'status'=>1])->count();
        // echo $countCategory; die;
        if ($countCategory==0) {
            abort(404);
        }

        // echo $url; die;
        // Get all Categories and Sub Categories
        $categories = Category::with('categories')->where(['parent_id' => 0])->get();

        $categoryDetails = Category::where(['url' => $url])->first();

        if ($categoryDetails->parent_id==0) {
            // if url is main category url
            $subCategories = Category::where(['parent_id' => $categoryDetails->id])->get();
            foreach ($subCategories as $key => $subcat) {
                $cat_ids[] = $subcat->id;
            }
            // echo $cat_ids; die;
            $productsAll = Product::whereIn('category_id', $cat_ids)->get();
            /*$productsAll = json_decode(json_encode($productsAll));
            echo "<pre>"; print_r($productsAll); die;*/
        }else{
            // if url is sub category url
            $productsAll = Product::where(['category_id' => $categoryDetails->id])->get();
        }

        return view('products.listing')->with(compact('categories', 'categoryDetails', 'productsAll'));
    }

    public function product($id = null){

        // Get Product Details
        $productDetails = Product::where('id',$id)->first();

        // Get all Categories and Sub categories
        $categories = Category::with('categories')->where(['parent_id'=>0])->get();

        return view('products.detail')->with(compact('productDetails', 'categories'));
    }
}
