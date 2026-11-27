@extends('layouts.app')
@push('styles')
    @vite(['resources/css/matchrec.css'])
@endpush

{{-- view content --}}
@section('content')
    <h2>比賽紀錄</h2>
    <div class="card p-3">
        <form method="POST" action="/matchrec">
            @csrf
            
        </form>
    </div>
@endsection