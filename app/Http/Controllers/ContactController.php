<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::sortable()
                    ->when(request('vendor'),function($q){
                        $q->where('vendor','LIKE','%'.request('vendor').'%');
                    })
                    ->when(request('vendor_hour'),function($q){
                        $q->where('vendor_hour',request('vendor_hour'));
                    })
                    ->when(request('phone'),function($q){
                        $q->where('phone',request('phone'));
                    })
                    ->when(request('email'),function($q){
                        $q->where('email','LIKE','%'.request('email').'%');
                    })
                    ->latest()
                    ->paginate(request('total_records',10));
        return view('contacts.index', compact('contacts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vendor' => 'required|string|max:255',
            'vendor_hour' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:contacts,email',
        ]);

        Contact::create($request->all());

        return response()->json(['success' => 'Contact added successfully!']);
    }

    public function update(Request $request, Contact $contact)
    {
        $request->validate([
            'vendor' => 'required|string|max:255',
            'vendor_hour' => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:contacts,email,' . $contact->id,
        ]);

        $contact->update($request->all());

        return response()->json(['success' => 'Contact updated successfully!']);
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        return response()->json(['success' => 'Contact deleted successfully!']);
    }
}
