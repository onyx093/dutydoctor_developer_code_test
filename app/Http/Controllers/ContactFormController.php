<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ContactFormRequest;
use App\Http\Resources\ContactFormResource;
use App\Mail\ContactFormMail;

class ContactFormController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\ContactFormRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContactFormRequest $request)
    {
        $contact = new Contact;
        $contact_resource = [];
        try {
            DB::transaction(function () use ($request, $contact, $contact_resource) {

                $contact->name = $request->input('name');
                $contact->email_address = $request->input('email_address');
                $contact->message = $request->input('message');

                if ($request->hasFile('attachment')) {
                    $logo = $request->file('attachment');
                    $filename = time() . '_' . $logo->getClientOriginalName();
                    Storage::putFileAs('uploads', $logo, $filename);
                    $contact->attachment = $filename;
                }
                $contact->save();
                $contact_resource = new ContactFormResource($contact);
                Mail::to(env("MAIL_NOTIFICATION"))->send(new ContactFormMail($contact_resource));
            });
        } catch (\Throwable $th) {
            return response()->json(['error' => 'An error occurred, ' . $th->getMessage()]);
        }

        return response()->json($contact_resource, 201);
    }
}
