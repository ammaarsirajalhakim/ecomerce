@extends('layouts.admin')

@section('content')
    <div class="main-content-inner">
        <div class="main-content-wrap">
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>User Infomation</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('admin.index') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <a href="{{ route('admin.users') }}">
                            <div class="text-tiny">Users</div>
                        </a>
                    </li>
                    <li>
                        <i class="icon-chevron-right"></i>
                    </li>
                    <li>
                        <div class="text-tiny">New User</div>
                    </li>
                </ul>
            </div>
            <div class="wg-box">
                <form class="form-new-product form-style-1" action="{{ route('admin.user.store') }}" method="POST">
                    @csrf
                    <fieldset class="name">
                        <div class="body-title">Full Name <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="text" placeholder="User Name" name="name"
                            value="{{ old('name') }}" required="">
                    </fieldset>
                    @error('name')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="email">
                        <div class="body-title">Email Address <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="email" placeholder="User Email" name="email"
                            value="{{ old('email') }}" required="">
                    </fieldset>
                    @error('email')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="password">
                        <div class="body-title">Password <span class="tf-color-1">*</span></div>
                        <input class="flex-grow" type="password" placeholder="Password" name="password" required="">
                    </fieldset>
                    @error('password')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <fieldset class="utype">
                        <div class="body-title">User Type <span class="tf-color-1">*</span></div>
                        <select name="utype" class="flex-grow">
                            <option value="USR">User</option>
                            <option value="ADM">Admin</option>
                        </select>
                    </fieldset>
                    @error('utype')
                        <span class="alert alert-danger text-center">{{ $message }}</span>
                    @enderror

                    <div class="bot">
                        <div></div>
                        <button class="tf-button w208" type="submit">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection