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
	
	td.has-error label{
		color:red !important;
	}
	
	#saveAttendanceBtn:disabled{
		background: #E4E4E4;
    	color: #C3C3C3;
	}
	</style>
	<link href='{{url()}}/assets//xcalender/fullcalendar.css' rel='stylesheet' />
	<link href='{{url()}}/assets//xcalender/fullcalendar.print.css' rel='stylesheet' media='print' />
@stop

@section('libraryJS')

<script src="{{url()}}/assets/js/pages/validator.js"></script>
<script src='{{url()}}/assets/fullcalender/lib/moment.min.js'></script>

	
<!-- <script src='{{url()}}/assets//xcalender/jquery/jquery-1.10.2.js'></script> -->
<script src='{{url()}}/assets//xcalender/jquery/jquery-ui.custom.min.js'></script>

<script src='{{url()}}/assets//xcalender/jquery/fullcalendar.js'></script>
<script
	src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script type="text/javascript">

$(document).ready(function() {
    var date = new Date();
	var d = date.getDate();
	var m = date.getMonth();
	var y = date.getFullYear();


	var eventArray = {{$batchSchedules}} 
	
	
	/*  className colors
	
	className: default(transparent), important(red), chill(pink), success(green), info(blue)
	
	*/		
	
	  
	/* initialize the external events
	-----------------------------------------------------------------*/

	$('#external-events div.external-event').each(function() {
	
		// create an Event Object (http://arshaw.com/fullcalendar/docs/event_data/Event_Object/)
		// it doesn't need to have a start or end
		var eventObject = {
			title: $.trim($(this).text()) // use the element's text as the event title
		};
		
		// store the Event Object in the DOM element so we can get to it later
		$(this).data('eventObject', eventObject);
		
		// make the event draggable using jQuery UI
		$(this).draggable({
			zIndex: 999,
			revert: true,      // will cause the event to go back to its
			revertDuration: 0  //  original position after the drag
		});
		
	});


	/* initialize the calendar
	-----------------------------------------------------------------*/
	
	var calendar =  $('#calendar').fullCalendar({
		header: {
			left: 'title',
			center: 'agendaDay,agendaWeek,month',
			right: 'prev,next today'
		},
		editable: true,
		firstDay: 1, //  1(Monday) this can be changed to 0(Sunday) for the USA system
		selectable: true,
		defaultView: 'month',
		
		axisFormat: 'h:mm',
		columnFormat: {
            month: 'ddd',    // Mon
            week: 'ddd d', // Mon 7
            day: 'dddd M/d',  // Monday 9/7
            agendaDay: 'dddd d'
        },
        titleFormat: {
            month: 'MMMM yyyy', // September 2009
            week: "MMMM yyyy", // September 2009
            day: 'MMMM yyyy'                  // Tuesday, Sep 8, 2009
        },
		allDaySlot: false,
		selectHelper: true,
		select: function(start, end, allDay) {
			/* var title = prompt(title);
			if (title) {
				calendar.fullCalendar('renderEvent',
					{
						title: title,
						start: start,
						end: end,
						allDay: allDay
					},
					true // make the event "stick"
				);
			}
			calendar.fullCalendar('unselect'); */
		},
		eventClick: function(calEvent, jsEvent, view) {

			 //alert('Event: ' + moment(calEvent.start).format('YYYY-MM-d hh:mm'));

			 getbatchesStudents(calEvent.id, moment(calEvent.start).format('YYYY-MM-DD'));

	        /* alert('Event: ' + calEvent.id);
	        alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
	        alert('View: ' + view.name);
	        $(this).css('border-color', 'red');
 			*/
	    },
		droppable: true, // this allows things to be dropped onto the calendar !!!
		drop: function(date, allDay) { // this function is called when something is dropped
		
			// retrieve the dropped element's stored Event Object
			var originalEventObject = $(this).data('eventObject');
			
			// we need to copy it, so that multiple events don't have a reference to the same object
			var copiedEventObject = $.extend({}, originalEventObject);
			
			// assign it the date that was reported
			copiedEventObject.start = date;
			copiedEventObject.allDay = allDay;
			
			// render the event on the calendar
			// the last `true` argument determines if the event "sticks" (http://arshaw.com/fullcalendar/docs/event_rendering/renderEvent/)
			$('#calendar').fullCalendar('renderEvent', copiedEventObject, true);
			
			// is the "remove after drop" checkbox checked?
			if ($('#drop-remove').is(':checked')) {
				// if so, remove the element from the "Draggable Events" list
				$(this).remove();
			}
			
		},
		gotoDate: eventArray[0].start,
		
		events:eventArray 

			/* [
			{
				title: 'All Day Event',
				start: new Date(y, m, 1)
			},
			{
				id: 999,
				title: 'Repeating Event',
				start: new Date(y, m, d-3, 16, 0),
				allDay: false,
				className: 'info'
			},
			{
				id: 999,
				title: 'Prasath',
				start: "2015-11-29 15:00:00",
				end: "2015-11-29 16:00:00",
				allDay: false,
				className: 'info'
			},
			{
				title: 'Meeting',
				start: new Date(y, m, d, 10, 30),
				allDay: false,
				className: 'important'
			},
			{
				title: 'Lunch',
				start: new Date(y, m, d, 12, 0),
				end: new Date(y, m, d, 14, 0),
				allDay: false,
				className: 'important'
			},
			{
				title: 'Birthday Party',
				start: new Date(y, m, d+1, 19, 0),
				end: new Date(y, m, d+1, 22, 30),
				allDay: false,
			},
			{
				title: 'Click for Google',
				start: new Date(y, m, 28),
				end: new Date(y, m, 29),
				url: 'http://google.com/',
				className: 'success'
			}
		] */,			
	});
	
	$('#calendar').fullCalendar('gotoDate', eventArray[0].start);
});

