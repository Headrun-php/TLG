@extends('layout.master')

@section('libraryCSS')
	<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css"/>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' />
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet' />

	<style type="text/css">

	</style>
@stop


@section('libraryJS')
<script src="{{url()}}/assets/js/pages/validator.js"></script>
<script src="{{url()}}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="{{url()}}/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
<script src="{{url()}}/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
<script src="{{url()}}/assets/js/custom/datatables_uikit.min.js"></script>
<script src="{{url()}}/assets/js/pages/plugins_datatables.min.js"></script>
<script src="{{url()}}/assets/js/kendoui_custom.min.js"></script>
<script src="{{url()}}/assets/js/pages/kendoui.min.js"></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>

<script type="text/javascript">
	
	$("#seasonTable").DataTable({
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $(nRow).click(function() {
            });
            return nRow;
        },
       "iDisplayLength": 20,
       "lengthMenu": [ 10, 50, 100, 150, 200 ]
	 });


	/*$(document).on("click", "a.remove" , function() {
            $(this).parent().parent(".uk-grid").remove();
    });

	function addCourses(){
		var markups ='<div class="uk-grid" data-uk-grid-margin>'+
					  	'<div class="uk-width-medium-1-5">'+
        					'<div class="parsley-row form-group">'+
        						'<label for="title[]">Course Name<span class="req">*</span></label>'+
        						'<input id="CourseName[]" required class="form-control input-sm md-input" name="CourseName[]" type="text"/>'+
        					'</div>'+
    					'</div>'+
    					'<div class="uk-width-medium-1-5">'+
        					'<div class="parsley-row form-group">'+
        						'<label for="title[]">Slug<span class="req">*</span></label>'+
        						'<input id="slug[]" required class="form-control input-sm md-input" name="slug[]" type="text"/>'+
        					'</div>'+
    					'</div>'+
    					'<div class="uk-width-medium-1-5">'+
        					'<div class="parsley-row form-group">'+
        						'<label for="title[]">Start Age(years)<span class="req">*</span></label>'+
        						'<input id="startAge[]" required class="form-control input-sm md-input" name="startAge[]" type="text"/>'+
        					'</div>'+
    					'</div>'+
    					'<div class="uk-width-medium-1-5">'+
        					'<div class="parsley-row form-group">'+
        						'<label for="title[]">End Age(years)<span class="req">*</span></label>'+
        						'<input id="endAge[]" required class="form-control input-sm md-input" name="endAge[]" type="text"/>'+
        					'</div>'+
    					'</div>'+
    					'<div class="uk-width-medium-1-5">'+	
    						'<a href="javascript:void(0);" class="remove badge" style = "background: #EC5F54; color: #fff"> &times; </a>'+
    					'</div>'+
					'</div>';
		$('#addCourses').append(markups);
	}*/

	function confirmation(id){
		$('#ConfirmDelete').modal('show');
    	$('#deleteBtn').click(function(){
        	deleteCourse(id);
        	//console.log(id);
    	});
    	$('#cancelBtn').click(function(){
        	setTimeout(function(){
                    window.location.reload(1);
                }, 10);
    	});
	}

	function deleteCourse(id){
		
		$('#ConfirmDelete').modal('hide');
    	var CourseId = id;
    	//console.log(newsId);
    	$.ajax({
        	type: "POST",
        	url: "{{URL::to('/quick/deleteCoursesMaster')}}",
        	data: {"id": CourseId},
        	dataType:"json",
        	success: function (response)
        	{
            	console.log(response);
            	$('#msgDiv1').html("<h5 alert class = 'uk-alert-success' style = 'color: #fff; width: 100%; padding: 8px; text-align: center'>Deleted Row Successfully. Please wait untill this page reloads.</h5>");
                setTimeout(function(){
                    window.location.reload(1);
                }, 2500);
        	}
    	});
	}

	function editCourse(id,name,slug,s_age,e_age){
		$('#courseName').val(name);
		$('#slug').val(slug);
		$('#startAge').val(s_age);
		$('#endAge').val(e_age);
		$('#courseId').val(id);

		$('#editModal').modal('show');
	}

	$('#saveBtn').click(function(){

		var id = $('#courseId').val();
		var name = $('#courseName').val();
		var slug = $('#slug').val();
		var s_age = $('#startAge').val();
		var e_age = $('#endAge').val();

		$.ajax({
        	type: "POST",
        	url: "{{URL::to('/quick/updateCoursesMaster')}}",
        	data: {'id': id, 'name': name, 'slug': slug, 's_age': s_age, 'e_age':e_age},
        	dataType:"json",
        	success: function (response)
        	{
            	$('#modalMsgDiv').html("<h5 style = 'color: #fff; background: green; width: 100%; padding: 8px; text-align: center'>Row was updated Successfully. Please wait untill this page reloads.</h5>");
                setTimeout(function(){
                    window.location.reload(1);
                }, 2500);
        	}
    	});
	});

	function saveCourses(){
		if($('#CourseName').val() != '' && $('#CourseSlug').val() != '' && $('#CourseStartAge').val() != '' && $('#CourseEndAge').val() != ''){

			$.ajax({
        		type: "POST",
        		url: "{{URL::to('/quick/InsertNewCoursesMaster')}}",
        		data: {'name': $('#CourseName').val(), 'slug': $('#CourseSlug').val(), 's_age': $('#CourseStartAge').val(), 'e_age': $('#CourseEndAge').val()},
        		dataType:"json",
        		success: function (response)
        		{
        			console.log(response);
	            	$('#msgDiv').html("<h5 style = 'color: #fff; background: #449D44; width: 90%; padding: 8px; text-align: center'>Row was updated Successfully. Please wait untill this page reloads.</h5>");
                	setTimeout(function(){
	                    window.location.reload(1);
                	}, 2500);
        		}
    		});

		}else{
			$('#msgDiv').html("<h5 style = 'color: #fff; background: #ec5f54; width: 90%; padding: 8px; text-align: center'>Please Fill all required fields and save.</h5>");
		}
	}

