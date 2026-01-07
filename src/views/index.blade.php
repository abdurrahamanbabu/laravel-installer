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
                                    <p class="text-center">Thank you for choosing Amazcart . Please follow the steps to complete Amazcart installation!</p>
                                    <div class="card-body-button mt-4 text-center">
                                        <a href="{{ route('install.requirements') }}" class="btn btn-success text-center">
                                            Get Started
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
