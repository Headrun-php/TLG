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
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script type="text/javascript">

$("#customersTable").DataTable();

	
	
</script>

@stop

@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="{{url()}}/batches" style="z-index:8;">Batches</a></li>
		<li><a href="#" style="z-index:7;">Attendance</a></li>
	</ul>
</div>
<br clear="all"/>
<div class="">
	<div class="row">
	
		
		
		
			<h4>  {{$batch->batch_name}} Attendance - {{$studentsInBatch}} Student(s)</h4>
			<h5><strong>Lead Instructor:</strong>{{$leadInstructor}} </h5>
			<h5><strong>Alternate Instructor:</strong>{{$alternateInstructor}}</h5>
		
            
            <div class="md-card">
	            <div class="md-card-content">
		            
		            
		            <?php 
		            	/*  echo "<pre>";
		            	print_r($attendanceArray);
		            	echo "</pre>";   */
		            
		           
		            
		            ?>
				                        
                            	<?php foreach($attendanceArray as $attendance){?>
                            	<div class="col-md-12" style="border:1px #e3e3e3 solid; padding:2px;">
                            		<?php 
                            		
                            		
                            		/* echo "<pre>";
                            		print_r($attendance);
                            		echo "</pre>"; */
                            		foreach($attendance['Attendance'] as $att){
                            			
                            		}
                            		?>
			                            	<?php 
								            	/* echo "<pre>";
								            	print_r($att);
								            	echo "</pre>";   */
								            
								            ?>
                            		<div class="col-md-12" >
                            			<div>
                            			<h4>{{$attendance['Student']->Students->student_name}} 
                            			<span class="new badge" style="background-color:#7CB342; font-size:10px;">
                            				Present days - {{$attendance['statistics']['present']}}
                            			</span>
                            			
                            			<span class="new badge" style="background-color:#1976D2; font-size:10px;">
                            				Excused absent - {{$attendance['statistics']['ea']}}
                            			</span>
                            			
                            			<span class="new badge" style="background-color:#E53935; font-size:10px;">
                            				Absent days - {{$attendance['statistics']['absent']}}
                            			</span>
                            			
                            			<span class="new badge" style="background-color:#E53935; font-size:12px;">
                            				Total sessions - {{$attendance['statistics']['totalSessions']}}
                            			</span>
                            			</h4>
                            			
                            			</div>
                            		</div>
                            		<div class="col-md-12" style="padding-left: 20px">
                            			<?php foreach($attendance['Attendance'] as $att){?>
                            					<div class="col-xs-1" style="    margin-bottom: 10px;  padding-left: 0px; ">
	                            					<span class="new badge" style="background-color:#909090; font-size:10px;">
	                            					{{date('d M y', strtotime($att->schedule_date))}}
	                            					</span>  
	                            					<div class="col-xs-1" style=" padding-top: 0px;">
	                            					      <?php 
	                            					      	if(isset($att['attendStat'])){
	                            					      		
	                            					      		print_r($att['attendStat']['status']);
	                            					      	}
	                            					      
	                            					      ?>     					
	                            					</div>                          					
                            					</div>
                            					
                            			
                            			<?php }?>
                            		</div>
                            	</div>
                            	
                            	<br clear="all"/>
                            	
                            	<?php }?>
                           
				</div>
			</div>
		
		
		
		
		
	</div><!-- row -->
</div><!-- Container -->


<!-- Modal -->
<div id="introVisitModal" class="modal fade" role="dialog" style="z-index: 99999;
    margin-top: 50px;">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Checking availability...</h4>
      </div>
      <div class="modal-body">
      
      		<div id="availabilityCheckDiv">      		
				<p>Please wait while we check availability of selected date and time</p>      		
      		</div>
      		<div id="messageDiv">
      		
      		
      		</div>
      
      
        	
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
 
@stop