<div>
    <form action="/users/{{ $user->id }}/barks" method="POST">
        @csrf
        <h3>What's barking?</h3>
        <textarea rows="3" cols="50" name="message"></textarea><br>
        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error">{{ $error }}</div>
            @endforeach
        @endif
        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif
        <input type="submit" value="Bark">
    </form>
</div>
