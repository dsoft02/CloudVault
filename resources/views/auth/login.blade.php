<x-guest-layout bgimage="assets/images/media/pngs/5.png">
    @section('title', 'Login')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card-sigin">
        <div class="main-signup-header">
            <h3 class="fs-26 mb-2">Welcome back!</h3>
            <h6 class="fw-medium mb-4 fs-17">Please sign in to continue.</h6>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" placeholder="Enter your email" type="email" id="email" name="email"
                        value="{{ old('email') }}" required autofocus autocomplete="username">
                        <div class="invalid-feedback">Please enter a valid email</div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <div class="form-group mb-1">
                    <label class="form-label">Password</label>
                    <input class="form-control" placeholder="Enter your password" type="password" name="password"
                        id="password" required>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>
                <div class="form-group mb-3">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>
                <input type="submit" name="loginbtn" id="loginbtn" class="btn btn-primary btn-block w-100"
                    value="Sign In" />
            </form>
            <div class="main-signin-footer mt-3">
                <p class="mb-1"><a href="{{ route('password.request') }}">Forgot your password?</a></p>
                <p>Don't have an account? <a href="{{ route('register') }}">Create an
                        Account</a></p>
            </div>
        </div>
    </div>
</x-guest-layout>