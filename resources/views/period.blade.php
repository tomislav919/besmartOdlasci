@extends('layouts.appUser')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @php

            @endphp
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header">
                <form action="period"  method="POST" id="formId">
                    @csrf
                    <div class="custom-font custom-font-height-2 txt-center" >Odaberite djelatnika i period
                        <select class="browser-default custom-select" name="user">
                            @if(isset($requestUser))
                                @else
                            <option selected>Odaberite djelatnika</option>
                            @endif
                            @foreach($users as $user)
                                @if(isset($requestUser))
                                    @if($user->id == $requestUser->id)
                                        <option selected value="{{$user->id}}">{{$user->name}} {{$user->lastName}}</option>
                                    @else
                                        <option value="{{$user->id}}">{{$user->name}} {{$user->lastName}}</option>
                                    @endif
                                @else
                                        <option value="{{$user->id}}">{{$user->name}} {{$user->lastName}}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    <div class="custom-font custom-font-height txt-center" >
                        <input type="text" class="form-control datetimepicker-input" name="daterange" value="
                            @if(isset($requestUser))
                            {{$requestUser->dateRange}}
                            @endif
                        " />
                        <script>
                            $(function() {
                                $('input[name="daterange"]').daterangepicker({
                                    opens: 'left'
                                }, function(start, end, label) {
                                    console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
                                });
                            });
                        </script>

                    </div>
                    <div class="custom-font custom-font-height-3 txt-center" >
                        <select class="browser-default custom-select" name="typeFilter">
                                <option selected value="1">Prikaz svih dolazaka/odlazaka</option>
                                <option value="2">Prikaz po danu</option>
                        </select>
                    </div>
                    <div class="custom-font padding-top-5 txt-center">
                        <input type="submit" class="btn btn-primary" value="Pošalji">
                    </div>
                </form>
                </div>
            </div>
        </div>

        </div>
        <br>


        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header">
                        @if(isset($requestUser))
                            {{$requestUser->name}} {{$requestUser->lastName}} ({{$requestUser->dateRange}})
                            @else
                            Period
                        @endif
                    </div>

                    <div class="card-body">
                        <table id="table_id" class="display">
                            <thead>
                            <tr class="txt-center">
                                <th>Rbr</th>
                                <th>Datum</th>
                                <th>Dolazak</th>
                                <th>Odlazak</th>
                                <th>Ukupno na pauzi</th>
                                <th>Detaljno</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $d)
                                <tr class="txt-center"
                                    @if(isset($d->trClass))
                                        id="{{$d->trClass}}"
                                    @endif>
                                    <td
                                        @if(isset($d->thClass))
                                        id="{{$d->thClass}}"
                                        @endif
                                    >{{$d->loopIt}}</td>
                                    <td>{{$d->date}}</td>
                                    <td>{{$d->arrival}}</td>
                                    <td>{{$d->departure}}</td>
                                    <td>{{$d->breakSum}}</td>
                                    <th>
                                        @if(isset($requestUser))
                                            <span class="goToUserDetails link" data-date="{{$d->date}}" data-id="{{$requestUser->id}}">detaljno</span>
                                        @endif
                                    </th>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                            <tr class="txt-center">
                                <th>Rbr</th>
                                <th>Datum</th>
                                <th>Dolazak</th>
                                <th>Odlazak</th>
                                <th>Ukupno na pauzi</th>
                                <th>Detaljno</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>




@endsection

