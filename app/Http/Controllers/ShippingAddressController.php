<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ShippingAddress;

class ShippingAddressController extends Controller
{
    public function store(Request $request){
        $request->validate([

            'user_id'=> 'required',
            'address' => 'required',
            'zone_id' => 'required',
            
        ]);

        ShippingAddress::create([
            'user_id' => auth()->id(),
            'address' => $request->address,
            'zone_id' => $request->zone_id,
            
        ]);

        return redirect()->back()->with('success', 'Dirección agregada correctamente.');
    }

    public function storeAddress(Request $request){
        $request->validate([

            'address' => 'required',
            'zone_id' => 'required',
            
        ]);

        ShippingAddress::create([

            'user_id' => auth()->id(),
            'address' => $request->address,
            'zone_id' => $request->zone_id,
        ]);

        return redirect()->back()->with('success', 'Dirección guardada correctamente.');
    }

    public function updateAddress(Request $request, $id){
        $address = ShippingAddress::where('user_id', auth()->id())->findOrFail($id);

        $request->validate([

            'address' => 'required',
            'zone_id' => 'required',
        ]);

        $address->update($request->all());

        return redirect()->back()->with('success', 'Dirección actualizada.');
    }

    public function destroyAddress($id){
        $address = ShippingAddress::where('user_id', auth()->id())->findOrFail($id);
        $address->delete();

        return redirect()->back()->with('success', 'Dirección eliminada.');
    }

}
