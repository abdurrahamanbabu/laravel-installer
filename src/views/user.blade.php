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
                                    <form action="{{ route('install.userStore') }}" method="post">
                                        <div class="form-group">
                                            <label for="">Email</label>
                                            <input type="email" required name="email" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="">password</label>
                                            <input type="password" required name="password" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Confirm password</label>
                                            <input type="password" required name="password_confirmation" class="form-control">
                                        </div>
                                        <div class="card-body-button mt-4 text-center">
                                            <button class="btn btn-success text-center">
                                                Finish
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