</script>
@stop




@section('content')
<div class="uk-width-medium-1-1">
	<div class="parsley-row form-group">
    	<div id = "msgDiv"></div>
    </div>
</div>
<div class="md-card">
	<div class="md-card-content large-padding">
			<h3 class="heading_b uk-margin-bottom">Add Courses</h3>
		<!--<div class="uk-width-medium-1-1">
			<div class="parsley-row form-group">
    			<strong><h4>Add<i class=" btn fa fa-plus fa-1x" onclick="addCourses()"></i></h4></strong>
    		</div>
		</div>-->

		<br clear="all"/>

		<div id = "addCourses">
			<div class="uk-grid" data-uk-grid-margin>
				<div class="uk-width-medium-1-5">
        			<div class="parsley-row form-group">
        				<label for="title[]">Course Name<span class="req">*</span></label>
        					<input id="CourseName" required class="form-control input-sm md-input" name="CourseName[]" type="text"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-5">
        			<div class="parsley-row form-group">
        				<label for="title[]">Slug<span class="req">*</span></label>
        					<input id="CourseSlug" required class="form-control input-sm md-input" name="slug[]" type="text"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-5">
        			<div class="parsley-row form-group">
        				<label for="title[]">Start Age(years)<span class="req">*</span></label>
        					<input id="CourseStartAge" required class="form-control input-sm md-input" name="startAge[]" type="number"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-5">
        			<div class="parsley-row form-group">
        				<label for="title[]">End Age(years)<span class="req">*</span></label>
        					<input id="CourseEndAge" required class="form-control input-sm md-input" name="endAge[]" type="number"/>
        			</div>
    			</div>
    		</div>
		</div>

		<br clear="all"/>

		<div class="row">
        <div class="col-md-11">
			<button type="button" id="saveCourses" onclick = saveCourses() class="md-btn md-btn-primary pull-right">Save Courses</button>
        </div>
    </div>
	</div>
