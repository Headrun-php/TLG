@extends('layout.master')


@section('libraryCSS')
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all">
@stop

@section('libraryJS')

	<script src='https://code.jquery.com/jquery-1.11.3.js'></script>
	<script src='http://momentjs.com/downloads/moment.min.js'></script>
	<script src='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.js'></script>
@stop

@section('content')




            <div id='calendar'></div>
   

<script type="text/javascript">

var jq = $.noConflict();
jq(function() {

	
	 jq("#franchiseeCourse").change(function (){
		getMasterRelatedClasses();
	  })

	  jq("#classId").change(function (){
		   getBatchByClassId();
	  })

	  
	  var date = new Date();
      var d = date.getDate();
      var m = date.getMonth();
      var y = date.getFullYear();

        
	  jq('#calendar').fullCalendar({
		  events: [
		           {
		               title  : 'event1',
		               start  : '2010-01-01'
		           },
		           {
		               title  : 'event2',
		               start  : '2010-01-05',
		               end    : '2010-01-07'
		           },
		           {
		               title  : 'event3',
		               start  : '2010-01-09T12:30:00',
		               allDay : false // will make the time show
		           }
		       ],
		});


	  
});


</script>
@stop
