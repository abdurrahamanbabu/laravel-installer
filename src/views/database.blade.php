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
                    <form action="{{ route('install.dbStore') }}" id="database_form" method="post">

                        <div class="form-group">
                            <label for="">Hostname <span class="text-danger">*</span></label>
                            <input type="text" required name="db_hostname" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Database port <span class="text-danger">*</span></label>
                            <input type="text" required name="db_port" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Database name <span class="text-danger">*</span></label>
                            <input type="text" required name="db_name" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Database User <span class="text-danger">*</span></label>
                            <input type="text" required name="db_user" class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="">Database Password </label>
                            <input type="text"  name="db_password" class="form-control">
                        </div>




                        <div class="card-body-button mt-4 text-center">
                            <button  class="btn btn-success text-center">
                                Save & Next
                            </button>
                        </div>

                    </form>




                </div>
            </div>
        </div>
    </div>
</div>
@endsection