</div>


<div class="uk-width-medium-1-1">
	<div class="parsley-row form-group">
    	<div id = "msgDiv1"></div>
    </div>
</div>

<div class="md-card">
	<div class="md-card-content large-padding">
		<h3 class="heading_b uk-margin-bottom">View Courses</h3>

		<div class="md-card-content">
        	<div class="uk-overflow-container">
            	<table  class="uk-table" id="seasonTable">
                	<thead>
		            	<tr>
		                	<th>Name Of Course</th>
		                    <th>Slug</th>
		                    <th>Start Age</th>
		                    <th>End Age</th>
                            <th>Actions</th>
		                </tr>
		            </thead>
                  	<tbody>
                        <?php if($allCourse){for($i=0;$i<sizeof($allCourse);$i++){ ?>
                        <tr>
                        	<td>{{$allCourse[$i]['course_name']}}</td>
                            <td>{{$allCourse[$i]['slug']}}</td>
                            <td>{{$allCourse[$i]['age_start']}}</td>
                            <td>{{$allCourse[$i]['age_end']}}</td>
                            <?php 
                            	for ($j=0; $j < count($eligibleForAction); $j++) { 
                            		if($allCourse[$i]['id'] == $eligibleForAction[$j]){ 
                            ?>
                            			<td>
                            				<span class='btn btn-info btn-xs' title='Modify'><span class = "glyphicon glyphicon-pencil" onclick = 'editCourse({{$eligibleForAction[$j]}},"{{$allCourse[$i]['course_name']}}","{{$allCourse[$i]['slug']}}", {{$allCourse[$i]['age_start']}}, {{$allCourse[$i]['age_end']}})'></span></span>
                            				<span class='btn btn-danger btn-xs' title='Delete'><span class = "glyphicon glyphicon-trash" onclick = "confirmation({{$eligibleForAction[$j]}})"></span></span>
                            			</td>
                            <?php
                            		}else{

                            		}
                            	}
                            ?>
                            <td></td>
                        </tr>
                        <?php }}else{ ?>
                        <tr>
                        	No seasons Added
                        </tr>
                        <?php } ?>                
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Delete-->
<div id="ConfirmDelete" class="modal fade" role="dialog" style = "margin-top: 3em">
  <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Confirmation</h4>
      </div>
      <div class="modal-body">
        <p>Are you Sure! you want to delete ?</p>
      </div>
      <div class="modal-footer">
        <button type = "button" class = "btn btn-primary" id = "deleteBtn">Delete</button>
        <button type="button" class="btn btn-default" data-dismiss_="modal" id = "cancelBtn">Cancel</button>
      </div>
    </div>

  </div>
</div>


<!-- Modal for Edit-->
<div id="editModal" class="modal fade" role="dialog" style = "margin-top: 3em">
  <div class="modal-dialog modal-md">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Course</h4>
      </div>
      <div class="modal-body">
        	<div class="form-group">
    			<label for="courseName">Name of Course:</label>
    			<input type="text" class="form-control" id="courseName" value = "">
    			<input type="hidden" class="form-control" id="courseId" value = "">
  			</div>
  			<div class="form-group">
	    		<label for="slug">Slug:</label>
    			<input type="text" class="form-control" id="slug" value = "">
  			</div>
	  		<div class="form-group">
    			<label for="startAge">Start Age:</label>
    			<input type="number" class="form-control" id="startAge" value = "">
  			</div>
  			<div class="form-group">
	    		<label for="endAge">End Age:</label>
    			<input type="number" class="form-control" id="endAge" value = "">
  			</div>
  			<div id = "modalMsgDiv"></div>
      </div>
      <div class="modal-footer">
        <button type = "button" class = "btn btn-primary" id = "saveBtn">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>

@stop