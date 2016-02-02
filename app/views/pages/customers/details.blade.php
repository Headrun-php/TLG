@extends('layout.master')

@section('libraryCSS')
<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->

<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css"/>
<link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' />
 <link href="{{url()}}/assets/tags/css/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="{{url()}}/assets/tags/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
<!--<link rel="stylesheet" href="{{url()}}/assets/tagseditor/jquery.tag-editor.css"> -->
<style>
	ul.tagit{
	    height: 30px;
    	font-size: 10px;
    	background: inherit;
    	border:0px;
   		border-bottom: 1px #C6C6C6 solid;
    	margin-top: -0px;
    }

</style>
@stop

@section('libraryJS')

	

<!--

<script src='https://code.jquery.com/jquery-1.11.3.js'></script>
 <script src='http://momentjs.com/downloads/moment.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.js'></script> 
-->
<script src="{{url()}}/assets/js/pages/validator.js"></script>
<script src="{{url()}}/assets/js/kendoui_custom.min.js"></script>
<script src="{{url()}}/assets/js/pages/kendoui.min.js"></script>

<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>


<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
<script src="{{url()}}/assets/tags/js/tag-it.js" type="text/javascript" charset="utf-8"></script>
 
<!-- <script src="{{url()}}/assets/tagseditor/jquery.caret.min.js"></script>
<script src="{{url()}}/assets/tagseditor/jquery.tag-editor.js"></script>
 -->
<script type="text/javascript">



var customerName = "{{$customer->customer_name}}";
var customerId   = "{{$customer->id}}";


//Initialize
//var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];
$('#hobbies').tagit({
	//availableTags: sampleTags        
});

$("#studentDob").kendoDatePicker();
$("#studentDob").find('span').find('input').attr("readonly", "readonly");
$("#reminderTxtBox").kendoDatePicker();

var isEditBtnClicked = false;
$("#editCustomerBtn").click(function (){
	isEditBtnClicked = true;
	getCities($("#state").val(), 'city');
	$("#editCustomerModal").modal('show');
})

$("#state").change(function (){
	 getCities($("#state").val(), 'city');
});

function getCities(regionCode, targetSelectorId){

		$.ajax({
			  type: "POST",
			  url: ajaxUrl+"getCities",
			  dataType: 'json',
			  async: true,
			  data:{'id':regionCode, 'countryCode':"IN"},
			  success: function(response, textStatus, jqXHR)
			  {
				   $('#'+targetSelectorId).empty();
				   $('#'+targetSelectorId).append('<option value=""></option');
				   $.each(response, function (index, item) {
				         $('#'+targetSelectorId).append(
				              $('<option></option>').val(index).html(item)
				          );
				     });

				   if(isEditBtnClicked){
				   	$("#city").val("{{$customer->city}}");
				   	console.log('city selected');
				   }
			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  {
		 
			  }
		});
}



<?php 
 	if (Session::has('continueEnroll')){	                      		 
    	$continueEnroll = Session::get('continueEnroll');
    	if($continueEnroll == "YES"){
?>

		$('#customerInfoTab').removeClass("uk-active");
		$('#customerInfoTabMenu').removeClass("uk-active");

		
		$('#studentInfoTab').addClass("uk-active");
		$('#studentInfoTabMenu').addClass("uk-active");

		$("#addKids").trigger( "click" );



<?php 
    	}
 	}
	
?>





$("#addKids").click(function(){ 
	$("#addKidsModal").modal('show');
	$("#formBody").show();
	$('#studentName').val("");
	$('#studentGender').val("");
	$('#studentDob').val("");
	$('#nickname').val("");
	$('#school').val(""),
	$('#location').val("");
	$('#hobbies').val("");
	$('#emergencyContact').val("");
	$('#remarks').val("");			      		
	$('#healthIssue').val("");
});

$("#kidsAddForm").submit(function (event){
	addKids(event);
});

function addKids(event){
	event.preventDefault();
	var postData = {'studentName'     : $('#studentName').val(),
			  		'studentGender'   : $('#studentGender').val(),
			  		'studentDob'      : $('#studentDob').val(),
			  		'nickname'        : $('#nickname').val(),
			  		'school'          : $('#school').val(),
			  		'location'        : $('#location').val(),
			  		'hobbies'         : $('#hobbies').val(),
			  		'emergencyContact': $('#emergencyContact').val(),
			  		'remarks'         : $('#remarks').val(),			      		
			  		'healthIssue'     : $('#healthIssue').val()	 ,
			  		'customerId'      : customerId     		
					};
	$.ajax({
        type: "POST",
        url: ajaxUrl+"addstudent",
        data: postData,
        dataType:"json",
        success: function (response)
        {
			if(response.status == "success"){
				$("#messageStudentAddDiv").html('<p class="uk-alert uk-alert-success">Kid details has been added successfully. Please wait till this page reloads</p>');
				$("#formBody").hide();

				setTimeout(function(){
				   window.location.reload(1);
				}, 5000);
				
			}else{

				$("#messageStudentAddDiv").html('<p class="uk-alert uk-alert-danger">Sorry! Kid details could not be added.</p>');
				$("#formBody").hide();

			}
      	 
        }
    }); 	  
}

$("#saveCommentBtn").click(function (){
	if($("#customerCommentTxtarea").val() != ""){
		var postData = {"customerId":customerId,
						"commentText":$("#customerCommentTxtarea").val(),
						"commentType":$("#commentType").val(),
						"reminderDate":$("#reminderTxtBox").val()					
						};
		$.ajax({
	        type: "POST",
	        url: ajaxUrl+"savecomment",
	        data: postData,
	        dataType:"json",
	        success: function (response)
	        {
				if(response.status == "success"){
					$("#commentMsgDiv").html('<p class="uk-alert uk-alert-success">Comments has been added successfully. Please wait till this page reloads</p>');
					setTimeout(function(){
					   window.location.reload(1);
					}, 3000);
					
				}else{
					$("#commentMsgDiv").html('<p class="uk-alert uk-alert-danger">Sorry! Comments could not be added.</p>');
				}
	        }
	    }); 
		
	}else{

		$("#commentMsgDiv").html('<p class="uk-alert uk-alert-danger">Please fill up the comments field.</p>');
	}
})



	
	$('#editCustomerModal').on('hidden.bs.modal', function () {
		  isEditBtnClicked = false;
	})



$('#editCustomerForm').validator().on('submit', function (e) {
	  if (e.isDefaultPrevented()) {
	    // handle the invalid form...
	  } else {
	    // everything looks good!
	    	  e.preventDefault();
	    	 // alert("form is valid")
			  $.ajax({
				  type: "POST",
				  url: ajaxUrl+"editCustomer",
				  dataType: 'json',
				  async: true,
				  data:$('#editCustomerForm').serialize(),
				  success: function(response, textStatus, jqXHR)
				  {
                                     
				  },
				  error: function (jqXHR, textStatus, errorThrown)
				  {
			 
				  }
			});
	  }
	})
	
	


	
	
	
	

$("#saveCustomerBtn").click(function (){
	
	
	
})



//Birthday party
$("#birthdayMonth").kendoDatePicker({
    depth:"year",
    start: "year",
    format: "MMMM yyyy",
    min: new Date(),
    change:function(){

		console.log($("#birthdayMonth").val());
		getAvailableWeekends();
		
   }
});

$("#weekendData").hide();
function getAvailableWeekends(){
	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/getWeekendsForBday')}}",
        data: {'dateSelected': $("#birthdayMonth").val()},
        dataType:"json",
        success: function (response)
        {
        	//console.log(response);   
			var saturdayString ="";
			var sundayString ="";

			$("#saturdaysDiv").empty();
			$("#sundaysDiv").empty();

			var i = 0;
        	 $.each(response.saturdays, function (index, item) {
         		  console.log(index+" = "+item);
         		  saturdayString += '<input type="radio" name="birthdayCelebrationDate" value="'+item+'" class="radio-custom" id="radio_demo_inline_'+i+'"  /><label for="radio_demo_inline_'+i+'" class="radio-custom-label">'+item+'</label></span><br>';
         		  i++;               
             });
             $("#saturdaysDiv").append(saturdayString);


             var j = 0;
        	 $.each(response.sundays, function (index, item) {
         		  console.log(index+" = "+item);
         		  sundayString += '<input type="radio" name="birthdayCelebrationDate" value="'+item+'" class="radio-custom" id="radio_demo_inline_j'+j+'"  /><label for="radio_demo_inline_j'+j+'" class="radio-custom-label">'+item+'</label></span><br>';
         		  j++;               
             });
             $("#sundaysDiv").append(sundayString);

             $("#weekendData").show();
        }
    }); 
}

