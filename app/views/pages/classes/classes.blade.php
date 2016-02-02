@extends('layout.master')

@section('content')
<div class="container">
	<div class="row">
	
		
		
		
		
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
                    
                       {{ Form::open(array('url' => '/classes', 'id'=>"classesForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
			             	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	
				                 	{{Form::text('className', null,array('id'=>'className', 'required', 'readonly', 'class' => 'form-control input-sm md-input','placeholder'=>'Class name *'))}}
				                 </div>
			                	</div>
			                	 <div class="parsley-row">
                                   
                                    {{ Form::select('franchiseeCourse', array('' => 'Please Select Master Course')+ $franchiseeCourses,null ,array('id'=>'franchiseeCourse', 'required', 'form-control', 'style'=>'width:250px;')) }}
                                </div>
			                    <div class="parsley-row">
                                   <select name="classId" id="classId" required style='width:250px;'>
                                   		
                                   
                                   </select>
                                   
                                </div>
                            </div>
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" class="md-btn md-btn-primary">Submit</button>
                            </div>
                        </div>
                    {{ Form::close() }}	
                </div>
            </div>
            
            
            <div class="md-card">
	            <div class="md-card-content large-padding">
		            <h3 class="heading_b uk-margin-bottom">Courses</h3>
		            
		            <?php 
		            	/*  echo "<pre>";
		            	print_r($classes);
		            	echo "</pre>";  */
		            ?>
		
		           
		            <div class="md-card uk-margin-medium-bottom">
		                <div class="md-card-content">
		                    <div class="uk-overflow-container">
		                        <table class="uk-table">
		                            <!-- <caption>Table caption</caption> -->
		                            <thead>
		                            <tr>
		                                <th>Course Name</th>
		                                <th>Master Course</th>
		                                <th>Created By</th>
		                                <th>Action</th>
		                            </tr>
		                            </thead>
		                            <tbody>
		                            @foreach($classes as $class)
		                            <tr>
		                                <td>{{$class->class_name}}</td>
		                                <td>{{$class->Courses->course_name}}</td>
		                                <td>{{$class->Users->first_name}} {{$class->Users->last_name}}</td>
		                                <td></td>
		                                
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

<script src="{{url()}}/assets/js/common.min.js"></script>
<!-- uikit functions -->
<script src="{{url()}}/assets/js/uikit_custom.min.js"></script>
<!-- altair common functions/helpers -->
<script src="{{url()}}/assets/js/altair_admin_common.min.js"></script>

<script type="text/javascript">

$(function() {
		                            
   $("#classId").change(function (){

	  

		$("#className").val($("#classId option:selected").text());
   });

  $("#franchiseeCourse").change(function (){
	getMasterRelatedClasses();
  })

  function getMasterRelatedClasses(){


	  $.ajax({// ajax call starts
          type: "POST",
          url: "{{URL::to('/quick/classesbymaster')}}",
          data: {'franchiseeCourse': $('#franchiseeCourse').val()},
          dataType:"json",
          success: function (response)
          {

        	  $("#className").val("");
        	  $('#classId').empty();
        	  $string = '<option value="">Select Class name</option>';
        	  $.each(response, function (index, item) {

        		  console.log(index+" = "+item);

        		  $string += '<option value='+index+'>'+item+'</option>';
                 
              });
        	  $('#classId').append($string);
          }//success
      }); //ajax 
	  
  }


   
});
		                            
</script>


@stop