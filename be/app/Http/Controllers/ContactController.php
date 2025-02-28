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

}