$("#birthdayPriceTable").hide();
function startChange() {
	/* var startTime = start.value();
	if (startTime) {
		startTime = new Date(startTime);
		startTime.setMinutes(startTime.getMinutes() + this.options.interval);
		end.value(startTime);
	} */

	$("#birthdayPriceTable").show();
}


//init start timepicker
var start = $("#birthdayTime").kendoTimePicker({
	change: startChange,
	interval:60
}).data("kendoTimePicker");

//init end timepicker
var end = $("#endTime").kendoTimePicker().data("kendoTimePicker");
//define min/max range
start.min("9:00 AM");
start.max("5:00 PM");



function calculateBirthdayPartyPrice(){


	if($('#applyMembership').is(":checked")) {

	$("#defaultBirthdayPrice").val('12000');
	if($("#membershipType").val() == "1"){
		
		$("#defaultBirthdayPrice").val('10000')
		
	}else if($("#membershipType").val() == "2"){
		
		$("#defaultBirthdayPrice").val('10000');
		
	}

	}else{
		$("#defaultBirthdayPrice").val('12000');

	}

	var additionalGuestPrice    = parseInt($("#additionalGuestPrice").val());
	var additionalHalfHourPrice = parseInt($("#additionalHalfHourPrice").val());
	var membershipPrice         = parseInt( $("#membershipPriceBday").val());
	var defaultBirthdayPrice    = parseInt($("#defaultBirthdayPrice").val());
		
	var grandTotal = (additionalGuestPrice + additionalHalfHourPrice + membershipPrice + defaultBirthdayPrice);

	
	
	$("#grandTotal").val(grandTotal);

	var advance = $('#advanceAmount').val();
	var remainingAmount = (grandTotal-advance);

	$("#remainingAmount").val(remainingAmount);

	$("#taxAmount").empty();
	$("#totalAmountPayable").empty();
	var tax = Math.floor(((14.5/100)*parseInt(advance)))
	
	$("#totalAmountPayable").val((parseInt(tax)+parseInt(advance)))
	$("#taxAmount").val(tax)
	
}

calculateBirthdayPartyPrice();


$('#advanceAmount').keyup(function  (){
	calculateBirthdayPartyPrice();
})
$('#additionalGuestCount').keyup(function (){
	
	$("#additionalGuestPrice").val((parseInt($(this).val())*300));
	calculateBirthdayPartyPrice();

});

$('#additionalHalfHourCount').keyup(function (){
	
	$("#additionalHalfHourPrice").val((parseInt($(this).val())*3000));
	calculateBirthdayPartyPrice();

});




$(document).ready(function (){


	$("#membershipType").on('change',function (){

		if($(this).val() == "1"){
			$("#membershipPriceBday").val("2000");
		}else if($(this).val() == "2"){
			$("#membershipPriceBday").val("5000");
		}else{
			$("#membershipPriceBday").val("");
		}
		calculateBirthdayPartyPrice();
	});
		
})


$("#membershipTableRow").hide();
$("#applyMembership").change(function (){

	if($(this).is(":checked")) {
		$("#membershipTableRow").show();
		$("#membershipType").prop('required','true');
		$("#defaultBirthdayPrice").val('10000');
	}else{

		$("#defaultBirthdayPrice").val('12000');
		$("#membershipTableRow").hide();
		$("#membershipType").prop('required','false');
	}

	calculateBirthdayPartyPrice()
})



$('#addBirthdayPartyForm').validator().on('submit', function (e) {
	  if (e.isDefaultPrevented()) {
	    // handle the invalid form...
	  } else {
	    // everything looks good!
	    	  e.preventDefault();
	    	  //alert("form is valid")
			  $.ajax({
				  type: "POST",
				  url: ajaxUrl+"addbirthdayParty",
				  dataType: 'json',
				  async: true,
				  data:$('#addBirthdayPartyForm').serialize(),
				  success: function(response, textStatus, jqXHR)
				  {
					  if(response.status == "success"){
                                                    $("#addBirthdayPartyForm").empty();
                                                    if(response.printUrl!=""){
                                                     var printvars = '<a target="_blank" href="'+response.printUrl+'" class="btn btn-primary">Print</a>';
                                                     $("#addbirthdaymodalbody").html('<p class="uk-alert uk-alert-success">Birthday party has been added successfully.</p>'+printvars);
                                                    $('#addbirthdaymodal').modal('show'); 
                                                    }else{
                                                        
                                                         $("#addbirthdaymodalbody").html('<p class="uk-alert uk-alert-success">Birthday party has been added successfully.</p>');
                                                         $('#addbirthdaymodal').modal('show'); 
                                                    }
                                                   
					  }else{
                                                  $("#addBirthdayPartyForm").empty();
						  $("#addbirthdaybody").html('<p class="uk-alert uk-alert-danger">Birthday Party could not be added at this moment</p>');
					  }
                                          $('#addbirthdayclose').click(function(){
                                              window.location.reload(1);
                                          });
				  
				  },
				  error: function (jqXHR, textStatus, errorThrown)
				  {
			 
				  }
			});
	  }
	})
	
