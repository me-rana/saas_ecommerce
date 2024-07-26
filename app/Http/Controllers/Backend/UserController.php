<?php

namespace App\Http\Controllers\Backend;

use File;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    //
    public function manage(){
        $users = User::all();
        return view('backend.user.index',compact('users'));
    }

    public function create(){
        $roles = Role::all();
        return view('backend.user.create',compact('roles'));
    }

    public function store(Request $request):RedirectResponse{
        $validation = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'phone' => 'required',
        ]);
        $image = $request->file('image');
        $profile_photo_path = null; 
        if (!is_null($image)){
            $name_gen = hexdec(uniqid()) . '.' .$image->getClientOriginalExtension();
            $image->move(public_path('assets/images/user'), $name_gen);
            $profile_photo_path = 'assets/images/user/'.$name_gen;
        }
        

        $user = User::create([
            'name' => $request->name,
            'email' =>  $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'profile_photo_path' => $profile_photo_path
        ]);
        $role = Role::where('id',$request->role)->first();
        $user->assignRole($role);

        if($user){
            noty()->info('User created successfully.');
        }else{
            noty()->error("User can't created!");
        }
        return redirect()->back();

    }

    public function edit($id){
        $roles = Role::all();
        $user = User::where('id', $id)->first();
        return view('backend.user.edit',compact('user','roles'));
    }

    public function update(Request $request){
        $image = $request->file('image');
        $user = User::where('id',$request->hidden_id)->first();

        if (!is_null($image)){
            if(File::exists($user->profile_photo_path)){
                File::delete($user->profile_photo_path);
            }
            $name_gen = hexdec(uniqid()) . '.' .$image->getClientOriginalExtension();
            $image->move(public_path('assets/images/user'), $name_gen);
            $user->update([
                'profile_photo_path' => 'assets/images/user/'.$name_gen
            ]);
        }

        if($request->password != null){
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' =>  $request->email,
        ]);

        if($user){
            noty()->info('User update successfully.');
        }else{
            noty()->error("User can't updated!");
        }
        return redirect()->back();
    }

    public function destroy($id){
        $user = User::where('id', $id)->first();
        if(File::exists($user->profile_photo_path)){
            File::delete($user->profile_photo_path);
        }
        $user->delete();

        if($user){
            noty()->info('User deleted successfully.');
        }else{
            noty()->error("User can't deleted!");
        }
        return redirect()->back();
    }
}
