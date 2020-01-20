@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="card shadow">
            <form action="home" method="POST" id="formId">
                @csrf
                <div class="card-header custom-font custom-font-height" > Odaberite dan za prikaz
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input type="text" id="datetimepickerfield" name="formDate" value="{{$data->selectedDate}}" class="form-control datetimepicker-input txt-center" data-target="#datetimepicker1"/>
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
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header">Svi djelatnici ({{$data->selectedDate}})</div>

                <div class="card-body">
                    <table id="table_id" class="display">
                        <thead>
                        <tr class="txt-center">
                            <th>Rbr</th>
                            <th>Ime</th>
                            <th>Prezime</th>
                            <th>Vrijeme dolaska</th>
                            <th>Vrijeme odlaska</th>
                            <th>Ukupna Pauza</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr class="txt-center"
                                @if(isset($user->trClass))
                                id="{{$user->trClass}}"
                                @endif
                                >
                                <th
                                @if(isset($user->thClass))
                                    id = "{{$user->thClass}}"
                                    @endif
                                >{{$user->loopIt}}</th>
                                <td><a href="userreport/{{$user->id}}">{{$user->name}}</a></td>
                                <td>{{$user->lastName}}</td>
                                <td>
                                        @if(isset($user->arrival))
                                            {{$user->arrival}}
                                        @else
                                            /
                                        @endif
                                </td>
                                <td>
                                        @if(isset($user->departure))
                                            {{$user->departure}}
                                        @else
                                            /
                                        @endif
                                    </td>
                                <td>
                                        @if(isset($user->break))
                                            {{$user->break}}
                                        @else
                                            /
                                        @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr class="txt-center">
                            <th>Rbr</th>
                            <th>Ime</th>
                            <th>Prezime</th>
                            <th>Vrijeme dolaska</th>
                            <th>Vrijeme odlaska</th>
                            <th>Ukupna Pauza</th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




@endsection
