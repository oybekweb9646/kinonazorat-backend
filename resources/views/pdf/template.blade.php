<?php

use App\Core\Enums\Role\RoleEnum;
use Carbon\Carbon;
$user = auth()->user();
?>
    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Document</title>
    <style>
        .container {
        }

        .header_container {
            text-align: center;
        }

        .header_image {
            width: 100px;
            height: 100px;
        }


        .doc_date_container {
            display: flex;
            flex-direction: column;
            gap: 8px;
            justify-content: space-between;
            margin-top: 32px;
        }

        .main_title {
            padding-left: 32px;
            padding-right: 32px;
            margin-top: 32px;
            text-align: center;
        }

        .main_title_2 {
            padding-left: 32px;
            padding-right: 32px;
            margin-top: 32px;
            text-align: center;
            font-weight: 400;
        }

        .font_bold {
            font-weight: 700;
        }

        .info_container {
            display: flex;
            flex-direction: column;
            gap: 8px;
            font-size: 18px;
        }
    </style>

</head>
<body>
<div class="container">
    <div class="header_container">
        <img src="{{ public_path('images/img.png') }}" class="header_image" alt="Emblem of Uzbekistan">
    </div>
    <!-- doc date -->
    <div class="doc_date_container">
        <div>
            <span>Ҳужжат яратилган сана:</span>
            <span>{{Carbon::parse($request['created_at'])->toDateString()}}</span>
        </div>
        <div>
            <span>Ҳужжат рақами:</span>
            <span>{{$request['request_no']}}</span>
        </div>
    </div>
    <!-- Main title -->
    <h3 class="main_title">
        @php if($user->role == RoleEnum::_TERRITORIAL_RESPONSIBLE): @endphp
             {{$user->region->name_uzc}} Ахборот ва оммавий коммуникациялар бошқармаси
        @php else: @endphp
            Ўзбекистон Республикаси Президенти Администрацияси ҳузуридаги Ахборот ва оммавий коммуникациялар агентлиги
        @php endif; @endphp
    </h3>

    <h3 class="main_title_2">
        «Ягона давлат назорати» ахборот тизимида орқали ишлаб чиқилган методика асосида назорат қилувчи органларнинг
        фаолиятини баҳолаш, уларнинг рейтингини тўғрисида МАЪЛУМОТНОМА
    </h3>

    <div class="info_container">
        <div>Ушбу маълумотнома <span class="font_bold">{{$request['authority']['name']}}</span>га фаолият
            тури {{$request['indicator_type']['name']}}</div>
        <div class="font_bold">Жойлашган жойи (почта манзили) - {{$request['authority']['billing_address']}}</div>
        <div class="font_bold">Солиқ тўловчининг инентификациялаш <br> рақами (СТИР) - {{$request['authority']['stir']}}
        </div>
        <div class="font_bold">Лицензия рақами -</div>
        <div class="font_bold">Лицензия берилган сана -</div>
        <div class="font_bold">Лицензия амал қилиш муддати -</div>
        <div class="font_bold">Жойлашган жойи (фаолият манзили) - {{$request['authority']['billing_address']}}</div>
        <div class="font_bold">Олинган балл кўрсатгичи - {{$request['score']}}</div>
    </div>
</div>
</body>
</html>
