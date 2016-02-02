@extends('layout.master') 
@section('libraryCSS')
    	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <style>
        .center{
            width:60%;
            margin: auto;
        } 
        .head { text-align: center }
    </style>
    	<style>
		.smallText td a, .smallText td {
			font-size:12px !important;
		
		}
		
		.smallText td a{
			text-decoration:none !important;
		}
	
	</style>
@stop
@section('content')
    <div class="center">
            <h2 class="head">Registration for Introductory visit</h2>                    
    </div> 
    <div>
       {{ Form::open(array('url' => 'inroductoryvisit')) }}
       <div class="row">
                <div class="col-lg-8 ">
                    <h3>Parent Information</h3>
                    <hr class="star-light">
                    <label>Name</label>
                    <><>
                </div>
                <div class="col-lg-4 ">
                    <h3>Membership</h3>
                    <hr class="star-light">
                </div>
        </div>
       {{ Form::close() }} 
    </div>
@stop
