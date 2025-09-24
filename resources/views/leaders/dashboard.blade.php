@extends('layouts.leader')

@section('content')
<div class="col-sm-12">
    <div class="card table-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4 class="text-primary">Dashboard Leader</h4>
        </div>
        <div class="card-body p-3">
            <p>Selamat datang, {{ $user->Name_User ?? 'Leader' }}</p>
        </div>
    </div>
</div>
@endsection
