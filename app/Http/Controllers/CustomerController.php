<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Region;

class CustomerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verified']);
    }

    public function edit()
    {
        $data['customer'] = User::where('status', 1)->find(auth()->user()->id);
        $data['loadCountriesUrl'] = route('load-countries');
        $data['regions'] = Region::get();

        return view('profile', $data);
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required'],
            'last_name' => ['required'],
            'nic' => ['required'],
            'email' => ['required', 'email', 'unique:users,email,'.auth()->user()->id],
            'mobile' => ['required', 'regex:/^([0-9\+]*)$/', 'min:10', 'unique:users,mobile,'.auth()->user()->id],
            'address' => ['required'],
            'region_id' => ['required'],
            'country_id' => ['required'],
            'password' => ['confirmed'],
        ]);

        $data['customer'] = User::find(auth()->user()->id);

        $input = $request->only('name', 'last_name', 'nic', 'email', 'mobile', 'address', 'address_line1', 'address_line2', 'region_id', 'country_id');
        if($request->password) {
            $input['password'] = bcrypt($request->password);
        }
        $data['customer']->update($input);

        $data['loadCountriesUrl'] = route('load-countries');
        $data['regions'] = Region::get();

        return view('profile', $data);
    }
}

