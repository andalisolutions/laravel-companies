@component('mail::message')
# Hi!

{{ $invitation->company->owner->name }} has invited you to join their company!

<br><br>

Since you already have an account, you may accept the invitation from your account settings screen.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
