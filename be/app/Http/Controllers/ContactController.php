<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi mỗi trang

        $contacts = Contact::when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone_number', 'LIKE', "%{$search}%");
            });
        })
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);


    $noResults = $contacts->isEmpty(); // Kiểm tra nếu không có kết quả

    return view('contacts.index', compact('contacts', 'noResults'));
}
public function edit(Contact $contact)
{
    return view('contacts.edit', compact('contact'));
}

// Cập nhật thông tin liên hệ
public function update(Request $request, $id )
{
    $contact = Contact::findOrFail($id);
    $contact->update($request->all());

    return redirect()->route('contacts.index')->with('success', 'Liên hệ đã được cập nhật thành công.');
}

}
