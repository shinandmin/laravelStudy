<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Position;
use App\Models\MyPosition;

class MainController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/auth/signin');
        }

        // 우리동네 정보 가져오기
        $my_poisition = MyPosition::where('user_id', auth()->user()->id)->first();

        $position = array();
        $position['nx'] = "61";
        $position['ny'] = "126";
        $position['name'] = "서울시 강남구 압구정동";

        if ($my_poisition) {
            $my_poisition_data = Position::select('1st as area1', '2st as area2', '3st as area3', 'posX', 'posY')->where([
                '1st' => $my_poisition->area_1st,
                '2st' => $my_poisition->area_2st,
                '3st' => $my_poisition->area_3st,
            ])->first();

            $position['nx'] = $my_poisition_data->posX;;
            $position['ny'] = $my_poisition_data->posY;
            $position['name'] = $my_poisition_data->area3;
            $position['area1'] = $my_poisition_data->area1;
            $position['area2'] = $my_poisition_data->area2;
            $position['area3'] = $my_poisition_data->area3;

        }

        // 초단기 실황
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

        $ch = curl_init();                                              //curl 초기화
        curl_setopt($ch, CURLOPT_URL, $url);                      //URL 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     //요청 결과를 문자열로 반환
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);        //connection timeout 5초
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);     //원격 서버의 인증서가 유효한지 검사 안함

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response);

        $result_list = "";
        if ($result) {
            $result_list = $result->response->body->items->item;
        }

        // 미세먼지
        $data2 = array(
            'stationName' => '종로구',
            'dataTerm' => 'DAILY',
            'pageNo' => '1',
            'numOfRows' => '1',
            'returnType' => 'json',
            'serviceKey' => "GunMw/0SkOU9IOCdYEpaU6MQDlcp2Gs26sDb3fUAFU3SxoSQI80ArRDvrQ0fQZhO0Obhlw4QPzVCcSXOo+2dHw==",
        );

        $url2 = "http://apis.data.go.kr/B552584/ArpltnInforInqireSvc/getMsrstnAcctoRltmMesureDnsty" . "?" . http_build_query($data2, '');

        $ch2 = curl_init();                                              //curl 초기화
        curl_setopt($ch2, CURLOPT_URL, $url2);                      //URL 지정하기
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);     //요청 결과를 문자열로 반환
        curl_setopt($ch2, CURLOPT_CONNECTTIMEOUT, 5);        //connection timeout 5초
        curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, false);     //원격 서버의 인증서가 유효한지 검사 안함

        $response2 = curl_exec($ch2);
        curl_close($ch2);

        $result2 = json_decode($response2);

        $result_list2 = "";
        if ($result2) {
            $result_list2 = $result2->response->body->items[0];
        }

        echo $result_list2->dataTime;

        // 지역 1단계 리스트
        $area1_list = Position::select('1st')->groupByRaw('1st')->get();

        return view('index', ['result_list' => $result_list, 'area1_list' => $area1_list, 'my_position' => $position]);
    }
}
