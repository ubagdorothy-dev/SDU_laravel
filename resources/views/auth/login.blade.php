<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SDU - Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
</head>

<body>
     <div class="login-container">

        <div class="login-left">
            <img src="{{ asset('images/SDU_Logo.png') }}" alt="SDU Logo" class="login-logo">
            <h1>SOCIAL DEVELOPMENT UNIT STAFF CAPACITY BUILDING MANAGEMENT SYSTEM</h1>
        </div>

        <div class="login-right">
            <div class="login-form-box">
                <h2>Sign In</h2>

                {{-- Display registration success message --}}
                @if(session('register_message'))
                    <div class="alert alert-{{ session('register_type', 'info') }}">
                        {!! session('register_message') !!}
                    </div>
                @endif

                {{-- Display approval notification --}}
                @if(session('approval_notification'))
                    <div class="alert alert-success">
                        {{ session('approval_notification') }}
                    </div>
                @endif

                {{-- Display login error --}}
                @if(session('login_error'))
                    <div class="alert alert-danger">
                        {{ session('login_error') }}
                    </div>
                @endif

                {{-- Display validation errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Laravel login form --}}
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <div class="input-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 1v.76L8.14 9.172a.5.5 0 0 1-.284 0L1 4.76V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1"/>
                            </svg>
                            <input type="email" name="email" placeholder="Type your Email" value="{{ old('email') }}" required>
                        </div>
                        @error('email')
                            <div class="text-danger" style="font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <div class="input-with-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 1 0-6 0v4a1 1 0 0 0-1 1v2a2 2 0 0 0 2 2v2a.5.5 0 0 0 1 0v-2a2 2 0 0 0 2-2V8a1 1 0 0 0-1-1M5.5 8.5a.5.5 0 0 1 1 0v2a.5.5 0 0 1-1 0z"/>
                            </svg>
                            <input type="password" name="password" placeholder="Type your password" required>
                        </div>
                        @error('password')
                            <div class="text-danger" style="font-size: 0.8rem; margin-top: 5px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="login-btn">LOG IN</button>
                </form>

                <div class="register">
                    <a href="{{ route('register') }}">Don't have an Account? Register</a>
                </div>

            </div>
        </div>

    </div>
</body>
</html>
