@extends('layout.master')

@section('libraryCSS')
	<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->
	<link href='{{url()}}/assets/fullcalender/fullcalendar.css' rel='stylesheet' />
	<link href='{{url()}}/assets/fullcalender/fullcalendar.print.css' rel='stylesheet' media='print' />
	<link rel="stylesheet" media="all" type="text/css" href="http://code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css" />
	<link type="text/css" href="{{url()}}/assets/timepicker/jquery-ui-timepicker-addon.css" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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

<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script type="text/javascript">

$(document).ready(function(){
    
   $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/season/getSeasonsForBatches')}}",
                        data: {},
			dataType: 'json',
			success: function(response){
                            console.log(response.season_data);
                            var data="<select name='selectSeason' class='form-control input-sm md-input' id='selectSeason' class='input-sm md-input'"+
                                     "style='padding: 0px; font-weight: bold; color: #727272; width:50%; float:right'>";
                                                        var options='';
                                                        for(var i=0;i<response.season_data.length;i++){
                                                               //        console.log(i);
                                                                       options=options+"<option value='"+response.season_data[i]['id']+"'>"+response.season_data[i]['season_name']+"</option>";
                                                            }	
                                                       data=data+options;
                                                    data=data+"</select>";
                            //console.log(data);
                            $('.seasonSelection').html(data);
                                     // for location
                                       $.ajax({
                                        type: "POST",
                                        url: "{{URL::to('/quick/season/getLocationBySeasonId')}}",
                                        data: {'seasonId':$('#selectSeason').val(),},
                                        dataType: 'json',
                                        success: function(response){
                                        //console.log(response.status);
                                        //console.log(response.data);
                                        $("#seasonLocation").val("");
                                        $("#seasonLocation").empty();
                                             //<select name="seasonLocation" id='seasonLocation' class = 'form-control input-sm md-input' style='padding:0px; font-weight:bold;color: #727272;' >
										
                				//					</select>
                                         string='';
                                        for(var i=0;i<response.data.length;i++) {
                                        string += '<option value='+response.data[i]['id']+'>'+response.data[i]['location_name']+'</option>';
                                        }
                                        $("#seasonLocation").append(string);

                                        }
                                       });
    
                        }
             });  
             
});

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
//	change: startChange,
        
	interval:5
}).data("kendoTimePicker");


var end = $("#endTime").kendoTimePicker({
//	change: startChange,
	interval:5
}).data("kendoTimePicker");

//for change start,end  time
var changeBatchStart=$('#changeBatchStartTime').kendoTimePicker({
    interval:5
}).data('kendoTimePicker');

var changeBatchEnd=$('#changeBatchEndTime').kendoTimePicker({
    interval:5
}).data('kendoTimePicker');

//init end timepicker
//var end = $("#endTime").kendoTimePicker().data("kendoTimePicker");
//define min/max range
start.min("7:00 AM");
start.max("10:00 PM"); 

//define min/max range
end.min("7:00 AM");
end.max("10:00 PM");

//defining min,max for batch start /end time
changeBatchStart.min("7:00 AM");
changeBatchStart.max("10:00 PM");

changeBatchEnd.min("7:00 AM");
changeBatchEnd.max("10:00 PM");

disabledDaysBefore = [
          +new Date("10/20/2014")
        ];
        
$('#startDate').kendoDatePicker({
	format: "d-MM-yyyy",
	start: "<?php echo date('Y')?>",
	//min: new Date(),
});   


