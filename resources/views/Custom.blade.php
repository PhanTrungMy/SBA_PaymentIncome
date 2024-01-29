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
            <tr>
                <th>{{$data[0]["categories"][0]["category_name"]}}</th>
                @foreach($data[0]["categories"][0]["data"] as $index=> $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>
            <tr>
                <th>{{$data[0]["categories"][1]["category_name"]}}</th>
                @foreach($data[0]["categories"][1]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>
            <tr>
                <th>{{$data[0]["categories"][2]["category_name"]}}</th>
                @foreach($data[0]["categories"][2]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[0]["group_name"]}}</th>
                @foreach($data[0]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[1]["categories"][0]["category_name"]}}</th>
                @foreach($data[1]["categories"][0]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[1]["group_name"]}}</th>
                @foreach($data[1]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[2]["group_name"]}}</th>
                @foreach($data[2]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[3]["categories"][0]["category_name"]}}</th>
                @foreach($data[3]["categories"][0]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[3]["group_name"]}}</th>
                @foreach($data[3]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[4]["group_name"]}}</th>
                @foreach($data[4]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[5]["categories"][0]["category_name"]}}</th>
                @foreach($data[5]["categories"][0]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[5]["categories"][1]["category_name"]}}</th>
                @foreach($data[5]["categories"][1]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[5]["categories"][2]["category_name"]}}</th>
                @foreach($data[5]["categories"][2]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[5]["group_name"]}}</th>
                @foreach($data[5]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[6]["group_name"]}}</th>
                @foreach($data[6]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[7]["group_name"]}}</th>
                @foreach($data[7]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[8]["categories"][0]["category_name"]}}</th>
                @foreach($data[8]["categories"][0]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[8]["categories"][1]["category_name"]}}</th>
                @foreach($data[8]["categories"][1]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[8]["categories"][2]["category_name"]}}</th>
                @foreach($data[8]["categories"][2]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[8]["categories"][3]["category_name"]}}</th>
                @foreach($data[8]["categories"][3]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[8]["group_name"]}}</th>
                @foreach($data[8]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[9]["group_name"]}}</th>
                @foreach($data[9]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[10]["group_name"]}}</th>
                @foreach($data[10]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr>
                <th>{{$data[11]["categories"][0]["category_name"]}}</th>
                @foreach($data[11]["categories"][0]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[11]["group_name"]}}</th>
                @foreach($data[11]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>
            
            <tr>
                <th>{{$data[12]["categories"][0]["category_name"]}}</th>
                @foreach($data[12]["categories"][0]["data"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[12]["group_name"]}}</th>
                @foreach($data[12]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[13]["group_name"]}}</th>
                @foreach($data[13]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[14]["group_name"]}}</th>
                @foreach($data[14]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[15]["group_name"]}}</th>
                @foreach($data[15]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>
            <tr style="background-color: #a199b1;">
                <th>{{$data[16]["group_name"]}}</th>
                @foreach($data[16]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[17]["group_name"]}}</th>
                @foreach($data[17]["total_month"] as $item)
                <th>{{$item}}</th>
                @endforeach
            </tr>

            <tr style="background-color: #a199b1;">
                <th>{{$data[18]["group_name"]}}</th>
                @foreach($data[18]["total_month"] as $item)
                    <th>{{$item}}</th>
                @endforeach
            </tr>
        </thead>
    </table>
</body>

</html>