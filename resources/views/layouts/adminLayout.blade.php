@extends('layouts.template')
@section('title')Admin - @yield('subtitle') @endsection
@section('styles')
@endsection
@section('main')
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="#">[Insert site name here]</a>
                </div>
                <ul class="nav navbar-nav">
                    <li><a href="#">Albums</a></li>
                    <li><a href="#">Gebruikers</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="#">Loguit</a></li>
                </ul>
            </div>
        </nav>
    @yield('content')
@endsection