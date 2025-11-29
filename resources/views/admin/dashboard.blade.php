@extends('admin.layouts.app')

@section('content')
<div class="row">

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h4>{{ $userCount }}</h4>
                <p>Users</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h4>{{ $orderCount }}</h4>
                <p>Total Orders</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card shadow-sm">
            <div class="card-body text-center">
                <h4>${{ number_format($revenue,2) }}</h4>
                <p>Total Revenue</p>
            </div>
        </div>
    </div>

</div>
@endsection
