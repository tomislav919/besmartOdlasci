@extends('layouts.appUser')

@section('content')

<div class="row justify-content-center">
    @php

        @endphp
    <div class="col-md-4">
        <div class="card shadow">
            <div class="card-header">
                <form action="period"  method="POST" id="formId">
                    @csrf
                    <div class="custom-font custom-font-height-2 txt-center" >Izmjena Djelatnika
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
                    <div class="custom-font padding-top-5 txt-center">
                        <input type="submit" class="btn btn-primary" value="Pošalji">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@endsection
