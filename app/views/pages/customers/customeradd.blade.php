@extends('layout.master')

@section('libraryCSS')
	<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->
	<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css"/>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' />
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

	$("#customersTable").DataTable();

	$("#introVisitDateDiv").hide();
	$("#state").change(function (){
		 getCities($("#state").val(), 'city');
	});

	$("#customerEmail").blur(function (){
		isCustomerExists();
	});


	function isCustomerExists(){
		
		var ajaxUrl = "{{url()}}/quick/"+"customerexistence";
		
		var isExists = "false";
		$.ajax({
			  type: "POST",
			  url: ajaxUrl,
			  dataType: 'json',
			  async: true,
			  data:{'email':$("#customerEmail").val()},
			  success: function(response, textStatus, jqXHR)
			  {
				    if (response.status == "exists"){	
					    isExists = true;			    	
				    	$("#callbackMessage").html('<div class="uk-alert uk-alert-danger" data-uk-alert><a href="#" class="uk-alert-close uk-close"></a>Sorry, This Email address already exists.</div>');
				    }else{
					    isExists = false;
				    	$("#callbackMessage").html("");
				    }			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  { }
		});

		//console.log(isExists);
		return isExists;
	}


	function onDateChangeFunction(){
		//alert($("#introVisitDate").val());
		$("#availabilityCheckDiv").show();
		$("#introVisitModal").modal("show");


		var ajaxUrl = "{{url()}}/quick/"+"checkslots";
		console.log(ajaxUrl);

		$.ajax({
			  type: "POST",
			  url: ajaxUrl,
			  dataType: 'json',
			  async: true,
			  data:{'datetime':$("#introVisitDate").val()},
			  success: function(response, textStatus, jqXHR)
			  {

				    if (response.status == "success"){
				    	$("#availabilityCheckDiv").hide();
				    	$("#submitMsgDiv").html("");
				    	$("#messageDiv").html('<p class="uk-alert uk-alert-success">Great! The selected time slot is available</p>');
				    	$("#customerSubmit").show();
				    	
				    }else{
				    	$("#availabilityCheckDiv").hide();
				    	$("#submitMsgDiv").html('<p class="uk-alert uk-alert-danger">Sorry! The selected time slot is not available</p>');
				    	$("#messageDiv").html('<p class="uk-alert uk-alert-danger">Sorry! The selected time slot is not available</p>');
				    	$("#customerSubmit").hide();
				    }
			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  {
		 
			  }
		});
	}

	$("#introVisitDate").kendoDateTimePicker({
		change:onDateChangeFunction
	});

	$("#reminderTxtBox").kendoDatePicker();

	$("#introVisit").change(function (){

		//alert("changed");

		if ($(this).is(':checked')) {

			$("#introVisitDateDiv").show();
			$("#introVisitDate").attr("required", true);
			
		}else{
			$("#introVisitDateDiv").hide();
			$("#introVisitDate").attr("required", false);

		}

	});

	function getCities(regionCode, targetSelectorId){

		var ajaxUrl = "{{url()}}/quick/"+"getCities";
		console.log(ajaxUrl);

		$.ajax({
			  type: "POST",
			  url: ajaxUrl,
			  dataType: 'json',
			  async: true,
			  data:{'id':regionCode, 'countryCode':"IN"},
			  success: function(response, textStatus, jqXHR)
			  {
				    
				   
				    //$("#"+targetSelectorId).append('<option value="" selected>Select City</option>');

				   console.log(response);
				   $('#'+targetSelectorId).empty();
				   $('#'+targetSelectorId).append('<option value=""></option');
				   $.each(response, function (index, item) {
				         $('#'+targetSelectorId).append(
				              $('<option></option>').val(index).html(item)
				          );
				     });
			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  {
		 
			  }
		});
	}


	
$("#customerSubmits").click(function (event){
	
	//event.preventDefault();
	var valid = false;
	console.log(isCustomerExists());
	if(isCustomerExists() == false){

		if($("#source").val == 'events'){

			if($( "#events" ).val() != ""){
				valid = true;
			}else{
				vald = false;
			}

		}


		if(valid == true){
			$("#addCustomerForm").submit();
		}else{
			event.preventDefault();
		}
	}
	
});


$('#addCustomerForm').validator()

$('#addCustomerForm').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    // handle the invalid form...
  } else {
    // everything looks good!
  }
})



$( "#eventsdiv" ).hide();
$("#source").change(function (){

	if($(this).val() == 'events'){
		$( "#eventsdiv" ).show();
		$("#events").prop('required','true');
	}else{
		$( "#eventsdiv" ).hide();
		$("#events").prop('required','false');
	}
	
})
	
