@component('mail::message')
# Hi!

{{ $invitation->company->owner->name }} has invited you to join their company!

If you do not already have an account, you may click the following link to get started:

@component('mail::button', ['url' => url('register?invitation='.$invitation->token)])
Accept
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