$(document.body).on('change','#selectSeason',function(){
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getBatchData')}}",
                        data: {'session_id':$('#selectSeason').val()},
			dataType: 'json',
			success: function(response){
                            if(response.status=='success'){
                                $('#batchData').html('');
                             
                         var htmldata="<div class='md-card-content'>"+
		                    "<div class='uk-overflow-container'>"+
		                        "<table id='batchTable' class='uk-table'>"+
		                            "<thead>"+
		                            "<tr>"+
		                                "<th>Batch Name</th>"+
		                                "<th>Location</th>"+
                                                "<th>Day</th>"+
		                                "<th>Timings</th>"+
                                                "<th>Cost</th>"+
                                                "<th>L Instructor</th>"+
                                                "<th>Status</th>"+
                                                "<th>Created Date</th>"+
		                                "<th>Action</th>"+
		                            "</tr>"+
		                            "</thead>";
                                    if(response.data.length>0){
                                for(var i=0;i<response.data.length;i++){
                                     
                                     htmldata=htmldata+"<tr>"+
		                                "<td>"+ response.data[i]['batch_name']+"</td>"+
		                                "<td>"+response.data[i]['location_name']+"</td>"+
                                                "<td>"+response.data[i]['day']+"</td>"+
		                                "<td>"+response.data[i]['preferred_time']+" to "+response.data[i]['preferred_end_time']+"</td>"+
                                                "<td>"+response.data[i]['class_amount']+"</td>"+
                                                "<td>"+response.data[i]['instructor_name']+"</td>"+
                                                "<td>"+response.data[i]['count']+"/18</td>"+
                                                
                                                "<td>"+response.data[i]['created']+"</td>"+
		                                "<td>"+
		                                	"<a class='btn btn-info btn-xs' href='{{url()}}/batches/attendance/"+response.data[i]['id']+"' title='Summary'><i class='Small material-icons' style='font-size:20px;'>assignment</i></a> " +
		                                        "<a class='btn btn-primary btn-xs' href='{{url()}}/batches/view/"+ response.data[i]['id'] +"' title='Attendance'><i class='Small material-icons' style='font-size:20px;'>snooze</i></a>"+
		                                	"<a  id='editBatchbutton' class='btn btn-warning btn-xs' onclick='editbatch("+response.data[i]['id']+','+response.data[i]['location_id']+','+response.data[i]['lead_instructor']+")' title='Edit'> <i class='Small material-icons' style='font-size:20px;'>mode_edit</i></a>"+
		                                "</td>"+
		                                
		                     "</tr>";
                                }
                                }
//                                else{
//                                htmldata=htmldata+"<tr><td>"+
//                                    "No batches added yet..."+
//                                    "</td></tr>";
//                                }
                                 htmldata=htmldata+  "</table>"+
                                            "</div>"+
                                            "</div>";
                                $('#batchData').html(htmldata);
                                $("#batchTable").DataTable();
                              //console.log(response.data);
                            }
                        }
             });  
             
             $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/season/getLocationBySeasonId')}}",
                        data: {'seasonId':$('#selectSeason').val(),},
			dataType: 'json',
			success: function(response){
                            //console.log(response.status);
                            console.log(response.data);
                            $("#seasonLocation").val("");
		  	$("#seasonLocation").empty();
                            //<select name="seasonLocation" id='seasonLocation' class = 'form-control input-sm md-input' style='padding:0px; font-weight:bold;color: #727272;' >
										
				//					</select>
                            string='';
                            for(var i=0;i<response.data.length;i++) {
		  		string += '<option value='+response.data[i]['id']+'>'+response.data[i]['location_name']+'</option>';
                            }
                             $("#seasonLocation").append(string);

                        }
             });
    
    
});


