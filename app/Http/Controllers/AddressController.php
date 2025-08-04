<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $addresses = Address::where('user_id', $userId)->orderByDesc('isdefault')->get();
        return view('user.address', compact('addresses'));
    }

    public function address_add()
    {
        return view('user.address-add');
    }

    public function address_store(Request $request)
    {
        $user = Auth::user();

        // Validasi input
        $validated = $request->validate([
            'name'      => 'required|max:100',
            'phone'     => 'required|numeric|digits_between:10,13',
            'locality'  => 'required',
            'address'   => 'required',
            'city'      => 'required',
            'state'     => 'required',
            'landmark'  => 'nullable|max:255',
            'zip'       => 'required|numeric|digits:5',
            'type'      => 'nullable|in:Rumah,Kantor,Lainnya',
            'isdefault' => 'nullable|boolean',
        ]);
        $validated['country'] = $request->input('country', 'Indonesia');

        // Cek apakah checkbox isdefault dicentang
        $validated['isdefault'] = $request->has('isdefault') ? 1 : 0;

        // Jika alamat ini default, nonaktifkan alamat default lama
        if ($validated['isdefault']) {
            Address::where('user_id', $user->id)->update(['isdefault' => false]);
        }

        // Simpan alamat baru
        $address = new Address($validated);
        $address->user_id = $user->id;
        $address->save();
        return redirect()->route('user.address.index')->with('success', 'Alamat berhasil ditambahkan!');
    }

    public function address_edit($id)
    {
        $address = Address::find($id);
        return view('user.address-edit', compact('address'));
    }

    public function address_update(Request $request, $id)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'zip'       => 'required|string|max:10',
            'state'     => 'required|string|max:100',
            'city'      => 'required|string|max:100',
            'address'   => 'required|string|max:255',
            'locality'  => 'required|string|max:255',
            'landmark'  => 'nullable|string|max:255',
            'type'      => 'required|in:Rumah,Kantor,Lainnya',
            'isdefault' => 'nullable|in:1',
        ]);

        // Ambil ID address dari hidden field atau route param (asumsi pakai ID)
        $address = Address::findOrFail($id);

        // Jika centang isdefault, kosongkan semua isdefault milik user
        if ($request->has('isdefault')) {
            Address::where('user_id', Auth::id())->update(['isdefault' => false]);
            $validated['isdefault'] = true;
        } else {
            $validated['isdefault'] = false;
        }

        $address->update($validated);

        return redirect()->route('user.address.index')->with('success', 'Alamat berhasil diupdate!');
    }

    public function address_delete($id)
    {
        $address= Address::find($id);
        $address->delete($id);
        return redirect()->route('user.address.index')->with('success', 'Alamat behasil dihapus!');
    }
}
