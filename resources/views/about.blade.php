@extends('layouts.app')
@section('content')
    <main>
        <section class="contact-us container pt-4">
            <div class="mw-930">
                <h2 class="page-title">Tentang Kami</h2>
            </div>

            {{-- Cek apakah data $about ada untuk menghindari error --}}
            @if($about)
            <div class="about-us__content pb-5 mb-5">
                {{-- Gambar Poster Utama --}}
                <p class="mb-5">
                    <img loading="lazy" class="w-100 h-auto d-block" src="{{ asset('uploads/about/' . $about->poster_image) }}" width="1410"
                        height="550" alt="About Us Poster" />
                </p>
                <div class="mw-930">
                    {{-- Our Story --}}
                    <h3 class="mb-4">Cerita Kami</h3>
                    <p class="mb-4">{!! nl2br(e($about->our_story)) !!}</p>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            {{-- Our Mission --}}
                            <h5 class="mb-3">Misi Kami</h5>
                            <p class="mb-3">{!! nl2br(e($about->our_mission)) !!}</p>
                        </div>
                        <div class="col-md-6">
                            {{-- Our Vision --}}
                            <h5 class="mb-3">Visi Kami</h5>
                            <p class="mb-3">{!! nl2br(e($about->our_vision)) !!}</p>
                        </div>
                    </div>
                </div>
                <div class="mw-930 d-lg-flex">
                    <div class="image-wrapper col-lg-6">
                        {{-- Gambar Poster Kedua --}}
                        <img class="h-auto" loading="lazy" src="{{ asset('uploads/about/' . $about->poster_image) }}" width="450"
                            height="500" alt="Our Company">
                    </div>
                    <div class="content-wrapper col-lg-6 px-lg-4">
                        {{-- The Company --}}
                        <h5 class="mb-3">Profil Usaha</h5>
                        <p>{!! nl2br(e($about->the_company)) !!}</p>
                    </div>
                </div>
            </div>
            @else
            {{-- Pesan jika data "About Us" belum diisi dari admin --}}
            <div class="mw-930 text-center">
                <p>Konten "Tentang Kami" belum tersedia.</p>
            </div>
            @endif
        </section>
    </main>
@endsection