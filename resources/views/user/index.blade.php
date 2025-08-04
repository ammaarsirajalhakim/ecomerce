@extends('layouts.app')
@section('content')
    <main class="pt-90">
        <div class="mb-4 pb-4"></div>
        <section class="my-account container">
            <h2 class="page-title">My Account</h2>
            <div class="row">
                <div class="col-lg-3">
                    @include('user.account-nav')
                </div>
                <div class="col-lg-9">
                    <div class="page-content my-account__dashboard">
                        <p>Hallo <strong>User</strong></p>
                        <p>Dari dashboard akun, Anda dapat melihat <a class="unerline-link"
                                href="{{ route('user.orders') }}">riwayat pesanan anda</a>, mengelola <a class="unerline-link" href="account_edit_address.html">shipping
                                alamat</a>, dan <a class="unerline-link" href="account_edit.html">ubah password dan detail akun.</a></p>
                    </div>
                </div>
            </div>
        </section>
    </main>
@endsection
