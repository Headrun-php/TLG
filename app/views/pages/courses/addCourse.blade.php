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
					
					
                       {{ Form::open(array('url' => '/courses', 'id'=>"courseCategoryForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
			             	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="courseName">Course Name<span class="req">*</span></label>
				                 	{{Form::text('courseName', null,array('id'=>'courseName', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
			                	</div>
			                    <div class="parsley-row">                                   
                                    {{ Form::select('masterCourse', array('' => 'Please Select Master Course')+ $courseList,null ,array('id'=>'val_select', 'required', 'data-md-selectize', 'style'=>'width:250px;')) }}
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
		            	print_r($courses);
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
		                            @foreach($courses as $course)
		                            <tr>
		                                <td>{{$course->course_name}}</td>
		                                <td>{{$course->CoursesMaster->course_name}}</td>
		                                <td>{{$course->Users->first_name}} {{$course->Users->last_name}}</td>
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
 
@stop