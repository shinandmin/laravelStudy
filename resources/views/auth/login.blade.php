@extends('layout.index')

@section('head_include')

@section('content')
    <div class="wrap col-lg-12">
        <div class="container col-lg-3 col-md-6 col-sm-12 mt-5 p-4 border">
            <form>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Userid</label>
                    <input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1">
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-sm-end">
                    <a href="{{ url('/signup') }}"><button class="btn btn-primary me-md-2" type="button">회원가입</button></a>
                    <button class="btn btn-primary" type="button">로그인</button>
                </div>

            </form>
        </div>
    </div>
@endsection
