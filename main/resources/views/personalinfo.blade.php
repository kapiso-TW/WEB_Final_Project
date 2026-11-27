@extends('layouts.app')
@push('styles')
    @vite(['resources/css/personalinfo.css'])
@endpush

{{-- view content --}}
@section('content')
    <h2>個人資料</h2>
    <div class="card p-3">
        <form method="POST" action="/personalinfo">
            @csrf
            
        </form>
    </div>
@endsection