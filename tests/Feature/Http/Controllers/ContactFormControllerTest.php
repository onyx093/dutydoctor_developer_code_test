<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ContactFormControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test to check if the contact form is properly validated and submitted.
     *
     * @return void
     */
    public function test_create_contact_form_record():void
    {

        $filename = public_path('cv_snapshot.png');

        $response = $this->json('POST', '/api/v1/contact', [
            'name' => $name = $this->faker()->name(),
            'email_address' => $email = $this->faker()->email,
            'attachment' => UploadedFile::fake()->image($filename),
            'message' => $message = $this->faker()->sentences(5, true),
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('contacts', [
            'name' => $name,
            'email_address' => $email,
            'message' => $message,
        ]);
    }
}
