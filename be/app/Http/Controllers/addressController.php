<?php

namespace App\Http\Controllers;

use App\Models\Address;

class AddressController extends Controller
{
    public function showAddresses()
    {
        $addresses = Address::all();

        return view('addresses.index', compact('addresses'));
    }
}
