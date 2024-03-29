<nav class="navbar navbar-expand-lg bg-light">
    <div class="container center">
        <a class="navbar-brand" href="@php if (isset(auth()->user()->name)) echo "/auth/signin"; else echo "/"; @endphp">우리동네</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    @auth
                        <a class="nav-link active" aria-current="page" href="/">Home</a>
                    @endauth
                    @guest
                        <a class="nav-link active" aria-current="page" href="/auth/signin">Home</a>
                    @endguest
                </li>
                <li class="nav-item">
                    @auth
                        <a class="nav-link" href="{{ url('/auth/signout') }}">로그아웃</a>
                    @endauth
                    @guest
                        <a class="nav-link" href="{{ url('/auth/signin') }}">로그인</a>
                    @endguest
                </li>
            </ul>
        </div>
        <div>
            @auth
                {{ auth()->user()->name }}님, 환영합니다.
            @endauth

        </div>
    </div>
</nav>
