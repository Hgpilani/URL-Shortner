@extends('layouts.app')

@section('header')
    <h2 class="h4 mb-0">{{ __('Dashboard') }}</h2>
@endsection

@section('content')
    <div class="container py-4">
        <div class="card">
            <div class="card-body">
                    {{ __("You're logged in!") }}
            </div>
        </div>
    </div>
@endsection
