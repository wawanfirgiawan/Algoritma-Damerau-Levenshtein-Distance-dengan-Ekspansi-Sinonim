@extends('layouts.app')
@section('content') 
    <main id="main">
        <div class="container login-container p-3" style="margin-top: 8rem; margin-bottom: 3rem">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <div class="card p-5 rounded-3">
                        <div class="card-body">
                            <form action="{{ url('login/validation') }}" method="post">
                                @csrf
                                {{-- <h4 class="fs-2" style="color: #38527E">Masuk</h4> --}}
                                <h5 class="lg">Belum punya akun? <a href="{{ url('register') }}" style="color: #38527E">Daftar</a></h5>
                                @error('message')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                                <div class="mb-3">
                                    <label for="Email" class="form-label">Email</label>
                                    <input type="text" value="{{ old('email') }}" name="email"
                                        class="form-control @error('email')
                                        is-invalid                                        
                                    @enderror"
                                        id="email" placeholder="">
                                    @error('email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password"
                                        class="form-control @error('password')
                                        is-invalid                                        
                                    @enderror"
                                        id="password" placeholder="">
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <a class="small" href="{{ url('forgot/password') }}" style="color: #38527E">Lupa Password?</a>
                                </div>
                                <button type="submit" class="btn  w-100" style="background-color: #38527E">
                                    <h5 class="text-light mt-2"><i class="fal fa-sign-in"></i> Masuk</h5>
                                </button>
                                <div class="divider "><span>Atau masuk dengan</span></div>

                                <div class="row mt-3 justify-content-center">
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ url('auth/google/redirect') }}">
                                            <div class="card p-2 d-flex justify-content-center align-items-center">
                                                <iconify-icon icon="flat-color-icons:google"
                                                    class="fs-2"></iconify-icon>
                                            </div>
                                        </a>
                                    </div>
                                    <div class="col-md-3">
                                        <a href="{{ url('auth/github/redirect') }}">
                                            <div class="card p-2 d-flex justify-content-center align-items-center">
                                                <iconify-icon icon="devicon:github" class="fs-2"></iconify-icon>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>


    </main><!-- End #main -->

    <!-- ======= Footer ======= -->
@endsection

<!-- ======= Header ======= -->
