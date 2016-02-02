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
@stop

@section('libraryJS')


<script src='{{url()}}/assets/fullcalender/lib/moment.min.js'></script>
<script src='{{url()}}/assets/fullcalender/lib/jquery.min.js'></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="{{url()}}/assets/timepicker/jquery-ui-sliderAccess.js"></script>
<script src='{{url()}}/assets/fullcalender/fullcalendar.min.js'></script>
<script type="text/javascript" src="{{url()}}/assets/timepicker/jquery-ui-timepicker-addon.js"></script>

	<!-- <script src='https://code.jquery.com/jquery-1.11.3.js'></script>
	<script src='http://momentjs.com/downloads/moment.min.js'></script>
	<script src='https://code.jquery.com/ui/1.11.3/jquery-ui.js'></script>
	<script src='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.js'></script> -->
@stop



@section('content')
<div class="container">
	<div class="row">

	<div id='calendar'></div>
		
	</div><!-- row -->
</div><!-- Container -->

<script type="text/javascript">








$(function() {

	
	 jq("#franchiseeCourse").change(function (){
		getMasterRelatedClasses();
	  })

	  jq("#classId").change(function (){
		   //getBatchByClassId();
	  })
	  
	  
	 
	 jq('#timepicker2').timepicker();
	 	
	    
	  
	  var date = new Date();
      var d = date.getDate();
      var m = date.getMonth();
      var y = date.getFullYear();

        
      jq('#calendar').fullCalendar({
          editable: true,
          header:{
        	    left:   'title',
        	    center: '',
        	    right:  'today prev,next'
        	},
          events: 

                   {{$schedules}}
              /*
              [ {
                  title: 'All Day Event',
                  start: new Date(y, m, 1)
              },
              {
                  title: 'Long Event',
                  start: new Date(y, m, d-5),
                  end: new Date(y, m, d-2)
              },
              {
                  id: 999,
                  title: 'Repeating Event',
                  start: new Date(y, m, d-3, 16, 0),
                  allDay: false
              },
              {
                  id: 999,
                  title: 'Repeating Event',
                  start: new Date(y, m, d+4, 16, 0),
                  allDay: false
              },
              {
                  title: 'Meeting',
                  start: new Date(y, m, d, 10, 30),
                  allDay: false
              },
              {
                  title: 'Lunch',
                  start: new Date(y, m, d, 12, 0),
                  end: new Date(y, m, d, 14, 0),
                  allDay: false
              },
              {
                  title: 'Birthday Party',
                  start: new Date(y, m, d+1, 19, 0),
                  end: new Date(y, m, d+1, 22, 30),
                  allDay: false
              },
              {
                  title: 'Click for Google',
                  start: new Date(y, m, 28),
                  end: new Date(y, m, 29),
                  url: 'http://google.com/'
              }] */
          ,
          eventClick: function(calEvent, jsEvent, view) {

              /* alert('Event: ' + calEvent.title);
              alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
              alert('View: ' + view.name);*/

              // change the border color just for fun
              jq(this).css('border-color', 'red'); 

          },
          dayClick: function(date, allDay, jsEvent, view) {

                 //alert('Clicked on: ' + date.format());
                 console.log('Clicked on: ' + date.format());

                 /*alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);

                alert('Current view: ' + view.name); */

                // change the day's background color just for fun
                //jq(this).css('background-color', '#CCCCCC !important');

                jq("#scheduleDate").val(date.format());
                jq("#formBody").show();
				jq("#messageDiv").html('');
				jq('#saveSchedule').show();
                jq('#myModal').modal("show");

          }
      });


      jq('#saveSchedule').click(function (){
          console.log("save clickes");

    		saveSchedule()
    	});
    	      
	  
});	  


function saveSchedule(){

	jq.ajax({
	    type: "POST",
	    url: "{{URL::to('/quick/saveSchedule')}}",
	    data: {'studentId': $('#studentId').val(),
		       'courseId': $('#franchiseeCourse').val(),
			   'classId': $('#classId').val(),
			   'scheduleDate': $('#scheduleDate').val(),
			   'scheduleTime': $('#timepicker2').val(),
		      },
	    dataType:"json",
	    success: function (response)
	    {
	
	  	  if(response.status == "success"){


		  		jq('#studentId').val("");
				jq('#franchiseeCourse').val("");
				jq('#classId').val("");
				jq('#scheduleDate').val("");
				jq('#timepicker2').val("");
				jq("#formBody").hide();
				
				jq("#messageDiv").html('<p class="alert alert-success">Schedule added successfully.</p>');
				jq('#saveSchedule').hide();

				setTimeout(function(){
				     //$('#alert-success').slideUp('slow').fadeOut(function() {
				         window.location.reload();
				         /* or window.location = window.location.href; */
				     
				}, 5000);
	  	  }
	    }
	}); 
	  
}

</script>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Student schedule</h4>
      </div>
      <div class="modal-body">
      <div id="messageDiv"></div>
      <div id="formBody">
      	 	<div class="uk-grid" data-uk-grid-margin>
             	<div class="uk-width-medium-1-2">
	                 <div class="parsley-row">
	                 	<label for="courseName">Student Name<span class="req">*</span></label>
        					{{ Form::select('franchiseeCourse', array('' => 'Please Select Master students')+ $students,null ,array('id'=>'studentId', 'required', 'form-control', 'style'=>'width:250px;')) }}
        			 </div>
       			</div>
       		</div>
        	<div class="uk-grid" data-uk-grid-margin>
             	<div class="uk-width-medium-1-2">
	                 <div class="parsley-row">
	                 	<label for="courseName">Course Name<span class="req">*</span></label>
        					{{ Form::select('franchiseeCourse', array('' => 'Please Select Master Course')+ $courseList,null ,array('id'=>'franchiseeCourse', 'required', 'form-control', 'style'=>'width:250px;')) }}
        				</div>
       			</div>
       		</div>
       		<div class="uk-grid" data-uk-grid-margin>
             	<div class="uk-width-medium-1-2">
	                 <div class="parsley-row">
	                 	<label for="courseName">Class name<span class="req">*</span></label>
				        <select  name="classId" id="classId">
				        
				        </select>
        			</div>
       			</div>
       		</div>
       
        
        	<div class="uk-grid" data-uk-grid-margin>
             	<div class="uk-width-medium-1-2">
	                 <div class="parsley-row">
	                 	<label for="courseName">Select Time<span class="req">*</span></label>
        				<input id="timepicker2" type="text" class="form-control input-small">
        				</div>
       			</div>
       		</div>
       		 <input type="hidden" id="scheduleDate"/>
         </div>
      </div>
      <div class="modal-footer">
      	<button type="button" id="saveSchedule" class="btn btn-default" >Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
 
@stop