@extends('kasir.layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')
    <h1>Selamat Datang {{ auth()->user()->name }} !</h1>
    <p>Kelola transaksi disini !</p>
@endsection


