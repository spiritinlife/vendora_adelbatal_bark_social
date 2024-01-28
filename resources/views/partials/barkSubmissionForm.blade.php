<div>
    <form action="/users/{{$user->id}}/barks" method="POST">
        @csrf
        <h3>What's barking?</h3>
        <textarea rows="3" cols="50" name="message">{{ old('message') ?? 'Bark' }}</textarea><br>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error">
                    {{ $error }}
                </div>
            @endforeach
            {{--                {!! session()->get('error') !!}--}}
        @endif
        <input type="submit" value="Bark">
    </form>
</div>
