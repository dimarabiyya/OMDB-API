<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>MOVIEWEB — Explore</title>

    <style>
        /* ── Variables ─────────────────────────────────── */
        :root {
            --red:        #e50914;
            --red-dim:    #7a050b;
            --bg:         #0a0a0c;
            --surface:    #111115;
            --surface2:   #18181e;
            --border:     rgba(255,255,255,0.07);
            --text:       #f0f0f0;
            --muted:      #6b6b7a;
            --gold:       #f5c518;
            --font-title: 'Bebas Neue', sans-serif;
            --font-body:  'DM Sans', sans-serif;
        }

        /* ── Reset & Base ──────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-body);
            font-size: 15px;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Grain Overlay ────────────────────────────── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='300' height='300'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='300' height='300' filter='url(%23n)' opacity='0.035'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1000;
            opacity: .5;
        }

        /* ── Navbar ───────────────────────────────────── */
        .navbar {
            background: linear-gradient(180deg, rgba(0,0,0,.95) 0%, rgba(0,0,0,0) 100%) !important;
            border: none !important;
            padding: 18px 0;
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 900;
            transition: background .3s;
        }
        .navbar.scrolled { background: rgba(0,0,0,.98) !important; }

        .navbar-brand {
            font-family: var(--font-title);
            font-size: 2rem !important;
            letter-spacing: 3px;
            color: var(--red) !important;
            text-shadow: 0 0 30px rgba(229,9,20,.4);
        }

        .nav-btn {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text) !important;
            font-family: var(--font-body);
            font-size: 12px;
            font-weight: 500;
            letter-spacing: 1px;
            padding: 6px 16px;
            border-radius: 4px;
            transition: all .2s;
            text-decoration: none;
        }
        .nav-btn:hover { border-color: rgba(255,255,255,.3); background: rgba(255,255,255,.07); color: #fff !important; text-decoration: none; }
        .nav-btn.fav-btn { color: var(--gold) !important; border-color: rgba(245,197,24,.2); font-size: 15px; padding: 5px 12px; }
        .nav-btn.fav-btn:hover { background: rgba(245,197,24,.08); border-color: var(--gold); }

        .user-name {
            font-size: 13px;
            color: var(--muted);
            font-weight: 500;
        }

        .lang-btn {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--muted);
            font-size: 11px;
            letter-spacing: 1.5px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: all .2s;
        }
        .lang-btn:hover { color: var(--text); border-color: rgba(255,255,255,.2); }
        .dropdown-menu { background: #18181e; border: 1px solid var(--border); border-radius: 8px; box-shadow: 0 20px 60px rgba(0,0,0,.8); }
        .dropdown-item { color: var(--muted) !important; font-size: 13px; padding: 9px 18px; transition: all .15s; }
        .dropdown-item:hover, .dropdown-item.active { color: var(--text) !important; background: rgba(255,255,255,.06) !important; }

        /* ── Hero Search Section ──────────────────────── */
        .hero-section {
            padding: 160px 0 80px;
            position: relative;
            text-align: center;
        }

        .hero-eyebrow {
            font-size: 11px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--red);
            font-weight: 600;
            margin-bottom: 16px;
            opacity: 0;
            animation: fadeUp .6s .1s forwards;
        }

        .hero-title {
            font-family: var(--font-title);
            font-size: clamp(3.5rem, 8vw, 7rem);
            line-height: .95;
            letter-spacing: 2px;
            color: var(--text);
            margin-bottom: 12px;
            opacity: 0;
            animation: fadeUp .7s .2s forwards;
        }
        .hero-title span { color: var(--red); }

        .hero-sub {
            font-size: 14px;
            color: var(--muted);
            font-weight: 300;
            margin-bottom: 44px;
            opacity: 0;
            animation: fadeUp .7s .35s forwards;
        }

        /* ── Search Bar ───────────────────────────────── */
        .search-wrap {
            max-width: 620px;
            margin: 0 auto;
            opacity: 0;
            animation: fadeUp .7s .45s forwards;
        }

        .search-box {
            display: flex;
            align-items: center;
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 50px;
            padding: 6px 6px 6px 24px;
            transition: border-color .25s, box-shadow .25s;
        }
        .search-box:focus-within {
            border-color: rgba(229,9,20,.5);
            box-shadow: 0 0 0 3px rgba(229,9,20,.12), 0 8px 40px rgba(229,9,20,.1);
        }

        .search-box input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: var(--text);
            font-family: var(--font-body);
            font-size: 15px;
            font-weight: 400;
        }
        .search-box input::placeholder { color: var(--muted); }

        .search-btn {
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 40px;
            padding: 11px 28px;
            font-family: var(--font-body);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .5px;
            cursor: pointer;
            transition: background .2s, transform .15s, box-shadow .2s;
            flex-shrink: 0;
        }
        .search-btn:hover { background: #ff1a27; transform: scale(1.03); box-shadow: 0 4px 20px rgba(229,9,20,.4); }
        .search-btn:active { transform: scale(.98); }

        /* ── Section Headings ─────────────────────────── */
        .section-header {
            display: flex;
            align-items: baseline;
            gap: 14px;
            margin-bottom: 28px;
        }

        .section-title {
            font-family: var(--font-title);
            font-size: 1.85rem;
            letter-spacing: 1.5px;
            color: var(--text);
        }

        .section-accent {
            width: 4px;
            height: 28px;
            background: var(--red);
            border-radius: 2px;
            display: inline-block;
            flex-shrink: 0;
            box-shadow: 0 0 12px rgba(229,9,20,.5);
        }

        .section-badge {
            font-size: 10px;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--muted);
            font-weight: 600;
            margin-left: 4px;
        }

        /* ── Movie Card ───────────────────────────────── */
        .movie-col { padding: 8px; }

        .movie-card {
            background: var(--surface);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            transition: transform .28s cubic-bezier(.34,1.56,.64,1), box-shadow .28s;
            height: 100%;
            border: 1px solid var(--border);
        }
        .movie-card:hover {
            transform: translateY(-6px) scale(1.02);
            box-shadow: 0 24px 60px rgba(0,0,0,.7), 0 0 0 1px rgba(229,9,20,.2);
        }

        .movie-poster-wrap {
            position: relative;
            overflow: hidden;
            background: var(--surface2);
        }

        .movie-poster {
            width: 100%;
            height: 300px;
            object-fit: cover;
            display: block;
            transition: transform .4s ease;
        }
        .movie-card:hover .movie-poster { transform: scale(1.06); }

        .movie-poster-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(0deg, rgba(0,0,0,.9) 0%, rgba(0,0,0,0) 55%);
            opacity: 0;
            transition: opacity .3s;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            padding-bottom: 16px;
            gap: 8px;
        }
        .movie-card:hover .movie-poster-overlay { opacity: 1; }

        .overlay-btn {
            border: none;
            border-radius: 6px;
            font-family: var(--font-body);
            font-size: 12px;
            font-weight: 600;
            padding: 8px 14px;
            cursor: pointer;
            transition: all .15s;
            transform: translateY(8px);
            opacity: 0;
        }
        .movie-card:hover .overlay-btn { transform: translateY(0); opacity: 1; }
        .overlay-btn:nth-child(2) { transition-delay: .05s; }
        .overlay-btn.btn-detail { background: var(--red); color: #fff; }
        .overlay-btn.btn-detail:hover { background: #ff1a27; }
        .overlay-btn.btn-fav { background: rgba(255,255,255,.12); color: var(--gold); backdrop-filter: blur(4px); }
        .overlay-btn.btn-fav:hover { background: rgba(245,197,24,.15); }
        .overlay-btn.btn-fav.is-fav { color: var(--gold); background: rgba(245,197,24,.2); }

        .movie-info {
            padding: 14px 14px 16px;
        }

        .movie-title-text {
            font-family: var(--font-body);
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .movie-year-text {
            font-size: 12px;
            color: var(--muted);
            font-weight: 400;
        }

        /* ── Skeleton Loader ──────────────────────────── */
        .skeleton {
            background: linear-gradient(90deg, var(--surface) 25%, var(--surface2) 50%, var(--surface) 75%);
            background-size: 200% 100%;
            animation: shimmer 1.4s infinite;
            border-radius: 10px;
            border: 1px solid var(--border);
        }
        .skeleton-poster { height: 300px; margin-bottom: 12px; }
        .skeleton-line { height: 12px; border-radius: 6px; margin-bottom: 8px; }
        .skeleton-line.short { width: 50%; }

        @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

        /* ── Loading / No Result ──────────────────────── */
        #loading { display: none; padding: 60px 0; }
        #loading .spinner-border { width: 2.5rem; height: 2.5rem; border-width: 3px; color: var(--red); }

        #no-result { display: none; padding: 80px 20px; text-align: center; }
        #no-result .nr-icon { font-size: 3rem; margin-bottom: 16px; opacity: .4; }
        #no-result h4 { font-family: var(--font-title); font-size: 1.8rem; letter-spacing: 1px; color: var(--text); margin-bottom: 8px; }
        #no-result p { color: var(--muted); font-size: 14px; }

        /* ── Divider ──────────────────────────────────── */
        .section-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 20px 0 50px;
        }

        /* ── Modal ────────────────────────────────────── */
        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 14px;
            overflow: hidden;
        }
        .modal-header {
            background: var(--surface2);
            border-bottom: 1px solid var(--border);
            padding: 18px 24px;
        }
        .modal-title { font-family: var(--font-title); font-size: 1.6rem; letter-spacing: 1px; color: var(--text); }
        .modal-body { padding: 24px; }
        .close { color: var(--muted) !important; font-size: 1.6rem; opacity: 1; transition: color .2s; }
        .close:hover { color: var(--text) !important; }

        .detail-poster { width: 100%; border-radius: 8px; box-shadow: 0 12px 40px rgba(0,0,0,.6); }

        .detail-list { list-style: none; padding: 0; margin: 0; }
        .detail-list li {
            padding: 12px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13.5px;
            color: var(--muted);
            line-height: 1.6;
        }
        .detail-list li:last-child { border-bottom: none; }
        .detail-list li strong { color: var(--text); display: block; font-size: 11px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 3px; }

        .rating-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(245,197,24,.1);
            border: 1px solid rgba(245,197,24,.25);
            color: var(--gold);
            font-weight: 700;
            font-size: 15px;
            padding: 4px 12px;
            border-radius: 6px;
        }

        /* ── Animations ───────────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .card-appear {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp .5s forwards;
        }

        /* ── Scrollbar ────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #2a2a35; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #3a3a4a; }

        /* ── Footer spacer ────────────────────────────── */
        .page-footer { padding: 60px 0 40px; text-align: center; color: var(--muted); font-size: 12px; }
    </style>