$( "#events" ).autocomplete({
	        source: "{{url()}}/quick/getEvents",
	        minLength: 2,
	        select: function( event, ui ) {

		        console.log(ui.item.value);

	        	$( "#events" ).val(ui.item.eventName);
	        	$( "#events" ).val(ui.item.eventId);
	        	$( "#eventsId" ).val(ui.item.id);
		        
	          console.log( ui.item ?
	            "Selected: " + ui.item.value + " aka " + ui.item.id :
	            "Nothing selected, input was " + this.value );
	        }
	      });



function isNumberKey(evt)
{  
	var charCode = (evt.which) ? evt.which : event.keyCode
	if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	    return false;
	} 
	return true;
}

var membershipTypes = {{json_encode($membershipTypes)}}
$("#paymentType").hide();
$("#membershipPriceOuterDiv").hide();
$("#membershipType").change(function (){

	if($("#membershipType").val() != ""){

		$.each( membershipTypes, function( index, value ){
			console.log($("#membershipType").val());
			if(value.id == $("#membershipType").val()){
						
				$("#membershipPrice").val(value.fee_amount);
				$("#membershipPriceOuterDiv").show();
				$("#membershipPriceOuterDiv .md-input-wrapper").addClass('md-input-filled');
				$("input[type='radio'][name='paymentTypeRadio']").attr('required',true);
				$("#paymentType").show();
			}
		});
		
	}else{
		
		$("#paymentType").hide();
		$("#membershipPriceOuterDiv").hide();
		$("input[type='radio'][name='paymentTypeRadio']").attr('required',false);
	}
	
});

$("#cardDetailsDiv").hide();
$("#chequeDetailsDiv").hide();
$("input[name='paymentTypeRadio']").change(function (){

	var selectedPaymentType = $("input[type='radio'][name='paymentTypeRadio']:checked").val();
	if(selectedPaymentType == "card"){
		$("#chequeDetailsDiv").hide();
		$("#cardDetailsDiv").show();

	}else if(selectedPaymentType == "cheque"){
		$("#chequeDetailsDiv").show();
		$("#cardDetailsDiv").hide();
	}
	else if(selectedPaymentType == "cash"){
		$("#chequeDetailsDiv").hide();
		$("#cardDetailsDiv").hide();
	}
	
});
	
$(document).ready(function(){
   $('.followup').hide();
})
</script>

@stop

@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="{{url()}}/customers/memberslist" style="z-index:8;">Customers</a></li>
		<li><a href="#" style="z-index:7;">New Customers</a></li>
	</ul>
