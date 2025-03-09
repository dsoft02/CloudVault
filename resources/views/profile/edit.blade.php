<x-app-layout>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div class="my-auto">
                <h5 class="page-title fs-21 mb-1">Edit Profile</h5>
            </div>

        </div>
        <!-- Page Header Close -->

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form method="post" action="{{ route('profile.update') }}" class="form-horizontal mt-6 space-y-6">
                        @csrf
                        @method('patch')
                        <div class="card-body">
                            <div class="mb-4 main-content-label">Personal Information</div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">Name</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="text" name="name" id="name" class="form-control"
                                            placeholder="Full Name" value="{{ old('name', $user->name) }}" required
                                            autofocus autocomplete="name">
                                        <x-input-error class="mt-2 text-danger" :messages="$errors->get('name')" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="email">Email</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="email" name="email" id="email" class="form-control"
                                            placeholder="Email address" value="{{ old('email', $user->email) }}"
                                            required autocomplete="username">
                                        <x-input-error class="mt-2 text-danger" :messages="$errors->get('email')" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Update
                                Profile</button>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <form method="post" action="{{ route('password.update') }}" class="orm-horizontal mt-6 space-y-6">
                        @csrf
                        @method('put')
                        <div class="card-body">
                            <div class="mb-4 main-content-label">Update Password</div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">Current Password</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="password" name="current_password" id="current_password"
                                            class="form-control" placeholder="Current Password"
                                            autocomplete="current-password">
                                        <x-input-error :messages="$errors->updatePassword->get('current_password')"
                                            class="mt-2 text-danger" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">New Password</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="password" name="password" id="password" class="form-control"
                                            placeholder="New Password" autocomplete="new-password">
                                        <x-input-error :messages="$errors->updatePassword->get('password')"
                                            class="mt-2 text-danger" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-3">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label class="form-label" for="name">Confirm Password</label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="password" name="password_confirmation" id="password_confirmation"
                                            class="form-control" placeholder="Confirm Password"
                                            autocomplete="current-password">
                                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')"
                                            class="mt-2 text-danger" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary waves-effect waves-light">Update
                                Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--End::row-1 -->
    </div>
</x-app-layout>