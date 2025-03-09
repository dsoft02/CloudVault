<div class="col-xl-3 col-md-4 col-sm-6" id="file_{{ $file->id }}">
    <div class="card p-0">
        <div class="d-flex align-items-center px-3 pt-3 pb-1">
            <div class="float-end ms-auto">
                <a href="javascript:void(0);" class="option-dots" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                <div class="dropdown-menu rounded-7">
                    @if(!$file->is_deleted)
                        <a class="dropdown-item" href="javascript:void(0);" onclick="showDownloadModal({{ $file->id }})">
                            <i class="fe fe-download me-2"></i> Decrypt and Download
                        </a>
                        <a class="dropdown-item" href="{{ route('files.download.raw', $file->id) }}">
                            <i class="fe fe-file me-2"></i> Download Original (Encrypted)
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="generateShareLink({{ $file->id }})">
                            <i class="fe fe-share me-2"></i> Share File
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="showInfo('file', {{ $file->id }})">
                            <i class="fe fe-info me-2"></i> Info
                        </a>
                        <div class="dropdown-divider"></div>
                        {{-- <a class="dropdown-item" href="{{ route('files.rename', $file->id) }}"><i
                                class="fe fe-edit me-2"></i> Rename</a> --}}
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="openMoveCopyModal('file', {{ $file->id }}, 'move')">
                            <i class="fe fe-arrow-right me-2"></i> Move
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="openMoveCopyModal('file', {{ $file->id }}, 'copy')">
                            <i class="fe fe-copy me-2"></i> Copy
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                            onclick="moveToRecycleBin('file', {{ $file->id }})"><i class="fe fe-trash me-2"></i> Move to
                            Recycle Bin</a>
                    @else
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="restoreFromRecycleBin('file', {{ $file->id }})">
                            <i class="fe fe-refresh-cw me-2"></i> Restore File
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="showInfo('file', {{ $file->id }})">
                            <i class="fe fe-info me-2"></i> Info
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                            onclick="deletePermanently('file', {{ $file->id }})">
                            <i class="fe fe-trash-2 me-2"></i> Delete Permanently
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body pt-0 text-center">
            <div class="file-manger-icon">
                <img src="{{ getFileIcon($file->file_name) }}" alt="file-icon" class="rounded-7">
            </div>
            <h6 class="mb-1 fw-semibold fs-14">{{ $file->file_name }}</h6>
            <span class="text-muted fs-11">{{ formatSize($file->file_size) }}</span>
        </div>
    </div>
</div>