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
<script
	src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>

<script type="text/javascript">

	$("#eventsTable").DataTable();

	$("#eventsTable tr").click(function (){

		window.location = $(this).find('a').attr('href');
	})
	

	$("#eventDate").kendoDatePicker({
		//change:onDateChangeFunction
	});

	function openEditModal(id){

		$("#eventTypeIdEdit").val("");
		$.ajax({
			  type: "POST",
			  url: "{{URL()}}"+"/quick/eventTypeById",
			  data: {"eventTypeId":id},
			  dataType: 'json',
			  async: true,
			  success: function(response, textStatus, jqXHR)
			  {
				  $("#eventTypeIdEdit").val(id);
				  $("#eventTypeNameEdit").val(response['0'].name);
					
				   console.log(response);
				  
			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  {
		 
			  }
		});
	
		$("#editEventTypesModal").modal('show');

		
	}


	$("#eventTypeEditSubmit").click(function (e){
		event.preventDefault();
		saveEventTypes();
	})

	function saveEventTypes(){

		$("#messageEventEditDiv").html("");
		$.ajax({
			  type: "POST",
			  url: "{{URL()}}"+"/quick/saveEventType",
			  data: {"eventTypeIdEdit":$("#eventTypeIdEdit").val(), "eventTypeNameEdit":$("#eventTypeNameEdit").val()},
			  dataType: 'json',
			  async: true,
			  success: function(response, textStatus, jqXHR)
			  {
				  $("#eventTypeIdEdit").val(id);
				  $("#eventTypeNameEdit").val(response['0'].name);
					
				   console.log(response);
				   if(response.status == "success"){
						$("#messageEventEditDiv").html('<p class="uk-alert uk-alert-success">Event Type successfully edited.</p>');
						setTimeout(function(){
						   window.location.reload(1);
						}, 5000);
				   }else{
					   $("#messageEventEditDiv").html('<p class="uk-alert uk-alert-danger">Sorry, Event Type could not be  edited.</p>');
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
<div class="container">
	<div class="row">
	
		
		
		
		
		 <h4>Event Type</h4>
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
					{{ Form::open(array('url' => '/events/types', 'id'=>"addEventForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
			             	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="customerName">Event Type Name<span class="req">*</span></label>
				                 	
				                 	{{Form::text('eventTypeName', null,array('id'=>'eventTypeName', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>				           
				        </div>
				        <br clear="all"/> 
			            <div class="uk-width-medium-1-3">
			                 <div class="parsley-row">
			                 	
			                 	<button type="submit" id="eventSubmit" class="md-btn md-btn-primary">Add New Event type</button>
			                 </div>
			            </div> 
				    {{ Form::close() }}	     
				        
				</div>
			</div>
            
            
            <div class="md-card">
	            <div class="md-card-content large-padding">
		            <h3 class="heading_b uk-margin-bottom">Event types</h3>
		            
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
		                                <th>Event Type</th>
		                                <th>Action</th>
		                            </tr>
		                            </thead>
		                            <tbody>
		                            @foreach($eventTypes as $eventType)
		                            <tr>
		                                <td>{{$eventType->name}}</td>
		                                <td><a href="#"  onclick="openEditModal({{$eventType->id}});"  class="md-btn md-btn-flat md-btn-flat-primary">View/Edit</a></td>
		                                
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
					Edit Event Types
				</h4>
			</div>
			<div class="modal-body">
				<div id="messageEventEditDiv"></div>
				<div id="formBody">
					{{ Form::open(array('id'=>"EditEventForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
			             	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="customerName">Event Type Name<span class="req">*</span></label>
				                 	<input type="hidden" id="eventTypeIdEdit" name="eventTypeIdEdit"/>
				                 	{{Form::text('eventTypeNameEdit', null,array('id'=>'eventTypeNameEdit', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>				           
				        </div>
				        <br clear="all"/> 
			            <div class="uk-width-medium-1-3">
			                 <div class="parsley-row">
			                 	
			                 	<button type="submit" id="eventTypeEditSubmit" class="md-btn md-btn-primary">Save Event type</button>
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