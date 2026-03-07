<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>My Favorites — MOVIEWEB</title>

    <style>
        /* ── Variables ─────────────────────────────────── */
        :root {
            --red:      #e50914;
            --bg:       #0a0a0c;
            --surface:  #111115;
            --surface2: #18181e;
            --border:   rgba(255,255,255,0.07);
            --text:     #f0f0f0;
            --muted:    #6b6b7a;
            --gold:     #f5c518;
            --font-title: 'Bebas Neue', sans-serif;
            --font-body:  'DM Sans', sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }

        body {
            background: var(--bg);
            color: var(--text);
            font-family: var(--font-body);
            font-size: 15px;
            min-height: 100vh;
        }

        /* ── Grain ────────────────────────────────────── */
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
            background: rgba(0,0,0,.95) !important;
            border-bottom: 1px solid var(--border) !important;
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 900;
        }

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
            cursor: pointer;
        }
        .nav-btn:hover { border-color: rgba(255,255,255,.3); background: rgba(255,255,255,.07); color: #fff !important; text-decoration: none; }

        /* ── Page Header ──────────────────────────────── */
        .page-header {
            padding: 60px 0 50px;
            position: relative;
            overflow: hidden;
        }

        /* Subtle red glow top-left */
        .page-header::before {
            content: '';
            position: absolute;
            top: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(229,9,20,.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .page-header-inner {
            position: relative;
            z-index: 1;
        }

        .page-eyebrow {
            font-size: 11px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: var(--gold);
            font-weight: 600;
            margin-bottom: 10px;
            opacity: 0;
            animation: fadeUp .5s .1s forwards;
        }

        .page-title {
            font-family: var(--font-title);
            font-size: clamp(2.8rem, 6vw, 5rem);
            letter-spacing: 2px;
            line-height: 1;
            color: var(--text);
            margin-bottom: 10px;
            opacity: 0;
            animation: fadeUp .6s .2s forwards;
        }
        .page-title span { color: var(--gold); }

        .page-count {
            font-size: 13px;
            color: var(--muted);
            font-weight: 400;
            opacity: 0;
            animation: fadeUp .6s .32s forwards;
        }
        .page-count strong { color: var(--text); font-weight: 600; }

        /* ── Section Divider ──────────────────────────── */
        .section-line {
            border: none;
            border-top: 1px solid var(--border);
            margin: 0 0 44px;
        }

        /* ── Movie Card ───────────────────────────────── */
        .movie-col { padding: 8px; }

        .movie-card {
            background: var(--surface);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
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

        /* Overlay with actions */
        .movie-poster-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(0deg, rgba(0,0,0,.92) 0%, rgba(0,0,0,0) 55%);
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

        .overlay-btn.btn-detail {
            background: var(--red);
            color: #fff;
        }
        .overlay-btn.btn-detail:hover { background: #ff1a27; }

        .overlay-btn.btn-remove {
            background: rgba(255,255,255,.1);
            color: #ff6b6b;
            backdrop-filter: blur(4px);
        }
        .overlay-btn.btn-remove:hover { background: rgba(255,80,80,.2); }

        .movie-info {
            padding: 14px 14px 16px;
        }
        .movie-title-text {
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .movie-year-text { font-size: 12px; color: var(--muted); }

        /* ── Empty State ──────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 100px 20px;
            width: 100%;
            opacity: 0;
            animation: fadeUp .6s .3s forwards;
        }

        .empty-icon {
            font-size: 4rem;
            margin-bottom: 20px;
            display: block;
            filter: grayscale(1);
            opacity: .4;
        }

        .empty-title {
            font-family: var(--font-title);
            font-size: 2.2rem;
            letter-spacing: 2px;
            color: var(--text);
            margin-bottom: 10px;
        }

        .empty-sub {
            font-size: 14px;
            color: var(--muted);
            margin-bottom: 32px;
            font-weight: 300;
        }

        .btn-explore {
            background: var(--red);
            color: #fff;
            border: none;
            border-radius: 6px;
            padding: 12px 32px;
            font-family: var(--font-body);
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 1px;
            text-decoration: none;
            transition: background .2s, transform .15s, box-shadow .2s;
            display: inline-block;
        }
        .btn-explore:hover {
            background: #ff1a27;
            color: #fff;
            text-decoration: none;
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(229,9,20,.35);
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
        .detail-list li strong {
            color: var(--text);
            display: block;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 3px;
        }

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

        /* ── Confirm Toast-style overlay ─────────────── */
        .confirm-overlay {
            display: none;
            position: fixed;
            bottom: 32px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 18px 28px;
            z-index: 9999;
            text-align: center;
            box-shadow: 0 20px 60px rgba(0,0,0,.8);
            min-width: 300px;
            animation: slideUp .25s ease;
        }
        .confirm-overlay.show { display: block; }
        .confirm-text { font-size: 14px; color: var(--text); margin-bottom: 14px; font-weight: 500; }
        .confirm-actions { display: flex; gap: 10px; justify-content: center; }
        .confirm-yes {
            background: var(--red); color: #fff; border: none;
            border-radius: 6px; padding: 8px 22px; font-size: 13px;
            font-weight: 600; cursor: pointer; transition: background .2s;
        }
        .confirm-yes:hover { background: #ff1a27; }
        .confirm-no {
            background: transparent; color: var(--muted); border: 1px solid var(--border);
            border-radius: 6px; padding: 8px 22px; font-size: 13px;
            cursor: pointer; transition: all .2s;
        }
        .confirm-no:hover { border-color: rgba(255,255,255,.2); color: var(--text); }

        /* ── Scrollbar ────────────────────────────────── */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: #2a2a35; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #3a3a4a; }

        /* ── Animations ───────────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(22px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes slideUp {
            from { opacity: 0; transform: translateX(-50%) translateY(16px); }
            to   { opacity: 1; transform: translateX(-50%) translateY(0); }
        }
        .card-appear {
            opacity: 0;
            transform: translateY(20px);
            animation: fadeUp .5s forwards;
        }

        .page-footer {
            padding: 60px 0 40px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }
    </style>
</head>

<body>
    <!-- ── Navbar ──────────────────────────────────────────── -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container d-flex align-items-center justify-content-between">
            <a class="navbar-brand" href="/">MOVIEWEB</a>

            <div class="d-flex align-items-center" style="gap: 10px;">
                <a href="/" class="nav-btn">← Back to Explore</a>
                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="nav-btn">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <!-- ── Page Header ─────────────────────────────────────── -->
    <div class="container">
        <div class="page-header">
            <div class="page-header-inner">
                <p class="page-eyebrow">★ YOUR COLLECTION</p>
                <h1 class="page-title">MY <span>FAVORITES</span></h1>
                <p class="page-count">
                    <strong>{{ $favorites->count() }}</strong> {{ $favorites->count() == 1 ? 'film' : 'films' }} saved
                </p>
            </div>
        </div>

        <hr class="section-line">

        <!-- ── Favorites Grid ──────────────────────────────── -->
        <div class="row" id="favorite-container">
            @forelse($favorites as $i => $fav)
                <div class="col-6 col-md-3 movie-col item-fav-{{ $fav->imdbID }}" style="animation-delay: {{ $i * 60 }}ms;">
                    <div class="movie-card card-appear" style="animation-delay: {{ $i * 60 }}ms;">
                        <div class="movie-poster-wrap">
                            <img src="{{ $fav->poster }}"
                                class="movie-poster"
                                loading="lazy"
                                onerror="this.src='https://via.placeholder.com/300x450/111115/6b6b7a?text=NO+POSTER'">

                            <div class="movie-poster-overlay">
                                <button class="overlay-btn btn-detail see-detail"
                                    data-id="{{ $fav->imdbID }}"
                                    data-toggle="modal"
                                    data-target="#movieModal">
                                    ▶ Detail
                                </button>
                                <button class="overlay-btn btn-remove btn-fav-remove"
                                    data-id="{{ $fav->imdbID }}"
                                    data-title="{{ $fav->title }}">
                                    ✕ Remove
                                </button>
                            </div>
                        </div>
                        <div class="movie-info">
                            <p class="movie-title-text" title="{{ $fav->title }}">{{ $fav->title }}</p>
                            <p class="movie-year-text">{{ $fav->year }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state">
                    <span class="empty-icon">🎬</span>
                    <h2 class="empty-title">No Favorites Yet</h2>
                    <p class="empty-sub">You haven't saved any films. Start exploring and hit the ★ on any movie.</p>
                    <a href="/" class="btn-explore">Explore Movies</a>
                </div>
            @endforelse
        </div>
    </div>

    <!-- ── Footer ──────────────────────────────────────────── -->
    <div class="page-footer">
        <p>MOVIEWEB &mdash; Powered by OMDB API &nbsp;·&nbsp; &copy; {{ date('Y') }}</p>
        <p>Dimar Abiyya</p>
    </div>

    <!-- ── Custom Confirm Dialog ───────────────────────────── -->
    <div class="confirm-overlay" id="confirmBox">
        <p class="confirm-text" id="confirmText">Remove this film from favorites?</p>
        <div class="confirm-actions">
            <button class="confirm-yes" id="confirmYes">Remove</button>
            <button class="confirm-no"  id="confirmNo">Cancel</button>
        </div>
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

        // ── Custom confirm instead of browser alert ───────────
        let pendingRemoveId   = null;
        let pendingRemoveTitle = '';

        $(document).on('click', '.btn-fav-remove', function () {
            pendingRemoveId    = $(this).data('id');
            pendingRemoveTitle = $(this).data('title') || 'this film';
            $('#confirmText').text(`Remove "${pendingRemoveTitle}" from favorites?`);
            $('#confirmBox').addClass('show');
        });

        $('#confirmNo').on('click',  function () { $('#confirmBox').removeClass('show'); pendingRemoveId = null; });

        $('#confirmYes').on('click', function () {
            $('#confirmBox').removeClass('show');
            if (!pendingRemoveId) return;

            let id = pendingRemoveId;
            $.post("{{ route('movie.favorite') }}", { imdbID: id }, function () {
                let $card = $(`.item-fav-${id}`);
                $card.css({ transition: 'opacity .35s, transform .35s', opacity: 0, transform: 'scale(.9)' });
                setTimeout(function () {
                    $card.remove();
                    updateCount();
                    if ($('#favorite-container').children('.movie-col').length === 0) {
                        showEmptyState();
                    }
                }, 380);
            });

            pendingRemoveId = null;
        });

        // ── Update film count in header ───────────────────────
        function updateCount() {
            let n = $('#favorite-container').children('.movie-col').length;
            $('.page-count strong').text(n);
            $('.page-count').html(`<strong>${n}</strong> ${n === 1 ? 'film' : 'films'} saved`);
        }

        // ── Show empty state without reload ──────────────────
        function showEmptyState() {
            $('#favorite-container').html(`
                <div class="empty-state" style="animation:fadeUp .5s forwards;">
                    <span class="empty-icon">🎬</span>
                    <h2 class="empty-title">No Favorites Yet</h2>
                    <p class="empty-sub">You haven't saved any films. Start exploring and hit the ★ on any movie.</p>
                    <a href="/" class="btn-explore">Explore Movies</a>
                </div>
            `);
        }

        // ── Movie Detail Modal ────────────────────────────────
        $(document).on('click', '.see-detail', function () {
            let id = $(this).data('id');
            $('#modalTitle').text('Loading...');
            $('#modalBody').html('<div class="text-center py-5"><div class="spinner-border" style="color:var(--red);"></div></div>');
            $('#movieModal').modal('show');

            $.get("{{ route('movie.detail') }}", { id: id }, function (res) {
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
    </script>
</body>
</html>