</div>
<br clear="all"/>
<div class="">
	<div class="row">
	
		
		
		
		<h4>New Customers</h4>
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
					
					<div id="callbackMessage"></div>
					<br clear="all"/>
                    
                       {{ Form::open(array('files'=> true,'url' => '/customers/add', 'id'=>"addCustomerForm", "class"=>"uk-form-stacked", 'method' => 'post')) }} 
                        
                                    <div class="uk-grid" data-uk-grid-margin>
                                            <div class="uk-width-medium-1-2">
				                 <div class="parsley-row form-group">
				                 	<label for="customerName">CustomerFirstName<span class="req">*</span></label>
				                 	{{Form::text('customerName', null,array('id'=>'customerName', 'required'=>'', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
                                            <div class="uk-width-medium-1-2">
				                 <div class="parsley-row form-group">
				                 	<label for="customerLastName">Customer Last Name<span class="req">*</span></label>
				                 	{{Form::text('customerLastName', null,array('id'=>'customerLastName', 'required'=>'', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
                                    </div>
                                    <div class="uk-grid" data-uk-grid-margin>
			                    <div class="uk-width-medium-1-2"> 
				                  <div class="parsley-row form-group">
				                 	<label for="customerEmail">Customer Email<span class="req">*</span></label>
				                 	{{Form::email('customerEmail', null,array('id'=>'customerEmail', 'required'=>'', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>    
				            <div class="uk-width-medium-1-2">    
				                  <div class="parsley-row form-group">
				                 	<label for="customerMobile">Customer Mobile<span class="req">*</span></label>
				                 	
				                 	{{Form::text('customerMobile', null,array('id'=>'customerMobile', 'required'=>'', "onkeypress"=>"return isNumberKey(event);", 'maxlength'=>'10',  'minlength'=>'10', 'pattern'=>'\d*',   'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
				                 </div>
				            </div>  
				            <br clear="all"/><br clear="all"/><br clear="all"/>
				   </div>    
				        
				        
				        <br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row form-group">
				                 	<label for="building">Building/Block</label>
				                 	{{Form::text('building', null,array('id'=>'building',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3"> 
				                  <div class="parsley-row form-group">
				                 	<label for="apartment">Apartment Name/Number</label>
				                 	{{Form::text('apartment', null,array('id'=>'apartment',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>    
				            <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row form-group">
				                 	<label for="lane">Lane</label>
				                 	{{Form::text('lane', null,array('id'=>'lane',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div> 
				            <br clear="all"/><br clear="all"/><br clear="all"/>
				       </div>     
				            
				       
				       <div class="uk-grid" data-uk-grid-margin>
				             <br clear="all"/>
				             <div class="uk-width-medium-1-3">
				                 <div class="parsley-row form-group">
				                 	<label for="locality">Locality</label>
				                 	{{Form::text('locality', null,array('id'=>'locality', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				             </div>
				             <div class="uk-width-medium-1-3">
				                 <div class="parsley-row form-group">
				                 	<label for="state">State</label>
				                 	{{ Form::select('state', array('' => '') + $provinces, null ,array('id'=>'state', 'class' => 'input-sm md-input', "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row form-group">
				                 	<label for="state">City</label>
				                 	{{ Form::select('city', array('' => ''), null ,array('id'=>'city', 'class' => 'input-sm md-input', "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				            <br clear="all"/><br clear="all"/><br clear="all"/>
				        </div>
				        
				        
				        <div class="uk-grid" data-uk-grid-margin>
				        	<br clear="all"/>
				            <div class="uk-width-medium-1-3"> 
				                  <div class="parsley-row form-group">
				                 	<label for="zipcode">Zipcode</label>
				                 	{{Form::text('zipcode', null,array('id'=>'zipcode', "onkeypress"=>"return isNumberKey(event);", 'maxlength'=>'6', 'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
				                 </div>
				            </div>    
				             
				            <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row form-group">
				                 	<label for="referredBy">Referred by<span class="req"></span></label>
				                 	{{Form::text('referredBy', null,array('id'=>'referredBy',  'class' => 'form-control input-sm md-input'))}}				                 	
				                 </div>
			                </div>
			                <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row form-group">
				                 	<label for="source">Source</label>
				                 	
				                 	{{ Form::select('source', array('' => '', 'word of mouth' => 'Word of Mouth', 'grass roots' => 'Grassroots', 'walkin' => 'Walkin', 'events' => 'Events','social media'=>'Social media','bussiness dev - schools apart'=>'Bussiness dev - schools apart','internal marketing'=>'Internal marketing','external gross root events'=>'External gross root events','business partnerships'=>'Business partnerships','internal events'=>'Internal events','PR'=>'PR','sales and telemarketing'=>'Sales and telemarketing','mass marketing'=>'Mass marketing','service calls'=>'Service calls'), null ,array('id'=>'source', 'class' => 'input-sm md-input',"placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div> 
				            <br clear="all"/><br clear="all"/>
			           </div>
			           <div class="uk-grid" data-uk-grid-margin id="eventsdiv">
				        	<br clear="all"/>
			                <div class="uk-width-medium-1-3">    
				                   <div class="parsley-row form-group">
				                 	<label for="events">Type and select Events<span class="req">*</span></label>				                 	
				                 	{{Form::text('events', null,array('id'=>'events',  'class' => 'form-control input-sm md-input'))}}
				                 	<input type="hidden" name="eventsId" id="eventsId">
				                 </div>
			                </div>
			           </div>
			       	  <!--<div class="uk-grid" data-uk-grid-margin>
				        	<br clear="all"/>
			                <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	  <input type="checkbox"  name="introVisit" id="introVisit"  /> 
				                 	 <label for="introVisit" class="inline-label">Need Intro Visit?</label>
				                 </div>
			                </div>
			                <div class="uk-width-medium-1-3" id="introVisitDateDiv"> 
				                  <div class="parsley-row">
				                 	<label for="introVisitDate">Intro visit Date and time<span class="req">*</span></label><br>
				                 	{{Form::text('introVisitDate', null,array('id'=>'introVisitDate', 'class' => 'uk-form-width-medium'))}}
				                 	
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="state">Willing to continue Payment?<span class="req">*</span></label>
				                 	{{ Form::select('willingPayment', array('' => '', 'yes' => 'Yes', 'no' => 'No'), null ,array('id'=>'willingPayment', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				            <br clear="all"/>
			            </div> -->
                                  
                                  
                                  
			            <div id="submitMsgDiv"></div>
			            
						<div class="uk-grid" data-uk-grid-margin>
                        	<div id="commentMsgDiv">
                        	</div>
					   		<div class="uk-width-medium-1-1 followup" >							             		 
							    <div class="parsley-row form-group">
								    <label for="customerCommentTxtarea">Comment<span class="req"></span></label> 
								    {{ Form::textarea('customerCommentTxtarea', null, ['id'=>'customerCommentTxtarea', 'size' => '50x3',  'class' => 'form-control input-sm md-input']) }}
							    </div><br>
						    </div>     
						    <div class="uk-width-medium-1-3 followup">
						     	<div class="parsley-row form-group">
						    		<label for="reminderTxtBox">Reminder date<span class="req">*</span></label> 
						    		{{Form::text('reminderTxtBox', null,array('id'=>'reminderTxtBox', 'class' => ''))}}								                 	
					    		</div>
						    </div>
						    <div class="uk-width-medium-1-3 followup">
				                 <div class="parsley-row form-group">
				                 	<label for="commentType">Comment Type<span class="req">*</span></label>
				                 	<!-- array('' => '', 'followup' => 'Followup', 'attended_iv' => 'Attended IV', 'iv_no_show' => 'IV No show', 'missed_call' => 'Missed Call') -->
				                 	{{ Form::select('commentType', array('FOLLOW_up' => 'Follow Up'), null ,array('id'=>'commentType', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				         </div>	 
				         
		      		         
				         <br clear="all"/><br clear="all"/>
				         <!--
                                         <h4>Membership details</h4>
				         <br clear="all"/>
				         <div class="uk-grid" data-uk-grid-margin>
					   		<div class="uk-width-medium-1-2">							             		 
							    <div class="parsley-row form-group">
								    <label for="membershipType">Membership Type<span class="req"></span></label> 
								    
								    <select id="membershipType" name="membershipType" class="input-sm md-input" style="padding:0px; font-weight:bold;color: #727272;">
								    <option value=""></option>
								    @foreach ($membershipTypes as $membershipType)
									    <option value="{{$membershipType->id}}"> {{$membershipType->name}}</option>
									@endforeach
								    
								    </select>
							    </div><br>
						    </div>  
						    <div class="uk-width-medium-1-2">							             		 
							    <div class="parsley-row form-group" id="membershipPriceOuterDiv">
								    <label for="membershipPrice">Membership Price<span class="req"></span></label> 
								    {{Form::text('membershipPrice', null,array('id'=>'membershipPrice', 'readonly','class' => 'input-sm md-input'))}}			
							    </div><br>
						    </div>     
						 </div>  
						 
						 <div id="paymentType" class="uk-grid" data-uk-grid-margin>
							<div class="uk-width-medium-1-3">
								<div class="parsley-row form-group">
									<input type="radio" name="paymentTypeRadio"
										id="paymentOptions_1" value="card" /> <label
										for="paymentOptions_1" class="inline-label">Card</label> <input
										type="radio" name="paymentTypeRadio" id="paymentOptions_2"
										value="cash" /> <label for="paymentOptions_2"
										class="inline-label">Cash</label> <input type="radio"
										name="paymentTypeRadio" id="paymentOptions_3" value="cheque" />
									<label for="paymentOptions_3" class="inline-label">Cheque</label>

								</div>
							</div>
						</div>
						<div id="paymentType" style="width: 100%"><br clear="all"/>
								<div id="cardDetailsDiv" class="uk-grid" data-uk-grid-margin>
									<div class="uk-width-medium-1-1">
										<h4>Card details</h4><br clear="all"/>
									</div>
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row form-group">
											<select name="cardType" id="cardType"
												class="input-sm md-input"
												class="form-control input-sm md-input"
												style='padding: 0px; font-weight: bold; color: #727272;'>
												<option value="master">Master card</option>
												<option value="maestro">Maestro</option>
												<option value="visa">Visa</option>
												<option value="visa">Rupay</option>
											</select>
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="card4digits" class="inline-label">Last 4 digits
												of your card<span class="req">*</span>
											</label> <input id="card4digits" number name="card4digits"
												maxlength="4" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>

								</div>
								<div id="chequeDetailsDiv" class="uk-grid" data-uk-grid-margin>

									<div class="uk-width-medium-1-1">
										<h4>Cheque details</h4><br clear="all"/>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row form-group">
											<label for="chequeBankName" class="inline-label">Bank name<span
												class="req">*</span></label> <input id="chequeBankName"
												name="bankName" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeNumber form-group" class="inline-label">Cheque number<span
												class="req">*</span></label> <input id="chequeNumber"
												name="chequeNumber" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
								</div>

							</div>
						 
				        --> 
				         
				         <br clear="all"/><br clear="all"/>
				         <div class="uk-grid" data-uk-grid-margin>
					         <div class="uk-width-medium-1-1">							             		 
								    <div class="parsley-row form-group">
				         				<span class="md-list-heading">{{Form::file('profileImage')}}</span>                                                       
                                        <span class="uk-text-small uk-text-muted">Select Profile Picture</span>
                                    </div>
                             </div>
                         </div>         
			            
                        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" id="customerSubmit" class="md-btn md-btn-primary">Add New Customer</button>
                            </div>
                        </div>
                    {{ Form::close() }}	
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