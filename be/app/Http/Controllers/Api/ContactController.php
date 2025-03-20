<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Lấy danh sách tất cả các contact.
     */
    public function index()
    {
        $contacts = Contact::all(); // Lấy tất cả contact
        return response()->json($contacts, 200);
    }

    /**
     * Lấy thông tin contact theo ID.
     */
    public function show($id)
    {
        $contact = Contact::find($id);

        if (!$contact) {
            return response()->json([
                'message' => 'Contact không tồn tại!'
            ], 404);
        }

        return response()->json($contact, 200);
    }
}