$('#endTime').change(function(){
        if(($('#startTime').val()=='')||($('#day').val()=='')){
            $('#msgdiv').html("<p class='uk-alert uk-alert-warning'> please select the starttime and day</p>");
        }else{
            $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/checkbatchesslot')}}",
                        data: {'season_id':$('#selectSeason').val(),'location_id':$('#seasonLocation').val(),
                               'day':$('#day').val(),'startTime':$('#startTime').val(),'endTime':$('#endTime').val(),},
			dataType: 'json',
			success: function(response){
                            console.log(response);
                            if(response.status=='success'){
                                if(response.batch_status=='exist'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-danger'> please select another time slot</p>");
                                }
                                if(response.batch_status=='invalid selection'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-warning'>please select valid time</p>");
                                
                                }
                                if(response.batch_status=='notexist'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-success'> Selected Timeslot available</p>");
                                
                                }
                            }
                        }
             });  
        }
        
});
$('#startTime').change(function(){
    if(($('#startTime').val()=='')||($('#day').val()=='')||($('#endTime').val()=='')){
            $('#msgdiv').html("<p class='uk-alert uk-alert-warning'> please select the starttime,endtime and day</p>");
        }else{
            $('#msgdiv').html('');
            $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/checkbatchesslot')}}",
                        data: {'season_id':$('#selectSeason').val(),'location_id':$('#seasonLocation').val(),
                               'day':$('#day').val(),'startTime':$('#startTime').val(),'endTime':$('#endTime').val(),},
			dataType: 'json',
			success: function(response){
                            console.log(response);
                            if(response.status=='success'){
                                if(response.batch_status=='exist'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-danger'> please select another time slot</p>");
                                }
                                if(response.batch_status=='invalid selection'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-warning'>please select valid time</p>");
                                
                                }
                                if(response.batch_status=='notexist'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-success'> Selected Timeslot available</p>");
                                
                                }
                            }
                        }
             });
        }
});
$('#day').change(function(){
    if(($('#startTime').val()=='')||($('#day').val()=='')||($('#endTime').val()=='')){
            $('#msgdiv').html("<p class='uk-alert uk-alert-warning'> please select the starttime,endtime and day</p>");
        }else{
            $('#msgdiv').html('');
            $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/checkbatchesslot')}}",
                        data: {'season_id':$('#selectSeason').val(),'location_id':$('#seasonLocation').val(),
                               'day':$('#day').val(),'startTime':$('#startTime').val(),'endTime':$('#endTime').val(),},
			dataType: 'json',
			success: function(response){
                            console.log(response);
                            if(response.status=='success'){
                                if(response.batch_status=='exist'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-danger'> please select another time slot</p>");
                                }
                                if(response.batch_status=='invalid selection'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-warning'>please select valid time</p>");
                                
                                }
                                if(response.batch_status=='notexist'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-success'> Selected Timeslot available</p>");
                                
                                }
                            }
                        }
             });
        }
});

$('#seasonLocation').change(function(){
 $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/checkbatchesslot')}}",
                        data: {'season_id':$('#selectSeason').val(),'location_id':$('#seasonLocation').val(),
                               'day':$('#day').val(),'startTime':$('#startTime').val(),'endTime':$('#endTime').val(),},
			dataType: 'json',
			success: function(response){
                            console.log(response);
                            if(response.status=='success'){
                                if(response.batch_status=='exist'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-danger'> please select another time slot</p>");
                                }
                                if(response.batch_status=='invalid selection'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-warning'>please select valid time</p>");
                                
                                }
                                if(response.batch_status=='notexist'){
                                  $('#msgdiv').html('');
                                  $('#msgdiv').html("<p class='uk-alert uk-alert-success'> Selected Timeslot available</p>");
                                
                                }
                            }
                        }
             });  

});

//
//$('#eachClassAmount').change(function(){
//        if(parseInt($('#eachClassAmount').val())<0){
//            $('#eachClassAmount').val('500');
//        }
//        if($('#eachClassAmount').val()===''){
//            $('#eachClassAmount').val('500');
//        }
//});



