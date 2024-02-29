{{-- spinner --}}
{{--<div id="spinner" class="show w-100 vh-100 bg-white position-fixed translate-middle top-50 start-50  d-flex align-items-center justify-content-center">--}}
{{--    <div class="spinner-grow text-primary" role="status"></div>--}}
{{--</div>--}}


{{-- navbar --}}
<div class="container-fluid sticky-top px-0">
    <div class="container-fluid topbar bg-dark d-none d-lg-block">
        <div class="container px-0">
            <div class="topbar-top d-flex justify-content-between flex-lg-wrap">
                <div class="top-info flex-grow-0">
                            <span class="rounded-circle btn-sm-square bg-primary me-2">
                                <i class="fas fa-bolt text-white"></i>
                            </span>
                    <div class="pe-2 me-3 border-end border-white d-flex align-items-center">
                        <p class="mb-0 text-white fs-6 fw-normal">Trending</p>
                    </div>
                    <div class="overflow-hidden" style="width: 735px;">
                        <div id="note" class="ps-2">
                            <a href="#">
                                @if($headlines)
                                    @foreach($headlines as $hl)
                                        <p class="text-white mb-0 link-hover">{{ $hl->title }}</p>
                                    @endforeach
                                @endif
                            </a>
                        </div>
                    </div>
                </div>
                <div class="top-link flex-lg-wrap">
                    <i class="fas fa-calendar-alt text-white border-end border-secondary pe-2 me-2"> <span>{{ getCurrentDate() }}</span></i>
                    <div class="d-flex icon">
                        <p class="mb-0 text-white me-2">Follow Us:</p>
                        <a href="" class="me-2"><i class="fab fa-instagram text-body link-hover"></i></a>
                        <a href="" class="me-2"><i class="fab fa-youtube text-body link-hover"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid bg-light">
        <div class="container px-0">
            <nav class="navbar navbar-light navbar-expand-xl">
                <a href="index.html" class="navbar-brand mt-3">
                    <p class="text-primary display-6 mb-2" style="line-height: 0;">KESIT</p>
                <small class="text-body fw-normal" style="letter-spacing: 12px;">LITERASI</small>
                </a>
                <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="fa fa-bars text-primary"></span>
                </button>
                <div class="collapse navbar-collapse bg-light py-3" id="navbarCollapse">
                    <div class="navbar-nav mx-auto border-top">
                        <a href="{{ route('public.homepage') }}" class="nav-item nav-link {{ Route::is('public.home') ? 'active' : '' }}">Home</a>
                        <a href="detail-page.html" class="nav-item nav-link">Post</a>
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Category</a>
                            <div class="dropdown-menu m-0 bg-secondary rounded-0">
                                @if($categories)
                                    @foreach($categories as $tag)
                                        <a href="#" class="dropdown-item">{{ $tag->name }}</a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <a href="{{ route('public.about') }}" class="nav-item nav-link {{ Route::is('public.about') ? 'active' : '' }}">About</a>
                        <a href="{{ route('public.contact') }}" class="nav-item nav-link {{ Route::is('public.contact') ? 'active' : '' }}">Contact Us</a>
                    </div>
                    <div class="d-flex flex-nowrap border-top pt-3 pt-xl-0 gap-3">
                        <button class="btn-search btn border border-primary btn-md-square rounded-circle bg-white my-auto" data-bs-toggle="modal" data-bs-target="#searchModal"><i class="fas fa-search text-primary"></i></button>
                        <div class="d-flex align-items-center">
                            <a href="{{ route('user.auth.login') }}" class="btn btn-primary text-white">LOGIN</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>


{{-- modal --}}
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search post by keyword</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex align-items-center">
                <div class="input-group w-75 mx-auto d-flex">
                    <input type="search" class="form-control p-3" placeholder="keywords" aria-describedby="search-icon-1">
                    <span id="search-icon-1" class="input-group-text p-3"><i class="fa fa-search"></i></span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- features --}}
<div class="container-fluid features mb-5">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="row g-4 align-items-center features-item">
                    <div class="col-4">
                        <div class="rounded-circle position-relative">
                            <div class="overflow-hidden rounded-circle">
                                <img src="img/features-sports-1.jpg" class="img-zoomin img-fluid rounded-circle w-100" alt="">
                            </div>
                            <span class="rounded-circle border border-2 border-white bg-primary btn-sm-square text-white position-absolute" style="top: 10%; right: -10px;">3</span>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="features-content d-flex flex-column">
                            <p class="text-uppercase mb-2">Sports</p>
                            <a href="#" class="h6">
                                Get the best speak market, news.
                            </a>
                            <small class="text-body d-block"><i class="fas fa-calendar-alt me-1"></i> December 9, 2024</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="row g-4 align-items-center features-item">
                    <div class="col-4">
                        <div class="rounded-circle position-relative">
                            <div class="overflow-hidden rounded-circle">
                                <img src="img/features-technology.jpg" class="img-zoomin img-fluid rounded-circle w-100" alt="">
                            </div>
                            <span class="rounded-circle border border-2 border-white bg-primary btn-sm-square text-white position-absolute" style="top: 10%; right: -10px;">3</span>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="features-content d-flex flex-column">
                            <p class="text-uppercase mb-2">Technology</p>
                            <a href="#" class="h6">
                                Get the best speak market, news.
                            </a>
                            <small class="text-body d-block"><i class="fas fa-calendar-alt me-1"></i> December 9, 2024</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="row g-4 align-items-center features-item">
                    <div class="col-4">
                        <div class="rounded-circle position-relative">
                            <div class="overflow-hidden rounded-circle">
                                <img src="img/features-fashion.jpg" class="img-zoomin img-fluid rounded-circle w-100" alt="">
                            </div>
                            <span class="rounded-circle border border-2 border-white bg-primary btn-sm-square text-white position-absolute" style="top: 10%; right: -10px;">3</span>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="features-content d-flex flex-column">
                            <p class="text-uppercase mb-2">Fashion</p>
                            <a href="#" class="h6">
                                Get the best speak market, news.
                            </a>
                            <small class="text-body d-block"><i class="fas fa-calendar-alt me-1"></i> December 9, 2024</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-6 col-xl-3">
                <div class="row g-4 align-items-center features-item">
                    <div class="col-4">
                        <div class="rounded-circle position-relative">
                            <div class="overflow-hidden rounded-circle">
                                <img src="img/features-life-style.jpg" class="img-zoomin img-fluid rounded-circle w-100" alt="">
                            </div>
                            <span class="rounded-circle border border-2 border-white bg-primary btn-sm-square text-white position-absolute" style="top: 10%; right: -10px;">3</span>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="features-content d-flex flex-column">
                            <p class="text-uppercase mb-2">Life Style</p>
                            <a href="#" class="h6">
                                Get the best speak market, news.
                            </a>
                            <small class="text-body d-block"><i class="fas fa-calendar-alt me-1"></i> December 9, 2024</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
