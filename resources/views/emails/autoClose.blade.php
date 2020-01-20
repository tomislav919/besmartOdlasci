<!DOCTYPE html>
<html>
<head>

    <title>Djelatnici koji se jučer nisu odjavili sa posla</title>

</head>

<body>

<p>Djelatnici koji se jučer nisu odjavili sa posla:<p>

<ul>

    @foreach($user as $user)
        <h3><li> {{ $user->name }} {{$user->lastName}} </li></h3>
    @endforeach
</ul>

</body>
</html>
