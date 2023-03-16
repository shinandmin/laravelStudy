<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Position;
use App\Models\MyPosition;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PositionController extends Controller
{
    public function get(Request $request) {
        $depth = $request->depth;
        $selArea = $request->area;
        $area1 = $request->area1;

        if ($depth == 1) {
            $areaList = Position::select('2st')->where('1st', $selArea)->where('2st', '!=', '')->groupBy('2st')->get();
        } else if ($depth == 2) {
            $areaList = Position::select('3st')->where('1st', $area1)->where('2st', $selArea)->where('3st', '!=', '')->groupBy('3st')->get();
        }

        return response()->json($areaList);
    }

    public function myArea(Request $request) {
        // 이미 저장된 데이터가 있는지 확인한다.
        $check = MyPosition::where('user_id', auth()->user()->id)->first();
        if ($check) {
            $check->area_1st = $request->area1;
            $check->area_2st = $request->area2;
            $check->area_3st = $request->area3;
            $check->save();
        } else {
            DB::table('my_positions')->insert([
                'user_id' => auth()->user()->id,
                'area_1st' => $request->area1,
                'area_2st' => $request->area2,
                'area_3st' => $request->area3,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect('/');
    }
}