function editbatch(batchId,locationId,instructorId){
         $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getBatchDetailsById')}}",
                        data: {'batch_id':batchId},
			dataType: 'json',
			success: function(response){
                            if(response.status=='success'){    
//                              console.log(response);
                                $('#classCost').val(response.batchData['class_amount']);
                                $('#changeBatchStartTime').val(response.batchData['preferred_time']);
                                $('#changeBatchEndTime').val(response.batchData['preferred_end_time']);
                                var data='';
                                var info='';
                                for(var i=0;i<response.instructorData.length;i++){
                                    data+="<option value="+response.instructorData[i]['id']+">"+response.instructorData[i]['first_name']+response.instructorData[i]['last_name']+"</option>";
                                }
                                
                                $('#editleadInstructor').append(data);
                                for(var i=0;i<response.locationData.length;i++){
                                    info+="<option value="+response.locationData[i]['id']+">"+response.locationData[i]['location_name']+"</option>";
                                }
                                $('#locationName').append(info);
                                $('#editleadInstructor').val(instructorId);
                                $('locationName').val(locationId);
                                $('#editBatch_id').val(batchId);
                                $('#editBatchmodal').modal('show');
                            }
                        }
          });
}

$('#savebatchedit').click(function(){
    
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/editbatchByBatchId')}}",
                        data: {'batch_id':$('#editBatch_id').val(),'location_id':$('#locationName').val(),
                               'l_instructor_id':$('#editleadInstructor').val(),'class_cost':$('#classCost').val(),
                                'batchStartTime':$('#changeBatchStartTime').val(),'batchEndTime':$('#changeBatchEndTime').val()},
			dataType: 'json',
			success: function(response){
                            if(response.status=='success'){
                                $('#batchEditMsg').html("<p class='uk-alert uk-alert-success'>updated successfully. please wait till page reloads.</p>");
                                setTimeout(function(){
				   window.location.reload(1);
				}, 2000);
                            }
                        }
          });  
});




function deletebatch(batch_id){
    $('#deleteBatch_id').val(batch_id);
    $('#deletebatch').modal('show');
}

