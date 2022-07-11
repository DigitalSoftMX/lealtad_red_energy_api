<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <title>SISTEMA DE VENTAS LITE</title>

    <!-- BEGIN GLOBAL MANDATORY STYLES -->
    <!-- @ include('error.styles') -->
    <!-- END GLOBAL MANDATORY STYLES -->

</head>
<body class="error404 text-center">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mr-auto mt-5 text-md-left text-center">
                <a href="{{url('/pos')}}" class="ml-md-5">
                    <img alt="image-404" src="{{ asset('assets/img/logo.jpg') }}" class="theme-logo">
                </a>
            </div>
        </div>
    </div>
    <div class="container-fluid error-content">
        <div class="">
            <h1 class="error-number">{{$status}}</h1>
            <p class="mini-text">Algo salio mal!</p>
            <p class="error-text mb-4 mt-1">{{$message}}</p>
            <a href="{{url('/')}}" class="btn btn-primary mt-5">Regresar</a>
        </div>
    </div>
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <!-- @ include('error.scripts') -->
    <!-- END GLOBAL MANDATORY SCRIPTS -->
</body>
</html>
