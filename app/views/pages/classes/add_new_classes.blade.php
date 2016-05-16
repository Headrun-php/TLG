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
       "iDisplayLength": 10,
       "lengthMenu": [ 10, 50, 100, 150, 200 ]
	 });



function saveClasses(){
	if($('#Courses').val() != '' && $('#ClassName').val() != '' && $('#ClassSlug').val() != '' && $('#ClassStartAge').val() != '' && $('#ClassEndAge').val() != '' && $('#Gender').val() != ''){
		$.ajax({
			type: "POST",
        	url: "{{URL::to('/quick/InsertNewClass')}}",
        	data: {'courseId': $('#Courses').val(), 'className': $('#ClassName').val(), 'slug': $('#ClassSlug').val(), 's_age': $('#ClassStartAge').val(), 'e_age': $('#ClassEndAge').val(), 'gender': $('#Gender').val()},
        	dataType:"json",
        	success: function (response)
        	{
        		//console.log(response);
	            $('#msgDiv').html("<h5 class = 'uk-alert uk-alert-success' style = 'color: #fff; width: 90%; padding: 8px; text-align: center'>Row was Inserted Successfully. Please wait untill this page reloads.</h5>");
                setTimeout(function(){
	            	window.location.reload(1);
                }, 2500);
        	}
    	});
	}else{
		$('#msgDiv').html("<h5 class = 'uk-alert uk-alert-danger' style = 'color: #fff; width: 90%; padding: 8px; text-align: center'>Please Fill all required fields and save.</h5>");
	}
}

function editClasses(Co_id, Cl_name, slug, s_age, e_age, gender, id){
	$('#editCourses').val(Co_id);
	$('#editClassName').val(Cl_name);
	$('#editClassSlug').val(slug);
	$('#editClassStartAge').val(s_age);
	$('#editClassEndAge').val(e_age);
	$('#editGender').val(gender);
	$('#editClassId').val(id);

	$('#editModal').modal('show');
}


