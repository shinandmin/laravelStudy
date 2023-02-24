@extends('layout.index')

@section('head_include')

@section('content')
    <div class="container">
        <div class="accordion mt-5" id="title">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        압구정동 현재 날씨
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                     data-bs-parent="#title">
                    <div class="accordion-body">
                        <ul class="list-group">
                            @foreach($result_list as $list)
                                @switch($list->category)
                                    @case('PTY')
                                    <li class="list-group-item">
                                        @php $data_arr = [0 => '맑음', 1 => '비', 2 => '비 또는 눈', 3 => '눈', 5 => '빗방울', 6 => '빗방울눈날림', 7 => '눈날림'];
                                        @endphp
                                        날씨 <p style="float:right">{{ $data_arr[$list->obsrValue] }}</p>
                                    </li>
                                    @break
                                    @case('REH')
                                    <li class="list-group-item">습도 <p style="float:right">{{ $list->obsrValue }}%</p>
                                    </li>
                                    @break
                                    @case('RN1')
                                    <li class="list-group-item">1시간 강수량 <p style="float:right">{{ $list->obsrValue }}
                                            mm</p></li>
                                    @break
                                    @case('T1H')
                                    <li class="list-group-item">기온 <p style="float:right">{{ $list->obsrValue }}℃</p>
                                    </li>
                                    @break
                                    @case('UUU')
                                    <li class="list-group-item">동서바람성분 <p style="float:right">{{ $list->obsrValue }}
                                            m/s</p></li>
                                    @break
                                    @case('VEC')
                                    <li class="list-group-item">풍향 <p style="float:right">{{ $list->obsrValue }}deg</p>
                                    </li>
                                    @break
                                    @case('VVV')
                                    <li class="list-group-item">남북바람성분 <p style="float:right">{{ $list->obsrValue }}
                                            m/s</p></li>
                                    @break
                                    @case('WSD')
                                    <li class="list-group-item">풍속 <p style="float:right">{{ $list->obsrValue }}m/s</p>
                                    </li>
                                    @break
                                    @default
                                @endswitch
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseTwo" aria-expanded="true" aria-controls="collapseOne">
                        우리동네 지정하기
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingOne"
                     data-bs-parent="#title">
                    <div class="accordion-body">
                        <select class="form-control" style="width:200px;display:inline-block" id="area1">
                            <option>지역 1단계</option>
                        </select>
                        <select class="form-control" style="width:200px;display:inline-block" id="area2">
                            <option>지역 2단계</option>
                        </select>
                        <select class="form-control" style="width:200px;display:inline-block" id="area3">
                            <option>지역 3단계</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
