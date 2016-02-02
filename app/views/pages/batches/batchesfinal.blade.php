@extends('layout.master')

@section('libraryCSS')
	<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->
	<link href='{{url()}}/assets/fullcalender/fullcalendar.css' rel='stylesheet' />
	<link href='{{url()}}/assets/fullcalender/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
	<link type="text/css" href="{{url()}}/assets/timepicker/jquery-ui-timepicker-addon.css" />

	<style>
	.fc-unthemed .fc-button:after{
		display:none;
	}
	
	.fc-button-group, .fc button{
		display:block !important;
	}
	
	.fc-view-container{
		background-color:#FFFFFF;
	}
	.modal-dialog{
		margin-top:100px;
		left:50px;
	}
	</style>
	<!-- <link href='{{url()}}/assets//xcalender/fullcalendar.css' rel='stylesheet' />
	<link href='{{url()}}/assets//xcalender/fullcalendar.print.css' rel='stylesheet' media='print' /> -->
	<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css"/>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' />
	
@stop

@section('libraryJS')
	

<!-- <script src='{{url()}}/assets/fullcalender/lib/moment.min.js'></script>

	
<script src='{{url()}}/assets//xcalender/jquery/jquery-1.10.2.js'></script>
<script src='{{url()}}/assets//xcalender/jquery/jquery-ui.custom.min.js'></script>

<script src='{{url()}}/assets//xcalender/jquery/fullcalendar.js'></script>
 -->
<script src="{{url()}}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="{{url()}}/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
<script src="{{url()}}/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
<script src="{{url()}}/assets/js/kendoui_custom.min.js"></script>
<script src="{{url()}}/assets/js/pages/kendoui.min.js"></script>
<!-- <script src='{{url()}}/assets//xcalender/jquery/jquery-1.10.2.js'></script> -->
<script type="text/javascript">


$("#batchTable").DataTable();

function getMasterRelatedClasses(){

	  $.ajax({
	    type: "POST",
	    url: "{{URL::to('/quick/classesbyCourse')}}",
	    data: {'franchiseeCourse': $('#franchiseeCourse').val()},
	    dataType:"json",
	    success: function (response)
	    {
		  	$("#className").val("");
		  	$("#className").empty();
		  	var string = '<option value="">Select Class name</option>';
		  	$.each(response, function (index, item) {
		  		string += '<option value='+index+'>'+item+'</option>';
		    });
		    $("#className").append(string);
	    }
	}); 
	  
}




$("#franchiseeCourse").change(function (){
	
	getMasterRelatedClasses();
})


//var jq = $.noConflict();
function startChange() {
	var startTime = start.value();
	if (startTime) {
		startTime = new Date(startTime);
		startTime.setMinutes(startTime.getMinutes() + this.options.interval);
		end.value(startTime);
	}
}


//init start timepicker
var start = $("#startTime").kendoTimePicker({
	//change: startChange,
	interval:5
}).data("kendoTimePicker");


var end = $("#endTime").kendoTimePicker({
	//change: startChange,
	interval:5
}).data("kendoTimePicker");

//init end timepicker
//var end = $("#endTime").kendoTimePicker().data("kendoTimePicker");
//define min/max range
/* start.min("9:00 AM");
start.max("5:00 PM"); */

//define min/max range
//end.min("6:00 PM");
//end.max("7:00 PM");

disabledDaysBefore = [
          +new Date("10/20/2014")
        ];
        
$('#startDate').kendoDatePicker({
	format: "d-MM-yyyy",
	start: "<?php echo date('Y')?>",
	//min: new Date(),
});   

</script>		                           	                            									

@stop



@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="#" style="z-index:8;">Batches</a></li>
		
	</ul>
</div>
<br clear="all"/>
<?php 


