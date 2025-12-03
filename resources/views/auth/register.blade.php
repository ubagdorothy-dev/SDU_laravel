<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SDU - Register</title>
    <link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
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