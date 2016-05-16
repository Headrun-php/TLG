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
<script type="text/javascript">

	$("#studentsTable").DataTable({
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {

            // Bind click event
            $(nRow).click(function() {
                  //window.open($(this).find('a').attr('href'));
				window.location = $(this).find('a').attr('href');
                  //OR

                // window.open(aData.url);

            });

            return nRow;
        },
        "iDisplayLength": 50,
        "lengthMenu": [ 10, 50, 100, 150, 200 ]
    });

	/* $("#studentsTable tr").click(function (){

		window.location = $(this).find('a').attr('href');
	}) */
		

</script>
@stop


@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="{{url()}}/students/enrolled" style="z-index:8;">Enrolled Students</a></li>
		
	</ul>
</div>
<br clear="all"/>
<div class="">
	<div class="row">
	
		
		
		
		
		 	<div class="md-card">
                
            </div>
            
            
            <div class="md-card">
	            <div class="md-card-content large-padding">
		            <h3 class="heading_b uk-margin-bottom">Enrolled Students</h3>
		            
		            <?php 
		            	/*  echo "<pre>";
		            	print_r($customers);
		            	echo "</pre>";  */
		            
		            ?>
		
		           
		            <div class="md-card uk-margin-medium-bottom">
		                <div class="md-card-content">
		                    <div class="uk-overflow-container">
		                        <table class="uk-table" id="studentsTable">
		                            <!-- <caption>Table caption</caption> -->
		                            <thead>
		                            <tr>
		                                <th>Student Name</th>
		                                <th>Student Gender</th>
		                                <th>Student Age</th>
		                                <th>Student DOB</th>
		                                <!-- <th>Action</th> -->
		                            </tr>
		                            </thead>
		                            <tbody>
		                            @foreach($students as $student)
                                            <?php if($student->students->franchisee_id==Session::get('franchiseId')){ ?>
		                            <tr>
		                                <td>{{$student->students->student_name}}</td>
		                                <td>{{$student->students->student_gender}}</td>
		                                <td><?php echo date_diff(date_create(date('Y-m-d',strtotime($student->students->student_date_of_birth))), date_create('today'))->y.'.'.date_diff(date_create(date('Y-m-d',strtotime($student->students->student_date_of_birth))), date_create('today'))->m.'years';?> </td>
		                                <td>
                                                    {{$student->students->student_date_of_birth}}
		                                	<a  style="display: none" href="{{url()}}/students/view/{{$student->students->id}}">View/Edit</a>
		                                
		                                </td>
		                                
		                                
		                            </tr>
                                            <?php } ?>
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
 
@stop
