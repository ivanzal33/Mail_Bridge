@if ($errors->any())
    <div class="errors">
        @foreach($errors->all() as $error)
            <div class="alert alert-error" role="alert">
                {{ $error }}
            </div>
        @endforeach
    </div>
@endif
