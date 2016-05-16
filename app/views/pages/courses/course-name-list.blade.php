@extends('layout.master')

@section('content')
    <div class="row">
        <div class="md-card">
            <div class="md-card-content large-padding">
                <h3 class="heading_b uk-margin-bottom">Add Courses</h3>
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
                {{ Form::open(array('url' => '/courses/add', 'id'=>"form_course_name_list", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                <div class="uk-grid" data-uk-grid-margin>
                    <div class="parsley-row">                                   
                        {{ Form::select('masterCourseList', array('' => 'Please Select Master Course')+ $courseList,null ,array('id'=>'val_select', 'required', 'data-md-selectize', 'style'=>'width:250px;')) }}                        
                        {{$errors->first('masterCourse')}}
                    </div>
                </div>
                <div class="uk-grid">
                    <div class="uk-width-1-1">
                        <button type="submit" class="md-btn md-btn-primary">Add Course</button>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="md-card">
            <div class="md-card-content large-padding">
                <h3 class="heading_b uk-margin-bottom">View Courses</h3>
                <?php
                /*  echo "<pre>";
                  print_r($courses);
                  echo "</pre>"; */
                ?>


                <div class="md-card uk-margin-medium-bottom">
                    <div class="md-card-content">
                        <div class="uk-overflow-container">
                            <table class="uk-table" id="table_course_name_list">
                                <!-- <caption>Table caption</caption> -->
                                <thead>
                                    <tr>
                                        <th>Course Name</th>
                                        <th>Slug</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($courses as $course)
                                    <tr>
                                        <td>{{$course->course_name}}</td>
                                        <td>{{$course->slug }}</td>
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

@stop