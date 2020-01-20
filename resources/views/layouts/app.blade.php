<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Administracija') }}</title>

    <!-- Scripts -->


    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/css/tempusdominus-bootstrap-4.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        @include('layouts.partials.userNav')

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/locale/hr.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.1/js/tempusdominus-bootstrap-4.min.js"></script>


    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

    <script>
        //datatable scripts
        $(document).ready( function () {
            $('#table_id').DataTable({
                pageLength: 30,
                language:{
                    "sEmptyTable":      "Nema podataka u tablici",
                    "sInfo":            "Prikazano _START_ do _END_ od _TOTAL_ rezultata",
                    "sInfoEmpty":       "Prikazano 0 do 0 od 0 rezultata",
                    "sInfoFiltered":    "(filtrirano iz _MAX_ ukupnih rezultata)",
                    "sInfoPostFix":     "",
                    "sInfoThousands":   ",",
                    "sLengthMenu":      "Prikaži _MENU_ rezultata po stranici",
                    "sLoadingRecords":  "Dohvaćam...",
                    "sProcessing":      "Obrađujem...",
                    "sSearch":          "Pretraži:",
                    "sZeroRecords":     "Ništa nije pronađeno",
                    "oPaginate": {
                        "sFirst":       "Prva",
                        "sPrevious":    "Nazad",
                        "sNext":        "Naprijed",
                        "sLast":        "Zadnja"
                    },
                    "oAria": {
                        "sSortAscending":  ": aktiviraj za rastući poredak",
                        "sSortDescending": ": aktiviraj za padajući poredak"
                    }
                },
            });
        } );

        $(document).ready( function () {
            $('#table_id2').DataTable({
                pageLength: 30,
                language:{
                    "sEmptyTable":      "Nema podataka u tablici",
                    "sInfo":            "Prikazano _START_ do _END_ od _TOTAL_ rezultata",
                    "sInfoEmpty":       "Prikazano 0 do 0 od 0 rezultata",
                    "sInfoFiltered":    "(filtrirano iz _MAX_ ukupnih rezultata)",
                    "sInfoPostFix":     "",
                    "sInfoThousands":   ",",
                    "sLengthMenu":      "Prikaži _MENU_ rezultata po stranici",
                    "sLoadingRecords":  "Dohvaćam...",
                    "sProcessing":      "Obrađujem...",
                    "sSearch":          "Pretraži:",
                    "sZeroRecords":     "Ništa nije pronađeno",
                    "oPaginate": {
                        "sFirst":       "Prva",
                        "sPrevious":    "Nazad",
                        "sNext":        "Naprijed",
                        "sLast":        "Zadnja"
                    },
                    "oAria": {
                        "sSortAscending":  ": aktiviraj za rastući poredak",
                        "sSortDescending": ": aktiviraj za padajući poredak"
                    }
                },
            });
        } );
    </script>

    <script type="text/javascript">
        //datetimepicker script
        //initialisation and options
        $(function () {
            $('#datetimepicker1').datetimepicker({
                format: 'L',
                language: 'hr'
            });
            $('#datetimepicker1').on("change.datetimepicker", function (e) {
                $('#formId').submit();
            });
        });


    </script>

    <script>
        //on hover change color of td
        $( "#tr-color" ).hover(
            function() {
                $( "#th-color-dark" ).attr( "id", "th-color-darker" );
            }, function() {
                $( "#th-color-darker" ).attr( "id", "th-color-dark" );
            }
        );
    </script>




</body>
</html>
