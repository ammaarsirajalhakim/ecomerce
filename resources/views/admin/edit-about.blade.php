@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27 page-header">
                <h3>Edit Halaman "About Us"</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Menu Utama</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Edit Halaman "About Us"</div>
                    </li>
                </ul>
            </div>
            
            <div class="wg-box">
                @if (session('status'))
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Toastify({
                                text: "{{ session('status') }}",
                                duration: 3500,
                                close: true,
                                gravity: "top",
                                position: "right",
                                stopOnFocus: true,
                                style: {
                                    padding: "16px",
                                    fontSize: "15px",
                                    background: "#27ae60",
                                    color: "white",
                                    borderRadius: "8px"
                                }
                            }).showToast();
                        });
                    </script>
                @endif
                <form class="form-new-product form-style-1" action="{{ route('admin.about.update') }}" method="POST"
                    enctype="multipart/form-data" id="aboutForm" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <fieldset>
                        <div class="body-title">Gambar Poster</div>
                        {{-- PERUBAHAN UTAMA ADA DI SINI --}}
                        <div class="upload-image flex-grow mt-10">
                            {{-- Kontainer untuk Pratinjau Gambar --}}
                            <div class="item" id="imgpreview" style="{{ $about->poster_image ? '' : 'display:none' }}">
                                <img src="{{ $about->poster_image ? asset('uploads/about/' . $about->poster_image) : '' }}" class="effect8" alt="Pratinjau Poster">
                            </div>
                            {{-- Kontainer untuk Tombol Upload --}}
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">
                                        Letakkan gambar di sini <span class="tf-color">cari</span>
                                        <span style="display: block; color: #888; font-size: 12px; margin-top: 5px;">
                                            Rasio 4:1 (Contoh: 1600 x 400px)
                                        </span>
                                    </span>
                                    <input type="file" id="myFile" name="poster_image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>

                    {{-- Sisa field form lainnya tetap sama --}}
                    <fieldset class="name">
                        <div class="body-title">Our Story <span class="tf-color-1">*</span></div>
                        <textarea class="flex-grow" name="our_story" rows="6" placeholder="Cerita kami..." required>{{ old('our_story', $about->our_story) }}</textarea>
                    </fieldset>
                    
                    <fieldset class="name">
                        <div class="body-title">Our Vision <span class="tf-color-1">*</span></div>
                        <textarea class="flex-grow" name="our_vision" rows="6" placeholder="Visi kami..." required>{{ old('our_vision', $about->our_vision) }}</textarea>
                    </fieldset>
                    
                    <fieldset class="name">
                        <div class="body-title">Our Mission <span class="tf-color-1">*</span></div>
                        <textarea class="flex-grow" name="our_mission" rows="6" placeholder="Misi kami..." required>{{ old('our_mission', $about->our_mission) }}</textarea>
                    </fieldset>

                    <fieldset class="name">
                        <div class="body-title">The Company <span class="tf-color-1">*</span></div>
                        <textarea class="flex-grow" name="the_company" rows="6" placeholder="Tentang perusahaan..." required>{{ old('the_company', $about->the_company) }}</textarea>
                    </fieldset>

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- PERUBAHAN UTAMA JUGA ADA DI SCRIPT INI --}}
    <script>
        $(function() {
            // ... (Fungsi showErrorToast tetap sama)
            function showErrorToast(message) {
                Toastify({
                    text: message,
                    duration: 3500,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        padding: "16px",
                        fontSize: "15px",
                        background: "#e74c3c",
                        color: "white",
                        borderRadius: "8px"
                    }
                }).showToast();
            }

            // ... (Logika validasi form submit tetap sama)
            $('#aboutForm').on('submit', function(e) {
                let formIsValid = true;
                
                $(this).find('input[required], select[required], textarea[required]').each(function() {
                    const fieldName = $(this).closest('fieldset').find('.body-title').text().trim().replace('*', '').trim();
                    let errorMessage = '';

                    if (!$(this).val().trim()) {
                        errorMessage = 'Kolom "' + fieldName + '" tidak boleh kosong.';
                        formIsValid = false;
                    }
                    
                    if (errorMessage) {
                        showErrorToast(errorMessage);
                        return false;
                    }
                });

                if (!formIsValid) {
                    e.preventDefault();
                }
            });

            // LOGIKA BARU UNTUK PRATINJAU GAMBAR
            $("#myFile").on("change", function(e) {
                const [file] = this.files;
                if (file) {
                    // Membuat URL sementara untuk file yang dipilih
                    const previewUrl = URL.createObjectURL(file);
                    // Mengatur sumber gambar pratinjau ke URL baru
                    $("#imgpreview img").attr('src', previewUrl);
                    // Memastikan kontainer pratinjau terlihat
                    $("#imgpreview").show();
                }
            });
             $('.alert.alert-danger').remove();
        });
    </script>
@endpush