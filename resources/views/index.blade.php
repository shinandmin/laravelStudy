@extends('layout.index')

@section('head_include')
    <script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=fff29527ad0b4c37c1ee39bc508a84b3"></script>
    <script charset="UTF-8" src="https://t1.daumcdn.net/mapjsapi/js/main/4.4.8/kakao.js"></script>
    <script charset="UTF-8" src="https://t1.daumcdn.net/mapjsapi/js/libs/services/1.0.2/services.js"></script>
    <script charset="UTF-8" src="https://t1.daumcdn.net/mapjsapi/js/libs/clusterer/1.0.9/clusterer.js"></script>
    <script charset="UTF-8" src="https://t1.daumcdn.net/mapjsapi/js/libs/drawing/1.2.6/drawing.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" />

    <style>
        .map_wrap, .map_wrap * {margin:0;padding:0;font-family:'Malgun Gothic',dotum,'돋움',sans-serif;font-size:12px;}
        .map_wrap a, .map_wrap a:hover, .map_wrap a:active{color:#000;text-decoration: none;}
        .map_wrap {position:relative;width:100%;height:500px;}
        #menu_wrap {position:absolute;top:0;left:0;bottom:0;width:250px;margin:10px 0 30px 10px;padding:5px;overflow-y:auto;background:rgba(255, 255, 255, 0.7);z-index: 1;font-size:12px;border-radius: 10px;}
        .bg_white {background:#fff;}
        #menu_wrap hr {display: block; height: 1px;border: 0; border-top: 2px solid #5F5F5F;margin:3px 0;}
        #menu_wrap .option{text-align: center;}
        #menu_wrap .option p {margin:10px 0;}
        #menu_wrap .option button {margin-left:5px;}
        #weather_wrap {position:absolute;top:0;right:0;bottom:0;width:400px;height:150px;margin:10px 10px 30px 10px;padding:5px;overflow-y:auto;background:rgba(255, 255, 255, 0.7);z-index: 1;font-size:12px;border-radius: 10px;}
        #placesList li {list-style: none;}
        #placesList .item {position:relative;border-bottom:1px solid #888;overflow: hidden;cursor: pointer;min-height: 65px;}
        #placesList .item span {display: block;margin-top:4px;}
        #placesList .item h5, #placesList .item .info {text-overflow: ellipsis;overflow: hidden;white-space: nowrap;}
        #placesList .item .info{padding:10px 0 10px 55px;}
        #placesList .info .gray {color:#8a8a8a;}
        #placesList .info .jibun {padding-left:26px;background:url(https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/places_jibun.png) no-repeat;}
        #placesList .info .tel {color:#009900;}
        #placesList .item .markerbg {float:left;position:absolute;width:36px; height:37px;margin:10px 0 0 10px;background:url(https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/marker_number_blue.png) no-repeat;}
        #placesList .item .marker_1 {background-position: 0 -10px;}
        #placesList .item .marker_2 {background-position: 0 -56px;}
        #placesList .item .marker_3 {background-position: 0 -102px}
        #placesList .item .marker_4 {background-position: 0 -148px;}
        #placesList .item .marker_5 {background-position: 0 -194px;}
        #placesList .item .marker_6 {background-position: 0 -240px;}
        #placesList .item .marker_7 {background-position: 0 -286px;}
        #placesList .item .marker_8 {background-position: 0 -332px;}
        #placesList .item .marker_9 {background-position: 0 -378px;}
        #placesList .item .marker_10 {background-position: 0 -423px;}
        #placesList .item .marker_11 {background-position: 0 -470px;}
        #placesList .item .marker_12 {background-position: 0 -516px;}
        #placesList .item .marker_13 {background-position: 0 -562px;}
        #placesList .item .marker_14 {background-position: 0 -608px;}
        #placesList .item .marker_15 {background-position: 0 -654px;}
        #pagination {margin:10px auto;text-align: center;}
        #pagination a {display:inline-block;margin-right:10px;}
        #pagination .on {font-weight: bold; cursor: default;color:#777;}
    </style>
@endsection

@section('content')
    @php
        $data_arr = [0 => '맑음', 1 => '비', 2 => '비 또는 눈', 3 => '눈', 5 => '빗방울', 6 => '빗방울눈날림', 7 => '눈날림'];
        $i = 0;

        $weatherData = array();
        foreach($result_list as $list) {
            $weatherData[$list->category] = $list->obsrValue;
        }
    @endphp
    <div class="container">
        <!-- 지도 영역 -->
        <div class="col-12 mt-3">
            <!-- 키워드 -->
            <div class="btn-group btn-group-sm mb-1" role="group" aria-label="Basic radio toggle button group">
                <button class="btn btn-outline-success" type="button">{{ $my_position['area1'].' '.$my_position['area2'].' '.$my_position['area3'] }}</button>
                <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                <label class="btn btn-outline-success" for="btnradio1" onclick="$('#keyword').val('맛집'); searchPlaces('default')">
                    <i class="fa-solid fa-utensils"></i> 맛집
                </label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                <label class="btn btn-outline-success" for="btnradio2" onclick="$('#keyword').val('병원'); searchPlaces('default')">
                    <i class="fa-solid fa-house-medical"></i> 병원
                </label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                <label class="btn btn-outline-success" for="btnradio3" onclick="$('#keyword').val('학원'); searchPlaces('default')">
                    <i class="fa-solid fa-square-pen"></i> 학원
                </label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio4" autocomplete="off">
                <label class="btn btn-outline-success" for="btnradio4" onclick="$('#keyword').val('편의점'); searchPlaces('default')">
                    <i class="fa-solid fa-store"></i> 편의점
                </label>

                <input type="radio" class="btn-check" name="btnradio" id="btnradio5" autocomplete="off">
                <label class="btn btn-outline-success" for="btnradio5" onclick="$('#keyword').val('로또'); searchPlaces('default')">
                    <i class="fa-solid fa-clover"></i> 로또
                </label>
            </div>

            <div class="map_wrap">
                <div id="map" style="width:100%;height:100%;position:relative;overflow:hidden;"></div>

                <div id="menu_wrap" class="bg_white">
                    <div class="option">
                        <div>
                            <form onsubmit="searchPlaces('default'); return false;">
                                키워드 : <input type="text" value="맛집" id="keyword" size="15">
                                <button type="submit">검색하기</button>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <ul id="placesList"></ul>
                    <div id="pagination"></div>
                </div>

                <div id="weather_wrap">
                    <table class="table table-border-white text-center">
                        <tr>
                            <td class="table-info" width="15%">미세먼지</td><td width="17%">{{ $pm10Status }}</td>
                            <td class="table-info" width="15%">날씨</td><td width="17%">{{ $data_arr[$weatherData['PTY']] }}</td>

                        </tr>
                        <tr>
                            <td class="table-primary" width="15%">습도</td><td width="17%">{{ $weatherData['REH'] }}</td>
                            <td class="table-primary" width="15%">1시간 강수량</td><td width="17%">{{ $weatherData['RN1'] }}</td>
                        </tr>
                        <tr>

                            <td class="table-info" width="15%">기온</td><td width="17%">{{ $weatherData['T1H'] }}</td>
                            <td class="table-info" width="15%">풍속</td><td width="17%">{{ $weatherData['WSD'] }}</td>
                            <!--<td class="table-primary" width="15%">동서바람성분</td><td width="17%">{{ $weatherData['UUU'] }}</td>-->
                        </tr>
                        <tr>
                            <td class="table-primary" width="15%">풍향</td><td width="17%">{{ $weatherData['VEC'] }}</td>
                            <td class="table-primary" width="15%">남북바람성분</td><td width="17%">{{ $weatherData['VVV'] }}</td>

                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- 아코디언 영역 -->
        <div class="accordion mt-2 pb-5" id="title">
            <!--
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
                        <table class="table table-border text-center">
                            <tr>
                                <th class="table-success" width="15%">미세먼지</th><td width="17%">{{ $pm10Status }}</td>
                                <th class="table-success" width="15%">날씨</th><td width="17%">{{ $data_arr[$weatherData['PTY']] }}</td>
                                <th class="table-success" width="15%">습도</th><td width="17%">{{ $weatherData['REH'] }}</td>
                            </tr>
                            <tr>
                                <th class="table-primary" width="15%">1시간 강수량</th><td width="17%">{{ $weatherData['RN1'] }}</td>
                                <th class="table-primary" width="15%">기온</th><td width="17%">{{ $weatherData['T1H'] }}</td>
                                <th class="table-primary" width="15%">동서바람성분</th><td width="17%">{{ $weatherData['UUU'] }}</td>
                            </tr>
                            <tr>
                                <th class="table-success" width="15%">풍향</th><td width="17%">{{ $weatherData['VEC'] }}</td>
                                <th class="table-success" width="15%">남북바람성분</th><td width="17%">{{ $weatherData['VVV'] }}</td>
                                <th class="table-success" width="15%">풍속</th><td width="17%">{{ $weatherData['WSD'] }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            -->
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
                            <select class="form-control" style="width:200px;display:inline-block" id="area1" name="area1" onchange="loadArea(this.value, 1);searchPlaces('select');">
                                <option value="default">지역 1단계</option>
                                @foreach($area1_list as $area1)
                                <option value="{{ $area1['1st'] }}">{{ $area1['1st'] }}</option>
                                @endforeach
                            </select>
                            <select class="form-control" style="width:200px;display:inline-block" id="area2" name="area2" onchange="loadArea(this.value, 2);searchPlaces('select');">
                                <option>지역 2단계</option>
                            </select>
                            <select class="form-control" style="width:200px;display:inline-block" id="area3" name="area3" onchange="chgMap();">
                                <option>지역 3단계</option>
                            </select>
                            <button class="btn btn-primary" type="submit">저장</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /아코디언 영역 -->
    </div>

    <script>
        // 마커를 담을 배열입니다
        var markers = [];

        var mapContainer = document.getElementById('map'), // 지도를 표시할 div
            mapOption = {
                center: new kakao.maps.LatLng(37.566826, 126.9786567), // 지도의 중심좌표
                level: 3 // 지도의 확대 레벨
            };

        // 지도를 생성합니다
        var map = new kakao.maps.Map(mapContainer, mapOption);

        // 장소 검색 객체를 생성합니다
        var ps = new kakao.maps.services.Places();

        // 검색 결과 목록이나 마커를 클릭했을 때 장소명을 표출할 인포윈도우를 생성합니다
        var infowindow = new kakao.maps.InfoWindow({zIndex:1});

        // 키워드로 장소를 검색합니다
        searchPlaces("default");

        // 주소로 검색하는 지도
        function map_1() {
            $('#map_wrap_2').hide(function() {
                $('#map_wrap_1').show();
            });
            var mapContainer = document.getElementById('map1'), // 지도를 표시할 div
                mapOption = {
                    center: new kakao.maps.LatLng(33.250701, 126.570667), // 지도의 중심좌표
                    level: 3 // 지도의 확대 레벨
                };

            // 지도를 생성합니다
            var map = new kakao.maps.Map(mapContainer, mapOption);

            // 주소-좌표 변환 객체를 생성합니다
            var geocoder = new kakao.maps.services.Geocoder();

            // 주소로 좌표를 검색합니다
            geocoder.addressSearch('{{ $my_position['area1'].' '.$my_position['area2'].' '.$my_position['area3'] }}', function(result, status) {

                // 정상적으로 검색이 완료됐으면
                 if (status === kakao.maps.services.Status.OK) {

                     var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

                     // 결과값으로 받은 위치를 마커로 표시합니다
                     var marker = new kakao.maps.Marker({
                         map: map,
                         position: coords
                     });

                     // 인포윈도우로 장소에 대한 설명을 표시합니다
                     var infowindow = new kakao.maps.InfoWindow({
                         content: '<div style="width:150px;text-align:center;padding:6px 0;">우리동네</div>'
                     });
                     infowindow.open(map, marker);

                     // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
                     map.setCenter(coords);
                 }
            });
        }

        function map_2() {
            // 마커를 담을 배열입니다
            var markers = [];

            var mapContainer = document.getElementById('map'), // 지도를 표시할 div
                mapOption = {
                    center: new kakao.maps.LatLng(37.566826, 126.9786567), // 지도의 중심좌표
                    level: 3 // 지도의 확대 레벨
                };

            // 지도를 생성합니다
            var map = new kakao.maps.Map(mapContainer, mapOption);

            // 장소 검색 객체를 생성합니다
            var ps = new kakao.maps.services.Places();

            // 검색 결과 목록이나 마커를 클릭했을 때 장소명을 표출할 인포윈도우를 생성합니다
            var infowindow = new kakao.maps.InfoWindow({zIndex:1});

            // 키워드로 장소를 검색합니다
            searchPlaces("default");
        }

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

        function chgMap() {
            var area1 = $('#area1').val();
            var area2 = $('#area2').val();
            var area3 = $('#area3').val();

            var addrStr = "";
            if (area1 != "default" && area1 != "") addrStr += area1;
            if (area2 != "default" && area2 != "지역 2단계") addrStr += " "+area2;
            if (area3 != "default" && area3 != "지역 3단계") addrStr += " "+area3;

            // 주소-좌표 변환 객체를 생성합니다
            var geocoder = new kakao.maps.services.Geocoder();

            // 주소로 좌표를 검색합니다
            geocoder.addressSearch(addrStr, function(result, status) {

                // 정상적으로 검색이 완료됐으면
                 if (status === kakao.maps.services.Status.OK) {

                     var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

                     // 결과값으로 받은 위치를 마커로 표시합니다
                     var marker = new kakao.maps.Marker({
                         map: map,
                         position: coords
                     });

                     // 인포윈도우로 장소에 대한 설명을 표시합니다
                     var infowindow = new kakao.maps.InfoWindow({
                         content: '<div style="width:150px;text-align:center;padding:6px 0;">우리동네</div>'
                     });
                     infowindow.open(map, marker);

                     // 지도의 중심을 결과값으로 받은 위치로 이동시킵니다
                     map.setCenter(coords);
                 }
            });
        }

        // 키워드 검색을 요청하는 함수입니다
        function searchPlaces(mode) {

            var keyword = "";

            if (mode == "default") {
                keyword = "{{ $my_position['area1'].' '.$my_position['area2'].' '.$my_position['area3'] }}";
                keyword += ' '+document.getElementById('keyword').value;
            } else {
                var area1 = $('#area1').val();
                var area2 = $('#area2').val();
                var area3 = $('#area3').val();

                if (area1 != "default" && area1 != "") keyword += area1;
                if (area2 != "default" && area2 != "지역 2단계") keyword += " "+area2;
                if (area3 != "default" && area3 != "지역 3단계") keyword += " "+area3;
            }


            if (!keyword.replace(/^\s+|\s+$/g, '')) {
                alert('키워드를 입력해주세요!');
                return false;
            }

            // 장소검색 객체를 통해 키워드로 장소검색을 요청합니다
            ps.keywordSearch( keyword, placesSearchCB);
        }

        // 장소검색이 완료됐을 때 호출되는 콜백함수 입니다
        function placesSearchCB(data, status, pagination) {
            if (status === kakao.maps.services.Status.OK) {

                // 정상적으로 검색이 완료됐으면
                // 검색 목록과 마커를 표출합니다
                displayPlaces(data);

                // 페이지 번호를 표출합니다
                displayPagination(pagination);

            } else if (status === kakao.maps.services.Status.ZERO_RESULT) {

                alert('검색 결과가 존재하지 않습니다.');
                return;

            } else if (status === kakao.maps.services.Status.ERROR) {

                alert('검색 결과 중 오류가 발생했습니다.');
                return;

            }
        }

        // 검색 결과 목록과 마커를 표출하는 함수입니다
        function displayPlaces(places) {

            var listEl = document.getElementById('placesList'),
            menuEl = document.getElementById('menu_wrap'),
            fragment = document.createDocumentFragment(),
            bounds = new kakao.maps.LatLngBounds(),
            listStr = '';

            // 검색 결과 목록에 추가된 항목들을 제거합니다
            removeAllChildNods(listEl);

            // 지도에 표시되고 있는 마커를 제거합니다
            removeMarker();

            for ( var i=0; i<places.length; i++ ) {

                // 마커를 생성하고 지도에 표시합니다
                var placePosition = new kakao.maps.LatLng(places[i].y, places[i].x),
                    marker = addMarker(placePosition, i),
                    itemEl = getListItem(i, places[i]); // 검색 결과 항목 Element를 생성합니다

                // 검색된 장소 위치를 기준으로 지도 범위를 재설정하기위해
                // LatLngBounds 객체에 좌표를 추가합니다
                bounds.extend(placePosition);

                // 마커와 검색결과 항목에 mouseover 했을때
                // 해당 장소에 인포윈도우에 장소명을 표시합니다
                // mouseout 했을 때는 인포윈도우를 닫습니다
                (function(marker, title) {
                    kakao.maps.event.addListener(marker, 'mouseover', function() {
                        displayInfowindow(marker, title);
                    });

                    kakao.maps.event.addListener(marker, 'mouseout', function() {
                        infowindow.close();
                    });

                    itemEl.onmouseover =  function () {
                        displayInfowindow(marker, title);
                    };

                    itemEl.onmouseout =  function () {
                        infowindow.close();
                    };
                })(marker, places[i].place_name);

                fragment.appendChild(itemEl);
            }

            // 검색결과 항목들을 검색결과 목록 Element에 추가합니다
            listEl.appendChild(fragment);
            menuEl.scrollTop = 0;

            // 검색된 장소 위치를 기준으로 지도 범위를 재설정합니다
            map.setBounds(bounds);
        }

        // 검색결과 항목을 Element로 반환하는 함수입니다
        function getListItem(index, places) {

            var el = document.createElement('li'),
            itemStr = '<span class="markerbg marker_' + (index+1) + '"></span>' +
                        '<div class="info">' +
                        '   <h5>' + places.place_name + '</h5>';

            if (places.road_address_name) {
                itemStr += '    <span>' + places.road_address_name + '</span>' +
                            '   <span class="jibun gray">' +  places.address_name  + '</span>';
            } else {
                itemStr += '    <span>' +  places.address_name  + '</span>';
            }

              itemStr += '  <span class="tel">' + places.phone  + '</span>' +
                        '</div>';

            el.innerHTML = itemStr;
            el.className = 'item';

            return el;
        }

        // 마커를 생성하고 지도 위에 마커를 표시하는 함수입니다
        function addMarker(position, idx, title) {
            var imageSrc = 'https://t1.daumcdn.net/localimg/localimages/07/mapapidoc/marker_number_blue.png', // 마커 이미지 url, 스프라이트 이미지를 씁니다
                imageSize = new kakao.maps.Size(36, 37),  // 마커 이미지의 크기
                imgOptions =  {
                    spriteSize : new kakao.maps.Size(36, 691), // 스프라이트 이미지의 크기
                    spriteOrigin : new kakao.maps.Point(0, (idx*46)+10), // 스프라이트 이미지 중 사용할 영역의 좌상단 좌표
                    offset: new kakao.maps.Point(13, 37) // 마커 좌표에 일치시킬 이미지 내에서의 좌표
                },
                markerImage = new kakao.maps.MarkerImage(imageSrc, imageSize, imgOptions),
                    marker = new kakao.maps.Marker({
                    position: position, // 마커의 위치
                    image: markerImage
                });

            marker.setMap(map); // 지도 위에 마커를 표출합니다
            markers.push(marker);  // 배열에 생성된 마커를 추가합니다

            return marker;
        }

        // 지도 위에 표시되고 있는 마커를 모두 제거합니다
        function removeMarker() {
            for ( var i = 0; i < markers.length; i++ ) {
                markers[i].setMap(null);
            }
            markers = [];
        }

        // 검색결과 목록 하단에 페이지번호를 표시는 함수입니다
        function displayPagination(pagination) {
            var paginationEl = document.getElementById('pagination'),
                fragment = document.createDocumentFragment(),
                i;

            // 기존에 추가된 페이지번호를 삭제합니다
            while (paginationEl.hasChildNodes()) {
                paginationEl.removeChild (paginationEl.lastChild);
            }

            for (i=1; i<=pagination.last; i++) {
                var el = document.createElement('a');
                el.href = "#";
                el.innerHTML = i;

                if (i===pagination.current) {
                    el.className = 'on';
                } else {
                    el.onclick = (function(i) {
                        return function() {
                            pagination.gotoPage(i);
                        }
                    })(i);
                }

                fragment.appendChild(el);
            }
            paginationEl.appendChild(fragment);
        }

        // 검색결과 목록 또는 마커를 클릭했을 때 호출되는 함수입니다
        // 인포윈도우에 장소명을 표시합니다
        function displayInfowindow(marker, title) {
            var content = '<div style="padding:5px;z-index:1;">' + title + '</div>';

            infowindow.setContent(content);
            infowindow.open(map, marker);
        }

         // 검색결과 목록의 자식 Element를 제거하는 함수입니다
        function removeAllChildNods(el) {
            while (el.hasChildNodes()) {
                el.removeChild (el.lastChild);
            }
        }
    </script>
@endsection
