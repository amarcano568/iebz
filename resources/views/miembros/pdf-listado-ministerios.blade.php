<!DOCTYPE html>
<html>
    <head>
        <title></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('css/bootstrap-3-simple.min.css') }}" rel="stylesheet" />
        <style>
        /** Define the margins of your page **/
        @page {
        margin: 100px 25px;
        }
        header {
        position: fixed;
        top: -80px;
        left: 0px;
        right: 0px;
        height: 40px;
        /** Extra personal styles **/
        color: black;
        text-align: center;
        }
        footer {
        position: fixed;
        bottom: -60px;
        left: 0px;
        right: 0px;
        height: 50px;
        /** Extra personal styles **/
        background-color: #03a9f4;
        color: white;
        text-align: center;
        line-height: 35px;
        }
 
        </style>
       
    </head>
    <body>
        <header>
            <img src="img/logo.png" alt="" width="15%" height="75%">
            <br>
            <span  class="text-center">INFORME DE MINISTERIOS ( {{ $nombreMinisterio }} )</span>
            <hr>
        </header>
        <br>
        
            {!! $salida !!}
       
    </body>
</html>