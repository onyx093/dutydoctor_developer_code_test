<x-mail::message>
# Notification message

A new contact has been saved to the database.

Details:
Name: {{ $contact_details['name'] }}
Email: {{ $contact_details['email_address'] }}
message: {{ $contact_details['message'] }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