$("#kidsSelect").change(function (){

	
	 $.ajax({
		  type: "POST",
		  url: ajaxUrl+"checkExistingBirthdayParty",
		  dataType: 'json',
		  async: true,
		  data:{'kidsSelect':$('#kidsSelect').val()},
		  success: function(response, textStatus, jqXHR)
		  {
		  		if(response.status == 'exist'){
		  			$("#birthdayMsgDiv").html('<p class="uk-alert uk-alert-danger">Birthday Party already exists for the selected Kid. Please slect another kid or use the edit birthday party button below on the listing.</p>');
		  			$('#kidsSelect').val("");
		  		}
		  },
		  error: function (jqXHR, textStatus, errorThrown)
		  {
	 
		  }
	});
	
})

$("#profileImageUploadForm").validator();






var membershipTypes = {{json_encode($membershipTypesAll)}}
$("#paymentType").hide();
$("#membershipPriceOuterDiv").hide();
$("#membershipTypesMembersDiv").change(function (){

	

	$.each( membershipTypes, function( index, value ){
		//console.log($("#membershipType").val());
		if(value.id == $("#membershipTypesMembersDiv").val()){
					
			$("#membershipPrice").val(value.fee_amount);
			$("#membershipPriceOuterDiv").show();
			$("#membershipPriceOuterDiv .md-input-wrapper").addClass('md-input-filled');
			$("input[type='radio'][name='paymentTypeRadio']").attr('required',true);
			$("#paymentType").show();
		}
	});
	
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
	


function pendingamount(pendingamountId,pendingAmount){
   var taxamount=Math.floor(((14.5/100)*parseInt(pendingAmount)));   
       $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/createorder')}}",
                        data: {'pending_id':pendingamountId,'taxamount':taxamount},
			dataType: 'json',
			success: function(response){
                            console.log(response.status);
                            $('#birthdays').empty();
                            var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                                                     
                            $('#pendingamountbody').html(printvars);
                            $('#pendingamount').modal('show'); 
                            $('#pendingamountclose').click(function(){
                            window.location.reload(1);        
                            });
                        }
             })
     
}
</script>
@stop
@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="{{url()}}/customers" style="z-index:8;">Customers</a></li>
		<li><a href="#" style="z-index:7;">{{$customer->customer_name}}</a></li>
	</ul>
</div>
<br clear="all"/>
<?php 
	/* echo "<pre>";
	print_r($students);
	echo "</pre>"; */
	
