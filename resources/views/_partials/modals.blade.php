<!-- Folder creation and rename Modal -->
<div class="modal fade" id="folderModal" tabindex="-1" aria-labelledby="folderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered"> <!-- Added modal-dialog-centered -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="folderModalLabel">Create Folder</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="folderForm" action="{{ route('folders.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="folder_id" name="folder_id">
                    <input type="hidden" id="parent_id" name="parent_id">
                    <label for="folder_name" class="form-label">Folder Name</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fa fa-folder"></i></span>
                        <input type="text" class="form-control" id="folder_name" name="name"
                            placeholder="Enter the folder name" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitbtn">Save Folder</button>
                </div>
            </form>

        </div>
    </div>
</div>

<!-- Move/Copy Modal -->
<div class="modal fade" id="moveCopyModal" tabindex="-1" aria-labelledby="moveCopyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="moveCopyModalLabel">Move/Copy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="moveCopyForm">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="move_copy_type" name="type">
                    <input type="hidden" id="move_copy_id" name="id">
                    <input type="hidden" id="move_copy_action" name="action">

                    <div class="mb-3">
                        <label for="new_path" class="form-label">Destination Path</label>
                        <input type="text" class="form-control" id="new_path" name="new_path"
                            placeholder="Leave blank for Root or type e.g., Documents/Reports/2024">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="override_existing" name="override_existing">
                        <label class="form-check-label" for="override_existing">Override existing file/folder if
                            exists</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="moveCopySubmitBtn">Proceed</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Offcanvas for File/Folder Info -->
<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header border-bottom border-block-end-dashed">
        <h5 class="offcanvas-title" id="offcanvasTitle">Item Info</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <!-- File/Folder Icon -->
        <div class="mb-3 text-center">
            <img id="infoIcon" src="" alt="Item Icon" class="img-fluid" style="max-width: 100px;">
        </div>
        <div id="infoContainer">
            <p><strong>Name:</strong> <span id="infoName"></span></p>
            <p><strong>Type:</strong> <span id="infoType"></span></p>
            <p><strong>Path:</strong> <span id="infoPath"></span></p>
            <p><strong>Total Size:</strong> <span id="infoSize"></span></p>
            <p><strong>Created At:</strong> <span id="infoCreated"></span></p>
            <p><strong>Last Modified At:</strong> <span id="infoUpdated"></span></p>
            <p id="infoDeletedContainer" style="display: none;">
                <strong>Deleted At:</strong> <span id="infoDeleted"></span>
            </p>
        </div>
    </div>
</div>

<!-- Download Decryption Modal -->
<div class="modal fade" id="downloadModal" tabindex="-1" aria-labelledby="downloadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="downloadModalLabel">Enter Decryption Key</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="downloadFileId">
                <label for="decryption_key" class="form-label">Decryption Key</label>
                <input type="password" id="decryption_key" class="form-control" placeholder="Enter decryption key"
                    required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="decryptAndDownload()">Download</button>
            </div>
        </div>
    </div>
</div>

<!-- Share Link Modal -->
<div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="shareModalLabel">Share File</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <p>Share this file using:</p>

                <!-- Social Share Buttons -->
                <div class="d-flex justify-content-center gap-3">
                    <a id="telegramShare" href="#" target="_blank" class="btn btn-primary">
                        <i class="fe fe-send"></i> Telegram
                    </a>
                    <a id="whatsappShare" href="#" target="_blank" class="btn btn-success">
                        <i class="ri ri-whatsapp-line"></i> WhatsApp
                    </a>
                    <a id="smsShare" href="#" class="btn btn-secondary">
                        <i class="fe fe-message-square"></i> Message
                    </a>
                </div>

                <!-- Copy Link Section -->
                <div class="mt-3 input-group">
                    <input type="text" id="shareableLink" class="form-control text-center" readonly>
                    <button class="btn btn-outline-primary" id="copyButton" data-clipboard-target="#shareableLink">
                        <i class="fe fe-copy"></i> Copy Link
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
