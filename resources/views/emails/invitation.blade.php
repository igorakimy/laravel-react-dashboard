<p>Hello! {{ $inviter }} invites you to join our site.</p>
@if($message_text)
    <p>{{ $message_text }}</p>
@endif
<p>
    You can join our site by clicking the following link:
    <a href="{{ config('app.url') . '/register/' . $token }}">Register now</a>
</p>

