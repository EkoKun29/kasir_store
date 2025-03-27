<!-- Vendor JS Files -->
<script src="{{ asset ('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ asset ('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset ('assets/vendor/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset ('assets/vendor/echarts/echarts.min.js') }}"></script>
<script src="{{ asset ('assets/vendor/quill/quill.js') }}"></script>
<script src="{{ asset ('assets/vendor/simple-datatables/simple-datatables.js') }}"></script>
<script src="{{ asset ('assets/vendor/tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset ('assets/vendor/php-email-form/validate.js') }}"></script>

<!-- Template Main JS File -->
<script src="{{ asset ('assets/js/main.js') }}"></script>

<!-- Sweet alert-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Sukses!',
            text: '{{ session('success') }}',
            confirmButtonText: 'OK'
        });
    @endif
</script>
<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data ini akan dihapus secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Hapus',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>


