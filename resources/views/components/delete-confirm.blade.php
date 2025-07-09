@push('scripts')
    <script>
        $(".delete-btn").on("click", function(e) {
            const btn = $(this)
            e.preventDefault();

            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Data yang sudah terhapus tidak akan bisa dikembalikan lagi!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Tidak, kembali!",
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.parent().submit()
                }
            })
        })

        $(".confirm-btn").on("click", function(e) {
            const btn = $(this)
            e.preventDefault();

            Swal.fire({
                title: "Apakah Anda Yakin?",
                text: "Perubahan yang dilakukan di sini tidak bisa dibatalkan",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, lanjutkan!",
                cancelButtonText: "Tidak, kembali!",
            }).then((result) => {
                if (result.isConfirmed) {
                    btn.parent().submit()
                }
            })
        })
    </script>
@endpush
