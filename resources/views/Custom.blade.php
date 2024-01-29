<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom View</title>
    <style>
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 0 auto;
        }
    
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        h3{
            text-align: center
        }
        h4{
            text-align:center
        }
    </style>
</head>

<body>
    <h3><strong>月次比較財務諸表(貸借対照表)</strong></h3>
    <h4>{{$year-1}}年4月1日 〜 {{$year}}年3月31日</h4>
    <h4></h4>
    <table border="3">
        <thead>
            <tr>
                <th>勘 定 科 目</th>
                <th>{{$year-1}}年度繰越</th>
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
            <thead>
                @for($i = 0; $i < count($data); $i++)
                    @if(count($data[$i]["categories"])> 0)
                        @foreach($data[$i]["categories"] as $category)
                            <tr>
                                <th>{{ $category['category_name'] }}</th>
                                @foreach($category["data"] as $item)
                                    <th>{{ $item }}</th>
                                @endforeach
                            </tr>
                        @endforeach
                        <tr>
                            <th>{{$data[$i]["group_name"]}}</th>
                            @foreach($data[$i]["total_month"] as $itemData)
                                <th>{{$itemData}}</th>
                            @endforeach
                        </tr>
                    @else
                        <tr>
                            <th>{{$data[$i]["group_name"]}}</th>
                            @foreach($data[$i]["total_month"] as $itemData)
                                <th>{{$itemData}}</th>
                            @endforeach
                        </tr>
                    @endif
                @endfor

        </thead>
    </table>
</body>

</html>