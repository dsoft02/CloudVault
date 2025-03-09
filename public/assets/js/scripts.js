$(function() {

  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

})

    function clearFolderModal() {
        document.getElementById("folderForm").reset();
        document.getElementById("folder_id").value = "";
        document.getElementById("parent_id").value = "";
    }

    function openFolderModal(folderId) {
        clearFolderModal();
        document.getElementById("folderModalLabel").textContent = "Create Folder";
        document.getElementById("folderForm").action = "/folders/store";
        document.getElementById("submitbtn").textContent = "Save Folder";
        document.getElementById("parent_id").value = folderId;

        var folderModalElement = document.getElementById("folderModal");
        var folderModal = new bootstrap.Modal(folderModalElement);

        folderModal.show();

        folderModalElement.addEventListener('shown.bs.modal', function () {
            document.getElementById("folder_name").focus();
        }, { once: true });
    }


    function renameFolder(folderId, folderName) {
        clearFolderModal();
        document.getElementById("folderModalLabel").textContent = "Rename Folder";
        document.getElementById("folderForm").action = "/folders/update/" + folderId;
        document.getElementById("folder_id").value = folderId;
        document.getElementById("folder_name").value = folderName;
        document.getElementById("submitbtn").textContent = "Rename";

        var folderModalElement = document.getElementById("folderModal");
        var folderModal = new bootstrap.Modal(folderModalElement);

        folderModal.show();

        folderModalElement.addEventListener('shown.bs.modal', function () {
            let inputField = document.getElementById("folder_name");
            inputField.focus();
            inputField.select();
        }, { once: true });
    }


    function moveToRecycleBin(type, itemId) {
        Swal.fire({
            title: "Move to Recycle Bin?",
            text: `Are you sure you want to move this ${type} to the recycle bin?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "Yes, move it",
            cancelButtonText: "Cancel"
        }).then((result) => {
            if (result.isConfirmed) {
                let url = type === 'folder' ? `/folders/${itemId}/trash` : `/files/${itemId}/trash`;

                $.ajax({
                    url: url,
                    type: "POST",
                    success: function(response) {
                        Swal.fire({
                            title: "Moved!",
                            text: response.message,
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $("#"+type+"_" + itemId).fadeOut();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            title: "Error",
                            text: "Something went wrong! Please try again.",
                            icon: "error"
                        });
                    }
                });
            }
        });
    }

    let selectedType = "";
    let selectedId = "";
    let selectedAction = "";

    function openMoveCopyModal(type, id, action) {
        selectedType = type;
        selectedId = id;
        selectedAction = action;

        document.getElementById("moveCopyModalLabel").textContent = `${action.charAt(0).toUpperCase() + action.slice(1)} ${type}`;
        document.getElementById("moveCopySubmitBtn").textContent = action.charAt(0).toUpperCase() + action.slice(1);
        document.getElementById("move_copy_type").value = type;
        document.getElementById("move_copy_id").value = id;
        document.getElementById("move_copy_action").value = action;

        var moveCopyModal = new bootstrap.Modal(document.getElementById("moveCopyModal"));
        moveCopyModal.show();
    }

    function processMoveCopy() {
        let newPath = document.getElementById("new_path").value.trim();
        let action = document.getElementById("move_copy_action").value;
        let overrideExisting = document.getElementById("override_existing").checked ? 1 : 0;

        let url = (selectedType === 'folder')
            ? `/folders/${selectedId}/${action}`
            : `/files/${selectedId}/${action}`;

        $.ajax({
            url: url,
            type: "POST",
            data: {
                new_path: newPath,
                override_existing: overrideExisting
            },
            success: function(response) {
                Swal.fire({
                    title: "Success!",
                    text: response.message,
                    icon: "success",
                    timer: 1500,
                    showConfirmButton: false
                });

                setTimeout(() => location.reload(), 1500);
            },
            error: function(xhr) {
                Swal.fire({
                    title: "Error",
                    text: xhr.responseJSON?.message || "Something went wrong! Please try again.",
                    icon: "error"
                });
            }
        });
    }

    document.getElementById("moveCopySubmitBtn").addEventListener("click", function() {
        processMoveCopy();
    });

    function showInfo(type, id) {
        let url = (type === "folder") ? `/folders/${id}/info` : `/files/${id}/info`;

        $.ajax({
            url: url,
            type: "GET",
            success: function(response) {
                document.getElementById("offcanvasTitle").textContent = type === "folder" ? "Folder Info" : "File Info";
                document.getElementById("infoName").textContent = response.name;
                document.getElementById("infoType").textContent = type.charAt(0).toUpperCase() + type.slice(1);
                document.getElementById("infoSize").textContent = response.size;
                document.getElementById("infoCreated").textContent = response.created_at;
                document.getElementById("infoUpdated").textContent = response.updated_at;
                document.getElementById("infoPath").textContent = response.path || "Root";

                document.getElementById("infoIcon").src = response.file_icon;

                let deletedAtContainer = document.getElementById("infoDeletedContainer");
                let deletedAtField = document.getElementById("infoDeleted");

                if (response.deleted_at) {
                    if (!deletedAtContainer) {
                        let newField = document.createElement("p");
                        newField.id = "infoDeletedContainer";
                        newField.innerHTML = `<strong>Deleted At:</strong> <span id="infoDeleted">${response.deleted_at}</span>`;
                        document.getElementById("infoContainer").appendChild(newField);
                    } else {
                        deletedAtContainer.style.display = "block";
                        deletedAtField.textContent = response.deleted_at;
                    }
                } else if (deletedAtContainer) {
                    deletedAtContainer.style.display = "none";
                }

                var offcanvas = new bootstrap.Offcanvas(document.getElementById("offcanvasRight"));
                offcanvas.show();
            },
            error: function(xhr) {
                Swal.fire({
                    title: "Error",
                    text: "Could not fetch details. Please try again.",
                    icon: "error"
                });
            }
        });
    }


    function addFileInput() {
        let encryptionType = document.getElementById('encryption_type').value;
        let div = document.createElement('div');
        div.classList.add('row', 'file-upload');

        div.innerHTML = `
            <div class="col mb-3">
                <label class="form-label">Select File:</label>
                <input class="form-control" type="file" name="files[]" required>
            </div>
            <div class="col mb-3 multi-key ${encryptionType === 'multiple' ? '' : 'd-none'}">
                <label class="form-label">Encryption Key:</label>
                <input type="password" class="form-control" name="encryption_keys[]" placeholder="Encryption Key:">
            </div>
        `;

        document.getElementById('file_inputs').appendChild(div);
    }

    function toggleEncryptionFields() {
        let encryptionType = document.getElementById('encryption_type').value;
        let multiKeyFields = document.querySelectorAll('.multi-key');
        let singleKeyField = document.getElementById('single_key_field');

        if (encryptionType === "multiple") {
            multiKeyFields.forEach(div => div.classList.remove('d-none'));
            singleKeyField.classList.add('d-none');
        } else {
            multiKeyFields.forEach(div => div.classList.add('d-none'));
            singleKeyField.classList.remove('d-none');
        }
    }


    function showDownloadModal(fileId) {
        document.getElementById('downloadFileId').value = fileId;
        document.getElementById('decryption_key').value = '';
        var downloadModal = new bootstrap.Modal(document.getElementById('downloadModal'));
        downloadModal.show();
    }

    function decryptAndDownload_old() {
        let fileId = document.getElementById('downloadFileId').value;
        let decryptionKey = document.getElementById('decryption_key').value;

        if (!decryptionKey) {
            alert('Please enter a decryption key.');
            return;
        }

        window.location.href = `/files/${fileId}/download?key=${encodeURIComponent(decryptionKey)}`;
    }

    function showDownloadModal(fileId) {
        document.getElementById('downloadFileId').value = fileId;
        document.getElementById('decryption_key').value = '';
        var downloadModal = new bootstrap.Modal(document.getElementById('downloadModal'));
        downloadModal.show();
    }

    function decryptAndDownload() {
        let fileId = document.getElementById('downloadFileId').value;
        let decryptionKey = document.getElementById('decryption_key').value;

        if (!decryptionKey) {
            Swal.fire({
                icon: 'warning',
                title: 'Missing Decryption Key',
                text: 'Please enter a decryption key to proceed!',
                confirmButtonText: 'OK'
            });
            return;
        }

        Swal.fire({
            title: 'Decrypting...',
            text: 'Please wait while we decrypt your file.',
            allowOutsideClick: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        var downloadModal = bootstrap.Modal.getInstance(document.getElementById('downloadModal'));
        downloadModal.hide();

        setTimeout(() => {
            Swal.close();
            window.location.href = `/files/${fileId}/download?key=${encodeURIComponent(decryptionKey)}`;
        }, 1000);
    }


document.addEventListener("DOMContentLoaded", function () {
    const clipboard = new ClipboardJS('#copyButton');

    clipboard.on('success', function () {
        Swal.fire({
            icon: 'success',
            title: 'Copied!',
            text: 'Share link copied to clipboard.',
            confirmButtonText: 'OK'
        });
    });

    clipboard.on('error', function () {
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: 'Could not copy link.',
            confirmButtonText: 'OK'
        });
    });
});

function generateShareLink(fileId) {
    $.ajax({
        url: `/files/${fileId}/generate-share-link`,
        type: 'POST',
        success: function(data) {
            if (data.success) {
                let shareUrl = `${window.location.origin}/files/share/${data.token}`;

                $('#shareableLink').val(shareUrl);

                $('#telegramShare').attr('href', `https://t.me/share/url?url=${encodeURIComponent(shareUrl)}&text=Download this file securely`);
                $('#whatsappShare').attr('href', `https://api.whatsapp.com/send?text=Download this file securely: ${encodeURIComponent(shareUrl)}`);
                $('#smsShare').attr('href', `sms:?body=Download this file securely: ${encodeURIComponent(shareUrl)}`);

                var shareModal = new bootstrap.Modal(document.getElementById('shareModal'));
                shareModal.show();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Could not generate share link.',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Could not generate share link.',
                confirmButtonText: 'OK'
            });
        }
    });
}

function restoreFromRecycleBin(type, id) {
    Swal.fire({
        title: "Are you sure?",
        text: "Do you want to restore this " + type + "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, restore it!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/recycle-bin/restore/${type}/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Restored!", "The " + type + " has been restored.", "success");
                    document.getElementById(`${type}_${id}`).remove();
                } else {
                    Swal.fire("Error!", data.message, "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire("Error!", "Something went wrong!", "error");
            });
        }
    });
}

function deletePermanently(type, id) {
    Swal.fire({
        title: "Are you sure?",
        text: "This action cannot be undone. Do you really want to permanently delete this " + type + "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/recycle-bin/delete/${type}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire("Deleted!", "The " + type + " has been permanently deleted.", "success");
                    document.getElementById(`${type}_${id}`).remove();
                } else {
                    Swal.fire("Error!", data.message, "error");
                }
            })
            .catch(error => {
                console.error("Error:", error);
                Swal.fire("Error!", "Something went wrong!", "error");
            });
        }
    });
}
