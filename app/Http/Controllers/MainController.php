<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Position;
use App\Models\MyPosition;
use App\Models\Measuring_station;

class MainController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/auth/signin');
        }

        /*--------------------------------------------------
         * 우리동네 정보 가져오기 : default > 서울특별시 강남구 압구정동
         *-------------------------------------------------*/
        $my_poisition = MyPosition::where('user_id', auth()->user()->id)->first();

        $position = array();
        $position['nx'] = "61";
        $position['ny'] = "126";
        $position['area1'] = "서울특별시";
        $position['area2'] = "강남구";
        $position['area3'] = "압구정동";

        if ($my_poisition) {
            $my_poisition_data = Position::select('1st as area1', '2st as area2', '3st as area3', 'posX', 'posY')->where([
                '1st' => $my_poisition->area_1st,
                '2st' => $my_poisition->area_2st,
                '3st' => $my_poisition->area_3st,
            ])->first();

            $position['nx'] = $my_poisition_data->posX;;
            $position['ny'] = $my_poisition_data->posY;
            $position['area1'] = $my_poisition_data->area1;
            $position['area2'] = $my_poisition_data->area2;
            $position['area3'] = $my_poisition_data->area3;
        }

        /*--------------------------------------------------
         * 날씨정보 가져오기
         *-------------------------------------------------*/
        // 기상청 - 초단기 실황정보 조회
        $data = array(
            'serviceKey' => 'GunMw/0SkOU9IOCdYEpaU6MQDlcp2Gs26sDb3fUAFU3SxoSQI80ArRDvrQ0fQZhO0Obhlw4QPzVCcSXOo+2dHw==',
            'numOfRows' => '10',
            'pageNo' => '1',
            'dataType' => 'JSON',
            'base_date' => date('Ymd'),
            'base_time' => date('Hi'),
            'nx' => $position['nx'],
            'ny' => $position['ny'],
        );

        $url = "http://apis.data.go.kr/1360000/VilageFcstInfoService_2.0/getUltraSrtNcst" . "?" . http_build_query($data, '');

        $do_curl = do_curl($url);

        $result_list = "";
        if ($do_curl) {
            $result_list = $do_curl->response->body->items->item;
        }

        /*--------------------------------------------------
         * 미세먼지 정보 가져오기
         *-------------------------------------------------*/
        // 측정소 정보 가져오기
        $getMeasuring = Measuring_station::where('agency', 'LIKE', $position['area1'].'%')->where('address', 'LIKE', '%'.$position['area2'].'%')->first();

        // 에어코리아 - 대기오염정보 조회 서비스
        $data2 = array(
            'stationName' => $getMeasuring->measuring_station,
            'dataTerm' => 'DAILY',
            'pageNo' => '1',
            'numOfRows' => '1',
            'returnType' => 'json',
            'serviceKey' => "GunMw/0SkOU9IOCdYEpaU6MQDlcp2Gs26sDb3fUAFU3SxoSQI80ArRDvrQ0fQZhO0Obhlw4QPzVCcSXOo+2dHw==",
        );

        $url2 = "http://apis.data.go.kr/B552584/ArpltnInforInqireSvc/getMsrstnAcctoRltmMesureDnsty" . "?" . http_build_query($data2, '');

        $do_curl2 = do_curl($url2);

        $result_list2 = "";
        if ($do_curl2) {
            $result_list2 = $do_curl2->response->body->items[0];
        }

        $pm10Status = "";
        switch ($result_list2->pm10Grade) {
            case 1 :
                $pm10Status = "좋음";
                break;
            case 2 :
                $pm10Status = "보통";
                break;
            case 3 :
                $pm10Status = "나쁨";
                break;
            case 4 :
                $pm10Status = "매우나쁨";
                break;
        }

        // 지역 1단계 리스트
        $area1_list = Position::select('1st')->groupByRaw('1st')->get();

        /*--------------------------------------------------
         * view 전달 데이터 리스트
         *--------------------------------------------------
         * result_list : 기상청 초단기 실황정보 조회 결과
         * area1_list : 1단계 지역 리스트
         * my_position : 우리동네 정보
         * pm10Status : 우리동네 미세먼지 정보
         *-------------------------------------------------*/
        return view('index', ['result_list' => $result_list, 'area1_list' => $area1_list, 'my_position' => $position, 'pm10Status' => $pm10Status]);
    }
}
