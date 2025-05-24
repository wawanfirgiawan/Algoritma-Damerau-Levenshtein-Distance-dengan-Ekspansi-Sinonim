@extends('layouts.admin.master')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">

        <!-- Page Heading -->
        {{-- <div class="d-sm-flex mb-4">
            <p class="fs-2 mb-0 text-center" style="color: #38527E">Profil Saya</p>
        </div> --}}

        <!-- Content Row -->
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-3 shadow-sm">
                    <h4 class="text-center mb-3" style="color: #38527E">Profil Saya</h4>
                    <div class="row justify-content-center">
                        <div class="col-md-3">
                            <img src="{{ asset('img/undraw_profile.svg') }}" class="img-fluid" alt="">
                        </div>
                    </div>
                    <h4 class="text-capitalize text-center mt-3">{{ Auth::user()->full_name }}</h4>
                    <p class="text-center">{{ Auth::user()->email }}</p>
                    @if ($errors->any())
                    <div class="alert alert-danger mt-3">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ url('reset-password') }}" id="form-reset-password" method="POST" class="mt-3">
                        @csrf
                        <label for="password">New Password</label>
                        <input type="password" class="form-control" name="password" id="password" required>
                        <label for="password_confirmation" class="mt-3">Confirm Password</label>
                        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                        <button type="submit" id="btn-reset-password" class="btn form-control mt-4 text-white mb-3"
                            style="background-color: #38527E"><i class="fas fa-save mr-1"></i> Perbaharui</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
    @if (session('message'))
        <script>
            Swal.fire({
                title: "Good job!",
                text: "{{ session('message') }}",
                icon: "success"
            });
        </script>
    @endif
@endsection
@section('scripts')
    <script>
        let formReset = document.getElementById('form-reset-password');
        formReset.addEventListener('submit', function() {
            let btnChange = document.getElementById('btn-reset-password')
            btnChange.disabled = true
            btnChange.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Processing...';
        })
    </script>
@endsection
