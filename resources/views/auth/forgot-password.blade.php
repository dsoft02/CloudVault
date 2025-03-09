<x-guest-layout bgimage="assets/images/media/pngs/3.png">
    @section('title', 'Forgot Password')
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="card-sigin">
        <div class="main-signup-header">
            <h3 class="fs-26 mb-2">Forgot Password!</h3>
            <h6 class="fw-medium mb-4 fs-14">Please Enter Your Email and we will email you a password reset link that will allow you to choose a new one</h6>
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" placeholder="Enter your email" type="email" id="email" name="email"
                        value="{{ old('email') }}" required autofocus autocomplete="username">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <input type="submit" name="forgotpassword" id="forgotpassword" class="btn btn-primary btn-block w-100"
                    value="Send password reset link" />
            </form>
            <div class="main-signin-footer mt-3">
                <p>Remember it, <a href="{{ route('login') }}"> Send me back</a> to the sign in screen.</p>
            </div>
        </div>
    </div>
</x-guest-layout>