?>

			
            <div class="uk-grid" data-uk-grid-margin data-uk-grid-match id="user_profile">
                <div class="uk-width-large-10-10">
                
                	<h4>Customer Details</h4>
                    <div class="md-card">
                        <div class="user_heading">
                            <div class="user_heading_menu" data-uk-dropdown>
                                <i class="md-icon material-icons md-icon-light">&#xE5D4;</i>
                                <div class="uk-dropdown uk-dropdown-flip uk-dropdown-small">
                                    <ul class="uk-nav">
                                        <li><a href="#">Action 1</a></li>
                                        <li><a href="#">Action 2</a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="user_heading_avatar">
                                <img src="{{url()}}/upload/profile/customer/{{$customer->profile_image}}"/>
                            </div>
                            <div class="user_heading_content">
                                <h2 class="heading_b uk-margin-bottom"><span class="uk-text-truncate">
                                	{{$customer->customer_name}} 
                                	<?php if($customerMembership){?>
                                		<span class="new badge" style="background-color: #7CB342">{{$customerMembership->name}} Membership</span> 
                                	
                                	<?php }else{?>
                                		<span class="new badge" style="background-color:#B10909">Not a member</span> 
                                	<?php }?>
                                	<span id="stageChange" class="new badge" style="background-color: #7CB342;">{{ucfirst($customer->stage)}}</span>
                                	
                                	
                                	
                                	</span>
                                	
                               	</h2>
                                <ul class="user_stats">
                                    <li>
                                        <h4 class="heading_a">{{$customer->customer_email}} <span class="sub-heading">Email</span></h4>
                                    </li>
                                    <li>
                                        <h4 class="heading_a">{{$customer->mobile_no}} <span class="sub-heading">Mobile</span></h4>
                                    </li>
                                  
                                </ul>
                            </div>
                            <a class="md-fab md-fab-small md-fab-accent" id="editCustomerBtn">
                                <i class="material-icons">&#xE150;</i>
                            </a>
                        </div>
                        <div class="user_content">
                            <ul id="user_profile_tabs" class="uk-tab" data-uk-tab="{connect:'#user_profile_tabs_content', animation:'slide-horizontal'}" data-uk-sticky="{ top: 48, media: 960 }">
                                <li id="customerInfoTabMenu" class="uk-active"><a href="#">About</a></li>
                                <li id="customerMembershipInfoTabMenu" class=""><a href="#">Membership</a></li>
                                <li id="studentInfoTabMenu" class=""><a href="#">Kids</a></li> 
                                <li id="birthdayPartyInfoTabMenu"><a>Birthday Parties</a></li>
                                <li id="commentsInfoTabMenu" class="" data-target="#commentsandlogsdivTab"><a href="#" data-target="#commentsandlogsdivTab">Comments and logs</a></li> 
                            </ul>
                            <ul id="user_profile_tabs_content" class="uk-switcher uk-margin">
                                <li id="customerInfoTab"> 
                                 
                                       <div class="uk-grid uk-margin-medium-top uk-margin-large-bottom" data-uk-grid-margin>
                                        <div class="uk-width-large-1-2">
                                            <h4 class="heading_c uk-margin-small-bottom">Contact Info</h4>
                                            <ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons">&#xE158;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{$customer->customer_email}}  </span>
                                                        <span class="uk-text-small uk-text-muted">Email</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons">&#xE0CD;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{$customer->mobile_no}}</span>
                                                        <span class="uk-text-small uk-text-muted">Phone</span>
                                                    </div>
                                                </li>
                                                
                                               
                                               @if($customer->building)
                                                <li>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{$customer->building}}</span>
                                                    </div>
                                                </li>
                                                @endif
                                                
                                                @if($customer->building)
                                                <li>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{$customer->apartment_name}}</span>
                                                    </div>
                                                </li>
                                                @endif
                                                @if($customer->building)
                                                <li>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{$customer->lane}}</span>
                                                    </div>
                                                </li>
                                                @endif
                                                
                                                <li>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{$customer->locality}}</span>
                                                        <span class="uk-text-small uk-text-muted">Locality</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{$customer->zipcode}}</span>
                                                        <span class="uk-text-small uk-text-muted">ZipCode</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">
                                                        
                                                        {{ucfirst($customer->source)}} - 
                                                        <?php if($customer->source == "events"){
                                                        		if(isset($customer->Events)){
                                                        	?>
                                                        		(<span style="font-style: italic;">{{ucfirst($customer->Events->name)}}</span>)
                                                        <?php 
                                                        		}
                                                        
                                                        }
                                                        ?>
                                                        </span>
                                                        <span class="uk-text-small uk-text-muted">Source</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            
                                            <h3>Profile Picture</h3>
                                            <ul>
                                            
                                            	 <li>
                                                 	
                                                 	 @if (Session::has('imageUploadMessage'))
													  <div class="uk-alert uk-alert-success" data-uk-alert>
								                      		 <a href="#" class="uk-alert-close uk-close"></a>
								                             {{ Session::get('imageUploadMessage') }}
								                      </div>
								                      <br clear="all"/>
													@endif
                                                    <div class="md-list-content form-group" >
                                                    	{{Form::open(array('files'=> true, 'url'=>'customers/profile/picture', "id"=>"profileImageUploadForm"))}}
                                                        <span class="md-list-heading">{{Form::file('profileImage',array('required', 'class'=>'form-control'))}}</span>                                                       
                                                        <span class="uk-text-small uk-text-muted">Change Profile Picture</span>
                                                        <input name="customerId" value="{{$customer->id}}" type="hidden"/>
                                                        <input type="submit"  class="btn btn-sm btn-primary" value="Upload Profile Picture"/>
                                                        {{Form::close()}}
                                                    </div>
                                                    <br clear="all"/>
                                                </li>
                                            
                                            </ul>
                                        </div>
                                        
                                </li>
                                <li id="customerMembershipInfoTab">
                                
                                <?php 
                                	/* echo '<pre>';
                               		print_r($customerMembership);
                                	echo '</pre>'; */
                                	
                                	if($customerMembership){
                                
                                ?>
                                		<ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons">verified_user</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{$customerMembership->name}}  </span>
                                                        <span class="uk-text-small uk-text-muted">Current membership</span>
                                                    </div>
                                                </li>
                                         </ul>
                                                
                                
                                
                                <?php 
                                	}else{
                                
                                ?>
                                
                                
                                <p class="uk-alert uk-alert-warning">No membership found.</p>
                                <?php $url = '/customers/view/'.$customer->id;?>
                                {{ Form::open(array('files'=> true,'url' => $url, 'id'=>"addCustomerForm", "class"=>"uk-form-stacked", 'method' => 'post')) }}
                                <br clear="all"/><br clear="all"/>
							         <h4>Membership details</h4>
							         <br clear="all"/>
							         <div class="uk-grid" data-uk-grid-margin>
								   		<div class="uk-width-medium-1-2">							             		 
										    <div class="parsley-row form-group">
											    <label for="membershipType">Membership Type<span class="req"></span></label> 
											    
											    <select id="membershipTypesMembersDiv" name="membershipTypesMembersDiv" class="input-sm md-input" style="padding:0px; font-weight:bold;color: #727272;">
											    <option value=""></option>
											    @foreach ($membershipTypesAll as $membershipType)
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
											<div class="uk-width-medium-1-2">
												<div class="parsley-row">
													<button class="btn btn-primary">Add Membership</button>
												</div>
											</div>
										</div>
									{{Form::close()}}
                                <?php 
                                	}
                                ?>
                                
                                
                                </li>
                                <li id="studentInfoTab">
                                
                                	<br clear="all"/>
                                	<?php 
                                	
                                	
                                	if($students->count()){?>
                                	<div class="uk-grid-width-small-1-2 uk-grid-width-medium-1-3 uk-grid-width-large-1-4 uk-grid-width-xlarge-1-5 hierarchical_show" id="contact_list">
                                	
                                		<?php 
                                		
                                		foreach($students as $student){?>
		                               		<div data-uk-filter="" style="float:left; margin-left:20px;">
							                    <div class="md-card md-card-hover">
							                        <div class="md-card-head">
							                            <div class="md-card-head-menu" data-uk-dropdown>
							                                <i class="md-icon material-icons">&#xE5D4;</i>
							                                <div class="uk-dropdown uk-dropdown-small uk-dropdown-flip">
							                                    <ul class="uk-nav">
							                                        <li><a href="{{url()}}/students/view/{{$student->id}}">View Student</a></li>
							                                       
							                                    </ul>
							                                </div>
							                            </div>
							                            <div class="uk-text-center">
							                                <img class="md-card-head-avatar" src="{{url()}}/assets/img/avatars/avatar_06.png" alt=""/>
							                            </div>
							                            <h3 class="md-card-head-text uk-text-center">
							                                {{$student->student_name}}  <!-- <span class="uk-text-truncate">Goodwin-Nienow</span> -->
							                            </h3>
							                        </div>
							                        <div class="md-card-content">
							                            <ul class="md-list">
							                                <li>
							                                    <div class="md-list-content">
							                                        <span class="md-list-heading">Nickname</span>
							                                        <span class="uk-text-small uk-text-muted">{{$student->nickname}}</span>
							                                    </div>
							                                </li>
							                                <li>
							                                    <div class="md-list-content">
							                                        <span class="md-list-heading">Date of Birth</span>
							                                        <span class="uk-text-small uk-text-muted uk-text-truncate">{{date('d M Y',strtotime($student->student_date_of_birth))}}</span>
							                                    </div>
							                                </li>
							                                <!-- <li>
							                                    <div class="md-list-content">
							                                        <span class="md-list-heading">Classes</span>
							                                        <span class="uk-text-small uk-text-muted">1-711-950-2023</span>
							                                    </div>
							                                </li> -->
							                            </ul>
							                        </div>
							                    </div>
							                </div>
		                                <?php } ?>
                                		
		                                
                                			
		                               </div>
		                               <?php 
		                               }else{
                                		?>
                                			<p class="uk-alert uk-alert-warning">No kids were added. Please add using the add button on bottom right.</p>
                                			<br clear="all"/>
                                		<?php 
                                		}
		                                
		                                ?>
		                               
		                               <br clear="all"/>
		                                <a class="md-fab md-fab-accent" id="addKids" href="#" style="float:right">
								            <i class="material-icons">&#xE145;</i>
								        </a>
		                               	<br clear="all"/>
		                               	<br clear="all"/>
		                               	<br clear="all"/>
		                           </li>
		                           <li id="birthdays">
		                           
		                           			<div id="birthdayMsgDiv"></div>
											{{Form::open(array("id"=>"addBirthdayPartyForm", 'files'=> true, 'url'=>'quick/addbirthdayParty'))}} 
											<input type="hidden" name="customerId" value="{{$customer->id}}"/>
											<div class="md-card-content large-padding">	
												<br clear="all" /><br clear="all" />				
												<div class="uk-grid" data-uk-grid-margin id="addbirthday">
													<div class="uk-width-medium-1-2">
														<div class="parsley-row" style="margin-top: -23px;">
															<label for="kidsSelect">Select Kid<span class="req">*</span></label> 
															<br clear="all" />
															{{ Form::select('kidsSelect', array('' => '') + $kidsSelect, null ,array('id'=>'kidsSelect', 'class' => 'input-sm md-input', "required"=>"", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
														</div>
													</div>
													<div class="uk-width-medium-1-2">
														<div class="parsley-row" style="margin-top: -23px;">
															<label for="birthdayMonth">Select Birthday Month<span
																class="req">*</span></label> <br clear="all" />
															{{Form::text('birthdayMonth',
															null,array('id'=>'birthdayMonth','required', 'class' => ''))}}
														</div>
													</div>
												</div>
					
											</div>
					
					
											<div class="uk-grid" data-uk-grid-margin id="weekendData">							
												<div class="uk-width-medium-1-3" id="saturdaysDiv">
													<h4>Saturdays</h4>
													<div></div>
												</div>					
												<div class="uk-width-medium-1-3" id="sundaysDiv">
													<h4>Sundays</h4>
													<div></div>							
												</div>					 						
												<div class="uk-width-medium-1-3">
													<h4>Timings</h4>
													<br clear="all"/>
													<div class="parsley-row" style="margin-top: -23px;">
														<label for="birthdayTime">Select Birthday Party Start Time<span class="req">*</span></label> <br clear="all" />
														{{Form::text('birthdayTime', null,array('id'=>'birthdayTime','required', 'class' => ''))}}
													</div>
												</div>
											</div>
											
											<div class="uk-grid" data-uk-grid-margin id="birthdayPriceTable">
												<div class="uk-width-medium-1-1">
													<table id="birthdayPriceTable" class="uk-table table-striped table-condensed">
															<!-- <caption>Table caption</caption> -->
															<thead>
																<tr>
																	<th>Particulars</th>
																	<th>Quantity</th>
																	<th>Totals</th>
																</tr>
															</thead>
															<tbody>
																<tr>
																	<td>
																	15 Guests and 2 hours (Default)
																	<?php if(!$customerMembership){?>
																		<br clear="all"/>
																		<input type="checkbox" name="applyMembership" class="checkbox-custom" id="applyMembership">
																		<label for="applyMembership"  class="checkbox-custom-label" >Apply Membership</label>
																	<?php }?>
																	
																	
																	</td>
																	<td>
																	
																	</td>
																	<td>Rs.
																		<?php 
																		/* echo '<pre>';
																		
																		print_r($customerMembership);
																		echo '</pre>'; */
																		
																		
																		if(isset($customerMembership->id)){?>
																			{{Form::text('defaultBirthdayPrice', '10000',array('id'=>'defaultBirthdayPrice', 'required',  'readonly', 'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																		<?php }else{?>
																			{{Form::text('defaultBirthdayPrice', '12000',array('id'=>'defaultBirthdayPrice', 'required',  'readonly', 'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}																		
																		<?php }?>
																	</td>															
																</tr>
																<tr id="membershipTableRow">
																	<td>
																		<div class="uk-width-medium-1-1">							             		 
																		    <div class="parsley-row">
																			    <label for="membershipType">Membership Type<span class="req"></span></label> 
																			    {{ Form::select('membershipType', array('' => '')+$membershipTypes, null ,array('id'=>'membershipType', 'class' => 'input-sm md-input',   "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
																		    </div><br>
																	    </div>  
																	
																	</td>
																	<td>
																		
																	</td>
																	<td>
																		{{Form::text('membershipPriceBday', '0',array('id'=>'membershipPriceBday', 'readonly', 'required',  'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>															
																</tr>
																<tr>
																	<td>Additional Guest</td>
																	<td>
																		{{Form::number('additionalGuestCount', '',array('id'=>'additionalGuestCount',  'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>
																	<td>
																		{{Form::text('additionalGuestPrice', '0',array('id'=>'additionalGuestPrice', 'readonly', 'required',  'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>															
																</tr>
																<tr>
																	<td>Additional Half an hour</td>
																	<td>
																		{{Form::number('additionalHalfHourCount', '',array('id'=>'additionalHalfHourCount',  'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>
																	<td>
																		{{Form::text('additionalHalfHourPrice', '0',array('id'=>'additionalHalfHourPrice', 'readonly', 'required',  'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>															
																</tr>
																
																<tr>
																	<td colspan="2">Grand Total</td>
																	<td>
																		{{Form::text('grandTotal', null,array('id'=>'grandTotal', 'readonly', 'required',  'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>															
																</tr>
																<tr>
																	<td>
																	Remaining Due amount
																	<br><span class="sub-heading">(After paying advance) Tax will be added at the time of payment</span>
																	</td>																	
																	<td>
																		{{Form::text('remainingAmount', '3000',array('id'=>'remainingAmount', 'required',  'readonly',  'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>
																	<td></td>
																</tr>
																
															</tbody>
													</table>
													<br clear="all"/>
													<h4>Payment</h4>
													<table id="birthdayPriceTable" class="uk-table table-striped table-condensed">
															<!-- <caption>Table caption</caption> -->
															<thead>
																<tr>
																	
																</tr>
															</thead>
															<tbody>
																
																<tr style="text-align: right;">
																	<td colspan="2">Advance </td>
																	<td>
																		{{Form::text('advanceAmount', '3000',array('id'=>'advanceAmount', 'required',  'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>
																</tr>																
																<tr style="text-align: right;">
																	<td colspan="2">Tax</td>
																	<td>
																		{{Form::text('taxAmount', '',array('id'=>'taxAmount', 'required', 'readonly', 'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>
																</tr>
																
																<tr style="text-align: right;">
																	<td colspan="2"><span>Total Amount payable</span></td>
																	<td>
																		{{Form::text('totalAmountPayable', '',array('id'=>'totalAmountPayable', 'required', 'readonly', 'class' => 'form-control input-sm md-input','style'=>'padding:0px'))}}
																	</td>
																</tr>
                                                                                                                                <tr>
                                                                                                                                    <td>
                                                                                                                                        <div id="emailEnrollPrintDiv" class="uk-grid" data-uk-grid-margin>

                                                                                                                                		<div class="uk-width-medium-1-1">
                                                                                                                                                    <h4>Invoice option</h4>
                                                                                                                                                </div>
                                                                                                                                                <div class="uk-width-medium-1-2">
                                                                                                                                                    <div class="parsley-row">
											
                                                                                                                                        		<input id="invoicePrintOption" name="invoicePrintOption"  value="yes"  type="checkbox"  class="checkbox-custom" />
                                                                                                                                                        <label for="invoicePrintOption"  class="checkbox-custom-label">Print Invoice<span
                                                                                                                                                        accesskey=""class="req">*</span></label> 
                                                                                                                                                    </div>
                                                                                                                				</div>
                                                                                                                                                <!--
                                                                                                                                                <div class="uk-width-medium-1-2">
                                                                                                                                                    <div class="parsley-row">
                                                                                                                                                        <input id="emailOption" name="emailOption" type="checkbox"  value="yes" class="checkbox-custom" style="display:none;" />
                                                                                                                                                    	<label for="emailOption" class="checkbox-custom-label">Email Invoice<span
                                                                                                                                                                accesskey=""class="req">*</span></label> 
                                                                                                                                         	    </div>
                                                                                                                                                </div>
                                                                                                                                                -->
                                                                                                                                        </div>
                                                                                                                                    </td>
                                                                                                                                </tr>
																<tr>
																	<td colspan="2"></td>
																	<td>
																		<button type="submit" id="birthdayPartyCreateBtn" class="md-btn md-btn-primary" style="float:right;">Add Birthday party</button>
																	</td>															
																</tr>
															</tbody>
													</table>
													
													
													
												</div>
											
											
											</div>	
											{{Form::close()}}
											<!-- Modal -->

  

											
											<br clear="all" /><br clear="all" />
											<h3>List of birthdays</h3>		
											<div class="md-card-content large-padding">		
																								
												<div class="uk-grid" data-uk-grid-margin id="listofBirthdays">
														<ul class="md-list">
							                                
							                           
							                            
							                            <?php 
							                            	/* echo "<pre>";
							                            	print_r($birthdays);
							                            	echo "</pre>"; */
							                            	if(isset($birthdays['0'])){
							                            	foreach($birthdays as $birthday){
							                            		//print_r($birthday->Students->student_name);
							                            		
							                            ?>
                                                                                                                    
                                                                                     
							                            	<li>
							                            		<div class="md-list-content">
					                                                <span class="md-list-heading"><a href="#">Kid Name: {{$birthday->Students->student_name}}</a></span>
					                                                <div class="uk-margin-small-top">
					                                                	<span class="uk-margin-right"> 
					                                                		Birthday Date: {{$birthday->birthday_party_date}}	
					                                                		<i class="material-icons">&#xE192;</i> 
					                                                		<span class="uk-text-muted uk-text-small">
					                                                    		{{$birthday->birthday_party_time}}					                                                    
					                                                    	</span>
					                                                </span>
					                                                <span class="uk-margin-right">
					                                                    <i class="material-icons"></i> 
					                                                    <span class="uk-text-muted uk-text-small">
					                                                     Added on: {{date('d M Y',strtotime($birthday->created_at))}}
					                                                    </span>
					                                                </span>
					                                                
					                                                <!-- <span class="uk-margin-right">
					                                                    <i class="material-icons">&#xE417;</i> <span class="uk-text-muted uk-text-small">185</span>
					                                                </span> -->
					                                                </div>
					                                            </div>
							                                    
							                                </li>
                                                                                        
                                                                                            
                                                                                        
							                            
							                            <?php 
							                            	
							                            	} 
							                            	}
							                            	//print_r($birthdays);
							                            	//echo '</pre>';
							                            
							                            ?>
							                             </ul>
												</div>
											</div>	
											
										
                                                                                <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">
                                                                                    <div class="uk-width-large-1-1">
                                                                                        <div class="md-card">
                                                                                            <div class="md-card-content">
                                                                                                <div class="uk-overflow-container">
                                                                                                    <h4>Payments Made</h4>
                                                                                                    <table class="uk-table dashboardTable" id="payment-details" >
                                                                                                         <thead>
                                                                                                            <tr>
                                                                                                               <th class="uk-text-nowrap">KidName</th>
                                                                                                               <th class="uk-text-nowrap">Birthdate</th>
                                                                                                               <th class="uk-text-nowrap">Celebration date</th>
                                                                                                               <th class="uk-text-nowrap">Received by</th>
                                                                                                               <th class="uk-text-nowrap">Amount(with Tax)</th>
                                                                                                               <th class="uk-text-nowrap">option</th>
                                                                                                            </tr>
                                                                                                         </thead>
                                                                                                         <tbody>
                                                                                                             
                                                                                                             <?php if(isset($birthdaypaiddata[0])){ for($i=0; $i<sizeof($birthdaypaiddata) ;$i++){ ?>
                                                                                                             <tr>
                                                                                                             <td>{{$birthdaypaiddata[$i]['student_name']}}</td>
                                                                                                             <td>{{$birthdaypaiddata[$i]['student_date_of_birth']}}</td>
                                                                                                             <td>{{$birthdaypaiddata[$i]['birthday_party_date']}}</td>
                                                                                                             <td>{{$birthdaypaiddata[$i]['name']}}</td>
                                                                                                             <td>{{$birthdaypaiddata[$i]['amount']+$birthdaypaiddata[$i]['tax_amount']}}</td>
                                                                                                             <td><a target="_blank" href="<?php echo url() ?>/orders/Bprint/{{$birthdaypaiddata[$i]['encrypted_id']}}" class="btn btn-primary btn-sm">Print</a></td>
                                                                                                             </tr>
                                                                                                             <?php }} ?>
                                                                                                             <?php if(!isset($birthdaypaiddata[0])){?>
                                                                                                             <tr><td>------No payments made------- </td></tr>
                                                                                                             <?php }?>
                                                                                                         </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="uk-width-large-1-1">
                                                                                        <div class="md-card">
                                                                                            <div class="md-card-content">
                                                                                                <div class="uk-overflow-container">
                                                                                                    <h4>Payments Due</h4>
                                                                                                    <table class="uk-table dashboardTable" id="payment-details" >
                                                                                                         <thead>
                                                                                                            <tr>                                                                                                 <tr>
                                                                                                               <th class="uk-text-nowrap">Kid Name</th>
                                                                                                               <th class="uk-text-nowrap">Amount Pending(without tax)</th>
                                                                                                               <th class="uk-text-nowrap">Received By</th>
                                                                                                               <th class="uk-text-nowrap">Date of Payment</th>
                                                                                                               <th class="uk-text-nowrap">Receive Due & Print Invoice</th>
                                                                                                               
                                                                                                            </tr>
                                                                                                         </thead>
                                                                                                         <tbody>
                                                                                                            <?php if(isset($birthdayDuedata[0])){ for($i=0;$i<sizeof($birthdayDuedata);$i++){ ?>
                                                                                                             
                                                                                                             <tr>
                                                                                                             <td>{{$birthdayDuedata[$i]['student_name']}}</td>
                                                                                                             <td>{{$birthdayDuedata[$i]['payment_due_amount']}}</td>
                                                                                                             <td>{{$birthdayDuedata[$i]['name']}}</td>
                                                                                                             <td>{{$birthdayDuedata[$i]['birthday_party_date']}}</td>
                                                                           
                                                                                                             <td><input id="pendingamount" name="pendingamount" type="hidden" value="{{$birthdayDuedata[$i]['payment_due_amount']}}">
                                                                                                                 <a id='paymentdue' class="btn btn-primary btn-xs"  onclick="pendingamount({{$birthdayDuedata[$i]['id']}},{{$birthdayDuedata[$i]['payment_due_amount']}})" >Receive Due</a></td>
                                                                                                             
                                                                                                               </tr>
                                                                                                             <?php }} ?>
                                                                                                             <?php if(!isset($birthdayDuedata[0])){?>
                                                                                                             <tr><td>------No Due------- </td></tr>
                                                                                                             <?php }?>
                      
                                                                                                         </tbody>
                                                                                                    </table>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                	
											
											
											
										</li>
		                           <li id="commentsandlogsdivTab">
		                           
		                           		<?php 
		                           			/* echo "<pre>";
		                           			print_r($comments);
		                           			echo "</pre>"; */
		                           		?>
		                               	
		                           		<div class="uk-grid" data-uk-grid-margin>
		                           			<div id="commentMsgDiv">
		                           			
		                           			</div>
							             	<div class="uk-width-medium-1-1">							             		 
								                 <div class="parsley-row">
								                 	<label for="customerCommentTxtarea">Comment<span class="req">*</span></label> 
								                 	{{ Form::textarea('customerCommentTxtarea', null, ['id'=>'customerCommentTxtarea', 'size' => '50x3',  'class' => 'form-control input-sm md-input']) }}
								                 </div><br>
								            </div>     
								            <div class="uk-width-medium-1-3">
								                 <div class="parsley-row">
								                 	<label for="reminderTxtBox">Reminder date<span class="req">*</span></label> 
								                 	{{Form::text('reminderTxtBox', null,array('id'=>'reminderTxtBox', 'required', 'class' => ''))}}								                 	
								                 </div>
								            </div>
								            <div class="uk-width-medium-1-3">
							                 <div class="parsley-row">
								                 	<label for="commentType">Comment Type<span class="req">*</span></label>
								                 	<!-- array('' => '', 'followup' => 'Followup', 'attended_iv' => 'Attended IV', 'iv_no_show' => 'IV No show', 'missed_call' => 'Missed Call') -->
								                 	{{ Form::select('commentType', array('' => '', 'FOLLOW_UP' => 'Follow up', 'CALL_BACK' => 'Call back', 'NOT_REACHABLE' => 'Not reachable', 'NOT_PICKING_UP' => 'Not Picking up', 'NOT_INTERESTED' => 'Not Interested', 'MISSED_CALL' => 'Missed Call'), null ,array('id'=>'commentType', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
								                 </div>
								            </div>
								            
								            <div class="uk-width-medium-1-3">
								                 <div class="parsley-row">
								                 	<button type="button" id="saveCommentBtn" class="md-btn md-btn-primary" style="float:right;">Post Comment</button>
								                 </div>
								            </div>
								         </div>
		                           		<br clear="all"/>
		                           		<?php if($comments->count() > 0){
		                           		
		                           		?>
		                           		<ul class="md-list">
		                           			<?php foreach ($comments as $comment){
		                           			
		                           				/* echo '<pre>';
		                           				print_r($comment->Users['first_name']);
		                           				echo '</pre>'; */
		                           			?>
	                                        <li>
	                                            <div class="md-list-content">
	                                                <span class="md-list-heading"><a href="#">{{$comment->log_text}}</a></span>
	                                                <div class="uk-margin-small-top">
	                                                <?php if($comment->reminder_date){?>
	                                                <span class="uk-margin-right">
	                                                    <i class="material-icons">&#xE192;</i> <span class="uk-text-muted uk-text-small">
	                                                    <span class="uk-badge uk-badge-success" style="float:left">{{ucfirst(str_replace("_"," ",$comment->comment_type))}}</span>{{date('d M Y',strtotime($comment->reminder_date))}} 
	                                                    
	                                                    </span>
	                                                </span>
	                                                <?php  }?>
	                                                <span class="uk-margin-right">
	                                                    <i class="material-icons">&#xE0B9;</i> 
	                                                    <span class="uk-text-muted uk-text-small">
	                                                    Added on: {{date('d M Y',strtotime($comment->created_at))}} By {{$comment->Users['first_name']}} {{$comment->Users['last_name']}}
	                                                    </span>
	                                                </span>
	                                                
	                                                <!-- <span class="uk-margin-right">
	                                                    <i class="material-icons">&#xE417;</i> <span class="uk-text-muted uk-text-small">185</span>
	                                                </span> -->
	                                                </div>
	                                            </div>
	                                        </li>
	                                        <?php }?>
                                        </ul>
                                        <?php }?>                        
		                           </li>  
		                      </ul>                                	
                          </li>
                                
                                
                         </ul>
                        </div>
                    </div>
                </div>
               
            </div>
            
            
<!-- Add Kids  -->
     <div id="addKidsModal" class="modal fade" role="dialog" style="margin-top:50px; z-index:99999;">
		  <div class="modal-dialog modal-lg">
		
		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Add Kids</h4>
		      </div>
		      <div class="modal-body">
			      <div id="messageStudentAddDiv"></div>
			      <div id="formBody">
			      
			      		
			      		<!-- {
				      		'studentName'     : $('#studentName').val(),
				      		'studentGender'   : $('#studentGender').val(),
				      		'studentDob'      : $('#studentDob').val(),
				      		'nickname'        : $('#nickname').val(),
				      		'school'          : $('#school').val(),
				      		'location'        : $('#location').val(),
				      		'hobbies'         : $('#hobbies').val(),
				      		'emergencyContact': $('#emergencyContact').val(),
				      		'remarks'         : $('#remarks').val(),			      		
				      		'healthIssue'     : $('#healthIssue').val()	      		
			      		} -->
			      		
			      		
			      		<form id="kidsAddForm" method="post>
			      		<br clear="all"/><br clear="all"/><br clear="all"/>
			      	 	<div class="uk-grid" data-uk-grid-margin>
			             	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="customerName">Kid Name<span class="req">*</span></label>
				                 	{{Form::text('studentName', null,array('id'=>'studentName', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="nickname">Nickname<span class="req">*</span></label>
				                 	{{Form::text('nickname', null,array('id'=>'nickname',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row" style="margin-top:-30px;">
				                 	<label for="studentDob">Date of birth<span class="req">*</span></label>
				                 	{{Form::text('studentDob', null,array('id'=>'studentDob','required', 'class' => ''))}}
				                 </div>
				            </div>		
				            		            				            
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="studentGender">Gender<span class="req">*</span></label>
				                 	<select id="studentGender" name="studentGender" class="form-control input-sm md-input" style="padding:0px; font-weight:bold;color: #727272;">
				                 		<option value=""></option>
				                 		<option value="male">Male</option>
				                 		<option value="female">Female</option>				                 	
				                 	</select>
				                 	
				                 </div>
				            </div>	
				        	
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="location">Location<span class="req">*</span></label>
				                 	{{Form::text('location', null,array('id'=>'location',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>	
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="school">School<span class="req">*</span></label>
				                 	{{Form::text('school', null,array('id'=>'school',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>		             				            
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="hobbies">Hobbies<span class="req">*</span></label>
				                 	{{Form::hidden('hobbies', 'Playing',array('id'=>'hobbies', 'required', 'class' => 'form-control input-sm md-input','style'=>''))}}
				                 </div>
				            </div>				        	
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="emergencyContact">Emergency contact<span class="req">*</span></label>
				                 	{{Form::number('emergencyContact', null,array('id'=>'emergencyContact', 'required', 'class' => 'form-control input-sm md-input', 'style'=>'padding:0px'))}}
				                 </div>
				            </div>
			             	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="remarks">Remarks<span class="req">*</span></label>
				                 	{{Form::text('remarks', null,array('id'=>'remarks', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>			            
				        </div>
				        <div class="uk-grid" data-uk-grid-margin>				        
				        	<div class="uk-width-medium-1-1">
				                 <div class="parsley-row">
				                 	<label for="healthIssue">Health Issues<span class="req">*</span></label>
				                 	{{ Form::textarea('healthIssue', null, ['id'=>'healthIssue', 'size' => '10x3', 'class' => 'form-control input-sm md-input']) }}
				                 </div>
				            </div>
				        </div>
				        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" id="saveKidsBtn" class="md-btn md-btn-primary">Submit</button>
                            </div>
                        </div>
				    </form>    
			        	
		         </div>
		      </div>
		      <div class="modal-footer">
		      	
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		
		  </div>
		</div>      
<!-- Add Kids -->
		
		
		
<!-- Edit Customer  -->
     <div id="editCustomerModal" class="modal fade" role="dialog" style="margin-top:50px; z-index:99999;">
		  <div class="modal-dialog modal-lg">
		
		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal">&times;</button>
		        <h4 class="modal-title">Edit Customer</h4>
		      </div>
		      <div class="modal-body">
			      <div id="messageEditCustomerDiv"></div>
			      <div id="editCustomerformBody">
			      
			      		
			      		<!-- {
				      		'studentName'     : $('#studentName').val(),
				      		'studentGender'   : $('#studentGender').val(),
				      		'studentDob'      : $('#studentDob').val(),
				      		'nickname'        : $('#nickname').val(),
				      		'school'          : $('#school').val(),
				      		'location'        : $('#location').val(),
				      		'hobbies'         : $('#hobbies').val(),
				      		'emergencyContact': $('#emergencyContact').val(),
				      		'remarks'         : $('#remarks').val(),			      		
				      		'healthIssue'     : $('#healthIssue').val()	      		
			      		} -->
			      		
			      		
			      		{{Form::open(array("id"=>"editCustomerForm", 'files'=> true, 'url'=>'quick/editCustomer'))}} 
			      		<br clear="all"/>
			      	 	<div class="uk-grid" data-uk-grid-margin>
			             	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="customerName">Customer name<span class="req">*</span></label>
				                 	{{Form::text('customerName', "$customer->customer_name",array('id'=>'customerName', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="customerEmail">Customer email<span class="req">*</span></label>
				                 	{{Form::text('customerEmail', $customer->customer_email,array('id'=>'customerEmail', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="customerMobile">Customer mobile number<span class="req">*</span></label>
				                 	{{Form::text('customerMobile', $customer->mobile_no,array('id'=>'customerMobile','required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>		
				            		            				            
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="building">Building<span class="req">*</span></label>
				                 	{{Form::text('building', $customer->building,array('id'=>'building', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="apartment">Apartment<span class="req">*</span></label>
				                 	{{Form::text('apartment', $customer->apartment_name,array('id'=>'apartment', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>	
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="lane">Lane<span class="req">*</span></label>
				                 	{{Form::text('lane', $customer->lane,array('id'=>'lane', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>			             				            
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="locality">Locality<span class="req">*</span></label>
				                 	{{Form::text('locality', $customer->locality,array('id'=>'locality', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>				        	
				        	 <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="state">State<span class="req">*</span></label>
				                 	{{ Form::select('state', array('' => '') + $provinces, $customer->state ,array('id'=>'state', 'class' => 'input-sm md-input', "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="state">City<span class="req">*</span></label>
				                 	{{ Form::select('city', array('' => ''), $customer->city ,array('id'=>'city', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>		            
				        </div>
				        <div class="uk-grid" data-uk-grid-margin>				        
				        	<div class="uk-width-medium-1-3"> 
				                  <div class="parsley-row">
				                 	<label for="zipcode">Zipcode<span class="req">*</span></label>
				                 	{{Form::text('zipcode', $customer->zipcode,array('id'=>'zipcode', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>    
				            <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	<label for="source">Source<span class="req">*</span></label>
				                 	
				                 	{{ Form::select('source', array('' => '', 'word of moutn' => 'Word of Mouth', 'grass roots' => 'Grassroots', 'walkin' => 'Walkin', 'events' => 'Events'), $customer->source ,array('id'=>'source', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>  
				            <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	<label for="referredBy">Referred by<span class="req">*</span></label>
				                 	{{Form::text('referredBy', $customer->referred_by,array('id'=>'referredBy',  'class' => 'form-control input-sm md-input'))}}				                 	
				                 </div>
			                </div>
				        </div>
				        <div class="uk-grid">
                            <div class="uk-width-1-1">
                            	<input type="hidden" name="customerId" value="{{$customer->id}}">
                                <button type="submit" id="saveCustomerBtn" class="md-btn md-btn-primary">Save Customer Details</button>
                            </div>
                        </div>
				    </form>    
			        	
		         </div>
		      </div>
		      <div class="modal-footer">
		      	
		        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>
		
		  </div>
		</div>      
<!-- Edit Customer -->
            

<div id="addbirthdaymodal" class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          
            <h4 class="modal-title">Add Birthday Party</h4>
        </div>
        <div class="modal-body" id="addbirthdaymodalbody">
          
        </div>
          <div class="modal-footer" id="addbirthdayfooter">
          <button type="button" id="addbirthdayclose" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>

<div id="pendingamount" class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          
            <h4 class="modal-title">PendingAmountPaid</h4>
        </div>
        <div class="modal-body" id="pendingamountbody">
          
        </div>
          <div class="modal-footer" id="pendingamountfooter">
          <button type="button" id="pendingamountclose" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
 
@stop