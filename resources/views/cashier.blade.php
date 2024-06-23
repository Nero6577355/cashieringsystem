@extends('layouts.app') <!-- Assuming you have a layout file named 'app.blade.php' -->

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Cashier Dashboard</div>

                    <div class="card-body">
                        <p>Welcome, {{ Auth::user()->name }}!</p>
                        <p>This is the cashier dashboard. You can manage transactions and other cashier-related tasks here.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
