<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SDU - Login</title>

    <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f0f2f5;
        background-image: url('{{ asset("images/BG.jpg") }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }

    .login-container {
        display: flex;
        width: 100%;
        max-width: 1000px;
        height: 100vh;
        max-height: 600px;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .login-left {
        background-color: #1a237e;
        color: white;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-left .login-logo {
        width: 200px;
        height: auto;
        margin-bottom: 5px;
        display: block;
    }

    .login-left h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-align: center; 
        line-height: 1.7;
    }

    .login-right {
        background-color: white;
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .login-form-box {
        width: 100%;
        max-width: 400px;
    }

    .login-form-box h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a237e;
        border-bottom: 3px solid #1a237e;
        padding-bottom: 5px;
        margin-bottom: 25px;
    }

    .form-group { margin-bottom: 20px; }

    .form-group label {
        display: block;
        font-size: 0.8rem;
        font-weight: 700;
        color: #495057;
        margin-bottom: 5px;
        text-transform: uppercase;
    }

    .input-with-icon {
        display: flex;
        align-items: center;
        border: 1px solid #ced4da;
        background-color: #e9ecef;
    }

    .input-with-icon svg {
        margin: 0 10px;
        color: #6c757d;
    }

    .input-with-icon input {
        width: 100%;
        border: none;
        padding: 10px;
        background-color: white;
        outline: none;
    }

    .input-with-icon input:focus {
        outline: 2px solid #1a237e;
        outline-offset: -2px;
    }

    .login-btn {
        width: 100%;
        padding: 12px;
        background-color: #1a237e;
        color: white;
        border: none;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
        margin-top: 10px;
    }

    .login-btn:hover { background-color: #141b63; }

    .register {
        text-align: center;
        margin-top: 20px;
    }

    .register a {
        color: #1a237e;
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: Bold;
    }

    .register a:hover { text-decoration: underline; }

    /* Alert Styles */
    .alert {
        padding: 12px 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    
    .alert-success {
        color: #155724;
        background-color: #d4edda;
        border-color: #c3e6cb;
    }
    
    .alert-danger {
        color: #721c24;
        background-color: #f8d7da;
        border-color: #f5c6cb;
    }
    
    .alert-info {
        color: #0c5460;
        background-color: #d1ecf1;
        border-color: #bee5eb;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }

    @media (max-width: 768px) {
        .login-container { flex-direction: column-reverse; height: auto; }
        .login-left, .login-right { width: 100%; padding: 40px 20px; }
    }
    </style>
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
