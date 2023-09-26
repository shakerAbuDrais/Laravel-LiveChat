@foreach($users as $user)
    <a href="{{ route('chat.show', $user->id) }}">{{ $user->name }}</a>
@endforeach
