<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDU - Register</title>
     
    <style>
    @import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap');

    * {
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Montserrat', sans-serif;
        margin: 0;
        padding: 0;
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

    .registration-container {
        display: flex;
        width: 100%;
        max-width: 1000px;
        height: auto;
        min-height: 650px;
        flex-direction: row-reverse;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .registration-left {
        background-color: #1a237e;
        color: white;
        flex: 1;
        display: flex;
        flex-direction: column; 
        justify-content: center; 
        align-items: center; 
        padding: 30px 20px;
    }

    .registration-left .register-logo {
        width: 200px;
        height: auto;
        margin-bottom: 15px; 
        display: block;
    }

    .registration-left h1 {
        font-size: 1.4rem;
        font-weight: 700;
        margin-bottom: 10px;
        text-align: center; 
        line-height: 1.7;
    }

    .registration-right {
        background-color: white;
        flex: 1;
        display: flex;
        justify-content: center; 
        align-items: center;    
        padding: 30px 20px;
        overflow-y: auto;
    }

    .form-content {
        width: 100%;
        max-width: 380px; 
    }

    .form-content h2 {
        font-size: 1.8rem;
        font-weight: 700;
        color: #1a237e;
        border-bottom: 3px solid #1a237e;
        padding-bottom: 5px;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 18px;
    }

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
        border-radius: 0;
        background-color: #e9ecef;
        /* limit transition to visual properties only to avoid layout reflow */
        transition: background-color 0.18s ease, border-color 0.18s ease;
        position: relative;
    }

    .input-with-icon:hover,
    .input-with-icon:focus-within {
        background-color: white;
        border-color: #1a237e;
    }

    /* keep icon area fixed so it doesn't move when input gets focus */
    .input-with-icon svg {
        /* fixed-size box for the icon */
        width: 20px;
        height: 20px;
        margin-left: 12px;
        margin-right: 12px;
        color: #6c757d;
        flex: 0 0 44px; /* reserve horizontal space for icon (including margins) */
        display: block;
        transition: color 0.18s ease;
    }

    .input-with-icon:hover svg,
    .input-with-icon:focus-within svg {
        color: #1a237e;
    }

    .input-with-icon input,
    .input-with-icon select {
        width: 100%;
        border: none;
        /* add horizontal padding so text doesn't sit flush to icon area */
        padding: 10px 12px;
        background-color: white;
        outline: none;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.95rem;
        color: #2c3e50;
        box-sizing: border-box; /* ensure padding doesn't change overall width */
    }

    .input-with-icon input::placeholder {
        color: #6c757d;
    }

    .input-with-icon input:focus,
    .input-with-icon select:focus {
        outline: none;
    }

    .input-with-icon select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%236c757d' stroke-width='2'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        /* move dropdown arrow a little inward to avoid overlap with scrollbar/edges */
        background-position: right 14px center;
        background-size: 18px;
        padding-right: 40px;
    }

    .register-btn { 
        width: 100%;
        padding: 12px;
        background-color: #1a237e;
        color: white;
        border: none;
        font-size: 1.1rem;
        font-weight: 700;
        font-family: 'Montserrat', sans-serif;
        cursor: pointer;
        transition: background-color 0.2s ease-in-out;
        margin-top: 10px;
    }

    .register-btn:hover {
        background-color: #141b63;
    }

    .register-btn:active {
        background-color: #0d1149;
    }

    .login { 
        text-align: center;
        margin-top: 18px;
        font-size: 0.9rem;
    }

    .login a {
        color: #1a237e;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.3s ease;
    }

    .login a:hover {
        color: #141b63;
        text-decoration: underline;
    }

    .text-danger {
        color: #e74c3c;
        font-weight: 600;
    }

    .message {
        padding: 12px 15px;
        margin-bottom: 20px;
        border-radius: 5px;
        text-align: center;
        font-weight: bold;
        font-size: 0.9rem;
        border-left: 4px solid;
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .error {
        background-color: #fff3cd;
        color: #856404;
        border-color: #ffc107;
    }

    .success {
        background-color: #d4edda;
        color: #155724;
        border-color: #28a745;
    }

    @media (max-width: 992px) {
        .registration-container {
            max-width: 800px;
        }
        .registration-left h1 {
            font-size: 1.4rem;
        }
    }

    @media (max-width: 768px) {
        .registration-container {
            flex-direction: column-reverse;
            height: auto;
            min-height: auto;
            border-radius: 0;
            box-shadow: none;
        }
        .registration-left, .registration-right {
            min-height: 40vh; 
            width: 100%;
            padding: 30px 20px;
        }
        .registration-right {
             min-height: 60vh;
        }
        .form-content {
            max-width: 100%;
        }
    }

    @media (max-width: 480px) {
        .registration-left h1 {
            font-size: 1.2rem;
        }
        .form-content h2 {
            font-size: 1.5rem;
        }
        .registration-left .register-logo {
            width: 150px;
        }
    }

    </style>
</head>
<body>
    <div class="registration-container">

        <div class="registration-right">
            <div class="form-content">
                <h2>Create an Account</h2>

                <form method="POST" action="{{ route('register.post') }}">
                    @csrf

                    {{-- FULL NAME FIELD (Replacing old 'name') --}}
                    <div class="form-group">
                        <label for="full_name">FULL NAME <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            {{-- User Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.685 10.567 10 8 10s-3.516.685-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                            </svg>
                            <input type="text" name="full_name" placeholder="Type your Full Name" required value="{{ old('full_name') }}">
                        </div>
                        @error('full_name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- EMAIL FIELD --}}
                    <div class="form-group">
                        <label for="email">EMAIL<span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            {{-- Email Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1zm13 1v.76L8.14 9.172a.5.5 0 0 1-.284 0L1 4.76V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1"/>
                            </svg>
                            <input type="email" name="email" placeholder="staff.name@sdu.edu.ph" required value="{{ old('email') }}">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>


                    {{-- PASSWORD FIELD --}}
                    <div class="form-group">
                        <label for="password">PASSWORD<span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            {{-- Lock Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2m3 6V3a3 3 0 1 0-6 0v4a1 1 0 0 0-1 1v2a2 2 0 0 0 2 2v2a.5.5 0 0 0 1 0v-2a2 2 0 0 0 2-2V8a1 1 0 0 0-1-1M5.5 8.5a.5.5 0 0 1 1 0v2a.5.5 0 0 1-1 0z"/>
                            </svg>
                            <input type="password" name="password" placeholder="Type your password" required>
                        </div>
                        @error('password')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- OFFICE/CENTER DROPDOWN --}}
                    <div class="form-group">
                        <label for="office_code">OFFICE / CENTER <span class="text-danger">*</span></label>
                        <div class="input-with-icon">
                            <!-- Office Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zM7 9a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V9zM13 1a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h2z"/>
                            </svg>
                            <select name="office_code" required>
                                <option value="" selected disabled>Select your Office</option>
                                @if(isset($offices))
                                    @foreach($offices as $officeCode) 
                                        <option value="{{ $officeCode }}" {{ old('office_code') == $officeCode ? 'selected' : '' }}>
                                            {{ $officeCode }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        {{-- Error display is already correct --}}
                        @error('office_code')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="register-btn">REGISTER</button>
                </form>

                <div class="login">
                    <a href="{{ route('login') }}">Already have an account? Sign In</a>
                </div>

            </div>
        </div>

        <div class="registration-left">
            <img src="{{ asset('images/SDU_Logo.png') }}" alt="SDU Logo" class="register-logo">
            <h1>SOCIAL DEVELOPMENT UNIT STAFF CAPACITY BUILDING MANAGEMENT SYSTEM</h1>
        </div>

    </div>
</body>
</html>