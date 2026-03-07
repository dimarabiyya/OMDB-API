<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSRF Token untuk AJAX Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <title>Movie App</title>

    <style>
        body { background-color: #141414; color: #fff; }
        .navbar { background-color: #000 !important; }
        .card { background-color: #1f1f1f; border: none; border-radius: 8px; overflow: hidden; transition: transform 0.2s; }
        .card:hover { transform: scale(1.03); }
        .card-img-top { height: 350px; object-fit: cover; }
        .card-title { font-size: 14px; font-weight: bold; color: #fff; }
        .card-subtitle { font-size: 12px; }
        h1 { color: #fff; }
        #loading { display: none; text-align: center; padding: 20px; }
        #no-result { display: none; text-align: center; padding: 20px; color: #aaa; }
        .modal-content { background-color: #1f1f1f; color: #fff; }
        .modal-header { border-bottom: 1px solid #333; }
        .modal-footer { border-top: 1px solid #333; }
        .list-group-item { background-color: #2a2a2a; color: #fff; border-color: #333; font-size: 14px; }
        .input-group .form-control { background-color: #2a2a2a; border-color: #444; color: #fff; }
        .input-group .form-control::placeholder { color: #888; }
        .input-group .form-control:focus { background-color: #2a2a2a; color: #fff; border-color: #e50914; box-shadow: none; }
        .btn-search { background-color: #e50914; border: none; color: #fff; }
        .btn-search:hover { background-color: #b00610; color: #fff; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="#" style="color:#e50914; font-size:1.5rem;">Movie Web</a>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-4">Cari Film Favoritmu</h1>
                <div class="input-group mb-3">
                    <input id="search-input" type="text" class="form-control form-control-lg"
                        placeholder="Ketik judul film...">
                    <div class="input-group-append">
                        <button class="btn btn-search btn-lg px-4" type="button" id="search-button">Cari</button>
                    </div>
                </div>
            </div>
        </div>

        <hr style="border-color:#333;">

        <div class="row" id="movie-list"></div>

        <div id="loading">
            <div class="spinner-border text-danger" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Memuat film...</p>
        </div>

        <div id="no-result">
            <h4>Film tidak ditemukan</h4>
            <p>Coba cari dengan judul yang berbeda.</p>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            
        </div>
    </div>

    <!-- Modal Detail Film -->
    <div class="modal fade" id="movieModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Detail Film</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-danger"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        // ✅ Setup CSRF token untuk semua AJAX request ke Laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ─── State ────────────────────────────────────────────────────────────
        let currentPage   = 1;
        let isLoading     = false;
        let hasMorePages  = true;
        let currentQuery  = '';

        // ─── Event: Tombol Cari & Enter ───────────────────────────────────────
        $('#search-button').on('click', function () {
            startNewSearch();
        });

        $('#search-input').on('keyup', function (e) {
            if (e.keyCode === 13) startNewSearch();
        });

        // ─── Event: Klik "Lihat Detail" ───────────────────────────────────────
        $('#movie-list').on('click', '.see-detail', function () {
            let id = $(this).data('id');

            $('#modalTitle').text('Memuat...');
            $('#modalBody').html('<div class="text-center py-4"><div class="spinner-border text-danger"></div></div>');
            $('#movieModal').modal('show');

            $.ajax({
                url: '/movie/detail',   
                type: 'GET',
                data: { id: id },
                success: function (result) {
                    if (result.Response === 'True') {
                        $('#modalTitle').text(result.Title);
                        $('#modalBody').html(`
                            <div class="container-fluid">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <img src="${result.Poster !== 'N/A' ? result.Poster : 'https://via.placeholder.com/300x450?text=No+Poster'}"
                                            class="img-fluid rounded" style="width:100%;">
                                    </div>
                                    <div class="col-md-8">
                                        <ul class="list-group list-group-flush">
                                            <li class="list-group-item"><strong>Rating IMDb</strong><br>${result.imdbRating}</li>
                                            <li class="list-group-item"><strong>Genre</strong><br>${result.Genre}</li>
                                            <li class="list-group-item"><strong>Rilis</strong><br>${result.Released}</li>
                                            <li class="list-group-item"><strong>Sutradara</strong><br>${result.Director}</li>
                                            <li class="list-group-item"><strong>Pemain</strong><br>${result.Actors}</li>
                                            <li class="list-group-item"><strong>Sinopsis</strong><br>${result.Plot}</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        `);
                    } else {
                        $('#modalBody').html('<p class="text-center text-muted">Detail tidak ditemukan.</p>');
                    }
                },
                error: function () {
                    $('#modalBody').html('<p class="text-center text-danger">Gagal memuat detail film.</p>');
                }
            });
        });

        // ─── Infinite Scroll ──────────────────────────────────────────────────
        $(window).on('scroll', function () {
            let scrollBottom = $(window).scrollTop() + $(window).height();
            let docHeight    = $(document).height();

            // Trigger 200px sebelum bawah halaman
            if (scrollBottom >= docHeight - 200 && !isLoading && hasMorePages && currentQuery !== '') {
                fetchMovies();
            }
        });

        // ─── Fungsi: Mulai Pencarian Baru ─────────────────────────────────────
        function startNewSearch() {
            let query = $('#search-input').val().trim();
            if (query === '') return;

            currentQuery  = query;
            currentPage   = 1;
            hasMorePages  = true;

            $('#movie-list').html('');
            $('#no-result').hide();

            fetchMovies();
        }

        // ─── Fungsi: Fetch Film dari Laravel Backend ──────────────────────────
        function fetchMovies() {
            if (isLoading || !hasMorePages) return;

            isLoading = true;
            $('#loading').show();

            // ✅ Panggil route Laravel, bukan OMDB langsung
            $.ajax({
                url: '/movie/search',       // sesuaikan dengan route Laravel kamu
                type: 'GET',
                dataType: 'json',
                data: {
                    s: currentQuery,
                    page: currentPage
                },
                success: function (result) {
                    $('#loading').hide();
                    isLoading = false;

                    if (result.Response === 'True') {
                        let movies = result.Search;
                        let totalResults = parseInt(result.totalResults);
                        let loadedSoFar  = (currentPage - 1) * 10 + movies.length;

                        // Cek apakah masih ada halaman selanjutnya
                        hasMorePages = loadedSoFar < totalResults;

                        $.each(movies, function (i, data) {
                            let poster = data.Poster !== 'N/A'
                                ? data.Poster
                                : 'https://via.placeholder.com/300x450?text=No+Poster';

                            $('#movie-list').append(`
                                <div class="col-6 col-md-3 mb-4">
                                    <div class="card h-100">
                                        <img src="${poster}" class="card-img-top" loading="lazy"
                                            onerror="this.src='https://via.placeholder.com/300x450?text=No+Poster'">
                                        <div class="card-body d-flex flex-column">
                                            <h5 class="card-title">${data.Title}</h5>
                                            <h6 class="card-subtitle mb-2 text-muted">${data.Year}</h6>
                                            <div class="mt-auto pt-2">
                                                <button class="btn btn-sm btn-danger see-detail w-100"
                                                    data-toggle="modal"
                                                    data-target="#movieModal"
                                                    data-id="${data.imdbID}">
                                                    Lihat Detail
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `);
                        });

                        currentPage++;

                    } else {
                        // Tidak ada hasil sama sekali (hanya pada page 1)
                        if (currentPage === 1) {
                            $('#no-result').show();
                        }
                        hasMorePages = false;
                    }
                },
                error: function (xhr) {
                    $('#loading').hide();
                    isLoading = false;
                    console.error('Error:', xhr.responseText);
                    alert('Terjadi kesalahan. Cek console untuk detail.');
                }
            });
        }
    </script>
</body>
</html>