<div>
    <form action="/users/{{$user->id}}/barks" method="POST">
        @csrf
        <h3>What's barking?</h3>
        <textarea rows="3" cols="50" name="message">{{ old('message') ?? 'Bark' }}</textarea><br>
        @if (session()->has('error'))
            <div class="error">
                {!! session()->get('error') !!}
            </div>
        @endif
        <input type="submit" value="Bark">
    </form>
</div>
