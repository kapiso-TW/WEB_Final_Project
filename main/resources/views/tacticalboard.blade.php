@extends('layouts.app')
@push('styles')
    @vite(['resources/css/tacticalboard.css'])
@endpush

{{-- view content --}}
@section('content')
    <h2>戰術板</h2>
    <div class="card p-3">
        <form method="POST" action="/tacticalboard">
            @csrf
            <div class="mb-3">
                <label for="tactic_name" class="form-label">戰術名稱</label>
                <input type="text" name="tactic_name" id="tactic_name" class="form-control" required>
            </div>
            
            {{-- 戰術板畫布或球員站位設定區... --}}

            <button type="submit" class="btn btn-primary">儲存戰術</button>
        </form>
    </div>
@endsection