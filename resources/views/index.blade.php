<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <title>Movie App - Explore</title>

    <style>
        body { background-color: #141414; color: #fff; min-height: 100vh; }
        .navbar { background-color: #000 !important; border-bottom: 1px solid #333; }
        .card { background-color: #1f1f1f; border: none; border-radius: 8px; overflow: hidden; transition: transform 0.2s; height: 100%; }
        .card:hover { transform: scale(1.03); }
        .card-img-top { height: 350px; object-fit: cover; background-color: #222; }
        .card-title { font-size: 14px; font-weight: bold; color: #fff; margin-bottom: 5px; }
        .card-subtitle { font-size: 12px; color: #aaa !important; }
        #loading, #no-result { display: none; text-align: center; padding: 40px; width: 100%; }
        .modal-content { background-color: #1f1f1f; color: #fff; border: 1px solid #444; }
        .modal-header { border-bottom: 1px solid #333; }
        .list-group-item { background-color: #2a2a2a; color: #fff; border-color: #333; font-size: 14px; }
        .btn-search, .btn-danger { background-color: #e50914; border: none; color: white; }
        .btn-search:hover, .btn-danger:hover { background-color: #b00610; color: white; }
        .btn-outline-warning:hover { color: #000; }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand font-weight-bold" href="/" style="color:#e50914; font-size:1.5rem;">MOVIEWEB</a>
            <div class="ml-auto d-flex align-items-center">
                <div class="dropdown mr-3">
                    <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-toggle="dropdown">
                        {{ strtoupper(App::getLocale()) }}
                        <div class="dropdown-menu dropdown-menu-right bg-dark shadow">
                            <a class="dropdown-item text-white {{ App::getLocale() == 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">English</a>
                            <a class="dropdown-item text-white {{ App::getLocale() == 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">Indonesia</a>
                        </div>
                    </button>
                    
                </div>

                <a href="{{ route('movie.favorites.list') }}" class="btn btn-link text-warning p-0 mr-3" style="font-size: 1.2rem; text-decoration:none;">⭐</a>
                
                <span class="text-white mr-3 d-none d-sm-inline">{{ __('messages.welcome') }}, {{ Auth::user()->name }}</span>
                
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
                </form>
            </div>

            
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="text-center mb-4 font-weight-bold">{{ __('messages.popular') }}</h1>
                <div class="input-group mb-5 shadow-sm">
                    <input id="search-input" type="text" class="form-control form-control-lg bg-dark text-white border-secondary"
                        placeholder="{{ __('messages.search_placeholder') }}">
                    <div class="input-group-append">
                        <button class="btn btn-search btn-lg px-4" type="button" id="search-button">{{ __('messages.search_button') }}</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="home-recommendation">
            <div class="mb-5">
                <h3 class="mb-4" style="border-left: 5px solid #e50914; padding-left: 15px;">🔥 {{ __('messages.popular') }}</h3>
                <div class="row" id="popular-movies">
                    <div class="col-12 text-center py-4"><div class="spinner-border text-danger"></div></div>
                </div>
            </div>

            <div class="mb-5">
                <h3 class="mb-4" style="border-left: 5px solid #e50914; padding-left: 15px;">✨ {{ __('messages.latest') }}</h3>
                <div class="row" id="new-movies">
                    <div class="col-12 text-center py-4"><div class="spinner-border text-danger"></div></div>
                </div>
            </div>
        </div>

        <div class="row" id="movie-list"></div>

        <div id="loading" class="py-5">
            <div class="spinner-border text-danger" role="status"></div>
            <p class="mt-2 text-muted">Loading movies...</p>
        </div>

        <div id="no-result">
            <h4 class="text-danger">Movie not found</h4>
            <p class="text-muted">Try another keyword.</p>
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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        let currentPage = 1;
        let currentQuery = '';
        let totalPages = 1;
        let isFetching = false;

        $(document).ready(function() {
            // Load Rekomendasi Awal
            fetchHomeMovies('Avengers', '#popular-movies'); 
            fetchHomeMovies('2024', '#new-movies');       

            // Event Search
            $('#search-button').on('click', () => startSearch());
            $('#search-input').on('keypress', (e) => { if(e.which == 13) startSearch(); });

            // Infinite Scroll Logic
            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 200) {
                    if (!isFetching && currentPage < totalPages && currentQuery !== '') {
                        loadMoreMovies();
                    }
                }
            });
        });

        function fetchHomeMovies(keyword, targetSelector) {
            $.ajax({
                url: "{{ route('movie.search') }}",
                type: 'GET',
                data: { s: keyword, page: 1 },
                success: function (res) {
                    if (res.Response === 'True') {
                        $(targetSelector).html(renderMovieCards(res.Search.slice(0, 4)));
                    }
                }
            });
        }

        function startSearch() {
            let query = $('#search-input').val().trim();
            if (query === '') return;

            currentQuery = query;
            currentPage = 1;
            $('#home-recommendation').hide();
            $('#movie-list').html('');
            $('#no-result').hide();
            
            loadMoreMovies();
        }

        function loadMoreMovies() {
            isFetching = true;
            $('#loading').show();

            $.ajax({
                url: "{{ route('movie.search') }}",
                type: 'GET',
                data: { s: currentQuery, page: currentPage },
                success: function (res) {
                    isFetching = false;
                    $('#loading').hide();
                    
                    if (res.Response === 'True') {
                        totalPages = Math.ceil(parseInt(res.totalResults) / 10);
                        $('#movie-list').append(renderMovieCards(res.Search));
                        currentPage++;
                    } else if (currentPage === 1) {
                        $('#no-result').show();
                    }
                },
                error: function() {
                    isFetching = false;
                    $('#loading').hide();
                }
            });
        }

        // Fungsi Render Card (Integrasi Lazy Load)
        function renderMovieCards(movies) {
            let html = '';
            $.each(movies, function (i, movie) {
                let poster = movie.Poster !== 'N/A' ? movie.Poster : 'https://via.placeholder.com/300x450?text=No+Poster';
                html += `
                    <div class="col-6 col-md-3 mb-4">
                        <div class="card shadow-sm h-100">
                            <img src="${poster}" class="card-img-top" loading="lazy">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-truncate">${movie.Title}</h5>
                                <h6 class="card-subtitle mb-3">${movie.Year}</h6>
                                <div class="d-flex mt-auto">
                                    <button class="btn btn-sm btn-danger see-detail flex-grow-1 mr-1" data-id="${movie.imdbID}">{{ __('messages.detail') }}</button>
                                    <button class="btn btn-sm btn-outline-warning add-favorite" 
                                        data-id="${movie.imdbID}" data-title="${movie.Title}" 
                                        data-year="${movie.Year}" data-poster="${poster}">⭐</button>
                                </div>
                            </div>
                        </div>
                    </div>`;
            });
            return html;
        }

        // Modal Detail
        $(document).on('click', '.see-detail', function () {
            let id = $(this).data('id');
            $('#modalTitle').text('Loading...');
            $('#modalBody').html('<div class="text-center py-5"><div class="spinner-border text-danger"></div></div>');
            $('#movieModal').modal('show');

            $.get("{{ route('movie.detail') }}", { id: id }, function (res) {
                if (res.Response === 'True') {
                    $('#modalTitle').text(res.Title);
                    $('#modalBody').html(`
                        <div class="row">
                            <div class="col-md-4"><img src="${res.Poster}" class="img-fluid rounded shadow" loading="lazy"></div>
                            <div class="col-md-8">
                                <ul class="list-group">
                                    <li class="list-group-item"><strong>Genre:</strong> ${res.Genre}</li>
                                    <li class="list-group-item"><strong>Plot:</strong> ${res.Plot}</li>
                                    <li class="list-group-item"><strong>Rating:</strong> ⭐ ${res.imdbRating}</li>
                                </ul>
                            </div>
                        </div>`);
                }
            });
        });

        // Add Favorite
        $(document).on('click', '.add-favorite', function() {
            let btn = $(this);
            let data = {
                imdbID: btn.data('id'),
                title: btn.data('title'),
                year: btn.data('year'),
                poster: btn.data('poster')
            };

            $.post("{{ route('movie.favorite') }}", data, function(res) {
                if(res.status === 'added') {
                    btn.addClass('btn-warning text-dark').removeClass('btn-outline-warning');
                    alert('Added to Favorites!');
                } else {
                    btn.addClass('btn-outline-warning').removeClass('btn-warning text-dark');
                    alert('Removed from Favorites!');
                }
            });
        });
    </script>
</body>
</html>