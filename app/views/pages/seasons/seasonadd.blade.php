@extends('layout.master')
@section('libraryCSS')
	<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->
	<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css"/>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' />
    <link href='https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css' rel='stylesheet' />
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

<!--for disabling days
@section('cal')
    <script src="//kendo.cdn.telerik.com/2016.1.112/js/jquery.min.js"></script>
    <script src="//kendo.cdn.telerik.com/2016.1.112/js/kendo.all.min.js"></script>
@stop
-->
<script type="text/javascript">
    $(document).ready(function() {
    $('#seasonstartdate').kendoDatePicker(
            //{disableDates: ["sa","su","tu","we","th","fr"],}
    );
    $('#seasonenddate').kendoDatePicker();
    $('input[name="startday[]"]').kendoDatePicker();
    $('input[name="endday[]"]').kendoDatePicker();

    });
        function cal(){
   // $('input[name="startday[]"]').each(function(i){
   //     console.log($('input[name="startday[]"]'));
   // });    
     var values = $('input[name="startday[]"]').map(function() {
        return this.value
    }).get()
    console.log(values);
    }
    
    function addholiday(){
       var data= "<div class='uk-grid' data-uk-grid-margin>"+
                        "<div class='uk-width-medium-1-3'>"+
                            "<div class='parsley-row form-group'>"+
                                "<label for='title[]'>Title<span class='req'>*</span></label>"+
                                "<input id='title[]' required class='form-control input-sm md-input'name='title[]' type='text'/>"+
                            "</div>"+
                        "</div>"+
                        "<div class='uk-width-medium-1-3'>"+
                            "<div class='parsley-row form-group'>"+
                                "<label for='startday'>Start day</label>"+
                                "<input id='holidaystartday[]' classs='k-input' required name='startday[]' type='text' data-role='datepicker' role='combobox' aria-expanded='false'aria-readonly='false'/>"+
                            "</div>"+
                        "</div>"+
                        "<div class='uk-width-medium-1-3'>"+
                            "<div class='parsley-row form-group'>"+
                                "<label for='endday'>End day</label>"+
                                 "<input id='holidayendday[]' classs='k-input' required name='endday[]' type='text' data-role='datepicker' role='combobox' aria-expanded='false'aria-readonly='false'/>"+
                       
                            "</div>"+
                        "</div>"+
                    "</div>";
            $('.addholiday').append(data);
            $('input[name="startday[]"]').kendoDatePicker();
            $('input[name="endday[]"]').kendoDatePicker();
            $('input[name="startday[]"]').kendoDatePicker();
            $('input[name="endday[]"]').kendoDatePicker();
            //console.log(data);
    }
    
    $('#seasonenddate').change(function(){
        if($('#seasonstartdate').val() != ''){
            $('#addseason').removeClass('disabled');
        
                    $.ajax({
                    type: "POST",
                     url: "{{URL::to('/quick/season/getWeekstartenddayseason')}}",
                    data: {'startdate': $('#seasonstartdate').val(),'enddate': $('#seasonenddate').val()},
                    dataType:"json",
                    success: function (response)
                     {
                         console.log(response);
                         if(response.statusofseasondate==='wrong'){
                              $('#sessioninfo').html("<p class='uk-alert uk-alert-warning'>please select season startday as monday and end day as sunday</p>");
                         } 
                        if(response.statusofseasondate==='correct'){
                         console.log(response.status);
                         console.log(response.sessionstartdate);
                         console.log(response.sessionenddate);
                         console.log(response.sessionno);
                         $('#sessioninfo').html("<p class='uk-alert uk-alert-primary'>Season Sessions selected:"+response.sessionno+" </p>");   
                     }                    
                     }
                     });
                     
        
        }else{
            console.log('please select start date');
            
        }
    });
    $('#seasonstartdate').change(function(){
        if($('#seasonenddate').val() != ''){
        $('#addseason').removeClass('disabled');
        $.ajax({
                    type: "POST",
                     url: "{{URL::to('/quick/season/getWeekstartenddayseason')}}",
                    data: {'startdate': $('#seasonstartdate').val(),'enddate': $('#seasonenddate').val()},
                    dataType:"json",
                    success: function (response)
                     {
                           console.log(response);
                         if(response.statusofseasondate==='wrong'){
                              $('#sessioninfo').html("<p class='uk-alert uk-alert-warning'>please select season startday as monday and end day as sunday</p>");
                         } 
                        if(response.statusofseasondate==='correct'){
                         console.log(response.status);
                         console.log(response.sessionstartdate);
                         console.log(response.sessionenddate);
                         console.log(response.sessionno);
                         $('#sessioninfo').html("<p class='uk-alert uk-alert-primary'>Season Sessions selected:"+response.sessionno+" </p>");   
                     }                     
                     }
                     });
        }
    });
    
    $('#addseason').click(function(e){
        e.preventDefault();
        $('#sessioninfo').html("<p class=' uk-alert uk-alert-warning'> please wait your new season is being added</p>");
        $('#addseason').addClass('disabled');
        var title,startdate,enddate,loc;
        if(($('input[name="title[]"').val()!='')){
            
       
                   var title = $('input[name="title[]"]').map(function() {
                        return this.value
                   }).get();
                   var startdate = $('input[name="startday[]"]').map(function() {
                        return this.value
                   }).get();
                   var enddate = $('input[name="endday[]"]').map(function() {
                        return this.value
                   }).get();
                   
            
        }else{
             title=[''];
             startdate=[''];
             enddate=[''];
            
        }
        if(($('input[name="location[]"').val()!='')){
                var loc = $('input[name="location[]"]').map(function() {
                        return this.value
                   }).get();
               
        }else{
             loc=[''];
        }
        console.log(loc);
        $.ajax({
                    type: "POST",
                     url: "{{URL::to('/quick/season/addSeason')}}",
                    data: {'startdate': $('#seasonstartdate').val(),'enddate': $('#seasonenddate').val(),
                        'secondchilddiscount':'0',//$('#secondchilddiscountPercentage').val(),
                        'secondclassdiscount':'0',//$('#secondclassdiscountPercentage').val(),
                        'title':title,'holidaystartdate':startdate,'holidayenddate':enddate,
                        'location':loc,'seasonType':$('#seasonType').val(),},
                    dataType:"json",
                    success: function (response)
                     {
                         console.log(response.status);
                         if(response.status=='success'){
                             console.log('success');
                             $('#sessioninfo').html("<p class=' uk-alert uk-alert-success'> Season added Succesfully for sessions:"+response.sessionafterholidays+".please wait till the page reloads.</p>");
                             setTimeout(function(){
				   window.location.reload(1);
				}, 3000);
                         }else{
                             console.log(response.status);
                         }
                     }
                     });
       // console.log($('input[name="title[]"').val());
       // console.log($('#secondchilddiscountPercentage').val());
       // console.log($('#secondclassdiscountPercentage').val());
    });
    
    
   function addLocation(){
                        
                        var data_location="<div class='uk-width-medium-1-1'>"+
                                                "<div class='parsley-row form-group'>"+
                                                     "<label for='locaation[]'>Location<span class='req'>*</span></label>"+
                                                    "<input id='location[]' type='text' required='required' class='form-control input-md md-input' name='location[]'>"+
                                                "</div>"+
                                           "</div>";
                           $('#addlocation').append(data_location);
                        
   }