$('#saveBtn').click(function(){

		var courseId = $('#editCourses').val();
		var ClassName = $('#editClassName').val();
		var ClassSlug = $('#editClassSlug').val();
		var s_age = $('#editClassStartAge').val();
		var e_age = $('#editClassEndAge').val();
		var gender = $('#editGender').val();
		var ClassId = $('#editClassId').val();
	if(courseId != '' && ClassName != '' && ClassSlug != '' && s_age != '' && e_age != '' && gender != ''){
		$.ajax({
        	type: "POST",
        	url: "{{URL::to('/quick/updateClassesMaster')}}",
        	data: {'gender': gender,'ClassSlug': ClassSlug, 'ClassId': ClassId, 'courseId': courseId, 'ClassName': ClassName, 's_age': s_age, 'e_age':e_age},
        	dataType:"json",
        	success: function (response)
        	{
        		console.log(response);
            	$('#modalMsgDiv').html("<h5 class = 'uk-alert uk-alert-success' style = 'color: #fff; width: 100%; padding: 8px; text-align: center'>Row was updated Successfully. Please wait untill this page reloads.</h5>");
                setTimeout(function(){
                    window.location.reload(1);
                }, 2500);
        	}
    	});
	}else{
		$('#modalMsgDiv').html("<h5 class = 'uk-alert uk-alert-danger' style = 'color: #fff; width: 100%; padding: 8px; text-align: center'>Please fill all required fields and Update.</h5>");		
	}	
	});



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
			<h3 class="heading_b uk-margin-bottom">Add Classes</h3>

		<br clear="all"/>
                
		<div id = "addCourses">
			<div class="uk-grid" data-uk-grid-margin>
				<div class="uk-width-medium-1-3">    
				    <div class="parsley-row">
				    	<label for="Courses">Select Courses<span class="req">*</span></label><br>
				        <select id="Courses" name="Courses" class="form-control input-sm md-input" required style='padding:0px; font-weight:bold;color: #727272;'>
				        	@for($i=0; $i < count($franchiseeCourses); $i++)
        					<option value = "{{$franchiseeCourses[$i]['id']}}">{{$franchiseeCourses[$i]['course_name']}}</option>
        					@endfor
        				</select>				                 	
				    </div>
			    </div>
				<div class="uk-width-medium-1-3">
        			<div class="parsley-row form-group">
        				<label for="title[]">Class Name<span class="req">*</span></label>
        					<input id="ClassName" required class="form-control input-sm md-input" name="CourseName[]" type="text"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-3">
        			<div class="parsley-row form-group">
        				<label for="title[]">Slug<span class="req">*</span></label>
        					<input id="ClassSlug" required class="form-control input-sm md-input" name="slug[]" type="text"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-3">
        			<div class="parsley-row form-group">
        				<label for="title[]">Start Age(Months)<span class="req">*</span></label>
        					<input id="ClassStartAge" required class="form-control input-sm md-input" name="startAge[]" type="number"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-3">
        			<div class="parsley-row form-group">
        				<label for="title[]">End Age(Months)<span class="req">*</span></label>
        					<input id="ClassEndAge" required class="form-control input-sm md-input" name="endAge[]" type="number"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-3">    
				    <div class="parsley-row">
				    	<label for="gender">Gender<span class="req">*</span></label><br>
				        <select id="Gender" name="gender" class="form-control input-sm md-input" required style='padding:0px; font-weight:bold;color: #727272;'>
        					<option value = "male">Male</option>
        					<option value = "female">Female</option>
        					<option value = "both">Both</option>
        				</select>				                 	
				    </div>
			    </div>
    		</div>
		</div>

		<br clear="all"/>
		<br clear="all"/>
		<div class="row">
        <div class="col-md-11">
			<button type="button" id="saveCourses" onclick = saveClasses() class="md-btn md-btn-primary pull-right">Save CLasses</button>
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
		<h3 class="heading_b uk-margin-bottom">View Classes</h3>
                
		<div class="md-card-content">
        	<div class="uk-overflow-container">
            	<table  class="uk-table" id="seasonTable">
                	<thead>
		            	<tr>
		            		<th>Name Of Course</th>
		                	<th>Name Of Class</th>
		                    <th>Slug</th>
		                    <th>Start Age</th>
		                    <th>End Age</th>
		                    <th>Gender</th>
                            <th>Actions</th>
		                </tr>
		            </thead>
                  	<tbody>
                         @for($i = 0; $i < count($getAllClassesMasters); $i++)       
                         	<tr>
                         		<td>{{$getAllClassesMasters[$i]['course_master_name']}}</td>
                         		<td>{{$getAllClassesMasters[$i]['class_name']}}</td>
                         		<td>{{$getAllClassesMasters[$i]['slug']}}</td>
                         		<td>{{$getAllClassesMasters[$i]['class_start_age']}}</td>
                         		<td>{{$getAllClassesMasters[$i]['class_end_age']}}</td>
                         		<td>{{$getAllClassesMasters[$i]['gender']}}</td>
                         		<td>
                         			<span class='btn btn-warning btn-xs' title='Modify' onclick = 'editClasses("{{$getAllClassesMasters[$i]['course_master_id']}}" ,"{{$getAllClassesMasters[$i]['class_name']}}", "{{$getAllClassesMasters[$i]['slug']}}", "{{$getAllClassesMasters[$i]['class_start_age']}}", "{{$getAllClassesMasters[$i]['class_end_age']}}", "{{$getAllClassesMasters[$i]['gender']}}", "{{$getAllClassesMasters[$i]['id']}}")'><i class="Small material-icons" style="font-size:20px;">mode_edit</i></span>
                         		</td>
                         	</tr>
                         @endfor
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Edit-->
<div id="editModal" class="modal fade" role="dialog" style = "margin-top: 3em">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Edit Class</h4>
      </div>
      <div class="modal-body">
      		<div id = "modalMsgDiv"></div>
      		<br clear="all"/>
        	<div class="uk-grid" data-uk-grid-margin>
				<div class="uk-width-medium-1-3">    
				    <div class="parsley-row">
				    	<label for="Courses">Select Courses<span class="req">*</span></label><br>
				        <select id="editCourses" name="Courses" class="form-control input-sm md-input" required style='padding:0px; font-weight:bold;color: #727272;'>
				        	@for($i=0; $i < count($franchiseeCourses); $i++)
        					<option value = "{{$franchiseeCourses[$i]['id']}}">{{$franchiseeCourses[$i]['course_name']}}</option>
        					@endfor
        				</select>				                 	
				    </div>
			    </div>
				<div class="uk-width-medium-1-3">
        			<div class="parsley-row form-group">
        				<label_ for="title[]" style = "font-weight:bold;">Class Name<span class_="req">*</span></label>
        					<input id="editClassName" required class="form-control input-sm md-input" name="CourseName[]" type="text"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-3">
        			<div class="parsley-row form-group">
        				<label_ for="title[]" style = "font-weight:bold;">Slug<span class="req">*</span></label>
        					<input id="editClassSlug" required class="form-control input-sm md-input" name="slug[]" type="text"/>
        			</div>
    			</div>
    			<br clear="all"/>
    			<br clear="all"/>
    			<br clear="all"/>
    			<br clear="all"/>
    			<br clear="all"/>
    			<div class="uk-width-medium-1-3">
        			<div class="parsley-row form-group">
        				<label_ for="title[]" style = "font-weight:bold;">Start Age(Months)<span class="req">*</span></label>
        					<input id="editClassStartAge" required class="form-control input-sm md-input" name="startAge[]" type="number"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-3">
        			<div class="parsley-row form-group">
        				<label_ for="title[]" style = "font-weight:bold;">End Age(Months)<span class="req">*</span></label>
        					<input id="editClassEndAge" required class="form-control input-sm md-input" name="endAge[]" type="number"/>
        			</div>
    			</div>
    			<div class="uk-width-medium-1-3">    
				    <div class="parsley-row">
				    	<label for="gender">Gender<span class="req">*</span></label><br>
				        <select id="editGender" name="gender" class="form-control input-sm md-input" required style='padding:0px; font-weight:bold;color: #727272;'>
        					<option value = "male">Male</option>
        					<option value = "female">Female</option>
        					<option value = "both">Both</option>
        				</select>				                 	
				    </div>
			    </div>
			    <input type = "hidden" id = "editClassId">
    		</div>
      </div>
      <div class="modal-footer">
        <button type = "button" class = "btn btn-primary" id = "saveBtn">Save</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
      </div>
    </div>

  </div>
</div>
@stop