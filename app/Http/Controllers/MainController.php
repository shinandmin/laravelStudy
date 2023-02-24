<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect('/auth/signin');
        }

        $data = array(
            'serviceKey' => 'GunMw/0SkOU9IOCdYEpaU6MQDlcp2Gs26sDb3fUAFU3SxoSQI80ArRDvrQ0fQZhO0Obhlw4QPzVCcSXOo+2dHw==',
            'numOfRows' => '10',
            'pageNo' => '1',
            'dataType' => 'JSON',
            'base_date' => date('Ymd'),
            'base_time' => date('Hi'),
            'nx' => '61',
            'ny' => '126',
        );

        // 초단기 실황
        $url = "http://apis.data.go.kr/1360000/VilageFcstInfoService_2.0/getUltraSrtNcst" . "?" . http_build_query($data, '');

        $ch = curl_init();                                              //curl 초기화
        curl_setopt($ch, CURLOPT_URL, $url);                     //URL 지정하기
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response);
        $result_list = $result->response->body->items->item;
        // print_r($result_list);
        return view('index', ['result_list' => $result_list]);
    }
}
