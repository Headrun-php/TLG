@extends('layout.master')

@section('libraryCSS')
	<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->
	<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css"/>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' />
@stop

@section('libraryJS')
<script src="{{url()}}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="{{url()}}/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
<script src="{{url()}}/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
<script src="{{url()}}/assets/js/custom/datatables_uikit.min.js"></script>
<script src="{{url()}}/assets/js/pages/plugins_datatables.min.js"></script>
<script src="{{url()}}/assets/js/kendoui_custom.min.js"></script>
<script src="{{url()}}/assets/js/pages/kendoui.min.js"></script>
<script src="{{url()}}/assets/js/pages/validator.js"></script>
<script
	src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script type="text/javascript">

	$("#eventsTable").DataTable();

	$("#eventsTable tr").click(function (){

		window.location = $(this).find('a').attr('href');
	})
	
	$("#state").change(function (){
		 getCities($("#state").val(), 'city');
	});

	function getCities(regionCode, targetSelectorId){

		var ajaxUrl = "{{url()}}/quick/"+"getCities";
		console.log(ajaxUrl);

		$.ajax({
			  type: "POST",
			  url: ajaxUrl,
			  dataType: 'json',
			  async: false,
			  data:{'id':regionCode, 'countryCode':"IN"},
			  success: function(response, textStatus, jqXHR)
			  {
				    
				   
				    //$("#"+targetSelectorId).append('<option value="" selected>Select City</option>');

				   console.log(response);
				   $('#'+targetSelectorId).empty();
				   $('#'+targetSelectorId).append('<option value=""></option');
				   $.each(response, function (index, item) {
				         $('#'+targetSelectorId).append(
				              $('<option></option>').val(index).html(item)
				          );
				     });
			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  {
		 
			  }
		});
	}

	$("#eventDate").kendoDatePicker({
		//change:onDateChangeFunction
	});

	$("#eventDateEdit").kendoDatePicker({
		//change:onDateChangeFunction
	});
	function openEditModal(id){

		
		$("#eventIdEdit").val("");
		$.ajax({
			  type: "POST",
			  url: "{{URL()}}"+"/quick/getEventById",
			  data: {"eventId":id},
			  dataType: 'json',
			  async: false,
			  success: function(response, textStatus, jqXHR)
			  {
				  $("#eventIdEdit").val(id);
				  $("#stateEdit").val(response.state);
                  getCities($("#stateEdit").val(), 'cityEdit');
				  
				  $("#eventNameEdit").val(response.name);
                  $("#eventDateEdit").val(response.event_date);
                  $("#eventDescriptionEdit").val(response.event_description);
                  $("#eventLocationEdit").val(response.area);
                  $("#eventTypeEdit").val(response.type);                 
                  $("#cityEdit").val(response.city);
					
				   console.log(response);


				  

				  $("#eventNameEditDiv .md-input-wrapper").addClass('md-input-filled');
                  $("#eventDateEditDiv  .md-input-wrapper").addClass('md-input-filled');
                  $("#eventDescriptionEditDiv  .md-input-wrapper").addClass('md-input-filled');
                  $("#locationEditDiv  .md-input-wrapper").addClass('md-input-filled');
                  $("#eventTypeEditDiv  .md-input-wrapper").addClass('md-input-filled');
                  $("#stateEditDiv  .md-input-wrapper").addClass('md-input-filled');              
                  $("#cityEditDiv  .md-input-wrapper").addClass('md-input-filled');

				  // md-input-filled
				  
			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  {
		 
			  }
		});
	
		$("#editEventTypesModal").modal('show');

		
	}


	/* $("#eventTypeEditSubmit").click(function (e){
		event.preventDefault();
		saveEventTypes();
	}) */
	
	$('#editEventForm').validator().on('submit', function (e) {
	  if (e.isDefaultPrevented()) {
	    // handle the invalid form...
	  } else {
		  	event.preventDefault();
			saveEventTypes();
	  }
	});

	function saveEventTypes(){

		$("#messageEventEditDiv").html("");
		$.ajax({
			  type: "POST",
			  url: "{{URL()}}"+"/quick/saveEvent",
			  data: $("#editEventForm").serialize(),
			  dataType: 'json',
			  async: true,
			  success: function(response, textStatus, jqXHR)
			  {
				
				   console.log(response.status);
				   if(response.status == "success"){
						$("#messageEventEditDiv").html('<p class="uk-alert uk-alert-success">Event  successfully edited.</p>');
						setTimeout(function(){
						   window.location.reload(1);
						}, 5000);
				   }else{
					   $("#messageEventEditDiv").html('<p class="uk-alert uk-alert-danger">Sorry, Event  could not be  edited.</p>');
				   }
				  
			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  {
		 
			  }
		});
	}

</script>
@stop

@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="#" style="z-index:8;">Events</a></li>
		<li><a href="#" style="z-index:7;">Event List</a></li>
	</ul>
</div>
<br clear="all"/>
<div class="">	
	<div class="row">
		 <h4>New Events</h4>
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
					
					<div id="callbackMessage"></div>
					{{ Form::open(array('url' => '/events', 'id'=>"addEventForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
			             	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="eventName">Event Name<span class="req">*</span></label>
				                 	{{Form::text('eventName', null,array('id'=>'eventName', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				           <div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="eventDate">Event date<span class="req">*</span></label>
				                 	{{Form::text('eventDate', null,array('id'=>'eventDate','required', 'class' => ''))}}
				                 </div>
				            </div>
				            
				            <br clear="all"/><br clear="all"/><br clear="all"/>
				        </div>  
				         <br clear="all"/>
				        <div class="uk-width-medium-1-1">
			                 <div class="parsley-row">
			                 	<label for="eventDescription">Event Description<span class="req">*</span></label>
			                 	{{ Form::textarea('eventDescription', null, ['id'=>'eventDescription', 'size' => '50x3',  'class' => 'form-control input-sm md-input']) }}
			                 </div>
			            </div> 
			             <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-2">    
				                  <div class="parsley-row">
				                 	<label for="customerMobile">Location/Area<span class="req">*</span></label>
				                 	{{Form::text('eventLocation', null,array('id'=>'eventLocation', 'required', 'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
				                 </div>
				            </div>  
				        	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="state">Event Type<span class="req">*</span></label>
				                 	{{ Form::select('eventType', array('' => '') + $eventTypes, null ,array('id'=>'eventType', 'class' => 'input-sm md-input', "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="state">State<span class="req">*</span></label>
				                 	{{ Form::select('state', array('' => '') + $provinces, null ,array('id'=>'state', 'class' => 'input-sm md-input', "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="state">City<span class="req">*</span></label>
				                 	{{ Form::select('city', array('' => ''), null ,array('id'=>'city', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				            <br clear="all"/> 
			            	
				         </div>
				         <br clear="all"/><br clear="all"/>
				         <div class="uk-width-medium-1-3">
			                 <div class="parsley-row">
			                 	
			                 	<button type="submit" id="eventSubmit" class="md-btn md-btn-primary">Add New Event</button>
			                 </div>
		            	</div> 
				        
				    {{ Form::close() }}	     
				        
				</div>
			</div>
            
            
            <div class="md-card">
	            <div class="md-card-content large-padding">
		            <h3 class="heading_b uk-margin-bottom">Events</h3>
		            
		            <?php 
		            	/* echo "<pre>";
		            	print_r($events);
		            	echo "</pre>";  */ 
		            
		            ?>
		
		           
		            <div class="md-card uk-margin-medium-bottom">
		                <div class="md-card-content">
		                    <div class="uk-overflow-container">
		                        <table class="uk-table table-striped" id="eventsTable">
		                            <!-- <caption>Table caption</caption> -->
		                            <thead>
		                            <tr>
		                                <th>Event Name</th>
		                                <th>Event Location</th>
		                                <th>Event City</th>
		                                <th>Event State</th>
		                                <th>Action</th>
		                            </tr>
		                            </thead>
		                            <tbody>
		                            @foreach($events as $event)
		                            <tr>
		                                <td>{{$event->eventName}}</td>
		                                <td>{{$event->area}}</td>
		                                <td>{{$event->city}}</td>
		                                <td>{{$event->state}}</td>
		                                <td><a href="#" onclick="openEditModal({{$event->eventId}})" class="md-btn md-btn-flat md-btn-flat-primary">View/Edit</a></td>
		                                
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
</div><!-- Container -->



<!-- Add Kids  -->
<div id="editEventTypesModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					Edit Events
				</h4>
			</div>
			<div class="modal-body">
				<div id="messageEventEditDiv"></div>
				<div id="formBody">
					{{ Form::open(array('url' => '/events', 'id'=>"editEventForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
			             	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="eventName">Event Name<span class="req">*</span></label>
				                 	<input name="eventIdEdit" type="hidden" id="eventIdEdit"/>
				                 	{{Form::text('eventNameEdit', null,array('id'=>'eventNameEdit', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				           <div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="eventDate">Event date<span class="req">*</span></label>
				                 	{{Form::text('eventDateEdit', null,array('id'=>'eventDateEdit','required', 'class' => ''))}}
				                 </div>
				            </div>
				            
				            <br clear="all"/><br clear="all"/><br clear="all"/>
				        </div>  
				         <br clear="all"/>
				        <div class="uk-width-medium-1-1">
			                 <div class="parsley-row" id="eventDescriptionEditDiv">
			                 	<label for="eventDescription">Event Description<span class="req">*</span></label>
			                 	{{ Form::textarea('eventDescriptionEdit', null, ['id'=>'eventDescriptionEdit', 'size' => '50x3',  'class' => 'form-control input-sm md-input']) }}
			                 </div>
			            </div> 
			             <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-2">    
				                  <div class="parsley-row" id="locationEditDiv">
				                 	<label for="customerMobile">Location/Area<span class="req">*</span></label>
				                 	{{Form::text('eventLocationEdit', null,array('id'=>'eventLocationEdit', 'required', 'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
				                 </div>
				            </div>  
				        	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row" id="eventTypeEditDiv">
				                 	<label for="eventTypeEdit">Event Type<span class="req">*</span></label>
				                 	{{ Form::select('eventTypeEdit', array('' => '') + $eventTypes, null ,array('id'=>'eventTypeEdit', 'class' => 'input-sm md-input', "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row" id="stateEditDiv">
				                 	<label for="state">State<span class="req">*</span></label>
				                 	{{ Form::select('stateEdit', array('' => '') + $provinces, null ,array('id'=>'stateEdit', 'class' => 'input-sm md-input', "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>				                 
				            </div>
				            <div class="uk-width-medium-1-2">
				                 <div class="parsley-row" id="cityEditDiv">
				                 	<label for="state">City<span class="req">*</span></label>
				                 	{{ Form::select('cityEdit', array('' => ''), null ,array('id'=>'cityEdit', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				            <br clear="all"/> 
			            	
				         </div>
				         <br clear="all"/><br clear="all"/>
				         <div class="uk-width-medium-1-3">
			                 <div class="parsley-row">
			                 	
			                 	<button type="submit" id="eventSubmit" class="md-btn md-btn-primary">Save Event</button>
			                 </div>
		            	</div> 
				        
				    {{ Form::close() }}	 
				</div>

			</div>
		</div>
	</div>
</div>
<!-- Add Kids -->
 
@stop