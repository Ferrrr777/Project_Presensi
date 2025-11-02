<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Kawai Musik Pontianak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .login-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            padding: 3rem;
            width: 100%;
            max-width: 400px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 1.2s forwards;
        }

        @keyframes fadeInUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: #343a40;
            font-weight: bold;
        }

        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            transition: all 0.3s;
        }

        .form-control:focus {
            box-shadow: 0 0 10px rgba(102, 126, 234, 0.5);
            border-color: #667eea;
        }

        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            border-radius: 10px;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            border: none;
            font-weight: bold;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #2575fc, #6a11cb);
            transform: scale(1.05);
        }

        .fade-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            z-index: -1;
            animation: bgFade 1s ease-in-out;
        }

        .text-error {
            color: #e74c3c;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }

        a.forgot-link {
            display: block;
            text-align: center;
            margin-top: 1rem;
            color: #ff6600;
            font-weight: 500;
            text-decoration: none;
        }

        a.forgot-link:hover {
            text-decoration: underline;
        }

        /* Responsif untuk mobile: Kurangi padding dan margin agar form tidak terlalu besar */
        @media (max-width: 576px) {
            .login-card {
                padding: 1.5rem; /* Kurangi padding dari 3rem menjadi 1.5rem untuk mobile */
                margin: 1rem; /* Tambahkan margin kecil agar tidak menempel tepi layar */
                border-radius: 10px; /* Sedikit kurangi border-radius untuk tampilan lebih compact */
            }

            .login-card h2 {
                margin-bottom: 1.5rem; /* Kurangi margin bawah judul */
                font-size: 1.5rem; /* Kurangi ukuran font judul jika perlu */
            }

            .form-control {
                padding: 0.5rem 0.75rem; /* Kurangi padding input untuk lebih compact */
            }

            .btn-primary {
                padding: 0.5rem; /* Kurangi padding tombol */
            }

            .mb-3 {
                margin-bottom: 1rem !important; /* Gunakan margin Bootstrap yang lebih kecil */
            }
        }
    </style>
</head>
<body>
    <div class="fade-bg"></div>

    <div class="login-wrapper">
        <div class="login-card">
            <h2>Login</h2>

            <form method="POST" action="{{ url('/login') }}">
                @csrf
                <div class="mb-3">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="text-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password">Password</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary mt-2">Login</button>

                <a href="#" class="forgot-link">Lupa Password?</a>
            </form>
        </div>
    </div>
</body>
</html>
