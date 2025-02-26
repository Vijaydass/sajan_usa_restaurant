<x-app-layout>
    <div class="content-wrapper">
        <div class="page-header">
            <h3 class="page-title">
                <span class="page-title-icon bg-gradient-primary text-white me-2">
                    <i class="mdi mdi-home"></i>
                </span> Dashboard
            </h3>
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item active" aria-current="page">
                        <span></span>Overview <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                    </li>
                </ul>
            </nav>
        </div>
        <div class="row">
            <div class="col-12 grid-margin">
                <!-- Bootstrap Carousel -->
                <div id="photoCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="https://picsum.photos/1200/450" class="d-block w-100" alt="Image 1">
                        </div>
                        <div class="carousel-item">
                            <img src="https://picsum.photos/1200/450" class="d-block w-100" alt="Image 2">
                        </div>
                        <div class="carousel-item">
                            <img src="https://picsum.photos/1200/450" class="d-block w-100" alt="Image 3">
                        </div>
                    </div>

                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#photoCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#photoCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>

                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#photoCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
                        <button type="button" data-bs-target="#photoCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#photoCarousel" data-bs-slide-to="2"></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Important Updates</h4>
                        @if($updates->count() > 0)
                            @foreach($updates as $update)
                                <div class="d-flex mt-3 align-items-top">
                                    <img src="{{asset('assets/images/samples/300x300/10.jpg')}}" class="img-sm rounded-circle me-3" alt="image">
                                    <div class="mb-0 flex-grow">
                                        <h5 class="me-2 mb-2">{{ $update->title }} - ({{$update->created_at->format('Y-m-d')}})</h5>
                                        <p class="mb-0 font-weight-light">{{ Str::limit($update->content, 100) }}</p>
                                    </div>
                                    @if($update->url)
                                        <div class="ms-auto">
                                            <a href="{{ $update->url }}" target="_blank">
                                            <i class="fa fa-external-link text-muted"></i>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p>No latest updates available.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 col-md-3">
                <a href="https://sso.inspirepartners.net/app/inspirepartners_adobeexperiencemanager_1/exk2mn85ssDid0y6q697/sso/saml">
                    <img src="https://dunkin-service.onrender.com/assets/images/learning1.png" alt="" class="rounded mb-2" height="100" width="200">
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="https://sso.inspirepartners.net/app/inspirepartners_adobeexperiencemanager_1/exk2mn85ssDid0y6q697/sso/saml">
                    <img src="https://dunkin-service.onrender.com/assets/images/learning2.png" alt="" class="rounded mb-2 bg-white" width="200" height="100">
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="https://dbi1346.net-chef.com/standalone/modern.ct#Login">
                    <img src="https://dunkin-service.onrender.com/assets/images/operations1.png" alt="" class="rounded mb-2 bg-white" width="200" height="100">
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="https://login.workpulse.com/core/login?signin=97608d4d9b34904da6d833c4f7243793">
                    <img src="https://dunkin-service.onrender.com/assets/images/operations2.png" alt="" class="rounded mb-2 bg-white" width="200" height="100">
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="https://dun01-ohra-prod.hospitality.oracleindustry.com/login.jsp">
                    <img src="https://dunkin-service.onrender.com/assets/images/operations3.png" alt="" class="rounded mb-2 bg-white" width="200" height="100">
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="https://natdcp.com/user/login">
                    <img src="https://dunkin-service.onrender.com/assets/images/operations4.png" alt="" class="rounded mb-2 bg-white" width="200" height="100">
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="https://lsm-prod-idp.dunkinbrands.com/my.policy">
                    <img src="https://dunkin-service.onrender.com/assets/images/management1.png" alt="" class="rounded mb-2 bg-white" width="200" height="100">
                </a>
            </div>
            <div class="col-12 col-md-3">
                <a href="https://lsm-prod-idp.dunkinbrands.com/my.policy">
                    <img src="https://dunkin-service.onrender.com/assets/images/management2.png" alt="" class="rounded mb-2 bg-white" width="200" height="100">
                </a>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="{{asset('assets/js/dashboard.js')}}"></script>
    @endpush
</x-app-layout>