var ajaxUrl = "{{url()}}/quick/";
function getbatchesStudents(batchId, dateStartEvent){

	//console.log(ajaxUrl);
	var isExists = "no";
	$("#addAttendanceTitle").html("");
	$("#addAttendanceTitle").html(dateStartEvent);
	$.ajax({
		  type: "POST",
		  url: ajaxUrl+"getStudentsByBatch",
		  dataType: 'json',
		  async: true,
		  data:{'batchId':batchId, 'selectedDate':dateStartEvent},
		  success: function(response, textStatus, jqXHR)
		  {
			  if (response.status == "success"){	
				    console.log(response.result);	

					var i = 0;
					var attendanceString = "";
					$("#attendanceTbody").empty();
					
					
				    $.each(response.result, function (index, item) {
						
				    	attendanceString = '<tr><td><input type="hidden" value="'+dateStartEvent+'"  name="attendanceDate_'+i+'"/><input type="hidden" value="'+batchId+'"  name="batch_'+i+'"/><input type="hidden" value="'+item.studentId+'"  name="student_'+i+'"/>'+item.studentName+'</td><td class="form-group"><input id="attendance_for_userP'+i+'" name="attendance_for_user'+i+'" value="P" type="radio" class="radio-custom" required /><label for="attendance_for_userP'+i+'" class="radio-custom-label">P</label><input id="attendance_for_userA'+i+'" name="attendance_for_user'+i+'" value="A"  type="radio" class="radio-custom" /><label for="attendance_for_userA'+i+'" class="radio-custom-label">A</label><input id="attendance_for_userEA'+i+'" name="attendance_for_user'+i+'" value="EA"  type="radio" class="radio-custom" /><label for="attendance_for_userEA'+i+'" class="radio-custom-label">EA</label></td><td></td></tr>';
				    	$("#attendanceTbody").append(attendanceString);
				    	
	                  	if(item.isAttendanceEntered == 'yes'){
		                  	console.log('attendanceentered'+item.isAttendanceEntered);
		                  	console.log('attendanceStatus'+item.attendanceStatus);
	                  		//$("#attendance_for_user"+i).val(item.attendanceStatus);
		                  	$("input[name=attendance_for_user"+i+"][value='"+item.attendanceStatus+"']").attr('checked','checked');
	                  	}
						i++;  
						
		            });

				    $("#attendanceTbody").append('<tr><td><input type="hidden" name="totalStudents" value="'+i+'"/></td><td></td></tr>');
				    

				    $("#saveAttendanceBtn").attr("disabled", false);
				    $("#addAttendance").modal('show');


				    		    	
			    	//$("#callbackMessage").html('<div class="uk-alert uk-alert-danger" data-uk-alert><a href="#" class="uk-alert-close uk-close"></a>Sorry, This Email address already exists.</div>');
			    }else{
			    	//$("#callbackMessage").html("");
			    }			  		  
		  },
		  error: function (jqXHR, textStatus, errorThrown)
		  { }
	});

	console.log(isExists);
	return isExists;
}

$("#addAttendanceForm").validator();


$('#addAttendanceForm').validator().on('submit', function (e) {
    if (e.isDefaultPrevented()) {
        
       
        
    } else {

    	$("#saveAttendanceBtn").attr("disabled", 'disabled');
		
        e.preventDefault();
        
        $.ajax({
  		  type: "POST",
  		  url: ajaxUrl+"addStudentAttendance",
  		  dataType: 'json',
  		  async: true,
  		  data:$("#addAttendanceForm").serialize(),
  		  success: function(response, textStatus, jqXHR)
  		  {

  				console.log(response);
  				if(response.status == "success"){
  					$("#messageAttendanceAddDiv").html('<p class="uk-alert uk-alert-success">Attendance has been added successfully.</p>');
  					$("#saveAttendanceBtn").attr("disabled", false);
  				}else{
  					$("#messageAttendanceAddDiv").html('<p class="uk-alert uk-alert-warning">Sorry, Attendance could not be  added. Please contact administrator</p>');
  				}
  			  
  		  },
  		  error: function (jqXHR, textStatus, errorThrown)
  		  { }
  		});
      	
    }
    
});



</script>

@stop



@section('content')



<!-- Add Attendance Modal  -->
<div id="addAttendance" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					Add Attendance(<span id="addAttendanceTitle"></span>)
				</h4>
			</div>
			<div class="modal-body">
				<div id="messageAttendanceAddDiv"></div>
				<div id="formBody">
					<form id="addAttendanceForm" method="post>
				      		<br  clear="all" />
				      		<table class="uk-table table-striped" id="customersTable">
		                            <!-- <caption>Table caption</caption> -->
		                            <thead>
		                            <tr>
		                                <th>Student Name</th>
		                                <th>Attendance Status</th>
		                                <!-- <th>Action</th> -->
		                            </tr>
		                            </thead>
		                            <tbody id="attendanceTbody"></tbody>
		                    </table>
					
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="saveAttendanceBtn" class="md-btn md-btn-primary">Save</button>
				<button type="button" id="closeAttendanceModal" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>

	</div>
</div>
<!--  Add Attendance Modal -->




<?php 
	/* echo "<pre>";
	print_r($batchSchedules);
	echo "</pre>"; */

?>
	<div class="md-fab-wrapper">
		<a class="md-fab md-fab-accent" href="#new_todo">
			<i class="material-icons">&#xE03B;</i>
		</a>
	</div>
	<div class="row">
		<div id='wrap'>
			<div id='calendar'></div>			
			<div style='clear:both'></div>
		</div>		
	</div><!-- row -->



<script type="text/javascript">

</script>


 
@stop