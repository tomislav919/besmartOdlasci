@extends('layouts.appUser')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="card shadow">
                <form action="{{$user->id}}" method="POST" id="formId">
                    @csrf
                    <div class="card-header custom-font custom-font-height txt-center" >{{$user->name}} {{$user->lastName}}
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input type="text" id="datetimepickerfield" name="formDate" value="
                                    @if($data->sessionDate == null)
                                    {{$data->selectedDate}}
                                    @else
                                    {{$data->sessionDate}}
                                    @endif
                                " class="form-control datetimepicker-input txt-center" data-target="#datetimepicker1"/>
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <input type="submit" value="submit" hidden>
                    </div>
                </form>
            </div>
        </div>
        <br>



        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">Pauze ({{$data->selectedDate}})</div>

                    <div class="card-body">
                        <table id="table_id" class="display">
                            <thead>
                            <tr class="txt-center">
                                <th>Rbr</th>
                                <th>Početak pauze</th>
                                <th>Kraj Pauze</th>
                                <th>Ukupno na pauzi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dataBreak as $d)
                                <tr class="txt-center">
                                    <td>{{$d->loopIt}}</td>
                                    <td>{{$d->checkout}}</td>
                                    <td>{{$d->checkin}}</td>
                                    <td>{{$d->onBreak}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="txt-center">
                                <th>Rbr</th>
                                <th>Početak pauze</th>
                                <th>Kraj Pauze</th>
                                <th>Pauza</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-header">Dolsci i odlasci s posla ({{$data->selectedDate}})</div>
                    <div class="card-body">
                        <table id="table_id2" class="display">
                            <thead>
                            <tr class="txt-center">
                                <th>Rbr</th>
                                <th>Dolazak</th>
                                <th>Odlazak</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($dataWork as $d)
                                <tr class="txt-center">
                                    <td>{{$d->loopIt}}</td>
                                    <td>{{$d->arrival}}</td>
                                    <td>{{$d->departure}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="txt-center">
                                <th>Rbr</th>
                                <th>Dolazak</th>
                                <th>Odlazak</th>
                            </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection
