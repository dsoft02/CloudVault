<x-app-layout>
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
            <div class="my-auto">
                <h5 class="page-title fs-21 mb-1">Cloud Drive</h5>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Cloud Drive</a></li>
                        @foreach($folder->getBreadcrumb() as $crumb)
                            <li class="breadcrumb-item">
                                <a href="{{ route('folders.open', $crumb['id']) }}">{{ $crumb['name'] }}</a>
                            </li>
                        @endforeach
                    </ol>
                </nav>

            </div>
            <div class="d-flex my-xl-auto right-content align-items-center">
                <div class="pe-1 mb-xl-0">
                    <button type="button" class="btn btn-primary btn-compose me-2 btn-b"
                        onclick="openFolderModal({{ $folder->id }})"><i class="fa fa-plus mx-2"></i>
                        Create Folder</button>
                </div>
                <div class="mb-xl-0">
                    <a href="{{ route('upload.folders.form', $folder) }}"
                        class="btn btn-success btn-compose me-2 btn-b"><i class="fa fa-upload mx-2"></i>
                        Upload</a>
                </div>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-6">
                        <div class="fs-20 mb-4">
                        </div>
                    </div>
                    @include('_partials.search-input')
                </div>
                <div class="row">
                    @if($folders->isEmpty() && $files->isEmpty())
                    <div class="card mb-4 text-center">
                        <div class="card-body h-100">
                            <img src="{{  asset('assets/images/svgicons/no-data.svg') }}" alt="" class="w-35">
                            <h5 class="mt-3 tx-18">Its Empty In Here</h5>
                        </div>
                    </div>
                    @else
                        @foreach($folders as $folder)
                            @include('_partials.folder-card', ['folder' => $folder])
                        @endforeach

                        @foreach($files as $file)
                            @include('_partials.file-card', ['file' => $file])
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
        <!--End::row-1 -->
    </div>
    @include('_partials.enc-key')
    @include('_partials.modals')

</x-app-layout>