</head>

<body>
    <!-- ── Navbar ──────────────────────────────────────────── -->
    <nav class="navbar navbar-expand-lg navbar-dark" id="mainNav">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/">MOVIEWEB</a>

            <div class="d-flex align-items-center" style="gap:12px;">
                <!-- Language -->
                <div class="dropdown">
                    <button class="lang-btn dropdown-toggle" data-toggle="dropdown">
                        {{ strtoupper(App::getLocale()) }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item {{ App::getLocale() == 'en' ? 'active' : '' }}" href="{{ route('lang.switch', 'en') }}">🇺🇸 English</a>
                        <a class="dropdown-item {{ App::getLocale() == 'id' ? 'active' : '' }}" href="{{ route('lang.switch', 'id') }}">🇮🇩 Indonesia</a>
                    </div>
                </div>

                <!-- Favorites -->
                <a href="{{ route('movie.favorites.list') }}" class="nav-btn fav-btn">★ Favorites</a>

                <!-- User -->
                <span class="user-name d-none d-sm-inline">{{ Auth::user()->name }}</span>

                <!-- Logout -->
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- ── Hero / Search ───────────────────────────────────── -->
    <section class="hero-section">
        <p class="hero-eyebrow">MILLIONS OF MOVIES AT YOUR FINGERTIPS</p>
        <h1 class="hero-title">DISCOVER<br><span>YOUR NEXT</span><br>OBSESSION</h1>
        <p class="hero-sub">Search any film, explore recommendations, save your favorites.</p>

        <div class="search-wrap">
            <div class="search-box">
                <input id="search-input" type="text" placeholder="{{ __('messages.search_placeholder') ?? 'Search movies, directors, genres...' }}" autocomplete="off">
                <button class="search-btn" id="search-button">{{ __('messages.search_button') ?? 'SEARCH' }}</button>
            </div>
        </div>
    </section>

    <!-- ── Main Content ────────────────────────────────────── -->
    <div class="container pb-5">

        <!-- Home Recommendations -->
        <div id="home-recommendation">

            <!-- Popular -->
            <div class="mb-5">
                <div class="section-header">
                    <span class="section-accent"></span>
                    <h2 class="section-title">🔥 {{ __('messages.popular') ?? 'POPULAR NOW' }}</h2>
                    <span class="section-badge">TOP PICKS</span>
                </div>
                <div class="row" id="popular-movies">
                    <!-- skeleton -->
                    <div class="col-6 col-md-3 movie-col" id="sk1"><div class="skeleton skeleton-poster"></div><div class="skeleton skeleton-line"></div><div class="skeleton skeleton-line short"></div></div>
                    <div class="col-6 col-md-3 movie-col" id="sk2"><div class="skeleton skeleton-poster"></div><div class="skeleton skeleton-line"></div><div class="skeleton skeleton-line short"></div></div>
                    <div class="col-6 col-md-3 movie-col d-none d-md-block" id="sk3"><div class="skeleton skeleton-poster"></div><div class="skeleton skeleton-line"></div><div class="skeleton skeleton-line short"></div></div>
                    <div class="col-6 col-md-3 movie-col d-none d-md-block" id="sk4"><div class="skeleton skeleton-poster"></div><div class="skeleton skeleton-line"></div><div class="skeleton skeleton-line short"></div></div>
                </div>
            </div>

            <hr class="section-divider">

            <!-- Latest -->
            <div class="mb-5">
                <div class="section-header">
                    <span class="section-accent"></span>
                    <h2 class="section-title">✨ {{ __('messages.latest') ?? 'LATEST RELEASES' }}</h2>
                    <span class="section-badge">NEW</span>
                </div>
                <div class="row" id="new-movies">
                    <div class="col-6 col-md-3 movie-col"><div class="skeleton skeleton-poster"></div><div class="skeleton skeleton-line"></div><div class="skeleton skeleton-line short"></div></div>
                    <div class="col-6 col-md-3 movie-col"><div class="skeleton skeleton-poster"></div><div class="skeleton skeleton-line"></div><div class="skeleton skeleton-line short"></div></div>
                    <div class="col-6 col-md-3 movie-col d-none d-md-block"><div class="skeleton skeleton-poster"></div><div class="skeleton skeleton-line"></div><div class="skeleton skeleton-line short"></div></div>
                    <div class="col-6 col-md-3 movie-col d-none d-md-block"><div class="skeleton skeleton-poster"></div><div class="skeleton skeleton-line"></div><div class="skeleton skeleton-line short"></div></div>
                </div>
            </div>

            <hr class="section-divider">
        </div>

        <!-- Search Results -->
        <div id="search-section" style="display:none;">
            <div class="section-header mb-4">
                <span class="section-accent"></span>
                <h2 class="section-title" id="search-section-title">RESULTS FOR "..."</h2>
            </div>
        </div>
        <div class="row" id="movie-list"></div>

        <!-- Loading -->
        <div id="loading" class="text-center">
            <div class="spinner-border" role="status"></div>
            <p class="mt-3 text-muted" style="font-size:13px; letter-spacing:1px;">LOADING MOVIES...</p>
        </div>

        <!-- No Result -->
        <div id="no-result">
            <div class="nr-icon">🎬</div>
            <h4>No Results Found</h4>
            <p>We couldn't find anything for that search. Try a different title.</p>
        </div>
    </div>

    <!-- ── Footer ──────────────────────────────────────────── -->
    <div class="page-footer">
        <p>MOVIEWEB &mdash; Powered by OMDB API &nbsp;·&nbsp; &copy; {{ date('Y') }}</p>
        <p>Dimar Abiyya</p>
    </div>

    <!-- ── Movie Detail Modal ──────────────────────────────── -->
    <div class="modal fade" id="movieModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">—</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body" id="modalBody">
                    <div class="text-center py-5">
                        <div class="spinner-border" style="color:var(--red);"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Scripts ─────────────────────────────────────────── -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

    <script>
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        let currentPage  = 1;
        let currentQuery = '';
        let totalPages   = 1;
        let isFetching   = false;

        // ── Navbar scroll effect ─────────────────────────────
        $(window).scroll(function() {
            if ($(this).scrollTop() > 60) $('#mainNav').addClass('scrolled');
            else $('#mainNav').removeClass('scrolled');
        });

        // ── Init ─────────────────────────────────────────────
        $(document).ready(function() {
            fetchHomeMovies('Avengers', '#popular-movies');
            fetchHomeMovies('2024',     '#new-movies');

            $('#search-button').on('click',  () => startSearch());
            $('#search-input').on('keypress', e => { if (e.which == 13) startSearch(); });

            $(window).scroll(function() {
                if ($(window).scrollTop() + $(window).height() > $(document).height() - 300) {
                    if (!isFetching && currentPage <= totalPages && currentQuery !== '') {
                        loadMoreMovies();
                    }
                }
            });
        });

        // ── Fetch home section movies ────────────────────────
        function fetchHomeMovies(keyword, target) {
            $.ajax({
                url: "{{ route('movie.search') }}",
                data: { s: keyword, page: 1 },
                success: function(res) {
                    if (res.Response === 'True') {
                        $(target).html(renderCards(res.Search.slice(0, 4), true));
                    } else {
                        $(target).html('');
                    }
                }
            });
        }

        // ── Start a new search ───────────────────────────────
        function startSearch() {
            let q = $('#search-input').val().trim();
            if (!q) return;

            currentQuery = q;
            currentPage  = 1;
            totalPages   = 1;

            $('#home-recommendation').hide();
            $('#search-section').show();
            $('#search-section-title').text('RESULTS FOR "' + q.toUpperCase() + '"');
            $('#movie-list').html('');
            $('#no-result').hide();

            loadMoreMovies();
        }

        // ── Load next page ───────────────────────────────────
        function loadMoreMovies() {
            if (isFetching) return;
            isFetching = true;
            $('#loading').show();

            $.ajax({
                url:  "{{ route('movie.search') }}",
                data: { s: currentQuery, page: currentPage },
                success: function(res) {
                    isFetching = false;
                    $('#loading').hide();

                    if (res.Response === 'True') {
                        totalPages = Math.ceil(parseInt(res.totalResults) / 10);
                        $('#movie-list').append(renderCards(res.Search, false));
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

        // ── Render card HTML ─────────────────────────────────
        function renderCards(movies, stagger) {
            let html = '';
            $.each(movies, function(i, m) {
                let poster = m.Poster !== 'N/A' ? m.Poster : 'https://via.placeholder.com/300x450/111115/6b6b7a?text=NO+POSTER';
                let delay  = stagger ? (i * 80) : 0;
                html += `
                <div class="col-6 col-md-3 movie-col">
                    <div class="movie-card card-appear" style="animation-delay:${delay}ms;">
                        <div class="movie-poster-wrap">
                            <img src="${poster}" class="movie-poster" loading="lazy"
                                onerror="this.src='https://via.placeholder.com/300x450/111115/6b6b7a?text=NO+POSTER'">
                            <div class="movie-poster-overlay">
                                <button class="overlay-btn btn-detail see-detail" data-id="${m.imdbID}" data-toggle="modal" data-target="#movieModal">
                                    ▶ Detail
                                </button>
                                <button class="overlay-btn btn-fav add-favorite"
                                    data-id="${m.imdbID}" data-title="${m.Title}"
                                    data-year="${m.Year}" data-poster="${poster}">
                                    ★
                                </button>
                            </div>
                        </div>
                        <div class="movie-info">
                            <p class="movie-title-text" title="${m.Title}">${m.Title}</p>
                            <p class="movie-year-text">${m.Year}</p>
                        </div>
                    </div>
                </div>`;
            });
            return html;
        }

        // ── Movie Detail Modal ───────────────────────────────
        $(document).on('click', '.see-detail', function() {
            let id = $(this).data('id');
            $('#modalTitle').text('Loading...');
            $('#modalBody').html('<div class="text-center py-5"><div class="spinner-border" style="color:var(--red);"></div></div>');
            $('#movieModal').modal('show');

            $.get("{{ route('movie.detail') }}", { id: id }, function(res) {
                if (res.Response !== 'True') return;
                let poster = res.Poster !== 'N/A' ? res.Poster : 'https://via.placeholder.com/300x450/111115/6b6b7a?text=NO+POSTER';
                $('#modalTitle').text(res.Title);
                $('#modalBody').html(`
                    <div class="row">
                        <div class="col-md-4 mb-4 mb-md-0">
                            <img src="${poster}" class="detail-poster" loading="lazy">
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <span class="rating-badge">★ ${res.imdbRating} <small style="color:var(--muted);font-weight:400;font-size:11px;">/ 10</small></span>
                            </div>
                            <ul class="detail-list">
                                <li><strong>Genre</strong>${res.Genre}</li>
                                <li><strong>Director</strong>${res.Director}</li>
                                <li><strong>Cast</strong>${res.Actors}</li>
                                <li><strong>Released</strong>${res.Released} &nbsp;·&nbsp; ${res.Runtime}</li>
                                <li><strong>Plot</strong>${res.Plot}</li>
                            </ul>
                        </div>
                    </div>
                `);
            });
        });

        // ── Add Favorite ─────────────────────────────────────
        $(document).on('click', '.add-favorite', function() {
            let btn  = $(this);
            let data = {
                imdbID: btn.data('id'),
                title:  btn.data('title'),
                year:   btn.data('year'),
                poster: btn.data('poster')
            };
            $.post("{{ route('movie.favorite') }}", data, function(res) {
                if (res.status === 'added') {
                    btn.addClass('is-fav').html('★');
                } else {
                    btn.removeClass('is-fav').html('☆');
                }
            });
        });
    </script>
</body>
</html>