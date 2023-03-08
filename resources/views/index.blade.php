@extends('layout.index')

@section('head_include')

@section('content')
    <div class="container">
        <div class="accordion mt-5" id="title">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        {{ $my_position['area1'].' '.$my_position['area2'].' '.$my_position['area3'] }} 현재 날씨
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                     data-bs-parent="#title">
                    <div class="accordion-body">
                        <ul class="list-group">
                                    <li class="list-group-item">
                                        미세먼지 <p style="float:right">{{ $pm10Status }}</p>
                                    </li>
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
                    <form method="POST" action="/myArea">
                        @csrf
                        <div class="accordion-body">
                            <select class="form-control" style="width:200px;display:inline-block" id="area1" name="area1" onchange="loadArea(this.value, 1)">
                                <option value="default">지역 1단계</option>
                                @foreach($area1_list as $area1)
                                <option value="{{ $area1['1st'] }}">{{ $area1['1st'] }}</option>
                                @endforeach
                            </select>
                            <select class="form-control" style="width:200px;display:inline-block" id="area2" name="area2" onchange="loadArea(this.value, 2)">
                                <option>지역 2단계</option>
                            </select>
                            <select class="form-control" style="width:200px;display:inline-block" id="area3" name="area3">
                                <option>지역 3단계</option>
                            </select>
                            <button class="btn btn-primary" type="submit">저장</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loadArea(selArea, depth) {
            var nextDepth = depth + 1;
            var area1 = "";

            if (depth == 2) area1 = $('#area1').val();

            if (selArea != "default") {
                $.ajax({
                    type: "GET",
                    url: "/area",
                    data: {
                        depth : depth,
                        area : selArea,
                        area1 : area1,
                    },
                    dataType: 'JSON',
                    success: function success(data) {
                        var areaList = '<option value="default">지역 '+nextDepth+'단계</option>';
                        $.each(data, function (index, element) {
                            areaList += '<option value="'+element[nextDepth+'st']+'">'+element[nextDepth+'st']+'</option>';
                        });

                        if (depth == 1) {
                            $('#area3').html('<option value="default">지역 3단계</option>');
                        }
                        $('#area'+nextDepth).html(areaList);
                    },
                    error: function (response) {
                        console.log(response);
                    }
                })
            } else {
                if (depth == 1) {
                    $('#area2').html('<option value="default">지역 2단계</option>');
                    $('#area3').html('<option value="default">지역 3단계</option>');
                } else if (depth == 2) {
                    $('#area3').html('<option value="default">지역 3단계</option>');
                }

            }
        }
    </script>
@endsection
