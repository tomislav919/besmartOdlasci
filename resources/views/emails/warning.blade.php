<!DOCTYPE html>
<html>
<head>

    <title>Pauza - kašnjenje</title>

</head>

<body>

<p>Djelatnik je prekoračio dozvoljeno vrijeme pauze.<p>

<h1> {{ $user->name }} {{$user->lastName}} </h1>

<p>Ukupno vrijeme na pauzi za današnji dan:<p>

<h1>@if (!($onBreakH == '00'))
        {{$onBreakH}} h i
    @endif
    {{ $onBreakMin }} min</h1>

Izvještaj o pauzama:
<br>
<table style="border: 2px solid black">
    <tr style="border: 1px solid black;background-color:#e4e8eb;">
        <th>Početak pauze</th>
        <th>Kraj pauze</th>
        <th>Vrijeme na pauzi</th>
    </tr>
    @foreach ($dataMail as $data)
        <tr style="border: 1px solid black;">
            <td style="padding: 10px">{{$data->checkout}}</td>
            <td style="padding: 10px">{{$data->checkin}}</td>
            <td style="padding: 10px"><center>
                    @if(!(gmdate("H", $data->onBreakTimestamp) == '00'))
                        {{gmdate("H", $data->onBreakTimestamp)}} h i
                    @endif
                    {{gmdate("i", $data->onBreakTimestamp)}} min</center></td>
        </tr>
    @endforeach

</table>








</body>
</html>
