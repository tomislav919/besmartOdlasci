@extends('layouts.appUser')

@section('content')
    <div class="container">
    @if (session('alert'))
            <script>alert("{{ session('alert') }}");</script>
    @endif
        <br>
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow">
                    <div class="card-header">
                        <div class="custom-font custom-font-height-2 txt-center" >
                            Administriranje ključića djelatnika <br>
                        </div>
                    </div>
                    <div class="card-body margin-top-table">
                        @if(!$users->isEmpty())
                        <table class="table">
                            <thead>
                            <tr class="txt-center no-border-top">
                                <th scope="col">Rbr</th>
                                <th scope="col">Ključić</th>
                                <th scope="col">Ime</th>
                                <th scope="col">Prezime</th>
                                <th scope="col">Spol</th>
                                <th scope="col">Izmjena</th>
                                <th scope="col">Dodaj</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr class="txt-center vertical-align row{{$user->loopIt}}">
                                    <form action="userEdit"  method="POST">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$user->id}}">
                                        <div class="form-group">
                                            <th scope="row" >{{$user->loopIt}}</th>
                                            <td><input type="text" class="form-control txt-center disable" name="keyId" value="{{$user->keyId}}" disabled></td>
                                            <td><input type="text" class="form-control txt-center disable" name="name" value="{{$user->name}}" disabled></td>
                                            <td><input type="text" class="form-control txt-center disable" name="lastName" value="{{$user->lastName}}" disabled></td>
                                            <td>
                                                <select class="browser-default custom-select disable" name="gender" disabled>
                                                    @if($user->gender == 1)
                                                        <option value="1" selected>Žena</option>
                                                        <option value="0">Muškarac</option>
                                                    @else
                                                        <option value="1">Žena</option>
                                                        <option value="0" selected>Muškarac</option>
                                                    @endif
                                                </select>
                                            </td>
                                            <td><button type="button" data-id="{{$user->loopIt}}" class="btn btn-info change-btn enableInput" value="">&#10054;</button></td>
                                            <td><input type="submit" disabled class="btn btn-success disable" value="&#10003;"></td>
                                        </div>
                                    </form>
                                </tr>
                            @endforeach
                            @else
                                <p class="text-center custom-p">Nema djelatnika!</p>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

