@extends('layout.index')

@section('head_include')

@section('content')
    @foreach($result_list as $list)
        @switch($list->category)
            @case('PTY')
                강수형태 : {{ $list->obsrValue }} <br/>
                @break
            @case('REH')
                습도 : {{ $list->obsrValue }} <br/>
                @break
            @case('RN1')
                1시간 강수량 : {{ $list->obsrValue }} <br/>
                @break
            @case('T1H')
                기온 : {{ $list->obsrValue }} <br/>
                @break
            @case('UUU')
                동서바람성분 : {{ $list->obsrValue }} <br/>
                @break
            @case('VEC')
                풍향 : {{ $list->obsrValue }} <br/>
                @break
            @case('VVV')
                남북바람성분 : {{ $list->obsrValue }} <br/>
                @break
            @case('WSD')
                풍속 : {{ $list->obsrValue }} <br/>
                @break
            @default
        @endswitch
    @endforeach
@endsection
