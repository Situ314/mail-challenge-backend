<div class="container" style="padding: 1rem; background: #f5f5f5;">
    <p>{{ $email->subject }}</p>
    <p>
        {{ $email->body }}
    </p>
    <p>
        Sincerely,<br>
        {{ $email->user->name }}<br>
        {{ $email->user->email }}
    </p>
</div>
