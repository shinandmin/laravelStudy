@extends('layout.index')

@section('head_include')

@section('content')
    <div class="wrap col-lg-12">
        <div class="container col-lg-3 col-md-6 col-sm-12 mt-5 p-4 border">
            <form method="POST" action="{{ url('/auth/signin') }}">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Userid</label>
                    <input type="text" class="form-control" name="userid" id="userid">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password">
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-sm-end">
                    <a href="{{ url('/signup') }}"><button class="btn btn-primary me-md-2" type="button">회원가입</button></a>
                    <button class="btn btn-primary" type="submit">로그인</button>
                </div>

            </form>
        </div>
    </div>
@endsection
