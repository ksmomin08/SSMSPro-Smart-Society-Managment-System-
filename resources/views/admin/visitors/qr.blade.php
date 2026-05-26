@extends('layouts.admin')

@section('content')

<h2>Visitor QR Pass</h2>

<br>

<div style="
    background:white;
    padding:30px;
    text-align:center;
    border-radius:10px;
">

    <h3>{{ $visitor->visitor_name }}</h3>

    <p>
        Purpose:
        {{ $visitor->purpose }}
    </p>

    <p>
        Resident:
        {{ $visitor->resident->name }}
    </p>

    <br>

    {!! QrCode::size(250)->generate(

        'Visitor: '.$visitor->visitor_name.
        ', Resident: '.$visitor->resident->name.
        ', Mobile: '.$visitor->mobile

    ) !!}

</div>

@endsection