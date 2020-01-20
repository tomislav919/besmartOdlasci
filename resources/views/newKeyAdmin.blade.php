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
                            Novi ključići <br>
                        </div>
                    </div>
                    <div class="card-body margin-top-table">
                        @if(!$newKeys->isEmpty())
                            <table class="table">
                                <thead>
                                <tr class="txt-center no-border-top">
                                    <th scope="col">Rbr</th>
                                    <th scope="col">Ključić</th>
                                    <th scope="col">Napravljen</th>
                                    <th scope="col">Ime</th>
                                    <th scope="col">Prezime</th>
                                    <th scope="col">Spol</th>
                                    <th scope="col">Obriši</th>
                                    <th scope="col">Dodaj</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($newKeys as $n)
                                    <tr class="txt-center vertical-align">
                                        <form action="addNewUser"  method="POST">
                                            @csrf
                                            <input type="hidden" name="keyId" value="{{$n->userKeyId}}">
                                            <div class="form-group">
                                                <th scope="row" >{{$n->loopIt}}</th>
                                                <td>{{$n->userKeyId}}</td>
                                                <td>{{$n->createdFormat}}</td>
                                                <td><input type="text" class="form-control" name="name" value=""></td>
                                                <td><input type="text" class="form-control" name="lastName" value=""></td>
                                                <td>
                                                    <select class="browser-default custom-select" name="gender">
                                                        <option value="1" select>Žena</option>
                                                        <option value="0">Muškarac</option>
                                                    </select>
                                                </td>
                                                <td><span class="deleteNewKey" data-id="{{$n->id}}"><button class="btn btn-danger" value="">&#10007;</button></span></td>
                                                <td><input type="submit" class="btn btn-success" value="&#10003;"></td>
                                            </div>
                                        </form>
                                    </tr>
                                @endforeach
                                @else
                                  <br>
                                    <p class="text-center custom-p">Nema novih ključića, dodajte novi ključić </p>
                                @endif
                                </tbody>
                            </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

