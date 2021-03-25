@extends('layouts.base', ["title"=> "Dashboard",
                            "sectionTitle"=>"Dashboard",
                            "subSectionTitle"=>"Dashboard",
                            "subSectionSubTitle"=>"Main"])

@section('content')

@endsection

<body class="">
    <div class="page">
        <div class="page-content">
            <div class="container text-center">
                <div class="display-1 text-primary mb-5"> 404</div>
                <h1 class="h2  mb-3">Página no encontrada</h1>
                <p class="h4 font-weight-normal mb-7 leading-normal">La página a la que intentaste acceder no se encontró</p>
                <a class="btn btn-primary" href="{{route('dashboard')}}">
                    Volver al Dashboard
                </a>
            </div>
        </div>
    </div>
</body>
