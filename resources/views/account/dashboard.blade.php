@extends('layouts.app')

@section('pagestyle')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/site.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/alertify.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/alertifyboot_theme.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('css/vue-datetime.min.css') }}">
@endsection

@section('content')

<div class="page-wrapper" id="hplaccapp">

    <div class="page-loader" v-show="pgload">
        <div class="d-flex justify-content-center align-items-center loader-wrapper">
            <div class="loader-content">
                <div class="spinner-grow" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                <p class="py-2 pl-3 fs-7">Loading...</p>
            </div>
        </div>
    </div>

   @include('shared.nav')

   <div class="page-container" v-if="clear">

        @include('shared.sidebar')

        <div class="main-content">

            {{-- <dashboard></dashboard> --}}
            <router-view></router-view>

        </div>

   </div>
   <div class="page-error" v-else>
        <div class="jumbotron jumbotron-fluid">
            <div class="container text-center">
                <h3>Your account has not been verified yet.</h3>
                <p class="lead">Contact administrator if the problem persist.</p>
                <div>
                    <button class="btn btn-secondary" onclick="event.preventDefault(); document.getElementById('page-error-form').submit();">
                        Logout
                    </button>
                    <form id="page-error-form" action="{{ url('hpl/logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
   </div>

</div>

@endsection


@section('pagejs')
    <script src="{{ URL::asset('js/accsite.js') }}"></script>
    <script src="{{ URL::asset('js/alertify.min.js') }}"></script>
@endsection
