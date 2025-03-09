@if (session('download_link'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: "Download Encryption Keys?",
                text: "For security reasons, the encryption key file will be deleted after this action.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Download",
                cancelButtonText: "No, Delete it",
                allowOutsideClick: false
            }).then((result) => {
                let deleteUrl = "{{ route('temp-files.delete', ['file' => basename(session('download_link'))]) }}";

                if (result.isConfirmed) {
                    window.location.href = "{{ session('download_link') }}";
                } else {
                    fetch(deleteUrl, { method: 'DELETE' }).then(() => location.reload());
                }
            });
        });
    </script>
@endif
