@extends('layout.master')

@section('libraryCSS')

@stop

@section('libraryJS')
<script type="text/javascript">
	function saveChanges(){
		var classCheck = ''
		var childCheck = ''
		if ($("#class").is(":checked")) {
    		classCheck = '1';
		}
		else{
			classCheck = '0';	
		}
		if ($("#child").is(":checked")) {
    		childCheck = '1';
		}
		else{
			childCheck = '0';	
		}

		$.ajax({
        		type: "POST",
        		url: "{{URL::to('/quick/approvingDiscounts')}}",
        		data: {'classCheck': classCheck, 'childCheck': childCheck},
        		dataType:"json",
        		success: function (response)
        		{
        			if (response.status == "success") {
        				$('#msgDiv').html('<h5 class = "uk-alert-success" style = "color: #fff; width: 90%; padding: 10px; text-align: center"> Changes are saved Successfully. Please wait untill this page reload</h5>');			
        				setTimeout(function(){
 							window.location.reload(1);
           				}, 3500);
        			}
        			else{
        				console.log(response);
        			}
        		}
        });
	}	
</script>
@stop

@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="#" style="z-index:8;">Discounts</a></li>
		<li><a href="#" style="z-index:7;">Enable/Desable Discounts</a></li>
	</ul>
</div>
<br clear="all"/>

<div class="uk-width-medium-1-1">
	<div class="parsley-row form-group">
    	<div id = "msgDiv"></div>
    </div>
</div>

<div class="md-card">
	<div class="md-card-content large-padding">
		<h3 class="heading_b uk-margin-bottom">Enable or Disable Discounts</h3>
		<div class="md-card uk-margin-medium-bottom" >
			<div class="md-card-content">
				<div class="uk-grid" data-uk-grid-margin>

					<?php  
						$checkChild = '';
						$checkClass = '';
						if($discount_data[0]['discount_second_child_approve'] == 1){
							$checkChild = 'checked';

						}
						else{
							$checkChild = '';	
						}
						if($discount_data[0]['discount_second_class_approve'] == 1){
							$checkClass = 'checked';
						}
						else{
							$checkClass = '';	
						}

					?>
					<div class="uk-width-medium-1-4 text-center">
						<input type = "checkbox" value = "discount_second_class_approve" id="class"  style = "zoom: 1.8" {{$checkClass}}>
                    </div>
                    <div class="uk-width-medium-1-4 text-center">
                    	<label style = "padding-top: 7px;">Second Class Discount</label>
                    </div>
                    <div class="uk-width-medium-1-4 text-center">
                    </div>
                    <div class="uk-width-medium-1-4 text-center">
                    </div>

                    <div class="uk-width-medium-1-4 text-center">
						<input type = "checkbox" value = "discount_second_child_approve" id="child" style = "zoom: 1.8" {{$checkChild}}>
                    </div>
                    <div class="uk-width-medium-1-4 text-center">
                    	<label style = "padding-top: 7px;">Second Child Discount</label>
                    </div>
                    <div class="uk-width-medium-1-4 text-center">
                    </div>
                    <div class="uk-width-medium-1-4 text-center">
                    </div>

                    <div class="uk-width-medium-1-4 text-center"></div>
                    <div class="uk-width-medium-1-4 text-right">
                    	<button type="button" id="savesaveChanges" onclick = "saveChanges()" class="md-btn md-btn-primary">Save Changes</button>
                    </div>
                    <div class="uk-width-medium-1-4 text-center"></div>
                    <div class="uk-width-medium-1-4 text-center"></div>
                </div>
		    </div>
		</div>
	</div>
</div>

@stop