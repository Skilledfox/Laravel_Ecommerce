<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth;

class UsersController extends Controller
{
    public function userLoginRegister(){
        return view('users.login_register');
    }

    public function register(Request $request){
    	if ($request->isMethod('post')) {
    		$data = $request->all();
    		/*echo "<pre>"; print_r($data); die;*/

    		// Check if User already exists
    		$usersCount = User::where('email', $data['email'])->count();
    		if ($usersCount>0) {
    			return redirect()->back()->with('flash_message_error', 'Email already exists!');
    		}else{
                // Save User Register data
    			$user = new User;
                $user->name = $data['name'];
                $user->email = $data['email'];
                $user->password = bcrypt($data['password']);
                $user->save();

                // Auth for new user registration and redirect to cart page
                if (Auth::attempt(['email'=>$data['email'], 'password'=>$data['password']])) {
                    return redirect('/cart');
                }
    		}
    	}
    }

    public function logout(){
        Auth::logout();
        return redirect('/');
    }

    public function checkEmail(Request $request){
        // Check if User already exists
        $data = $request->all();
        $usersCount = User::where('email', $data['email'])->count();
        if ($usersCount>0) {
            echo "false";
        }else{
            echo "true"; die;
        }
    }
}
