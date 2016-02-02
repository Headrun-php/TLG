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
                    
                       {{ Form::open(array('url' => '/batches', 'id'=>"courseCategoryForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
                        
                        	<div class="uk-width-medium-1-2">    
				                  <div class="parsley-row">
				                 	<label for="customerMobile">Batch Name<span class="req">*</span></label>
				                 	{{Form::text('batchName', null,array('id'=>'batchName', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				             </div>   
			             	<div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="customerName">Course<span class="req">*</span></label>
				                 	{{ Form::select('franchiseeCourse', array('' => 'Please Select  Course')+ $courseList,null ,array('id'=>'franchiseeCourse', 'required', 'data-md-selectize', 'style'=>'width:250px;')) }}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-2">
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
		            <h3 class="heading_b uk-margin-bottom">Students</h3>
		            
		            <?php 
		            	/* echo "<pre>";
		            	print_r($batches);
		            	echo "</pre>";  */
		           
		            ?>
		
		           
		            <div class="md-card uk-margin-medium-bottom">
		                <div class="md-card-content">
		                    <div class="uk-overflow-container">
		                        <table class="uk-table">
		                            <!-- <caption>Table caption</caption> -->
		                            <thead>
		                            <tr>
		                                <th>Batch Name</th>
		                                <th>Class Name</th>
		                                <th>Action</th>
		                            </tr>
		                            </thead>
		                            <tbody>
		                            <?php  if(isset($batches)){?>
		                            @foreach($batches as $batch)
		                            <tr>
		                                <td>{{$batch->batch_name}}</td>
		                                <td>{{$batch->class_name}}</td>
		                                <td><button class="md-btn md-btn-flat md-btn-flat-primary">View</button></td>
		                                
		                            </tr>
		                            @endforeach
		                            <?php }?> 
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