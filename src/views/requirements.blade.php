@extends('installer::layouts.master')
@section('content')
                <div class="form">
                    <div class="row d-flex justify-content-center">
                        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                            <div class="card mt-5">
                                <div class="card-header text-center">
                                    <h5>Let's check Server Requirements for app.</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">

                                        @foreach ($servers as $server)
                                         @php
                                            if(arrayGetValue($server, 'type') == 'error' and !$has_false){
                                                $has_false = true;
                                            }
                                        @endphp
                                        <div class="col-md-6">
                                            <p
                                                class="alert alert-font alert-{{ arrayGetValue($server, 'type') == 'error' ? 'danger' : 'success' }}">
                                                <i class="ti-{{ arrayGetValue($server, 'type') == 'error' ? 'na' : 'check-box' }} mr-1"></i>
                                                {{ arrayGetValue($server, 'message') }}
                                            </p>
                                        </div>
                                        @endforeach
                                        <div class="col-md-12">
                                            <h4>Folder Requirements </h4>
                                            <hr class="mt-0">
                                        </div>
                                        @foreach ($folders as $folder)
                                        @php
                                            if(arrayGetValue($folder, 'type') == 'error' and !isset($has_false)){
                                                $has_false = true;
                                            }
                                        @endphp
                                        <div class="col-md-6">
                                            <p
                                                class="alert-font alert alert-{{ arrayGetValue($folder, 'type') == 'error' ? 'danger' : 'success' }}">
                                                <i class="ti-{{ arrayGetValue($folder, 'type') == 'error' ? 'na' : 'check-box' }} mr-1"></i>
                                                {{ arrayGetValue($folder, 'message') }}
                                            </p>
                                        </div>
                                        @endforeach
                                    </div>


                                    @if(isset($has_false) && $has_false == true)
                                    <p class="text-center alert alert-danger mt-40">
                                        Please solve the requirements issue.
                                    </p>
                                    @else
                                    <div class="card-body-button mt-4 text-center">
                                        <a href="{{ route('install.database') }}" class="btn btn-success text-center">
                                            Go To Next
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
@endsection
