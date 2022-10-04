@extends('layouts.profile')

@section('title', 'Edit Your Profile')

@section('content')
    <div class="w-100 p-3">
        <div class="card">
            <div class="card-header">Your current data</div>
            <div class="card-body">
                <form method="post" action="{{ route('main') }}/users/{{ Auth::user()->id }}">
                    @csrf
                    @method('PUT')
                    <div class="form-floating mb-3">
                        <input
                            type="text"
                            class="form-control @error('last_name') is-invalid @elseif($errors->any()) is-valid @enderror"
                            id="last_name"
                            value="{{ Auth::user()->last_name }}"
                            name="last_name"
                            placeholder="ivanov"
                            required
                        >
                        <label for="last_name">Last name</label>
                        @error('last_name')
                        <div class="invalid-tooltip" id="message_last_name">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input
                            type="text"
                            class="form-control @error('name') is-invalid @elseif($errors->any()) is-valid @enderror"
                            id="name"
                            value="{{ Auth::user()->name }}"
                            name="name"
                            placeholder="ivan"
                            required
                        >
                        <label for="name">Name</label>
                        @error('name')
                        <div class="invalid-tooltip" id="message_name">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input
                            type="email"
                            class="form-control @error('email') is-invalid @elseif($errors->any()) is-valid @enderror"
                            id="email"
                            value="{{ Auth::user()->email }}"
                            name="email"
                            placeholder="no@mail.ua"
                            required
                        >
                        <label for="email">E-mail</label>
                        @error('email')
                        <div class="invalid-tooltip" id="message_name">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" id="password" name="password" placeholder="qwerty">
                        <label for="password">Password</label>
                    </div>
                    <button type="submit" class="btn btn-outline-success">Сохранить</button>
                </form>
            </div>
        </div>
    </div>
    <script>
        let objInputs = document.querySelectorAll('input');
        objInputs.forEach(function (objInput) {
            if ('form-control  is-invalid ' === objInput.className) {
                objInput.addEventListener('input', function () {
                    objInput.className = 'form-control is-valid';
                    document.getElementById('message_' + objInput.name).remove();
                });
            }
        });
    </script>
@endsection
