<x-guest-layout bgimage="assets/images/media/pngs/5.png">
    @section('title', 'Register')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card-sigin">
        <div class="main-signup-header">
            <h3 class="fs-26 mb-2">Get Started!</h3>
            <h6 class="fw-medium mb-4 fs-17">Fill your details below to continue.</h6>
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <!-- Name -->
                <div class="form-group mb-3">
                    <label class="form-label">First Name and Last Name</label>
                    <input class="form-control" placeholder="Enter your first Name and last Name" type="text" id="name" name="name"
                        value="{{ old('name') }}" required autofocus autocomplete="name">
                        <div class="invalid-feedback">Please enter a valid name</div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" placeholder="Enter your email" type="email" id="email" name="email"
                        value="{{ old('email') }}" required autocomplete="username">
                        <div class="invalid-feedback">Please enter a valid email</div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="form-group mb-1">
                    <label class="form-label">Password</label>
                    <input class="form-control" placeholder="Enter your password" type="password" name="password"
                        id="password" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="form-group mb-4">
                    <label class="form-label">Confirm Password</label>
                    <input class="form-control" placeholder="Enter your password" type="password" name="password_confirmation"
                        id="password_confirmation" required autocomplete="new-password">
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
                <input type="submit" name="registerbtn" id="registerbtn" class="btn btn-primary btn-block w-100"
                    value="Create Account" />
            </form>
            <div class="main-signin-footer mt-3">
                <p>Already have an account? <a href="{{ route('login') }}">Sign In</a></p>
            </div>
        </div>
    </div>
</x-guest-layout>