$('#batch_delete').click(function(){
    
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/deleteBatchById')}}",
                        data: {'batch_id':$('#deleteBatch_id').val(),},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                                 if(response.status=='success'){   
				   window.location.reload(1);
                                 }
                        }
             });  
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
{{ Form::open(array('url' => '/batches', 'id'=>"courseCategoryForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        
	<div class="row">	
		 <div class="md-card">
                <div class="md-card-content large-padding">
                    <div class="row" >
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">    
                                <div id='msgdiv'class="parsley-row">
                                    
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">    
				                  <div class="parsley-row">
                                                      <div class="seasonSelection" id="SeasonSelection">
                                                <!--
                                                    <select name="selectSeason" id="selectSeason" class="input-sm md-input"
                                                     style='padding: 0px; font-weight: bold; color: #727272; width:50%; float:right '>
                                                        <option value="0" >Select discount percentage</option>
											<option value="10">10%  discount</option>
											<option value="20">20%  discount</option>
											<option value="30">30%  discount</option>
											<option value="40">40%  discount</option>
											<option value="50">50%  discount</option>
                                                    </select>
                                                -->
                                                    </div>
                                                      <br>
                                                      <br clear="all">
                                                  </div>
                            </div>
                        </div>
                    </div>
                    <div class='row'>
                        <div class="uk-grid" data-uk-grid-margin>
                            
                        </div>
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
				                 	<select id="className" name="className" class="form-control input-sm md-input" required style='padding:0px; font-weight:bold;color: #727272;'></select>				                 	
				                 </div>
			                 </div>
			                <!--
			                <div class="uk-width-medium-1-3"> 
				                  <div class="parsley-row">
				                 	<label for="customerEmail">Start date<span class="req">*</span></label><br>
				                 	{{Form::text('startDate', null,array('id'=>'startDate', 'required', 'class' => 'uk-form-width-medium'))}}
				                 	
				                 </div>
				              </div>
                                        -->
				              <!-- comment --> 
				              <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	<label for="franchiseeCourse">Day<span class="req">*</span></label><br>
				                 	<select name="day" id='day' class = 'form-control input-sm md-input' style='padding:0px; font-weight:bold;color: #727272;' required >
										<option value="">Select Day</option>
										<option value="0">Monday</option>
										<option value="1">Tuesday</option>
										<option value="2">Wednesday</option>
										<option value="3">Thursday</option>
										<option value="4">Friday</option>
										<option value="5">Saturday</option>
										<option value="6">Sunday</option>
									</select>
                                                        
				                 </div>
				             </div>  
				               
			             	
                            <div class="uk-width-medium-1-3">
                            	<label for="startTime">Start Time<span class="req">*</span></label><br>
                            	<input name="startTime" id="startTime" type="number" class="uk-form-width-medium" required />
                            </div>
                            <div class="uk-width-medium-1-3">
                            	<label for="customerEmail">End time<span class="req">*</span></label><br>
                            	<input name="endTime" id="endTime" type="number" class="uk-form-width-medium"  required/>
                            </div>
                            <div class="uk-width-medium-1-3">
                            	<label for="seasonLocation">Location<span class="req">*</span></label><br>
                                <select id="seasonLocation" class='seasonLocation form-control input-sm md-input' name="seasonLocation" class="form-control input-sm md-input" style='padding:0px; font-weight:bold;color: #727272;'></select>		
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
                                        <div class="uk-width-medium-1-3"> 
			                  <div class="parsley-row">
<!--			                 		<label for="eachClassAmount">Class Amount<span class="req">*</span></label><br>
				                 	{{Form::number('eachClassAmount', '500',array('id'=>'eachClassAmount',  'class' => 'form-control input-sm md-input','style'=>'padding:0px','required'))}}
-->			                 </div>
			                </div>
		                </div>
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button  id='addbatch' type="submit" class="md-btn md-btn-primary">Add New Batch</button>
                            </div>
                        </div>
                    {{ Form::close() }}
                    </div>
                </div>
            </div>
            <div class="md-card">
	            <div class="md-card-content large-padding">
		            <h3 class="heading_b uk-margin-bottom">Batches</h3>
		            <div class="md-card uk-margin-medium-bottom" id='batchData'>
		                <div class="md-card-content">
		                    <div class="uk-overflow-container">
		                        <table id="batchTable" class="uk-table">
		                            <!-- <caption>Table caption</caption> -->
		                            <thead>
		                            <tr>
		                                <th>Batch Name</th>
                                                <th>location</th>
                                                <th>Day</th>
		                                <th>Timings</th>
                                                <th>Cost</th>
                                                <th>L Instructor</th>
                                                <th>status</th>
                                                <th>Created Date</th>
		                                <th>Action</th>
		                            </tr>
		                            </thead>
		                            <tbody>
                                            <?php if(isset($batches)){ ?>    
		                             @foreach($batches as $batch)
		                             <tr>
		                                <td>{{$batch->batch_name}}</td>
                                                <td>{{$batch->location_name}}</td>
                                                <td>{{$batch->day}}</td>
                                                
		                                <td>{{$batch->preferred_time}} - {{$batch->preferred_end_time}}</td>
                                                <td>{{$batch->class_amount}}</td>
		                                <td>{{$batch->instructor_name}}</td>
                                                <td>{{$batch->count}}/18</td>
		                                <td>{{$batch->created}}</td>
                                                <td >
                                                    
                                                        <a class="btn btn-info btn-xs" href="{{url()}}/batches/attendance/{{$batch->id}}" title="Summary" ><i class="Small material-icons" style="font-size:20px;">assignment</i></a>
		                                        <a class="btn btn-success btn-xs" href="{{url()}}/batches/view/{{$batch->id}}" title="Attendance"><i class="Small material-icons" style="font-size:20px;">snooze</i></a>
                                                       
                                                        <a id='editBatchbutton' class="btn btn-warning btn-xs" onclick="editbatch({{$batch->id}},{{$batch->location_id}},'{{$batch->lead_instructor}}')" title="Edit"> <i class="Small material-icons" style="font-size:20px;">mode_edit</i></a>
                                                       <?php if($batch->count==0){ ?>
                                                        <a id='deleteBatchbutton' class="btn btn-danger btn-xs" onclick="deletebatch({{$batch->id}})"> <i class="Small material-icons" style="font-size:20px;" title="Delete">delete</i></a>
                                                       
                                                       <?php }?> 
		                                	
		                                </td>
		                                
		                            </tr>
		                            @endforeach
                                            <?php } ?>
		                            </tbody>
		                        </table>
		                    </div>
		                </div>
		            </div>
				</div>
			</div>
	</div><!-- row -->



 <!-- Edit  Batch modal Modal -->
  <div id='editBatchmodal' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="editBatchheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">EditBatch</h4>
        </div>
        <div class="modal-body" id="editBatchbody">
            <div class="batchEditMsg" id="batchEditMsg"></div>
            <div><input type="hidden" value="" id='editBatch_id'/></div>
            <div class="uk-grid" data-uk-grid-margin>
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="locationName">Location<span
                        accesskey=""class="req">*</span></label><br>
                        <select id="locationName"
                        name="locationName" required
			class='locationNameCbx form-control input-sm md-input'
			style="padding: 0px; font-weight: bold; color: #727272;">
			</select>
                    </div>
                </div>
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="editleadInstructor">LeadInstructor<span
                        accesskey=""class="req">*</span></label><br>
                        <select id="editleadInstructor"
                        name="editleadInstructor" required
			class='leadInstructorCbx form-control input-sm md-input'
			style="padding: 0px; font-weight: bold; color: #727272;">
			</select>
                    </div>
                </div>
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="classCost">Class Cost<span
                        accesskey=""class="req">*</span></label><br>
                        <input type="number" value='' id='classCost' class='form-control input-sm md-input' name='classCost' style="padding:0px;"/>
                    </div>
                </div>
            </div>
            <div class="uk-grid" data-uk-grid-margin>
                <!--
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="changeDay">Day<span
                        accesskey="" class="req">*</span></label>
                        <select id="changeDay"
                        name="changeDay" required
			class='changeDayCbx form-control input-sm md-input'
			style="padding: 0px; font-weight: bold; color: #727272;">
                               <option value="0">Monday</option>
			       <option value="1">Tuesday</option>
                               <option value="2">Wednesday</option>
                               <option value="3">Thursday</option>
			       <option value="4">Friday</option>
                               <option value="5">Saturday</option>
			       <option value="6">Sunday</option>
			</select>
                    </div>
                </div>
                -->
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="changeBatchStartTime">Start Time<span class="req">*</span></label>
                        <input name="changeBatchStartTime" id="changeBatchStartTime" type="number" class="uk-form-width-medium" required />
                    </div>
                </div>
                <div class="uk-width-medium-1-3">
                    <div class="parsley-row">
                        <label for="changeBatchEndTime">End Time<span class="req">*</span></label>
                        <input name="changeBatchEndTime" id="changeBatchEndTime" type="number" class="uk-form-width-medium" required />
                   
                    </div>
                </div>
            </div>
            <br>
        </div>
        <div class="modal-footer" id="editBatchfooter">
          <button id="savebatchedit" type="button" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
 

 <!-- Modal -->
  <div class="modal fade" id="deletebatch" role="dialog" style="margin-top: 50px; z-index: 99999;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <div class="deletepriceheader" id='deletepriceheader'>
                <h4 class="modal-title">Confirm Delete</h4>
            </div>
        </div>
        <div class="modal-body deletepricebody" id='deletepricebody'>
          <p>Do you really want to delete this Batch ?</p>
        </div>
        <div class="modal-footer deletepricefooter" id='deletepricefooter'>
          <center>
          <button type="button" class="btn btn-primary" id='batch_delete' >Yes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
          </center>
        </div>
      </div>
    </div>
  </div>
 
@stop