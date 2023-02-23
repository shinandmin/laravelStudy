@extends('layout.index')

@section('head_include')

@section('content')
    <div class="wrap col-lg-12">
        <div class="container col-lg-3 col-md-6 col-sm-12 mt-5 p-4 border">
            <form method="POST" action="{{ url('/signup') }}" onsubmit="chkForm(this)">
                <div class="mb-3">
                    <label for="userid" class="form-label">Userid</label>
                    <input type="text" class="form-control" name="userid" id="userid">
                </div>
                <div class="mb-3">
                    <label for="password1" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password1" id="password1">
                </div>
                <div class="mb-3">
                    <label for="password2" class="form-label">Re-Password</label>
                    <input type="password" class="form-control" name="password2" id="password2">
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="password" class="form-control" name="name" id="name">
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-sm-end">
                    <button class="btn btn-primary me-md-2" type="submit">가입</button>
                    <a href="{{ url('/') }}"><button class="btn btn-primary" type="button">취소</button></a>
                </div>

            </form>
        </div>
    </div>

    <script>
        function chkForm(f) {
            var userId = f.userid.value;
            var password1 = f.password1.value;
            var password2 = f.password2.value;
            var name = f.name.value;

            if (password1 != password2) {
                alert('패스워드가 일치하지 않습니다.');
                return false;
            }
        }
    </script>
@endsection
