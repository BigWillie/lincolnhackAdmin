@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <h2>Post a message</h2>
        </div>
    </div>
    <messages></messages>
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
        <h2>Tshirts</h2>
        </div>
    </div>

    <tshirt source="../api/shirts"></tshirt>
@endsection
