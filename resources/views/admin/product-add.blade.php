@extends('layouts.admin')
@section('content')
    <div class="main-content-inner">
        <!-- main-content-wrap -->
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Tambah Produk</h3>
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
                        <a href="{{ route('admin.products') }}">
                            <div class="text-tiny">Produk</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">Tambah Produk</div>
                    </li>
                </ul>
            </div>
            <!-- form-add-product -->
            <form class="tf-section-2 form-add-product" method="POST" enctype="multipart/form-data"
                action="{{ route('admin.product.store') }}">
                @csrf
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Nama Produk<span class="tf-color-1">*</span>
                        </div>
                        <input class="mb-10" type="text" placeholder="Masukkan Nama Produk" name="name"
                            tabindex="0" value="{{ old('name') }}" aria-required="true" required="">
                        <div class="text-tiny">Nama produk tidak boleh melebihi 100 karakter.</div>
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="name">
                        <div class="body-title mb-10">Link produk <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" placeholder="masukkan Link Produk" name="slug" tabindex="0"
                            value="{{ old('slug') }}" aria-required="true" required="">
                        <div class="text-tiny">Link produk tidak boleh melebihi 100 karakter.</div>
                    </fieldset>
                    @error('slug')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Kategori <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="category_id">
                                    <option>Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </fieldset>
                        @error('category_id')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="brand">
                            <div class="body-title mb-10">Merek <span class="tf-color-1">*</span>
                            </div>
                            <div class="select">
                                <select class="" name="brand_id">
                                    <option>Pilih Merek</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                    @endforeach

                                </select>
                            </div>
                        </fieldset>
                        @error('brand_id')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <fieldset class="shortdescription">
                        <div class="body-title mb-10">Deskripsi Singkat</div>
                        <textarea class="mb-10 ht-150" name="short_description" placeholder="Deskripsi Singkat" tabindex="0">{{ old('short_description') }}</textarea>
                        <div class="text-tiny">Deskripsi Produk tidak boleh melebihi 100 karakter.</div>
                    </fieldset>
                    @error('short_description')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="description">
                        <div class="body-title mb-10">Deskripsi <span class="tf-color-1">*</span>
                        </div>
                        <textarea class="mb-10" name="description" placeholder="Deskripsi" tabindex="0" aria-required="true"
                            required="">{{ old('description') }}</textarea>
                        <div class="text-tiny">Deskripsi produk tidak boleh melebihi 100 karakter.</div>
                    </fieldset>
                    @error('description')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror
                </div>
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title">Unggah Gambar <span class="tf-color-1">*</span>
                        </div>
                        <div class="upload-image flex-grow">
                            <div class="item" id="imgpreview" style="display:none">
                                <img src="../../../localhost_8000/images/upload/upload-1.png" class="effect8"
                                    alt="">
                            </div>
                            <div id="upload-file" class="item up-load">
                                <label class="uploadfile" for="myFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Letakkan gambar di sini <span
                                            class="tf-color">cari</span></span>
                                    <input type="file" id="myFile" name="image" accept="image/*">
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    @error('image')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset>
                        <div class="body-title mb-10">Unggah Galeri Gambar</div>
                        <div class="upload-image mb-16">
                            <!-- <div class="item">
                                                                                                                                                            <img src="images/upload/upload-1.png" alt="">
                                                                                                                                                        </div>                                                 -->
                            <div id="galUpload" class="item up-load">
                                <label class="uploadfile" for="gFile">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="body-text">Letakkan gambar di sini <span
                                            class="tf-color">cari</span></span>
                                </label>

                                <!-- input untuk memilih file, bisa diklik -->
                                <input type="file" id="gFile" accept="image/*" multiple style="display: none;">

                                <!-- input tersembunyi untuk menyimpan semua file (yang akan dikirim saat submit) -->
                                <input type="file" id="allImages" name="images[]" accept="image/*" multiple
                                    style="display: none;">
                            </div>

                        </div>
                    </fieldset>
                    @error('images')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Harga Standar <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Masukkan Harga Standar"
                                name="regular_price" tabindex="0" value="{{ old('regular_price') }}"
                                aria-required="true" required="">
                        </fieldset>
                        @error('regular_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Harga Jual <span class="tf-color-1">*</span></div>
                            <input class="mb-10" type="text" placeholder="Masukkan Harga Jual" name="sale_price"
                                tabindex="0" value="{{ old('sale_price') }}" aria-required="true" required="">
                        </fieldset>
                        @error('sale_price')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>


                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Kode Barang <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Masukkan Kode Barang " name="SKU"
                                tabindex="0" value="{{ old('SKU') }}" aria-required="true" required="">
                        </fieldset>
                        @error('SKU')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Jumlah <span class="tf-color-1">*</span>
                            </div>
                            <input class="mb-10" type="text" placeholder="Massukkan Jumlah" name="quantity"
                                tabindex="0" value="{{ old('quantity') }}" aria-required="true" required="">
                        </fieldset>
                        @error('quantity')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Stok</div>
                            <div class="select mb-10">
                                <select class="" name="stock_status">
                                    <option value="instock">Tersedia</option>
                                    <option value="outofstock">Stok Habis</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('stock_status')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                        <fieldset class="name">
                            <div class="body-title mb-10">Produk Unggulan</div>
                            <div class="select mb-10">
                                <select class="" name="featured">
                                    <option value="0">Tidak</option>
                                    <option value="1">Ya</option>
                                </select>
                            </div>
                        </fieldset>
                        @error('featured')
                            <span class="alert alert-danger text-center">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Tambah Produk</button>
                    </div>
                </div>
            </form>
            <!-- /form-add-product -->
        </div>
        <!-- /main-content-wrap -->
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {
            let dataTransfer = new DataTransfer(); // untuk menyimpan file secara dinamis

            $("#gFile").on("change", function(e) {
                const newFiles = Array.from(this.files);

                newFiles.forEach((file) => {
                    dataTransfer.items.add(file); // tambahkan file ke dataTransfer

                    // preview gambar
                    $(`<div class="item gitems"><img src="${URL.createObjectURL(file)}"/></div>`)
                        .insertBefore("#galUpload");
                });

                // set file list hasil gabungan ke input hidden
                document.getElementById("allImages").files = dataTransfer.files;

                // reset input utama supaya bisa pilih file yang sama lagi
                this.value = "";
            });

            $("#myFile").on("change", function() {
                const [file] = this.files;
                if (file) {
                    $("#imgpreview img").attr('src', URL.createObjectURL(file));
                    $("#imgpreview").show();
                }
            });

            $("input[name='name']").on("change", function() {
                $("input[name='slug']").val(StringToSlug($(this).val()));
            });

            function StringToSlug(Text) {
                return Text.toLowerCase()
                    .replace(/[^\w ]+/g, "")
                    .replace(/ +/g, "-");
            }
        });
    </script>
@endpush
