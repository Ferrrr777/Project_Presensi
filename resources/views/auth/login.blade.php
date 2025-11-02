@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="login-wrapper">
    <div class="login-card">
        <h2>Login</h2>

        <form id="loginForm">
            @csrf
            <div class="mb-3">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                <div id="emailError" class="text-error"></div>
            </div>

            <div class="mb-3">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" required>
                <div id="passwordError" class="text-error"></div>
            </div>

            <button type="submit" class="btn btn-primary mt-2">Login</button>
        </form>

        <div id="loginDebug" class="mt-3 text-info"></div>
    </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', async function(e){
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const debug = document.getElementById('loginDebug');

    debug.innerHTML = 'Mengirim login...';

    try {
        const response = await fetch('{{ url('/login') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email, password })
        });

        if(!response.ok){
            debug.innerHTML = 'Login gagal: Response tidak OK';
            return;
        }

        const data = await response.json();
        
        if(data.guard){
            debug.innerHTML = `Login berhasil. Guard aktif: ${data.guard}`;
            window.location.href = data.redirect; // Redirect sesuai guard
        } else {
            debug.innerHTML = `Login gagal: ${data.errors ? JSON.stringify(data.errors) : 'Email atau password salah'}`;
        }

    } catch (err) {
        debug.innerHTML = 'Terjadi error: ' + err.message;
    }
});
</script>
@endsection
