@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center align-items-center min-vh-100">
    <div class="col-md-4">
        <div class="card shadow-lg">
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-link-45deg display-1 text-primary" style="font-size: 3rem;"></i>
                    <h4 class="mt-3">TableLink</h4>
                    <p class="text-muted">Technical Test System</p>
                </div>

                @yield('content')

                <div class="text-center mt-4">
                    <small class="text-muted">
                        © 2024 PT. TABLELINK DIGITAL INOVASI
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
