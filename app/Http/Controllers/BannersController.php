<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Banner;
use Image;

class BannersController extends Controller
{
    public function addBanner(Request $request){
    	if ($request->isMethod('post')) {
    		$data =$request->all();
    		/*echo "<pre>"; print_r($data); die;*/

    		$banner = new Banner;
                       

            // Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Change Image file name before uploading
                    $filename = time(rand(111,99999)).'.'.$extension;
                    $banner_path = 'images/frontend_images/banners/'.$filename;
                    // Resize Image
                    Image::make($image_tmp)->resize(1140,441)->save($banner_path);

                    // Store Image name in products table
                    $banner->image = $filename;
                }
            }
            $banner->title = $data['title'];
            $banner->link = $data['link'];

            if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }

            $banner->status = $status;
            $banner->save();
            return redirect()->back()->with('flash_message_success', 'Banner has been added succesfully!');
    	}

    	return view('admin.banners.add_banner');
    }

    public function editBanner(Request $request, $id=null){
    	if($request->isMethod('post')){
    		$data = $request->all();
    		/*echo "<pre>"; print_r($data); die;*/

    		if (empty($data['status'])) {
                $status = 0;
            }else{
                $status = 1;
            }

            if (empty($data['title'])) {
            	$data['title'] = '';
            }

            if (empty($data['link'])) {
            	$data['link'] = '';
            }
    		// Upload Image
            if ($request->hasFile('image')) {
                $image_tmp = Input::file('image');
                if ($image_tmp->isValid()) {
                    $extension = $image_tmp->getClientOriginalExtension();
                    // Change Image file name before uploading
                    $filename = time(rand(111,99999)).'.'.$extension;
                    $banner_path = 'images/frontend_images/banners/'.$filename;
                    // Resize Image
                    Image::make($image_tmp)->resize(1140,441)->save($banner_path);

                    /*// Store Image name in products table
                    $banner->image = $filename;
*/                }
            }else if (!empty($data['current_image'])) {
            	$filename = $data['current_image'];
            }else{
            	$filename = '';
            }

            Banner::where('id',$id)->update(['status'=>$status, 'title'=>$data['title'], 'link'=>$data['link'], 'image'=>$filename]);
            return redirect()->back()->with('flash_message_success', 'Banner has been edited succesfully!');

    	}
    	$bannerDetails = Banner::where('id',$id)->first();
    	return view('admin.banners.edit_banner')->with(compact('bannerDetails'));
    }

    public function viewBanners(){
    	$banners = Banner::get();
    	return view('admin.banners.view_banners')->with(compact('banners'));
    }

    public function deleteBanner($id = null){
        Banner::where(['id'=>$id])->delete();
        return redirect()->back()->with('flash_message_success', 'Banner has been deleted succesfully!');
    }
}
