<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $addresses = Address::where('user_id', auth()->id())->get();
        return view('profile.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
        ]);

        $data = $request->all();
        $data['user_id'] = auth()->id();

        if ($request->has('is_default')) {
            // Remove default from other addresses
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
            $data['is_default'] = true;
        }

        Address::create($data);

        return redirect()->route('addresses.index')->with('success', 'Address added successfully!');
    }

    public function update(Request $request, Address $address)
    {
        if ($address->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'label' => 'required|string|max:50',
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
        ]);

        $data = $request->all();

        if ($request->has('is_default')) {
            Address::where('user_id', auth()->id())->update(['is_default' => false]);
            $data['is_default'] = true;
        } else {
            $data['is_default'] = false;
        }

        $address->update($data);

        return redirect()->route('addresses.index')->with('success', 'Address updated successfully!');
    }

    public function destroy(Address $address)
    {
        if ($address->user_id != auth()->id()) {
            abort(403);
        }

        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Address deleted successfully!');
    }

    public function setDefault(Address $address)
    {
        if ($address->user_id != auth()->id()) {
            abort(403);
        }

        Address::where('user_id', auth()->id())->update(['is_default' => false]);
        $address->update(['is_default' => true]);

        return redirect()->route('addresses.index')->with('success', 'Default address set!');
    }
}