?>
	<div class="row">	
		 <div class="md-card">
                <div class="md-card-content large-padding">
                	@if(!$errors->isEmpty())
                	<div class="uk-alert uk-alert-danger" data-uk-alert>
                    	<a href="#" class="uk-alert-close uk-close"></a>
                                {{$errors->first('courseName')}}
								{{$errors->first('masterCourse')}}
                    </div>
				    @endif	
				    @if (Session::has('msg'))
					  <div class="uk-alert uk-alert-success" data-uk-alert>
                      		 <a href="#" class="uk-alert-close uk-close"></a>
                             {{ Session::get('msg') }}
                      </div>
                      <br clear="all"/>
					@endif
					 @if (Session::has('error'))
					  <div class="uk-alert uk-alert-danger" data-uk-alert>
                      		 <a href="#" class="uk-alert-close uk-close"></a>
                             {{ Session::get('error') }}
                      </div>
                      <br clear="all"/>
					@endif
					
					<!-- <div class="uk-grid" data-uk-grid-margin>
	                    <div class="uk-width-large-1-2">
	                    	<input id="kUI_timepicker_range_start" type="number" class="uk-form-width-medium" />
	                    </div>
	                    
                    </div> -->


                       {{ Form::open(array('url' => '/batches', 'id'=>"courseCategoryForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
                        
                        	<div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	<label for="franchiseeCourse">Program<span class="req">*</span></label><br>
				                 	{{ Form::select('franchiseeCourse', array('' => 'Please Select programs')+ $franchiseeCourses,null ,array('id'=>'franchiseeCourse', 'required',  'class' => 'form-control input-sm md-input', 'style'=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				             </div>   
				             <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	<label for="className">Class<span class="req">*</span></label><br>
				                 	<select id="className" name="className" class="form-control input-sm md-input" style='padding:0px; font-weight:bold;color: #727272;'></select>				                 	
				                 </div>
			                 </div>
			                
			                <div class="uk-width-medium-1-3"> 
				                  <div class="parsley-row">
				                 	<label for="customerEmail">Start date<span class="req">*</span></label><br>
				                 	{{Form::text('startDate', null,array('id'=>'startDate', 'required', 'class' => 'uk-form-width-medium'))}}
				                 	
				                 </div>
				              </div>  
				              <!-- 
				              <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	<label for="franchiseeCourse">Day<span class="req">*</span></label><br>
				                 	<select name="day" class = 'form-control input-sm md-input' style='padding:0px; font-weight:bold;color: #727272;' >
										<option value="">Select Day</option>
										<option value="1">Monday</option>
										<option value="2">Tuesday</option>
										<option value="3">Wednesday</option>
										<option value="4">Thursday</option>
										<option value="5">Friday</option>
										<option value="6">Saturday</option>
										<option value="7">Sunday</option>
									</select>
				                 </div>
				             </div>  
				              -->  
			             	
                            <div class="uk-width-medium-1-3">
                            	<label for="customerEmail">Start Time<span class="req">*</span></label><br>
                            	<input name="startTime" id="startTime" type="number" class="uk-form-width-medium" />
                            </div>
                            <div class="uk-width-medium-1-3">
                            	<label for="customerEmail">End time<span class="req">*</span></label><br>
                            	<input name="endTime" id="endTime" type="number" class="uk-form-width-medium"  />
                            </div>
                        </div>
                        <div class="uk-grid" data-uk-grid-margin>
	                        <div class="uk-width-medium-1-3"> 
			                  <div class="parsley-row">
			                 	<label for="leadInstructor">Lead Instructor<span class="req">*</span></label><br>
			                 	{{ Form::select('leadInstructor', array('' => 'Please Select Instructor')+ $Instructors,null ,array('id'=>'leadInstructor',   'class' => 'form-control input-sm md-input', 'style'=>'padding:0px; font-weight:bold;color: #727272;')) }}
			                 	
			                 </div>
			                </div>  
			                 <div class="uk-width-medium-1-3"> 
			                  <div class="parsley-row">
			                 	<label for="alternateInstructor">Alternate Instructor<span class="req"></span></label><br>
			                 	{{ Form::select('alternateInstructor', array('' => 'Please Select Instructor')+ $Instructors,null ,array('id'=>'alternateInstructor',  'class' => 'form-control input-sm md-input', 'style'=>'padding:0px; font-weight:bold;color: #727272;')) }}
			                 	
			                 </div>
			                </div>  
		                </div>
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-primary">Add New Batch</button>
                            </div>
                        </div>
                    {{ Form::close() }}	
                </div>
            </div>
            <div class="md-card">
	            <div class="md-card-content large-padding">
		            <h3 class="heading_b uk-margin-bottom">Batches</h3>
		            <div class="md-card uk-margin-medium-bottom">
		                <div class="md-card-content">
		                    <div class="uk-overflow-container">
		                        <table id="batchTable" class="uk-table">
		                            <!-- <caption>Table caption</caption> -->
		                            <thead>
		                            <tr>
		                                <th>Batch ID</th>
		                                <th>Batch Name</th>
		                                <th>Batch Timings</th>
		                                <th>Action</th>
		                            </tr>
		                            </thead>
		                            <tbody>
		                             @foreach($batches as $batch)
		                             <tr>
		                                <td>{{$batch->id}}</td>
		                                <td>{{$batch->batch_name}}</td>
		                                <td>{{$batch->preferred_time}} to {{$batch->preferred_end_time}}</td>
		                                <td>
		                                	<a class="btn btn-primary" href="{{url()}}/batches/view/{{$batch->id}}">View</a>
		                                	<a class="btn btn-info" href="{{url()}}/batches/attendance/{{$batch->id}}">Attendance</a>
		                                
		                                </td>
		                                
		                            </tr>
		                            @endforeach 
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		            </div>
				</div>
			</div>
	</div><!-- row -->





 
@stop