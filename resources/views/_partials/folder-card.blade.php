<div class="col-xl-3 col-md-4 col-sm-6" id="folder_{{ $folder->id }}">
    <div class="card p-0">
        <div class="d-flex align-items-center px-3 pt-3 pb-1">
            <div class="float-end ms-auto">
                <a href="javascript:void(0);" class="option-dots" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false"><i class="fe fe-more-vertical"></i></a>
                <div class="dropdown-menu rounded-7">
                    @if(!$folder->is_deleted)
                        <a class="dropdown-item" href="{{ route('folders.open', $folder->id) }}"><i
                                class="fe fe-folder me-2"></i> Open</a>
                        <a class="dropdown-item" href="{{ route('folders.share', $folder->id) }}"><i
                                class="fe fe-share me-2"></i> Share</a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="showInfo('folder', {{ $folder->id }})">
                            <i class="fe fe-info me-2"></i> Info
                        </a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="renameFolder({{ $folder->id }}, '{{ $folder->name }}')"><i class="fe fe-edit me-2"></i>
                            Rename</a>
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="openMoveCopyModal('folder', {{ $folder->id }}, 'move')">
                            <i class="fe fe-arrow-right me-2"></i> Move
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="openMoveCopyModal('folder', {{ $folder->id }}, 'copy')">
                            <i class="fe fe-copy me-2"></i> Copy
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                            onclick="moveToRecycleBin('folder', {{ $folder->id }})"><i class="fe fe-trash me-2"></i> Move to
                            Recycle Bin</a>
                    @else
                        <a class="dropdown-item" href="javascript:void(0);"
                            onclick="restoreFromRecycleBin('folder', {{ $folder->id }})">
                            <i class="fe fe-refresh-cw me-2"></i> Restore Folder
                        </a>
                        <a class="dropdown-item" href="javascript:void(0);" onclick="showInfo('folder', {{ $folder->id }})">
                            <i class="fe fe-info me-2"></i> Info
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="javascript:void(0);"
                            onclick="deletePermanently('folder', {{ $folder->id }})">
                            <i class="fe fe-trash-2 me-2"></i> Delete Permanently
                        </a>
                    @endif
                </div>
            </div>
        </div>
        <div class="card-body pt-0 text-center">
            <div class="file-manger-icon">
                <a href="{{ route('folders.open', $folder->id) }}">
                    <img src="{{ getFileIcon($folder->name, true) }}" alt="folder-icon" class="rounded-7">
                </a>
            </div>
            <h6 class="mb-1 fw-semibold fs-14">{{ $folder->name }}</h6>
            <span class="text-muted fs-11">{{ formatSize($folder->getTotalSize()) }}</span>
        </div>
    </div>
</div>