</script>

@stop


@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="#" style="z-index:8;">Seasons</a></li>
		<li><a href="#" style="z-index:7;">Add Seasons</a></li>
	</ul>
</div>
<br clear="all"/>
<div class="">
	<div class="row">
            <h4>New Season</h4>
		 <div class="md-card">
                    <div class="md-card-content large-padding ">
                     <div class="addholiday">
                   {{ Form::open(array('files'=> true,'url' => '/seasons/add', 'id'=>"addSeasonsForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                            <div id='sessioninfo'> </div>
                            </div>
			    <div class="uk-width-medium-1-3">
				                 <div class="parsley-row form-group">
                                                    <label for="seasonstart">Season start date</label><br>(MM/DD/YYYY)
                                                        {{Form::text('seasonstartdate',
								null,array('id'=>'seasonstartdate', 'class' => '','required'))}} 
                                                 </div>
                                
                            </div>
                            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row form-group">
                                                    <label for="seasonend">Season end date</label><br>(MM/DD/YYYY)
                                                        {{Form::text('seasonenddate',
								null,array('id'=>'seasonenddate', 'class' => '','required'))}}                                 
                                                 </div>
                                
                            </div>
                            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row form-group">
                                                      <strong><label>Season Type</label></strong>
                                                            <select name="seasonType" id="seasonType" class="input-sm md-input"
                                                                 style='padding: 0px; font-weight: bold; color: #727272; width:100%'>
                                                                                        <option value="Launch Season" >Launch Season</option>
											<option value="Full Season">Full Season</option>
											<option value="Summer Season">Summer Season</option>
											<option value="Summer Camp">Summer Camp</option>
											<option value="Fall Camp">Fall Camp</option>
											<option value="Regular Camp">Regular Camp</option>
                                                            </select>
                                                 
                                                 </div>
                                
                            </div>
                            <br clear="all"/><br clear="all"/>
                            
                                <div class="uk-width-medium-1-1">
                                    <div class="parsley-row form-group">
                                        <strong><h4>Location<i class=" btn fa fa-plus fa-1x" onclick="addLocation()"></i></h4</strong>
                                    </div>
                                </div>
                           
                            
                                <div class="addlocation" id="addlocation"> 
                                    <div class="uk-width-medium-1-1">
                                        <div class="parsley-row form-group">
                                             <label for="locaation[]">Location<span class="req">*</span></label>
                                            {{Form::text('location[]', 'GYM',array('id'=>'location[]',
								'required', 'class' => 'form-control input-md md-input'))}}
                                        </div>
                                    </div>
                                        <!--
                                     <div class="uk-width-medium-1-1">
                                        <div class="parsley-row form-group">
                                             <label for="locaation[]">Location<span class="req">*</span></label>
                                            {{Form::text('location[]', 'MPR',array('id'=>'location[]',
								'required', 'class' => 'form-control input-md md-input'))}}
                                        </div>
                                    </div>
                                        -->
                                </div>
                                   
                                   
                                
                            
                             <br clear="all"/><br clear="all"/>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row form-group">
                                    <strong><h4>Holidays <i class=" btn fa fa-plus fa-1x" onclick="addholiday()"></i></h4</strong> <p style="font-size:12px;">(please select start holiday as monday and end holiday as sunday)</p>
                                </div>
                            </div>    

                            </div>
                        
                         <div class="uk-grid" data-uk-grid-margin>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row form-group">
                                <label for="title[]">Title<span class="req">*</span></label>
                                {{Form::text('title[]', null,array('id'=>'title[]',
								'required', 'class' => 'form-control input-sm md-input'))}}
                            </div>
                        </div>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row form-group">
                                <label for="startday">Start day</label>
                                {{Form::text('startday[]',
								null,array('id'=>'holidaystartday[]', 'class' => '','required'))}} 
                            </div>
                        </div>
                        <div class="uk-width-medium-1-3">
                            <div class="parsley-row form-group">
                                <label for="startday">End day</label>
                                {{Form::text('endday[]',
								null,array('id'=>'holidayendday[]', 'class' => '','required'))}} 
                            </div>
                        </div>
                    </div>  
                    
                            </div>
                        
                        
                        <?php if(Session::get('userType') == 'SUPER_ADMIN'){?>
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <div class="parsley-row form-group">
                                <br>
                                <strong><h4>Discounts</h4</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-2">
                                <div class="parsley-row form-group">
                                    <strong><label>second child</label></strong>
                                    <select name="secondchilddiscountPercentage" id="secondchilddiscountPercentage" class="input-sm md-input"
                                               style='padding: 0px; font-weight: bold; color: #727272; width:50%'>
                                        <option value="0" >Select discount percentage</option>
											<option value="10">10%  discount</option>
											<option value="20">20%  discount</option>
											<option value="30">30%  discount</option>
											<option value="40">40%  discount</option>
											<option value="50">50%  discount</option>
                                                                                        <option value="60">60%  discount</option>
                                                                                        <option value="70">70%  discount</option>
                                                                                        <option value="80">80%  discount</option>
                                                                                        <option value="90">90%  discount</option>
                                    </select>
                                </div>
                            </div>
                            <div class="uk-width-medium-1-2">
                                <div class="parsley-row form-group">
                                    <strong><label>second class</label></strong>
                                    <select name="secondclassdiscountPercentage" id="secondclassdiscountPercentage" class="input-sm md-input"
                                               style='padding: 0px; font-weight: bold; color: #727272; width:50% '>
                                        <option value="0" >Select discount percentage</option>
											<option value="10">10%  discount</option>
											<option value="20">20%  discount</option>
											<option value="30">30%  discount</option>
											<option value="40">40%  discount</option>
											<option value="50">50%  discount</option>
                                                                                        <option value="60">60%  discount</option>
                                                                                        <option value="70">70%  discount</option>
                                                                                        <option value="80">80%  discount</option>
                                                                                        <option value="90">90%  discount</option>
                                    </select></div>
                            </div>
                        </div>
                        
                        <?php } ?>
                        
                        
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-1">
                                <br>
                                    <button type="submit" id='addseason' class='md-btn md-btn-primary disabled'  style="float:right;">Add season</button>
                                
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
        </div>
@stop