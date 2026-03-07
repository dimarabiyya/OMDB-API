<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>Favorit Saya - Movie App</title>

    <style>
        body { background-color: #141414; color: #fff; min-height: 100vh; }
        .navbar { background-color: #000 !important; border-bottom: 1px solid #333; }
        .card { background-color: #1f1f1f; border: none; border-radius: 8px; overflow: hidden; height: 100%; }
        .card-img-top { height: 350px; object-fit: cover; }
        .card-title { font-size: 14px; font-weight: bold; color: #fff; }
        .btn-search, .btn-danger { background-color: #e50914; border: none; }
        .modal-content { background-color: #1f1f1f; color: #fff; border: 1px solid #444; }
        .modal-header { border-bottom: 1px solid #333; }
        .list-group-item { background-color: #2a2a2a; color: #fff; border-color: #333; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="/" style="color:#e50914; font-size:1.5rem;">MOVIEWEB</a>
            <div class="ml-auto d-flex align-items-center">
                <a href="/" class="btn btn-outline-light btn-sm mr-2">Kembali ke Pencarian</a>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">❤ Film Favorit Saya</h2>
        <hr style="border-color:#333;">

        <div class="row" id="favorite-container">
            @forelse($favorites as $fav)
                <div class="col-6 col-md-3 mb-4 item-fav-{{ $fav->imdbID }}">
                    <div class="card">
                        <img src="{{ $fav->poster }}" class="card-img-top" onerror="this.src='https://via.placeholder.com/300x450?text=No+Poster'">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $fav->title }}</h5>
                            <h6 class="card-subtitle mb-3 text-muted">{{ $fav->year }}</h6>
                            <div class="mt-auto">
                                <button class="btn btn-sm btn-primary btn-block see-detail mb-2" data-id="{{ $fav->imdbID }}">Detail</button>
                                <button class="btn btn-sm btn-danger btn-block btn-fav-remove" data-id="{{ $fav->imdbID }}">Hapus</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Belum ada film favorit.</p>
                    <a href="/" class="btn btn-danger">Cari Film Sekarang</a>
                </div>
            @endforelse
        </div>
    </div>

    <div class="modal fade" id="movieModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Detail Film</h5>
                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modalBody"></div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        // Logic Hapus Favorit
        $(document).on('click', '.btn-fav-remove', function() {
            let imdbID = $(this).data('id');
            if(confirm('Hapus film ini dari favorit?')) {
                $.post("{{ route('movie.favorite') }}", { imdbID: imdbID }, function(res) {
                    $(`.item-fav-${imdbID}`).fadeOut(300, function() {
                        $(this).remove();
                        if ($('#favorite-container').children(':visible').length === 0) {
                            location.reload(); // Reload untuk munculkan pesan "Belum ada favorit"
                        }
                    });
                });
            }
        });

        // Logic Detail (Sama seperti di Index)
        $(document).on('click', '.see-detail', function () {
            let id = $(this).data('id');
            $('#modalTitle').text('Memuat...');
            $('#modalBody').html('<div class="text-center py-5"><div class="spinner-border text-danger"></div></div>');
            $('#movieModal').modal('show');

            $.ajax({
                url: "{{ route('movie.detail') }}",
                type: 'GET',
                data: { id: id },
                success: function (res) {
                    if (res.Response === 'True') {
                        $('#modalTitle').text(res.Title);
                        $('#modalBody').html(`
                            <div class="row">
                                <div class="col-md-4"><img src="${res.Poster}" class="img-fluid rounded mb-3"></div>
                                <div class="col-md-8">
                                    <ul class="list-group">
                                        <li class="list-group-item"><strong>Genre:</strong> ${res.Genre}</li>
                                        <li class="list-group-item"><strong>Plot:</strong> ${res.Plot}</li>
                                        <li class="list-group-item"><strong>Rating:</strong> ⭐ ${res.imdbRating}</li>
                                    </ul>
                                </div>
                            </div>`);
                    }
                }
            });
        });
    </script>
</body>
</html>