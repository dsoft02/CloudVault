<x-app-layout>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div class="my-auto">
                <h5 class="page-title fs-21 mb-1">Cloud Drive</h5>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Cloud Drive</a></li>
                        @if($folder)
                            @foreach($folder->getBreadcrumb() as $crumb)
                                <li class="breadcrumb-item">
                                    <a href="{{ route('folders.open', $crumb['id']) }}">{{ $crumb['name'] }}</a>
                                </li>
                            @endforeach
                        @endif
                        <li class="breadcrumb-item active">File Upload</li>
                    </ol>
                </nav>

            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">
                            Upload Files to:
                            <span class="text-primary ms-2">
                                Cloud Drive
                                @if($folder)
                                    /
                                    @foreach($folder->getBreadcrumb() as $index => $crumb)
                                        {{ $crumb['name'] }}{{ $loop->last ? '' : ' / ' }}
                                    @endforeach
                                @endif
                            </span>
                        </div>
                    </div>
                    <form
                        action="{{ $folder ? route('upload.folders', ['folder' => $folder->id]) : route('upload.root') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">

                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <svg class="flex-shrink-0 me-2 svg-danger" xmlns="http://www.w3.org/2000/svg"
                                    enable-background="new 0 0 24 24" height="1.5rem" viewBox="0 0 24 24" width="1.5rem"
                                    fill="#000000">
                                    <g>
                                        <rect fill="none" height="24" width="24"></rect>
                                    </g>
                                    <g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M15.73,3H8.27L3,8.27v7.46L8.27,21h7.46L21,15.73V8.27L15.73,3z M19,14.9L14.9,19H9.1L5,14.9V9.1L9.1,5h5.8L19,9.1V14.9z">
                                                </path>
                                                <rect height="6" width="2" x="11" y="7"></rect>
                                                <rect height="2" width="2" x="11" y="15"></rect>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                                <div><strong>Important:</strong> Keep your encryption key safe! Without it, you will not be able to decrypt your files.</div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    @include('_partials.error-card')
                                    <div class="row">
                                        <!-- Encryption Type -->
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label" for="encryption_type">Encryption Type</label>
                                            <select id="encryption_type" name="encryption_type" class="form-select"
                                                onchange="toggleEncryptionFields()">
                                                <option value="single">Use Single Key</option>
                                                <option value="multiple">Use Different Keys</option>
                                            </select>
                                        </div>

                                        <!-- Single Encryption Key -->
                                        <div class="col-md-12 mb-3" id="single_key_field">
                                            <label class="form-label" for="encryption_key">Encryption Key:</label>
                                            <input type="password" class="form-control" name="encryption_key"
                                                placeholder="Encryption Key:">
                                        </div>

                                        <!-- File Inputs -->
                                        <div class="col-md-12 mb-3" id="file_inputs">
                                            <div class="row file-upload">
                                                <div class="col mb-3">
                                                    <label class="form-label">Select File:</label>
                                                    <input class="form-control" type="file" name="files[]" required>
                                                </div>
                                                <div class="col mb-3 multi-key d-none">
                                                    <label class="form-label">Encryption Key:</label>
                                                    <input type="password" class="form-control" name="encryption_keys[]"
                                                        placeholder="Encryption Key:">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Add Another File -->
                                        <div class="col-md-12">
                                            <button type="button" class="btn btn-outline-primary"
                                                onclick="addFileInput()">
                                                <i class="fa fa-add me-2"></i>Add Another File
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Upload File(s)</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <!--End::row-1 -->
    </div>

</x-app-layout>