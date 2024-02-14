<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function adduser(Request $request)
    {
      

        $google2fa = app('pragmarx.google2fa');
        $registration_data=[];
        session()->put('registration_data',$registration_data);
        $registration_data = $request->all();
  
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();
  
        $request->session()->flash('registration_data', $registration_data);
        session()->put('registration_data',$registration_data);

        User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'password'=>Hash::make('12345678'),
            'google2fa_secret'=>$registration_data["google2fa_secret"] 
        ]);
  
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );

        return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
    }

    public function verifyuser(Request $request)
    {
        $data=session()->get('registration_data');

        $validated = $request->validate([
            'one_time_password' => 'required',
        ]);

        $user = User::where('email',$data['email'])->first();
        $google2fa = app('pragmarx.google2fa');
        dd($valid = $google2fa->verifyKey($user->google2fa_secret, $request->input('one_time_password')));
        return request();
    }
}
