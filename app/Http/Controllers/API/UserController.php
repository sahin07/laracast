<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Image;

class UserController extends Controller
{

    public function __construct()
    {
       // $this->middleware('auth:api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate(10);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = new User();
        $user->name= $request->name;
        $user->email= $request->email;
        $user->bio= $request->bio;
        $user->password= Hash::make($request->password);
        $user->type= $request->type;
        $insetId= $user->save();



    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user=User::findOrFail($id);
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['sometimes', 'string', 'min:8'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->type = $request->type;
        $user->save();

        return ['message'=>'Successfuly Updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('isAdmin');
        $user->delete();

        return ['message'=>'User Successfully Deleted'];
    }

    public function profile(){
       return User::findOrFail(1);

    }

    public function updateprofile(Request $request){

        $user=User::findOrFail(1);
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'password' => ['sometimes', 'string', 'min:8'],
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $currentImage=$user->photo;

        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        $user->type = $request->type;

        if($file=$request->file('photo')){
            if($file->getClientOriginalName() != $currentImage ){
                $new_name = time().'-'.$file->getClientOriginalName();
                Image::make($file->path())->save(public_path('images/profile/').$new_name);
                $user->photo = $new_name;

                $oldPhoto=public_path('images/profile/').$currentImage;
                if(file_exists($oldPhoto)){
                    unlink($oldPhoto);
                }
            }
        }

        $user->save();
    }
}
