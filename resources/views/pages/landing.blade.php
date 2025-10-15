@extends('layouts.app')

@section('content')
    <style>
        main,
        .bg-light {
            margin-top: 50px;
            /* laras ikut tinggi navbar */
        }

        /* Samakan saiz kad & gambar */
        #roomsGrid .room-card .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        #roomsGrid .room-card .card-img-top {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        #roomsGrid .room-card .card-body {
            display: flex;
            flex-direction: column;
        }

        #roomsGrid .room-card .actions {
            margin-top: auto;
        }
    </style>

    <div class="bg-light py-5">
        <div class="container">

            {{-- Controls: Campus filter + search --}}
            <div class="row g-3 align-items-center mb-4">
                <div class="col-12 col-md-8">
                    <div class="btn-group flex-wrap" role="group" aria-label="Penapis Kampus">
                        <button type="button" class="btn btn-outline-primary active" data-campus="ALL">Semua
                            Kampus</button>
                        <button type="button" class="btn btn-outline-primary" data-campus="Samarahan">Samarahan
                            1</button>
                        <button type="button" class="btn btn-outline-primary" data-campus="Samarahan 2">Samarahan
                            2</button>
                        <button type="button" class="btn btn-outline-primary" data-campus="Mukah">Mukah</button>
                    </div>
                </div>
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bx bx-search"></i></span>
                        <input type="text" class="form-control" id="searchInput"
                            placeholder="Cari ruang / kapasiti / kemudahan...">
                    </div>
                </div>
            </div>

            {{-- Grid Ruang (statik / contoh) --}}
            <div class="row g-4" id="roomsGrid">
                {{-- S1 --}}
                <div class="col-12">
                    <h5 class="mb-2"><span class="badge bg-primary">Kampus Samarahan</span></h5>
                </div>

                <div class="col-12 col-md-6 col-xl-4 room-card" data-campus="Samarahan"
                    data-keywords="dewan utama auditorium pa sistem 500 kerusi pentas">
                    <div class="card shadow-sm">
                        <img src="https://scontent.fkul8-3.fna.fbcdn.net/v/t39.30808-6/487777584_1129792625616360_4170391684103227381_n.jpg?stp=cp6_dst-jpg_tt6&_nc_cat=104&ccb=1-7&_nc_sid=833d8c&_nc_ohc=diFIBKP_8GkQ7kNvwGUz4_M&_nc_oc=AdlXgdJtSSmquA9Q275TknkKOzMWFs35nzf0HvTPhNzbH0QsM3y_fHqufoFOpf3t9n8uikNBPKVvYJ7SPQeXFg36&_nc_zt=23&_nc_ht=scontent.fkul8-3.fna&_nc_gid=RysUROkthQPZlq8yJa7ZhQ&oh=00_AffEzVAAAHZ97o2YUDdzFt0dR-6KH6NS-T3gNDQwMpevxg&oe=68F4CB88"
                            class="card-img-top" alt="Dewan Jubli">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title mb-1">Dewan Jubli</h5>
                                <span class="badge bg-secondary">Samarahan</span>
                            </div>
                            <p class="text-muted small mb-2">Majlis Besar</p>
                            <ul class="list-unstyled small mb-0">
                                <li><i class="bx bx-group me-1"></i> Kapasiti: ~500</li>
                                <li><i class="bx bx-wifi me-1"></i> PA System, Projektor, Wi-Fi</li>
                            </ul>
                            <div class="actions d-flex justify-content-end pt-3">
                                <a href="{{ route('login') }}" class="btn btn-primary">Lihat & Tempah</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 room-card" data-campus="Samarahan"
                    data-keywords="bilik mesyuarat meeting tv 20 kerusi">
                    <div class="card shadow-sm">
                        <img src="https://library.uitm.edu.my/images/space/image/sarawak/computer_lab_srwk.jpg"
                            class="card-img-top" alt="Makmal Komputer">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title mb-1">Makmal Komputer</h5>
                                <span class="badge bg-secondary">Samarahan</span>
                            </div>
                            <p class="text-muted small mb-2">Mesyuarat / Bengkel Kecil</p>
                            <ul class="list-unstyled small mb-0">
                                <li><i class="bx bx-group me-1"></i> Kapasiti: 20</li>
                                <li><i class="bx bx-tv me-1"></i> TV 65", HDMI, Whiteboard</li>
                            </ul>
                            <div class="actions d-flex justify-content-end pt-3">
                                <a href="{{ route('login') }}" class="btn btn-primary">Lihat & Tempah</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- S2 --}}
                <div class="col-12 mt-2">
                    <h5 class="mb-2"><span class="badge bg-primary">Kampus Samarahan 2</span></h5>
                </div>

                <div class="col-12 col-md-6 col-xl-4 room-card" data-campus="Samarahan 2"
                    data-keywords="makmal komputer pc 35 unit software">
                    <div class="card shadow-sm">
                        <img src="https://scontent.fkul8-5.fna.fbcdn.net/v/t39.30808-6/481474411_653497990387721_4898825800927554743_n.jpg?stp=cp6_dst-jpg_tt6&_nc_cat=103&ccb=1-7&_nc_sid=833d8c&_nc_ohc=6lkmF9fxxo8Q7kNvwGi2aPf&_nc_oc=AdlQwWosWJZu8SX2Sr0bwFPh2XWoV65yRYFriYDWfIxIgsFZYYv0SdqL0dkiFpDHJtj5-Cu2ArLurs8L46UyXspS&_nc_zt=23&_nc_ht=scontent.fkul8-5.fna&_nc_gid=Nz_UZXWFNN70Y831v60GbA&oh=00_AfcpjgrSGJIV1pzVuhHN8SIgeu17s5RkeWBcdP5tfgHyLA&oe=68F4A6B8"
                            class="card-img-top" alt="Bilik Seminar Utama">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title mb-1">Bilik Seminar Utama</h5>
                                <span class="badge bg-secondary">Samarahan 2</span>
                            </div>
                            <p class="text-muted small mb-2">Acara / Seminar</p>
                            <ul class="list-unstyled small mb-0">
                                <li><i class="bx bx-group me-1"></i> Kapasiti: 200</li>
                                <li><i class="bx bx-plug me-1"></i> Kerusi, Wifi</li>
                            </ul>
                            <div class="actions d-flex justify-content-end pt-3">
                                <a href="{{ route('login') }}" class="btn btn-primary">Lihat & Tempah</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 room-card" data-campus="Samarahan 2"
                    data-keywords="bilik seminar lecture 80 kerusi mikrofon">
                    <div class="card shadow-sm">
                        <img src="https://scontent.fkul8-4.fna.fbcdn.net/v/t39.30808-6/488641459_1113162634188316_6081588724196745929_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=127cfc&_nc_ohc=uVza5E9m3W8Q7kNvwHFRJpf&_nc_oc=Adkl39qvk9cYbYLlJ05m9EIzptcd6Nqs_Sd6OzgOTSA0vOD-08fj9Fw1RFoaedqwsKSiStxkFHndIPLepKZvRDMu&_nc_zt=23&_nc_ht=scontent.fkul8-4.fna&_nc_gid=KGeeGLBSyxiieJ8M9LPrsA&oh=00_Affd1AgOLNisd0pJURFNxISboRrO9XPFmYXBxiUqtxbchw&oe=68F4B40E"
                            class="card-img-top" alt="Auditorium">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title mb-1">Auditorium</h5>
                                <span class="badge bg-secondary">Samarahan 2</span>
                            </div>
                            <p class="text-muted small mb-2">Seminar / Taklimat</p>
                            <ul class="list-unstyled small mb-0">
                                <li><i class="bx bx-group me-1"></i> Kapasiti: 200</li>
                                <li><i class="bx bx-microphone me-1"></i> PA, Projektor, Podium</li>
                            </ul>
                            <div class="actions d-flex justify-content-end pt-3">
                                <a href="{{ route('login') }}" class="btn btn-primary">Lihat & Tempah</a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mukah --}}
                <div class="col-12 mt-2">
                    <h5 class="mb-2"><span class="badge bg-primary">Kampus Mukah</span></h5>
                </div>

                <div class="col-12 col-md-6 col-xl-4 room-card" data-campus="Mukah"
                    data-keywords="dewan serbaguna sukan exam 200">
                    <div class="card shadow-sm">
                        <img src="https://scontent.fkul8-1.fna.fbcdn.net/v/t39.30808-6/493999641_711541281437128_3715376000729520120_n.jpg?stp=cp6_dst-jpg_tt6&_nc_cat=111&ccb=1-7&_nc_sid=833d8c&_nc_ohc=qSY6pw33nTIQ7kNvwHR2w_3&_nc_oc=AdlJYJybG6SDiggS4UwAvngU6q_lBPwZ1wJInNiKWeFwQkk8H0NP6paEhkM4PNd201ID90YP36Mwb1Uj_jq4tnUv&_nc_zt=23&_nc_ht=scontent.fkul8-1.fna&_nc_gid=t1PcAnFurGlC3fMyI-Ydgg&oh=00_AffHS1R5AZ_0nnojz0MHTLN0MJEFvc1QCT8vGDFwWZMMtQ&oe=68F4C8A4"
                            class="card-img-top" alt="Auditorium">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title mb-1">Auditorium</h5>
                                <span class="badge bg-secondary">Mukah</span>
                            </div>
                            <p class="text-muted small mb-2">Serbaguna / Sukan / Peperiksaan</p>
                            <ul class="list-unstyled small mb-0">
                                <li><i class="bx bx-group me-1"></i> Kapasiti: ~200</li>
                                <li><i class="bx bx-dumbbell me-1"></i> Peralatan asas sukan</li>
                            </ul>
                            <div class="actions d-flex justify-content-end pt-3">
                                <a href="{{ route('login') }}" class="btn btn-primary">Lihat & Tempah</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6 col-xl-4 room-card" data-campus="Mukah"
                    data-keywords="bilik kuliah kelas 40 whiteboard projektor">
                    <div class="card shadow-sm">
                        <img src="https://www.kpmaiwp.edu.my/2025/wp-content/uploads/2024/09/red_DSC01724-1.jpg"
                            class="card-img-top" alt="Bilik Kuliah">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title mb-1">Bilik Kuliah</h5>
                                <span class="badge bg-secondary">Mukah</span>
                            </div>
                            <p class="text-muted small mb-2">Kelas / Bengkel</p>
                            <ul class="list-unstyled small mb-0">
                                <li><i class="bx bx-group me-1"></i> Kapasiti: 40</li>
                                <li><i class="bx bx-chalkboard me-1"></i> Projektor & Whiteboard</li>
                            </ul>
                            <div class="actions d-flex justify-content-end pt-3">
                                <a href="{{ route('login') }}" class="btn btn-primary">Lihat & Tempah</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('scripts')
    <script>
        (function() {
            var campusButtons = document.querySelectorAll('[data-campus]');
            var filterButtons = document.querySelectorAll('button[data-campus]');
            var cards = document.querySelectorAll('.room-card');
            var searchInput = document.getElementById('searchInput');

            function applyFilters() {
                var activeBtn = document.querySelector('button[data-campus].active');
                var campus = activeBtn ? activeBtn.getAttribute('data-campus') : 'ALL';
                var term = (searchInput.value || '').toLowerCase();

                cards.forEach(function(card) {
                    var cardCampus = card.getAttribute('data-campus');
                    var keywords = (card.getAttribute('data-keywords') || '').toLowerCase();
                    var matchCampus = (campus === 'ALL') || (cardCampus === campus);
                    var matchSearch = !term || keywords.indexOf(term) > -1;
                    card.style.display = (matchCampus && matchSearch) ? '' : 'none';
                });
            }

            filterButtons.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    filterButtons.forEach(function(b) {
                        b.classList.remove('active');
                    });
                    this.classList.add('active');
                    applyFilters();
                });
            });

            searchInput && searchInput.addEventListener('input', applyFilters);

            // init
            applyFilters();
        })();
    </script>
@endpush
