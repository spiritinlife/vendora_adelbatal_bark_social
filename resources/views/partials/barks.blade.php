@foreach($feed as $bark)
    <article>
        @if($feedType === 'friends')
            <a href="/users/{{$bark->user->id}}">{{$bark->user->name}} barked:</a>
        @endif
        <p>{{ $bark->message }}</p>
        <small>{{ $bark->created_at }}</small>
    </article>
    <hr>
@endforeach
