<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h3><strong>月次比較財務諸表(損益計算書)</strong></h3>
    <h4>{{$year-1}}年4月1日 〜 {{$year}}年3月31日</h4>
    <h4></h4>
    <table border="3">
        <thead>
            <tr>
                <th>勘 定 科 目</th>
                <th>4 月</th>
                <th>5 月</th>
                <th>6 月</th>
                <th>7 月</th>
                <th>8 月</th>
                <th>9 月</th>
                <th>10 月</th>
                <th>11 月</th>
                <th>12 月</th>
                <th>1 月</th>
                <th>2 月</th>
                <th>3 月</th>
                <th>当期累計</th>
            </tr>
        </thead>
        <thead>
                    @for($i = 0; $i < count($data); $i++)
                        @if(count($data[$i]["categories"])> 0)
                            @foreach($data[$i]["categories"] as $category)
                                <tr>
                                    <th>{{ $category['category_name'] }}</th>
                                    @foreach($category["data"] as $item)
                                        <th>{{ number_format($item, 2, '.', ',') }}</th>
                                    @endforeach
                                </tr>
                            @endforeach
                        <tr>
                            <th>{{$data[$i]["group_name"]}}</th>
                            @foreach($data[$i]["total_month"] as $itemData)
                                <th>{{number_format($itemData, 2, '.', ',')}}</th>
                            @endforeach
                        </tr>
                        @else
                            <tr>
                                <th>{{$data[$i]["group_name"]}}</th>
                                @foreach($data[$i]["total_month"] as $itemData)
                                    <th>{{number_format($itemData, 2, '.', ',')}}</th>
                                @endforeach
                            </tr>
                        @endif
                    @endfor
        </thead>
    </table>
</body>