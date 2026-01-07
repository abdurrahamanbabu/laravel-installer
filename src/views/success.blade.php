@extends('installer::layouts.master')
@section('content')
<div class="form">
    <div class="row d-flex justify-content-center">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <div class="card mt-5">
                <div class="card-header text-center">
                    <h5>Welcome To Amazcart</h5>
                </div>
                <div class="card-body">
                    <p class="text-center">Your installation is done. Enjoy your App. Thank you</p>
                    <div class="w-100 mt-3 mb-3 text-center">
                        <p>Your Super Admin email : {{ session()->get('email') }} <br>
                            Your Super Admin password : {{ session()->get('password') }} </p>
                    </div>
                    <div class="card-body-button mt-4 text-center">
                        <a href="/" class="btn btn-success text-center">
                            Go to Home
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
