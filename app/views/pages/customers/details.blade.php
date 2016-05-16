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

    #enrolledtable{
        display: block;
    width: auto;
    height: auto;
    margin: 0;
    padding: 0;
    border: none;
    border-collapse: inherit;
    border-spacing: 0;
    border-color: inherit;
    vertical-align: inherit;
    text-align: left;
    font-weight: inherit;
    -webkit-border-horizontal-spacing: 0;
    -webkit-border-vertical-spacing: 0;
    }
    #enrolledtable tr:hover{
        background-color: #e0e0e0 !important;
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


 <!-- datatables -->
    <script src="{{url()}}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <!-- datatables colVis-->
    <script src="{{url()}}/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
    <!-- datatables tableTools-->
    <script src="{{url()}}/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
    <!-- datatables custom integration -->
    <script src="{{url()}}/assets/js/custom/datatables_uikit.min.js"></script>

    <!--  datatables functions -->
    <script src="{{url()}}/assets/js/pages/plugins_datatables.min.js"></script>
    
    
    
 
<!-- <script src="{{url()}}/assets/tagseditor/jquery.caret.min.js"></script>
<script src="{{url()}}/assets/tagseditor/jquery.tag-editor.js"></script>
 -->
<script type="text/javascript">



var customerName = "{{$customer->customer_name}}";
var customerId   = "{{$customer->id}}";

$("#followuptable").DataTable({
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {

            // Bind click event
            $(nRow).click(function() {
                  //window.open($(this).find('a').attr('href'));
				window.location = $(this).find('a').attr('href');
                  //OR

                // window.open(aData.url);

            });

            return nRow;
        },
        "iDisplayLength": 10,
        "lengthMenu": [ 10, 50, 100, 150, 200 ]
    });




//Initialize
//var sampleTags = ['c++', 'java', 'php', 'coldfusion', 'javascript', 'asp', 'ruby', 'python', 'c', 'scala', 'groovy', 'haskell', 'perl', 'erlang', 'apl', 'cobol', 'go', 'lua'];
$('#hobbies').tagit({
	//availableTags: sampleTags        
});

$("#studentDob").kendoDatePicker();
$('#remindDate').kendoDatePicker();
$('#birthdayReminderDate').kendoDatePicker();
$('#membershipReminderDate').kendoDatePicker();
$('#rDate').kendoDatePicker();
//$('#remdDate').kendoDatePicker();
$('#enrollmentReminderDate').kendoDatePicker();
$('#rmDate').kendoDatePicker();
$('#rmdDate').kendoDatePicker();
//$('#Reschedule-date').kendoDatePicker();
$('#Reminder-date').kendoDatePicker();
$('#introVisitTxtBox').kendoDatePicker();
$('#reminderDateBox').kendoDatePicker();
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
        /*var dob;
        if($('#studentDob').val()==''){
            dob='2000-11-26';
            alert(dob);
        }else{
            dob=$('#studentDob').val();
            alert(dob);
        }*/
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
                if($("#reminderTxtBox").val()==''){
		var postData = {"customerId":customerId,
						"commentText":$("#customerCommentTxtarea").val(),
						"commentType":$("#commentType").val(),									
						};
                }else{
                var postData = {"customerId":customerId,
						"commentText":$("#customerCommentTxtarea").val(),
						"commentType":$("#commentType").val(),
						"reminderDate":$("#reminderTxtBox").val(),					
						};
                }
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
	
	
$("#saveCustomerBtn").click(function (e){
	e.preventDefault();
        console.log('prevented');
	
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/editCustomer')}}",
                        data: $('#editCustomerForm').serialize(),
			dataType: 'json',
			success: function(response){
                            console.log(response);
                            if(response.status=='success'){
                              $('#messageEditCustomerDiv').html('<p class="uk-alert uk-alert-success">Sucessfully saved changes </p>')
                            }else{
                                 $('#messageEditCustomerDiv').html('<p class="uk-alert uk-alert-failure">Sucessfully saved changes </p>')
                            }
                        }
             }); 
	
});



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
        $("input[name='birthdayPaymentTypeRadio']").prop('required',true);
        $('#birthdayPartyCreateBtn').addClass('disabled');
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
        
        //check if he is a member return 10000
                //else return 12000
                
	if($('#applyMembership').is(":checked")) {

	$("#defaultBirthdayPrice").val('12000');
	if($("#membershipType").val() == "1"){
		
		$("#defaultBirthdayPrice").val('10000')
		
	}else if($("#membershipType").val() == "2"){
		
		$("#defaultBirthdayPrice").val('10000');
		
	}
        
            // if member are selected 
            
            var additionalGuestPrice    = parseInt($("#additionalGuestPrice").val());
            var additionalHalfHourPrice = parseInt($("#additionalHalfHourPrice").val());
            if(($('#membershipType').val()=='1')){
            var membershipPrice         = parseInt( $("#membershipPriceBday").val());
            }
            var defaultBirthdayPrice    = parseInt($("#defaultBirthdayPrice").val());
	    if($('#membershipType').val()=='1'){
            var grandTotal = (additionalGuestPrice + additionalHalfHourPrice + membershipPrice + defaultBirthdayPrice);
            }else {
                var grandTotal = (additionalGuestPrice + additionalHalfHourPrice + defaultBirthdayPrice);
            }
            
	
            $("#grandTotal").val(grandTotal);
            console.log($("#grandTotal").val());
            var advance = $('#advanceAmount').val();
            var remainingAmount = (grandTotal-advance);

            $("#remainingAmount").val(remainingAmount);

            $("#taxAmount").empty();
            $("#totalAmountPayable").empty();
            var tax = Math.floor(((14.5/100)*parseInt(advance)))
	
            $("#totalAmountPayable").val((parseInt(tax)+parseInt(advance)))
            $("#taxAmount").val(tax);
	
        

	}else{
                if($("#applyMembership").css('display')=='block'){
                  //  alert($("#applyMembership").css('display'));
                    $("#defaultBirthdayPrice").val('12000');
                }else{
                   // alert($("#applyMembership").css('display'));
                    $("#defaultBirthdayPrice").val('10000');
                }
	}

	var additionalGuestPrice    = parseInt($("#additionalGuestPrice").val());
	var additionalHalfHourPrice = parseInt($("#additionalHalfHourPrice").val());
        
	var membershipPrice         = parseInt( $("#membershipPriceBday").val());
	
        var defaultBirthdayPrice    = parseInt($("#defaultBirthdayPrice").val());
		
	var grandTotal = (additionalGuestPrice + additionalHalfHourPrice + membershipPrice+ defaultBirthdayPrice);

	
	
	$("#grandTotal").val(grandTotal);

	var advance = $('#advanceAmount').val();
	var remainingAmount = (grandTotal-advance);

	$("#remainingAmount").val(remainingAmount);

	$("#taxAmount").empty();
	$("#totalAmountPayable").empty();
	var tax = Math.floor(((14.5/100)*parseInt(advance)))
	
	$("#totalAmountPayable").val((parseInt(tax)+parseInt(advance)))
	$("#taxAmount").val(tax);
	
}

calculateBirthdayPartyPrice();

// advance amount for keyup  

$('#advanceAmount').keyup(function  (){
        if(parseInt($('#advanceAmount').val())<0){
            $('#advanceAmount').val('0');
        }
        if($('#advanceAmount').val()==''){
            $('#advanceAmount').val('0');
        }
	calculateBirthdayPartyPrice();
});

// advance amount for change  

$('#advanceAmount').change(function(){
        if(parseInt($('#advanceAmount').val())<0){
            $('#advanceAmount').val('0');
        }
        if($('#advanceAmount').val()==''){
            $('#advanceAmount').val('0');
        }
	calculateBirthdayPartyPrice();
});


//additionalGuestCount for keyup action
$('#additionalGuestCount').keyup(function (){
	if(parseInt($('#additionalGuestCount').val())<0){
            $('#additionalGuestCount').val('0');
        }
        if($('#additionalGuestCount').val()==''){
            $('#additionalGuestCount').val('0');
        }
        
	$("#additionalGuestPrice").val((parseInt($(this).val())*300));
	calculateBirthdayPartyPrice();

});


//additionalGuestCount for change action
$('#additionalGuestCount').change(function (){
	if(parseInt($('#additionalGuestCount').val())<0){
            $('#additionalGuestCount').val('0');
        }
        if($('#additionalGuestCount').val()==''){
            $('#additionalGuestCount').val('0');
        }
        
	$("#additionalGuestPrice").val((parseInt($(this).val())*300));
	calculateBirthdayPartyPrice();

});


//  additionalHalfHourCount for keyup action
$('#additionalHalfHourCount').keyup(function (){
	if(parseInt($('#additionalHalfHourCount').val())<0){
            $('#additionalHalfHourCount').val('0');
        }
        if($('#additionalHalfHourCount').val()==''){
            $('#additionalHalfHourCount').val('0');
        }
        
        
	$("#additionalHalfHourPrice").val((parseInt($(this).val())*3000));
	calculateBirthdayPartyPrice();

});
//  additionalHalfHourCount for change action
$('#additionalHalfHourCount').change(function (){
	if(parseInt($('#additionalHalfHourCount').val())<0){
            $('#additionalHalfHourCount').val('0');
        }
        if($('#additionalHalfHourCount').val()==''){
            $('#additionalHalfHourCount').val('0');
        }
        
        
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
			$("#membershipPriceBday").val("0");
		}
		calculateBirthdayPartyPrice();
	});
		
})


$("#membershipTableRow").hide();
$("#applyMembership").change(function (){

	if($('#applyMembership').is(":checked")) {
                console.log('applying membership');
                $("#membershipTableRow").show();
                $('#membershipType').val('');
                $('#membershipPriceBday').val('0');
		$("#membershipType").prop('required','true');
		$("#defaultBirthdayPrice").val('10000');
	}else{
                console.log('removing membership');
                if($('#membershipType').val()==''){
                   
                }
                $('#membershipType').val('');
                $('#membershipPriceBday').val('0');
		$("#defaultBirthdayPrice").val('12000');
		$("#membershipTableRow").hide();
		$("#membershipType").prop('required','false');
	}

	calculateBirthdayPartyPrice();
})


var data;
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
                                  async: false,
				  data:$('#addBirthdayPartyForm').serialize(),
				  success: function(response, textStatus, jqXHR)
				  {
                                       if(response.status == "success"){
                                                    $("#addBirthdayPartyForm").empty();
                                                    if(response.printUrl!=""){
                                                     var printvars = '<a target="_blank" href="'+response.printUrl+'" class="btn btn-primary" >Print</a>';
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
                                              //console.log('reloading');
                                              window.location.reload(1);
                                          });
				  },
                                  
				  error: function (jqXHR, textStatus, errorThrown)
				  {
                                      
				  }
                                
			});
         
	  }
	});
       
	
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
	

    $('#birthdayPaymentOptionsReceive_1').change(function(){
       if($('#birthdayPaymentOptionsReceive_1').is(":checked")){
          console.log('card');
           //$('#birthdayReceiveemailEnrollPrintDiv').hide();
          $('#receiveBirthdayCardDetailsDiv').show();
          $('#birthdayReceiveemailEnrollPrintDiv').show();
       }
    });
    
    $('#birthdayPaymentOptionsReceive_2').change(function(){
    if($('#birthdayPaymentOptionsReceive_2').is(":checked")){
           console.log('cash');
           $('#receiveBirthdayCardDetailsDiv').hide();
           $('#birthdayReceiveChequeDetailsDiv').hide();
           //$('#birthdayReceiveemailEnrollPrintDiv').hide();
           $('#birthdayReceiveemailEnrollPrintDiv').show();
       }
     });
     
    $('#birthdayPaymentOptionsReceive_3').change(function(){
     if($('#birthdayPaymentOptionsReceive_3').is(":checked")){
           console.log('cheque');
           $('#receiveBirthdayCardDetailsDiv').hide();
           $('#birthdayReceiveChequeDetailsDiv').show();
           $('#birthdayReceiveemailEnrollPrintDiv').show();
       }
    });
     


function pendingamount(pendingamountId,pendingAmount){
   var taxamount=Math.floor(((14.5/100)*parseInt(pendingAmount)));  
/*   $('#birthdayPending_id').val(pendingamountId);
   $('#birthdaypending_amt').val(pendingAmount);
   $('#receiveBirthdayCardDetailsDiv').hide();
   $('#birthdayReceiveChequeDetailsDiv').hide();
   $('#birthdayReceiveemailEnrollPrintDiv').hide();
   $('#birthdayReceivepayment').addClass('disabled');
   $('#receiveBirthdayDue').modal('show');
   return;
        */
   $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getBirthdayOrderPendingDetails')}}",
                        data: {'pending_id':pendingamountId,},
			dataType: 'json',
                        
			success: function(response){
                            //console.log(response);
                            $('#pendingamountpayheader').html("<button type='button' class='close' data-dismiss='modal'>&times;</button>  <h5>Pending amount for "+response.student_data['student_name']+"</h5>");
                            $('#additionalguestNo').val(response.birthday_data['additional_number_of_guests']);
                            $('#additionalguestcost').val(response.birthday_data['additional_guest_price']);
                            $('#additionalhalfhours').val(response.birthday_data['additional_half_hours']);
                            $('#additionalhalfhourscost').val(response.birthday_data['additional_halfhour_price']);
                            $('#amountpending').val(response.birthday_data['remaining_due_amount']);
                            var tax=Math.floor(((14.5/100)*parseInt($('#amountpending').val())));
                            $('#advancepaid').val(response.birthday_data['advance_amount_paid']);
                            $('#taxamount').val(tax);
                            $('#amountPendingAfterTax').val(parseInt($('#taxamount').val())+parseInt(response.birthday_data['remaining_due_amount']));
                            // $('#birthdayPending_id').val(pendingamountId);
                            // $('#birthdaypending_amt').val(pendingAmount);
                             $('#receiveBirthdayCardDetailsDiv').hide();
                             $('#birthdayReceiveChequeDetailsDiv').hide();
                             $('#birthdayReceiveemailEnrollPrintDiv').hide();
                             //$('#pendingamountpayadd').addClass('disabled');
                            $("#pendingamountpay").modal('show');
                            $('#changeorder').change(function(){
                                if($(this).is(":checked")) {
                                    $('#additionalguestNo').removeAttr("readonly");
                                    $('#additionalhalfhours').removeAttr("readonly");
                                
                                 }else{
                                         $('#additionalguestNo').val(response.birthday_data['additional_number_of_guests']);
                                         $('#additionalguestcost').val(response.birthday_data['additional_guest_price']);
                                         $('#additionalhalfhours').val(response.birthday_data['additional_half_hours']);
                                         $('#additionalhalfhourscost').val(response.birthday_data['additional_halfhour_price']);
                                         $('#amountpending').val(response.birthday_data['remaining_due_amount']);
                                         $('#advancepaid').val(response.birthday_data['advance_amount_paid']);
                           
                                 }
                                 
                               
                                
                            });
                            $('#additionalguestNo').change(function(){
                                if($('#additionalguestNo').val()<0){
                                    $('#additionalguestNo').val(0);
                                }
                                if($('#additionalguestNo').val()==''){
                                    $('#additionalguestNo').val(0);
                                }
                                var addguestno=$('#additionalguestNo').val()
                                var addguestamt=$('#additionalguestNo').val() * 300;
                                $('#additionalguestcost').val(addguestamt);
                                var addtionaltimecost=parseInt($('#additionalhalfhourscost').val());
                                $('#amountpending').val(parseInt(response.birthday_data['default_birthday_cost'])+addguestamt+addtionaltimecost);
                                var tax=Math.floor(((14.5/100)*parseInt($('#amountpending').val())));
                                $('#taxamount').val(tax);
                                $('#amountPendingAfterTax').val(parseInt($('#taxamount').val())+parseInt($('#amountpending').val()));
                            });
                            //for keyup operation additionalguestNo
                            $('#additionalguestNo').keyup(function(){
                                if($('#additionalguestNo').val()<0){
                                    $('#additionalguestNo').val(0);
                                }
                                if($('#additionalguestNo').val()==''){
                                    $('#additionalguestNo').val(0);
                                }
                                var addguestno=$('#additionalguestNo').val()
                                var addguestamt=$('#additionalguestNo').val() * 300;
                                $('#additionalguestcost').val(addguestamt);
                                var addtionaltimecost=parseInt($('#additionalhalfhourscost').val());
                                $('#amountpending').val(parseInt(response.birthday_data['default_birthday_cost'])+addguestamt+addtionaltimecost);
                                var tax=Math.floor(((14.5/100)*parseInt($('#amountpending').val())));
                                $('#taxamount').val(tax);
                                $('#amountPendingAfterTax').val(parseInt($('#taxamount').val())+parseInt($('#amountpending').val()));
                            });
                            //additional half hour change for receive due
                            $('#additionalhalfhours').change(function(){
                                if($('#additionalhalfhours').val()<0){
                                    $('#additionalhalfhours').val(0);
                                }
                                if($('#additionalhalfhours').val()==''){
                                    $('#additionalhalfhours').val(0);
                                }
                                $('#additionalhalfhourscost').val(parseInt($('#additionalhalfhours').val()) * 3000);
                                var addtionaltimecost=parseInt($('#additionalhalfhourscost').val());
                                $('#amountpending').val(parseInt(response.birthday_data['default_birthday_cost'])+parseInt($('#additionalguestcost').val())+addtionaltimecost);
                                var tax=Math.floor(((14.5/100)*parseInt($('#amountpending').val())));
                                $('#taxamount').val(tax);
                                $('#amountPendingAfterTax').val(parseInt($('#taxamount').val())+parseInt($('#amountpending').val()));
                        
                            });
                            // for keyup operation
                            $('#additionalhalfhours').keyup(function(){
                                if($('#additionalhalfhours').val()<0){
                                    $('#additionalhalfhours').val(0);
                                }
                                if($('#additionalhalfhours').val()==''){
                                    $('#additionalhalfhours').val(0);
                                }
                                $('#additionalhalfhourscost').val(parseInt($('#additionalhalfhours').val()) * 3000);
                                var addtionaltimecost=parseInt($('#additionalhalfhourscost').val());
                                $('#amountpending').val(parseInt(response.birthday_data['default_birthday_cost'])+parseInt($('#additionalguestcost').val())+addtionaltimecost);
                                var tax=Math.floor(((14.5/100)*parseInt($('#amountpending').val())));
                                $('#taxamount').val(tax);
                                $('#amountPendingAfterTax').val(parseInt($('#taxamount').val())+parseInt($('#amountpending').val()));
                        
                            });
                            
                        }
             }); 
   $('#pendingamountpayadd').click(function(){
            var paymentType = $("input[type='radio'][name='birthdayPaymentReceiveTypeRadio']:checked").val();
            var printoption=$('#birthdayReceiveinvoicePrintOption').is(':checked');
            if($('#changeorder').is(":checked")==false){
             // create normal order form pending order
            var taxamount=Math.floor(((14.5/100)*parseInt($('#amountpending').val())));   
            
            
                  if(paymentType=='cash'){
                      //for without change of order cash
                    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/createorder')}}",
                        data: {'pending_id':pendingamountId,'taxamount':taxamount,'paymentType':paymentType,},
			dataType: 'json',
			success: function(response){
                            console.log(response);
                            if(response.status=='success'){
                               // $('#pendingamountpayadd').attr("disabled","");
                            //$('#birthdays').empty();
                            $('#pendingamountpayadd').css('display','none');
                            var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                            $('#pendingamountpaybody').empty();         
                            if(printoption){
                                $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> </br>"+printvars);
                            }else{
                                $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> ");
                            
                            }
                            $('#pendingclose').click(function(){
				   window.location.reload(1);
                            });

                        }
                        }
                    });
                   }else if(paymentType=='cheque'){
                       // for without change of order cheque
                                 
                                 
                                 if(($('#birthdayReceivechequeBankName').val()!='') && ($('#birthdayReceivechequeNumber').val()!='')){
                                       console.log('cheque');
                                       $.ajax({
                                	type: "POST",
                                        url: "{{URL::to('/quick/createorder')}}",
                                        data: {'pending_id':pendingamountId,'taxamount':taxamount,'paymentType':paymentType,
                                               'birthdayReceivechequeBankName':$('#birthdayReceivechequeBankName').val(),
                                                'birthdayReceivechequeNumber':$('#birthdayReceivechequeNumber').val(),},
                                        dataType: 'json',
                                        success: function(response){
                                        console.log(response);
                                        if(response.status=='success'){
                                        $('#pendingamountpayadd').css('display','none');
                                        //$('#birthdays').empty();
                                        var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                                        $('#pendingamountpaybody').empty();                         
                                        if(printoption){
                                           $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> </br>"+printvars);
                                        }else{
                                            $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> ");
                                        }            
                                        $('#pendingclose').click(function(){
                                         window.location.reload(1);
                                        });

                                        }
                                        }
                                        });
                                 }else{
                                    $('#msg').html("<p class='uk-alert uk-alert-warning'>Please enter all details.</p>");
                                 }
                        
                        
                   }else if(paymentType=='card'){
                    
                                 if(($('#birthdayReceivecard4digits').val()!='') && ($('#birthdayReceivecardBankName').val()!='') &&
                                    ($('#birthdayReceivecardRecieptNumber').val()!='')){
                                       console.log('card');
                                       $.ajax({
                                	type: "POST",
                                        url: "{{URL::to('/quick/createorder')}}",
                                        data: {'pending_id':pendingamountId,'taxamount':taxamount,'paymentType':paymentType,
                                               'birthdayReceivecard4digits':$('#birthdayReceivecard4digits').val(),
                                               'birthdayReceivecardBankName':$('#birthdayReceivecardBankName').val(),
                                               'birthdayReceivecardRecieptNumber':$('#birthdayReceivecardRecieptNumber').val(),
                                               'birthdayReceivecardType':$('#birthdayReceivecardType').val(),
                                              },
                                        dataType: 'json',
                                        success: function(response){
                                        console.log(response);
                                        if(response.status=='success'){
                                        $('#pendingamountpayadd').css('display','none');
                                        //$('#birthdays').empty();
                                        var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                                        $('#pendingamountpaybody').empty();                         
                                        if(printoption){
                                           $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> </br>"+printvars);
                                        }else{
                                            $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> ");
                                        }
                                        $('#pendingclose').click(function(){
                                         window.location.reload(1);
                                        });

                                        }
                                        }
                                        });
                                 }else{
                                           //console.log('comingto else');
                                    $('#msg').html("<p class='uk-alert uk-alert-warning'>Please enter all details.</p>");
                                 }
                   }else{
                     $('#msg').html("<p class='uk-alert uk-alert-warning'>please select payment type details</p>")
                   }
                   
                   
         }else{
             //update birthday and payment due table the create order from pending order
              var taxamount=Math.floor(((14.5/100)*parseInt($('#amountpending').val())));
                if(paymentType=='cash'){ 
                 $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/modifyBirthdayPendingOrder')}}",
                        data: {'pending_id':pendingamountId,'amountpending':$('#amountpending').val(),'taxamount':taxamount,
                            'additionalguestNo':$('#additionalguestNo').val(),'additionalguesAmount':$('#additionalguestcost').val(),
                            'additionalhalfhours':$('#additionalhalfhours').val(),'additionalhalfhourscost':$('#additionalhalfhourscost').val(),
                             'paymentType':paymentType,},
			dataType: 'json',
			success: function(response){
                            console.log(response.status);
                            $('#pendingamountpayadd').css('display','none');
                             var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                              $('#pendingamountpaybody').empty();                         
                              if(printoption){
                              $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> </br>"+printvars);
                               }else{
                              $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> ");
                                }
                              $('#pendingclose').click(function(){
				   window.location.reload(1);
                            });
                        }
                 });  
                 }else if(paymentType=='card'){
                      if(($('#birthdayReceivecard4digits').val()!='') && ($('#birthdayReceivecardBankName').val()!='') &&
                        ($('#birthdayReceivecardRecieptNumber').val()!='')){
                        $.ajax({
			 type: "POST",
			 url: "{{URL::to('/quick/modifyBirthdayPendingOrder')}}",
                         data: {'pending_id':pendingamountId,'amountpending':$('#amountpending').val(),'taxamount':taxamount,
                            'additionalguestNo':$('#additionalguestNo').val(),'additionalguesAmount':$('#additionalguestcost').val(),
                            'additionalhalfhours':$('#additionalhalfhours').val(),'additionalhalfhourscost':$('#additionalhalfhourscost').val(),
                             'paymentType':paymentType,'birthdayReceivecard4digits':$('#birthdayReceivecard4digits').val(),
                                               'birthdayReceivecardBankName':$('#birthdayReceivecardBankName').val(),
                                               'birthdayReceivecardRecieptNumber':$('#birthdayReceivecardRecieptNumber').val(),
                                               'birthdayReceivecardType':$('#birthdayReceivecardType').val(),},
			 dataType: 'json',
			 success: function(response){
                            console.log(response.status);
                            $('#pendingamountpayadd').css('display','none');
                             var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                              $('#pendingamountpaybody').empty();                         
                              if(printoption){
                               $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> </br>"+printvars);
                                }else{
                                 $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> ");
                                }
                               $('#pendingclose').click(function(){
				   window.location.reload(1);
                            });
                         }
                        });
                        }else{
                         $('#msg').html("<p class='uk-alert uk-alert-warning'>Please enter all details.</p>");
                        }
                 }else
                 if(paymentType=='cheque'){
                     if(($('#birthdayReceivechequeBankName').val()!='') && ($('#birthdayReceivechequeNumber').val()!='')){
                     $.ajax({
			 type: "POST",
			 url: "{{URL::to('/quick/modifyBirthdayPendingOrder')}}",
                         data: {'pending_id':pendingamountId,'amountpending':$('#amountpending').val(),'taxamount':taxamount,
                            'additionalguestNo':$('#additionalguestNo').val(),'additionalguesAmount':$('#additionalguestcost').val(),
                            'additionalhalfhours':$('#additionalhalfhours').val(),'additionalhalfhourscost':$('#additionalhalfhourscost').val(),
                             'paymentType':paymentType, 'birthdayReceivechequeBankName':$('#birthdayReceivechequeBankName').val(),
                                                'birthdayReceivechequeNumber':$('#birthdayReceivechequeNumber').val(),},
			 dataType: 'json',
			 success: function(response){
                            console.log(response.status);
                            $('#pendingamountpayadd').css('display','none');
                             var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                             $('#pendingamountpaybody').empty();                         
                                if(printoption){
                                           $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> </br>"+printvars);
                                        }else{
                                            $('#pendingamountpaybody').html("<p class='uk-alert uk-alert-success'>successfully received pending amount.</p> ");
                                        }
                              $('#pendingclose').click(function(){
                                window.location.reload(1);
                            });
                         }
                        });
                        }else{
                         $('#msg').html("<p class='uk-alert uk-alert-warning'>Please enter all details.</p>");
                        }
                 }else{
                     $('#msg').html("<p class='uk-alert uk-alert-warning'>please select payment type details</p>")
                   }

         }
         });
}

$.urlParam = function(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if(results){
        return results[1];
        }else{
        return 0;
        }
}

//for selecting comments and logs page from dashboard
$(document).ready(function(){
   // for 
   if($.urlParam('tab')==='ivfollowup'){
   //for heading     
   $('#customerInfoTabMenu').removeClass('uk-active');
   $('#customerInfoTabMenu').attr('aria-expanded','false');
   $('#followupTabMenu').addClass('uk-active');
   $('#followupTabMenu').attr('aria-expanded','false');
   
   //for tabs data
   $('#customerInfoTab').attr('aria-hidden','true');
   $('#customerInfoTab').removeClass('uk-active');
   $('#customerInfoTab').attr('style','');
   
   $('#followupTab').attr('aria-hidden','false');
   $('#followupTab').addClass('uk-active');
   $('#followupTab').attr('style','animation-duration: 200ms;');
   
   
   }
   
   //for birthday tabs from dashboard
   if($.urlParam('tab')==='birthdayparty'){
   //for heading     
   $('#customerInfoTabMenu').removeClass('uk-active');
   $('#customerInfoTabMenu').attr('aria-expanded','false');
   $('#birthdayPartyInfoTabMenu').addClass('uk-active');
   $('#birthdayPartyInfoTabMenu').attr('aria-expanded','false');
   
   //for tabs data
   $('#customerInfoTab').attr('aria-hidden','true');
   $('#customerInfoTab').removeClass('uk-active');
   $('#customerInfoTab').attr('style','');
   
   $('#birthdays').attr('aria-hidden','false');
   $('#birthdays').addClass('uk-active');
   $('#birthdays').attr('style','animation-duration: 200ms;');
   
   
   }
   
});
/*
$('#followupTypeCbx').change(function(){

   if($('#followupTypeCbx').val()=='INTROVISIT'){
       $('#actionCbx').html('');
       $('#actionCbx').append("<option value=''></option>"+
                            "<option value='ADDINTROVISIT'>AddIntrovisit</option>"+
                            "<option value='FOLLOWUPINTROVISIT'>FollowupIntrovisit</option>");
   }else{
       $('#actionCbx').html('');
   } 
});

*/
function getEligibleClasses(ageYear,ageMonth,studentGender){
var yearAndMonth= (parseInt(ageYear*12)+parseInt(ageMonth));
          //console.log(ageYear);
          //console.log(ageMonth);
          //console.log(yearAndMonth);
	  $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/eligibleClassess')}}",
        //data: {'ageYear': ageYear, 'ageMonth': ageMonth, 'gender':studentGender,'yearAndMonth':yearAndMonth,},
        data: {'ageYear': ageYear, 'ageMonth': ageMonth, 'gender':studentGender,'yearAndMonth':yearAndMonth},
        dataType:"json",
        success: function (response)
        {
         // console.log(response);
      	  $(".eligibleClassesCbx").empty("");      	  
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  //console.log(index+" = "+item);
      		  $string += '<option value='+item.id+'>'+item.class_name+'</option>';               
            });
      	  $('.eligibleClassesCbx').append($string); 
        }
    });
}

function getivdata(ivid){
    //console.log(ivid);
    
    
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getIntrovisitHistory')}}",
                        data: {'introvisit_id':ivid},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            var data="<table class='uk-table'>"+
                                    "<thead>"+
                                    "<tr>"+
                                    "<th class='uk-text-nowrap'>FollowupType</th>"+
                                    "<th class='uk-text-nowrap'>Quality</th>"+
                                    "<th class='uk-text-nowrap'>Description</th>"+
                                    "<th class='uk-text-nowrap'>CommentedBy</th>"+
                                    "</tr>"+
                                    "</thead>";
                           for(var i=0;i<response.status.length;i++){
                                data+="<tr role='row'>"+
                                        "<td>"+response.status[i]['followup_status']+"</td><td>"+response.status[i]['comment_type']+"</td>"+
                                        "<td>"+response.status[i]['log_text']+"</td>"+
                                        "<td>"+response.status[i]['commentor_name']+"</td>"+
                                        "</tr>";
                            }
                            data+="</table>";
                            //console.log(data);
                            $('#ivhistorybody').html(data);
                            $('#ivhistory').modal('show');
                        }
             });      
}


function selectstatus(ivId,ivStatus,comment_type){
//console.log(ivId);     
//console.log(ivStatus);
        if(ivStatus=='ENROLLED'){
            //console.log('working');
            $('#reminderDate').css('display','none');
        }
        if(ivStatus=='NOT_INTERESTED'){
            $('#reminderDate').css('display','none');
        }
        $('#iv_id').val(ivId);
	$("#iveditSelect").data('iveditid',ivId);
	$("#ivEditForm").show();
	$("#iveditstatusSelect").val(ivStatus);
        if(comment_type!=='ACTION_LOG'){
            $('#iveditActionSelect').val(comment_type);
        }else{
            $('#iveditActionSelect').val(' ');
        }
	$("#introvisitEditDiv").show();
	$("#saveIntroVisitBtn").show();
	$("#editIntrovisitModal").modal('show');
        
}

function selectEnrollmetnstatus(payment_due_id,followup_status,comment_type){
    $('#payment_due_id').val(payment_due_id);
    $('#enrollmenteditstatusSelect').val(followup_status);
    $('#enrollmenteditActionSelect').val(comment_type);
    $("#enrollmentEditDiv").show();
    $("#saveenrollmentVisitBtn").show();
    $("#editEnrollmentModal").modal('show');
        
    
}

$("#saveIntroVisitBtn").click(function (){
    if(($('#iveditActionSelect').val()!=' ') && $('#iveditstatusSelect').val()=='NOT_INTERESTED'){
	saveIv();
    }else if(($('#iveditActionSelect').val()!=' ') && ($('#iveditstatusSelect').val()=='ENROLLED')){
        saveIv();
    }else if(($('#iveditActionSelect').val()!=' ') && ($('#Reminder-date').val()!='')){
        saveIv();
    }else{
        $('#introVisitEditMessage').html('<p class="uk-alert uk-alert-warning">Please select the reminder-date for further followup</p>');
    }	
});


$('#saveEnrollmentBtn').click(function(){
  if(($('#enrollmenteditActionSelect').val()!=' ')&&($('#enrollmenteditstatusSelect').val()=='NOT_INTERESTED')){
      
      saveEnrollment();
  }else if(($('#enrollmenteditActionSelect').val()!=' ')&& ($('#enrollmenteditstatusSelect').val()=='CLOSE_CALL')){
      
      saveEnrollment();
  }else if(($('#enrollmenteditActionSelect').val()!=' ')&& ($('#enrollmentReminderDate').val()!='')){
      
      saveEnrollment();
  }else{
        $('#enrollmentEditMessage').html('<p class="uk-alert uk-alert-warning">Please select the reminder-date for further followup</p>');
  }
  
});

function saveEnrollment(){
    console.log('saving enrollment');
    $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/editEnrollment')}}",
        data:{'paymentdue_id':$('#payment_due_id').val(), "customerCommentTxtarea":$("#enrollmentcustomerCommentTxtarea").val(),
              'reminder-date':$('#enrollmentReminderDate').val(),'enrollmentstatus':$('#enrollmenteditstatusSelect').val(),
              'enrollmenteditAction':$('#enrollmenteditActionSelect').val(),},
        //data: {'classId': $("#eligibleClassesCbx").val(), 'batchId':$("#batchCbx").val(), "studentId":studentId},
        dataType:"json",
        success: function (response)
        {
           
            
        	if(response.status == "success"){

                $("#enrollmentEditForm").hide();
            	$("#enrollmentEditMessage").val('');
            	$("#saveEnrollmentBtn").hide();
                $('#enrollmentcomments').hide();
                $('#comment_hide').css('display','none');
				$("#enrollmentEditMessage").html('<p class="uk-alert uk-alert-success">Followup changed successfully. Please wait till this page reloads</p>');
				setTimeout(function(){
                                    //console.log(window.location.href.split('?')[0]);
				   var path= window.location.href.split('?')[0];
                                   window.location.href = path+'?tab=ivfollowup';
                                   window.location.reload(1);
				}, 4000);
				
        	}else{
        		$("#enrollmentEditForm").hide();
	                $("#enrollmentEditMessage").val('');
                        $('#enrollmentcomments').hide();
                       //$('#comment_hide').css('display','none');
        		$("#enrollmentEditMessage").html('<p class="uk-alert uk-alert-danger">Sorry! Followup could not be changed.</p>');
        	}
            
        }
    }); 
}
$('#enrollmenteditstatusSelect').change(function(){
   if(($('#enrollmenteditstatusSelect').val()=='NOT_INTERESTED')||($('#enrollmenteditstatusSelect').val()=='CLOSE_CALL')){
       $("#enrollmentEditMessage").html('<p class="uk-alert uk-alert-warning">you are about to close followup call.</p>');
       $('#eReminderDate').hide();
   }else{
       $('#eReminderDate').show();
   }
});

$('#reschedule-date').kendoDatePicker();
function saveIv(){


	//var ivid = $("#iveditSelect").data('iveditid');
	//var ivstatus = $("#iveditSelect").val();

	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/editIntrovisit')}}",
        data:{'iv_id':$('#iv_id').val(), "customerCommentTxtarea":$("#ivcustomerCommentTxtarea").val(),
              'reminder-date':$('#Reminder-date').val(),'ivstatus':$('#iveditstatusSelect').val(),
              'iveditAction':$('#iveditActionSelect').val(),},
        //data: {'classId': $("#eligibleClassesCbx").val(), 'batchId':$("#batchCbx").val(), "studentId":studentId},
        dataType:"json",
        success: function (response)
        {
            console.log(response);
            
        	if(response.status == "success"){

                $("#ivEditForm").hide();
            	$("#introvisitEditDiv").hide();
            	$("#saveIntroVisitBtn").hide();
                $('#ivcustomerCommentTxtarea').hide();
                $('#comment_hide').css('display','none');
				$("#introVisitEditMessage").html('<p class="uk-alert uk-alert-success">Followup changed successfully. Please wait till this page reloads</p>');
				setTimeout(function(){
                                    //console.log(window.location.href.split('?')[0]);
				   var path= window.location.href.split('?')[0];
                                   window.location.href = path+'?tab=ivfollowup';
                                   window.location.reload(1);
				}, 4000);
				
        	}else{
        		$("#ivEditForm").hide();
	                $("#introvisitEditDiv").hide();
                        $('#ivcustomerCommentTxtarea').hide();
                       $('#comment_hide').css('display','none');
        		$("#introVisitEditMessage").html('<p class="uk-alert uk-alert-danger">Sorry! Followup could not be changed.</p>');
        	}
            
        }
    }); 
}


$('#saveBirthdayBtn').click(function(){
    if($('#birthdayActionSelect').val()!=' ' && $('#birthdayeditstatusSelect').val()=='NOT_INTERESTED'){
        saveBirthday();
    }else if($('#birthdayActionSelect').val()!=' ' && $('#birthdayeditstatusSelect').val()=='ATTENDED/CELEBRATED'){
        saveBirthday();
    }else if($('#birthdayActionSelect').val()!=' ' && $('#birthdayReminderDate').val()!=''){
        saveBirthday();
    }else{
        $('#birthdayEditMessage').html('<p class="uk-alert uk-alert-warning">Please select reminder-date for further followup</p>');
    }
});

function saveBirthday(){
    $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/editBirthdayCelebrationFollowup')}}",
        data:{'birthday_id':$('#birthday_id').val(), "birthdayCommentTxtarea":$("#birthdayCommentArea").val(),
              'birthdayReminderDate':$('#birthdayReminderDate').val(),'birthdaystatusSelect':$('#birthdayeditstatusSelect').val(),
              'birthdayAction':$('#birthdayActionSelect').val(),},
        //data: {'classId': $("#eligibleClassesCbx").val(), 'batchId':$("#batchCbx").val(), "studentId":studentId},
        dataType:"json",
        success: function (response)
        {
            console.log(response);
            
        	if(response.status == "success"){

				$("#birthdayEditMessage").html('<p class="uk-alert uk-alert-success">Followup changed successfully. Please wait till this page reloads</p>');
				setTimeout(function(){
                                    //console.log(window.location.href.split('?')[0]);
				   var path= window.location.href.split('?')[0];
                                   window.location.href = path+'?tab=ivfollowup';
                                   window.location.reload(1);
				}, 4000);
				
        	}else{
        		
        		$("#introVisitEditMessage").html('<p class="uk-alert uk-alert-danger">Sorry! Followup could not be changed.</p>');
        	}
            
        }
    }); 
}


$('#followupcalllTypeCbx').change(function(){
       if(($('#followupcalllTypeCbx').val()=='COMPLAINTS')||($('#followupcalllTypeCbx').val()=='RETENTION')){
           //disabling iv default followup
           $('.introvisitfollowup').css('display','none');
           $('#addIntroVisitSubmit').css('display','none');
           $('#defaultfollowup').css('display','none');
            //enabling complaints
           
           $('.otherFollowups').css('display','block');
           
            console.log('compa');
       }
       if($('#followupcalllTypeCbx').val()=='INQUIRY'){
           if($('#followupTypeCbx').val()==='SETUPIV'){
         
           //to hide others
           $('.introvisitfollowup').css('display','none');
           $('#addIntroVisitSubmit').css('display','none');
           $('.otherFollowups').css('display','none');
          // $('.attendediv').css('display','none');
           $('#defaultfollowup').css('display','none');
         
           $('.introvisitfollowup').css('display','block');
           $('#addIntroVisitSubmit').css('display','block');
            if($('#followupkidCbx').val()===null){
                $('#followupMsg').html('<p class="uk-alert uk-alert-danger">Please add kid to add Intro Visit</p>');
            }else{
                $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getStudentDetailsByIdForBatches')}}",
                        data: {'studentId':$('#followupkidCbx').val()},
			dataType: 'json',
			success: function(response){
                           // console.log(response);
                            
                            getEligibleClasses(response.ageYear,response.ageMonth,response.student_gender);
                        }
            }); 
          }
          }else{
             $('.introvisitfollowup').css('display','none');
             $('#addIntroVisitSubmit').css('display','none');
             $('#defaultfollowup').css('display','none');
             $('.otherFollowups').css('display','block');
          }    
       }else if($('#followupcalllTypeCbx').val()===''){
           $('.introvisitfollowup').css('display','none');
           $('#addIntroVisitSubmit').css('display','none');
           $('#defaultfollowup').css('display','none');
          $('.otherFollowups').css('display','none');
          }
       
});
  
  
  
$('#followupTypeCbx').change(function(){
     if($('#followupTypeCbx').val()===''){
          if($('#followupcalllTypeCbx').val()===''){
             $('#defaultfollowup').css('display','block');
          }else{
              $('#defaultfollowup').css('display','none');
          }
     }
     if($('#followupcalllTypeCbx').val()=='INQUIRY'){
        if($('#followupTypeCbx').val()==='SETUPIV'){
         
         //to hide others
         $('.introvisitfollowup').css('display','none');
         $('#addIntroVisitSubmit').css('display','none');
         $('.attendediv').css('display','none');
         $('#defaultfollowup').css('display','none');
         $('.otherFollowups').css('display','none');
         
         $('.introvisitfollowup').css('display','block');
         $('#addIntroVisitSubmit').css('display','block');
            if($('#followupkidCbx').val()===null){
                $('#followupMsg').html('<p class="uk-alert uk-alert-danger">Please add kid to add Intro Visit</p>');
            }else{
                $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getStudentDetailsByIdForBatches')}}",
                        data: {'studentId':$('#followupkidCbx').val()},
			dataType: 'json',
			success: function(response){
                           // console.log(response);
                            
                            getEligibleClasses(response.ageYear,response.ageMonth,response.student_gender);
                        }
            }); 
            }
             
         
     }else{
         $('.introvisitfollowup').css('display','none');
         $('#addIntroVisitSubmit').css('display','none');
         $('.attendediv').css('display','none');
         $('.otherFollowups').css('display','block');
        
     }
     }
     
});

$(document).ready(function(){
        $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getStudentsByCustomerid')}}",
                        data: {'customer_id':<?php echo $customer->id ?>, },
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            if(response.status==='success'){
                                //console.log(response.student_data[0]['id']);
                                $('#followupkidCbx').html('');
                                var data="";
                                for(var i=0; i<response.student_data.length; i++){
                               data+= '<option value='+response.student_data[i]['id']+'>'+response.student_data[i]['student_name']+'</option>';
                                }
                                //console.log(data);
                                $('#followupkidCbx').append(data);
                            }
                        }
             });  
});



function getSeasons(){
$.ajax({
			type: "POST",
			url: "{{URL::to('/quick/season/getSeasonsForEnrollment')}}",
                        data: {},
			dataType: 'json',
			success: function(response){
                           // console.log(response.season_data);
                            
                            $(".SeasonsCbx").empty("");      	  
                            string = '';
                            for(var i=0;i<response.season_data.length;i++){
                                string += '<option value='+response.season_data[i]['id']+'>'+response.season_data[i]['season_name']+'</option>';
                            }
                            
                            $('.SeasonsCbx').append(string); 
                           // console.log(string);
                        }
             })  ;
}

$(document).ready(function(){
    getSeasons();

});

$('#eligibleClassIntro').change(function(){
    	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/batchesByClassSeasonId')}}",
        data: {'classId': $('#eligibleClassIntro').val(),'seasonId':$('#SeasonsCbx').val()},
        dataType:"json",
        success: function (response)
        {      	   
      	  $('#introbatchCbx').empty();      	  
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  $string += '<option value='+item.id+'>'+item.batch_name+' '+item.day+'  '+item.start_time+' - '+item.end_time+' '+item.instructor+'</option>';               
            });
      	  $('#introbatchCbx').append($string); 
      	  //$('#introbatchCbx').append($string);
        }
    });
});

$('#SeasonsCbx').change(function(){
    if($('#eligibleClassIntro').val()!=''){
     $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/batchesByClassSeasonId')}}",
        data: {'classId': $('#eligibleClassIntro').val(),'seasonId':$('#SeasonsCbx').val()},
        dataType:"json",
        success: function (response)
        {      	   
      	  $('#introbatchCbx').empty();      	  
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  $string += '<option value='+item.id+'>'+item.batch_name+' '+item.day+'  '+item.start_time+' - '+item.end_time+' '+item.instructor+'</option>';               
            });
      	  $('#introbatchCbx').append($string); 
      	  //$('#introbatchCbx').append($string);
        }
     }); 
    }
});

$("#introVisitTxtBox").kendoDatePicker({	
    change: function() {
    	var postData = {"batchId":$("#introbatchCbx").val(), "scheduleDate":$("#introVisitTxtBox").val()};
    	$.ajax({
            type: "POST",
            url: "{{URL::to('/quick/checkSlotAvailableForIntrovisit')}}",
            data: postData,
            dataType:"json",
            success: function (response)
            {
          	  	//console.log(response);      	 	
    			if(response.status == "clear"){
                                $('#addIntroVisitSubmit').addClass('disabled');
    				$("#Msg").html('<p class="uk-alert uk-alert-danger">Please select another day. Batch chosen does not have schedule on selected date.</p>');
    				//$("#KidsformBody").hide();
    			}else{
                                $('#addIntroVisitSubmit').removeClass('disabled');
    				$("#Msg").html("");
                                
    			}
    	     	
            }
        });
    }
});

$('#addIntroVisitSubmit').click(function(){
    if(($('#eligibleClassIntro').val()=='')||($('#introbatchCbx').val()=='')||($('#introVisitTxtBox').val()=='')||
       ($('#followupcalllTypeCbx').val()=='')||($('#followupQualityTypeCbx').val()=='')||($('#eligibleClassIntro').val()=='')||
       ($('#customerCommentTxt').val()=='')){
       // console.log('validation error');
        $("#Msg").html('<p class="uk-alert uk-alert-danger">Please select all required fields </p>');
    }else{
        $('#addIntroVisitSubmit').addClass('disabled');
        //console.log('now register');
        
         $.ajax({
	        type: "POST",
	        url: "{{URL::to('/quick/addIntroVisit')}}",
	        data: {'seasonId':$('#SeasonsCbx').val(),'customerId':customerId,'studentIdIntroVisit':$('#followupkidCbx').val(),
                       'eligibleClassesCbx':$('#eligibleClassIntro').val(),'introbatchCbx':$('#introbatchCbx').val(),
                       'introVisitTxtBox':$('#introVisitTxtBox').val(),'customerCommentTxtarea':$('#customerCommentTxt').val(),
                       'followupType':$('#followupcalllTypeCbx').val(),'commentType':$('#followupQualityTypeCbx').val(),   },
	        //data: {'classId': $("#eligibleClassesCbx").val(), 'batchId':$("#batchCbx").val(), "studentId":studentId},
	        dataType:"json",
	        success: function (response)
	        {
                        console.log(response);
	        	if(response.status == "success"){
	            	
					$("#Msg").html('<p class="uk-alert uk-alert-success">Introductory visit was added successfully. Please wait till this page reloads</p>');
					setTimeout(function(){
					   var path= window.location.href.split('?')[0];
                                            window.location.href = path+'?tab=ivfollowup';
                                            window.location.reload(1);
					}, 5000);
	        	}else{
	            	
					
	        		$("#Msg").html('<p class="uk-alert uk-alert-danger">Sorry! Introductory visit could not be enrolled.</p>');
	        	}     	   
	        }
	    });
            
    }

});

   
$('#iveditstatusSelect').change(function(){
  
  if($('#iveditstatusSelect').val()=='NOT_INTERESTED'){
      $('#reminderDate').css('display','none');
      $('#introVisitEditMessage').html("<p class='uk-alert uk-alert-warning'>You are about to close the followupcall </p>");
  }else if($('#iveditstatusSelect').val()=='ENROLLED'){
      $('#reminderDate').css('display','none');
        $('#introVisitEditMessage').html("<p class='uk-alert uk-alert-warning'>You are about to close the followupcall </p>");
        $('#reminderDate').css('display','none');
  }else{
      $('#introVisitEditMessage').html("");
      $('#reminderDate').css('display','block');
  }
});

function selectbirthdaystatus(birthday_id,birthday_followup_status,birthday_comment_type){
if(birthday_followup_status=='ATTENDED/CELEBRATED'){
    $('#birthdayreminder').css('display','none');
}
if(birthday_followup_status=='NOT_INTERESTED'){
     $('#birthdayreminder').css('display','none');
}
$('#birthday_id').val(birthday_id);
$('#birthdayeditstatusSelect').val(birthday_followup_status);
$('#birthdayActionSelect').val(birthday_comment_type);
$("#birthdayEditForm").show();
$("#editBirthdayModal").modal('show');
}



$('#birthdayeditstatusSelect').change(function(){
    //console.log('changed');
   if($('#birthdayeditstatusSelect').val()=='NOT_INTERESTED'){
      // console.log('working');
       $('#birthdayreminder').css('display','none');
       $('#birthdayEditMessage').html("<p class='uk-alert uk-alert-warning'>you are about to close the followupcall </p>");
   }else
   if($('#birthdayeditstatusSelect').val()=='ATTENDED/CELEBRATED'){
       $('#birthdayreminder').css('display','none');
       $('#birthdayEditMessage').html("<p class='uk-alert uk-alert-warning'>you are about to close the followupcall </p>");
   }else{
       $('#birthdayreminder').css('display','block');
       $('#birthdayEditMessage').html("");
   } 
       
});


function getbirthdaydata(birthday_id){
    
    
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getBirthdayHistory')}}",
                        data: {'birthday_id':birthday_id},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            if(response.status=='success'){
                            var data="<table class='uk-table'>"+
                                    "<thead>"+
                                    "<tr>"+
                                    "<th class='uk-text-nowrap'>FollowupType</th>"+
                                    "<th class='uk-text-nowrap'>Quality</th>"+
                                    "<th class='uk-text-nowrap'>Description</th>"+
                                    "<th class='uk-text-nowrap'>CommentedBy</th>"+
                                    "</tr>"+
                                    "</thead>";
                           for(var i=0;i<response.data.length;i++){
                                data+="<tr role='row'>"+
                                        "<td>"+response.data[i]['followup_status']+"</td><td>"+response.data[i]['comment_type']+"</td>"+
                                        "<td>"+response.data[i]['log_text']+"</td>"+
                                        "<td>"+response.data[i]['commentor_name']+"</td>"+
                                        "</tr>";
                            }
                            data+="</table>";
                            //console.log(data);
                            $('#birthdayhistorybody').html(data);
                            $('#birthdayhistory').modal('show'); 
                        }
                        }
             });      
}


function getEnrollmentData(paymentfollowupId){
    //console.log('working');
     
  
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getEnrollmetHistory')}}",
                        data: {'paymentfollowupId':paymentfollowupId},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            if(response.status=='success'){
                               
                            var data="<table class='uk-table'>"+
                                    "<thead>"+
                                    "<tr>"+
                                    "<th class='uk-text-nowrap'>FollowupType</th>"+
                                    "<th class='uk-text-nowrap'>Quality</th>"+
                                    "<th class='uk-text-nowrap'>Description</th>"+
                                    "<th class='uk-text-nowrap'>CommentedBy</th>"+
                                    "</tr>"+
                                    "</thead>";
                           for(var i=0;i<response.data.length;i++){
                                data+="<tr role='row'>"+
                                        "<td>"+response.data[i]['followup_status']+"</td><td>"+response.data[i]['comment_type']+"</td>"+
                                        "<td>"+response.data[i]['log_text']+"</td>"+
                                        "<td>"+response.data[i]['commentor_name']+"</td>"+
                                        "</tr>";
                            }
                            data+="</table>";
                            //console.log(data);
                            $('#enrollmenthistorybody').html(data);
                            $('#enrollmentyhistory').modal('show'); 
                        }
                        }
             });      
}




$('#addOtherFollowupSubmit').click(function(e){
   e.preventDefault();       
  if(($('#SeasonsCbx').val()=='')||($('#followupkidCbx').val()=='')||
     ($('#followupcalllTypeCbx').val()=='')||($('#followupQualityTypeCbx').val()=='')||
     ($('#otherCommentTxtarea').val()=='')||($('#remindDate').val()=='')){
      //error
      $('#followupMsg').html('<p class="uk-alert uk-alert-warning"> please select all the details to add followup.</p>');
      
  }else{
      //add followup
      $('#addOtherFollowupSubmit').addClass('disabled');
      $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/createFollowup')}}",
                        data: {'season_id':$('#SeasonsCbx').val(),'student_id':$('#followupkidCbx').val(),'customer_id':customerId,
                               'followupType':$('#followupcalllTypeCbx').val(),'comment_type':$('#followupQualityTypeCbx').val(),
                               'followupstatus':$('#followupTypeCbx').val(),
                               'otherCommentTxtarea':$('#otherCommentTxtarea').val(),'remindDate':$('#remindDate').val(),},
			dataType: 'json',
			success: function(response){
                            console.log(response.status);
                            if(response.status=='success'){
                                $('#followupMsg').html('<p class="uk-alert uk-alert-success">Followup created successfully. please wait till page reloads.</p>');
                                setTimeout(function(){
                                    //console.log(window.location.href.split('?')[0]);
				   var path= window.location.href.split('?')[0];
                                   window.location.href = path+'?tab=ivfollowup';
                                   window.location.reload(1);
				}, 3000);
                            }else{
                                $('#followupMsg').html('<p class="uk-alert uk-alert-danger">Followup not created successfully.Try again later</p>');
                                
                            }
                            
                            
                            
                        }
             });  
  }
});



function getComplaintData(complaintId){
            $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getComplaintHistoryById')}}",
                        data: {'complaintId':complaintId},
			dataType: 'json',
			success: function(response){
                         //console.log(response);
                         if(response.status=='success'){
                         var data="<table class='uk-table'>"+
                                    "<thead>"+
                                    "<tr>"+
                                    "<th class='uk-text-nowrap'>FollowupType</th>"+
                                    "<th class='uk-text-nowrap'>Quality</th>"+
                                    "<th class='uk-text-nowrap'>Description</th>"+
                                    "<th class='uk-text-nowrap'>CommentedBy</th>"+
                                    "</tr>"+
                                    "</thead>";
                           for(var i=0;i<response.data.length;i++){
                                data+="<tr role='row'>"+
                                        "<td>"+response.data[i]['followup_status']+"</td><td>"+response.data[i]['comment_type']+"</td>"+
                                        "<td>"+response.data[i]['log_text']+"</td>"+
                                        "<td>"+response.data[i]['commentor_name']+"</td>"+
                                        "</tr>";
                            }
                            data+="</table>";
                            //console.log(data);
                            $('#Complianthistorybody').html(data);
                            $('#complianthistory').modal('show');
                        }
                      }
             });
}

function selectComplaintstatus(complaintId,complaintStatus,complaintComment_type){
if(complaintStatus=='CLOSE_CALL'){
     $('#remDate').hide();
}
$('#complainteditstatusSelect').val(complaintStatus);
$('#complainteditActionSelect').val(complaintComment_type);
$('#complaint_id').val(complaintId);
$('#editComplaintModal').modal('show');
}
function selectRetentionstatus(retentionId,retentionStatus,retentionComment_type){
    if(retentionStatus=='CLOSE_CALL'){
        $('#rmDate').hide();
    }
$('#retentioneditstatusSelect').val(retentionStatus);
$('#retentioneditActionSelect').val(retentionComment_type);
$('#retention_id').val(retentionId);
$('#editRetentionModal').modal('show');
}
function selectInquirystatus(inquiryId,inquiryStatus,inquiryComment_type){
    if(inquiryStatus=='CLOSE_CALL'){
        $('#remdDate').hide();
    }
$('#inquiryeditstatusSelect').val(inquiryStatus);
$('#inquiryeditActionSelect').val(inquiryComment_type);
$('#inquiry_id').val(inquiryId);
$('#editInquiryModal').modal('show');
}
function selectmembershipstatus(membershipId,membershipStatus,membership_comment_type){
    if((membershipStatus=='CLOSE_CALL')||(membershipStatus=='NOT_INTERESTED')){
        $('#membeshipReminderDate').hide();
    }
$('#membershipeditstatusSelect').val(membershipStatus);
$('#membershipeditActionSelect').val(membership_comment_type);
$('#membership_id').val(membershipId);
$('#editMembershipModal').modal('show');
    
}

$('#membershipeditstatusSelect').change(function(){
   if(($('#membershipeditstatusSelect').val()=='CLOSE_CALL')||($('#membershipeditstatusSelect').val()=='NOT_INTERESTED')){
       $('#membershipEditMessage').html('<p class="uk-alert uk-alert-warning">you are about to close the Call</p>');
       $('#membeshipReminderDate').hide();
   }else{
       $('#membeshipReminderDate').show();
       $('#membershipEditMessage').html('');
   }
});

$('#complainteditstatusSelect').change(function(){
   if($('#complainteditstatusSelect').val()=='CLOSE_CALL') {
       $('#complaintEditMessage').html('<p class="uk-alert uk-alert-warning">you are about to close the Call</p>');
       $('#remDate').hide();
   }else{
       $('#remDate').show();
       $('#complaintEditMessage').html('');
       
   }
});


$('#saveMembershipBtn').click(function(){
    if($('#membershipeditActionSelect').val()!=' ' && $('#membershipcustomerCommentTxtarea').val()!=''){
        
                if($('#membershipReminderDate').val()!=''||($('#membershipeditstatusSelect').val()=='NOT_INTERESTED')||($('#membershipeditstatusSelect').val()=='CLOSE_CALL')){
                    
                    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/updateMembershipFollowup')}}",
                        data: {'followup_status':$('#membershipeditstatusSelect').val(),'comment_type':$('#membershipeditActionSelect').val(),
                               'membership_followup_id':$('#membership_id').val(),'remider_date':$('#membershipReminderDate').val(),
                               'comment':$('#membershipcustomerCommentTxtarea').val()},
			dataType: 'json',
			success: function(response){
                            if(response.status=='success'){
                                $('#membershipEditMessage').html('<p class="uk-alert uk-alert-success"> Followup is successfully changed. please wait still the page reloads.</p>')
                               setTimeout(function(){
                                    //console.log(window.location.href.split('?')[0]);
				   var path= window.location.href.split('?')[0];
                                   window.location.href = path+'?tab=ivfollowup';
                                   window.location.reload(1);
				}, 3000);
                            }    
                        }
                    });  
                    
                }else{
                               $('#membershipEditMessage').html('<p class="uk-alert uk-alert-danger">please fill reminder date</p>');
     
                }
      
    }else{
        $('#membershipEditMessage').html('<p class="uk-alert uk-alert-danger">please fill required details</p>');
       
    }
});

$('#saveComplaintBtn').click(function(){
 if(($('#complaintstatusSelect').val()!='') && ($('#complainteditActionSelect').val()!='') && 
         ($('#complaintcustomerCommentTxtarea').val()!='')){
       //call ajax
         if($('#rDate').val()!=''||($('#complainteditstatusSelect').val()=='CLOSE_CALL')){
           $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/UpdateFollowup')}}",
                        data: {'complaint_id':$('#complaint_id').val(),'followup_status':$('#complainteditstatusSelect').val(),
                               'comment_type':$('#complainteditActionSelect').val(),'customer_text_area':$('#complaintcustomerCommentTxtarea').val(),
                               'rDate':$('#rDate').val(),},
			dataType: 'json',
			success: function(response){
                            //console.log(response.status);
                            if(response.status=='success'){
                                $('#complaintEditMessage').html('<p class="uk-alert uk-alert-success"> Followup is successfully changed. please wait still the page reloads.</p>')
                               setTimeout(function(){
                                    //console.log(window.location.href.split('?')[0]);
				   var path= window.location.href.split('?')[0];
                                   window.location.href = path+'?tab=ivfollowup';
                                   window.location.reload(1);
				}, 3000);
                            }
                        }
             });
          }else{
          $('#complaintEditMessage').html('<p class="uk-alert uk-alert-danger">please fill all required details</p>');
          }
 } else{
     $('#complaintEditMessage').html('<p class="uk-alert uk-alert-danger">please fill all required details</p>');
     
 }
});



$('#saveRetentionBtn').click(function(){
 if(($('#retentionstatusSelect').val()!='') && ($('#retentioneditActionSelect').val()!='') && 
         ($('#retentioncustomerCommentTxtarea').val()!='')){
       //call ajax
       
           $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/UpdateRetentionFollowup')}}",
                        data: {'retention_id':$('#retention_id').val(),'followup_status':$('#retentioneditstatusSelect').val(),
                               'comment_type':$('#retentioneditActionSelect').val(),'customer_text_area':$('#retentioncustomerCommentTxtarea').val(),
                               'rDate':$('#rmDate').val(),},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            if(response.status=='success'){
                                $('#retentionEditMessage').html('<p class="uk-alert uk-alert-success"> Followup is successfully changed. please wait still the page reloads.</p>')
                               setTimeout(function(){
                                    //console.log(window.location.href.split('?')[0]);
				   var path= window.location.href.split('?')[0];
                                   window.location.href = path+'?tab=ivfollowup';
                                   window.location.reload(1);
				}, 3000);
                            }
                        }
             });
 } else{
     $('#retentionEditMessage').html('<p class="uk-alert uk-alert-danger">please fill all required details</p>');
     
 }
});



$('#saveInquiryBtn').click(function(){
 if(($('#inquirystatusSelect').val()!='') && ($('#inquiryeditActionSelect').val()!='') && 
         ($('#inquirycustomerCommentTxtarea').val()!='')){
       //call ajax
       
           $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/UpdateInquiryFollowup')}}",
                        data: {'inquiry_id':$('#inquiry_id').val(),'followup_status':$('#inquiryeditstatusSelect').val(),
                               'comment_type':$('#inquiryeditActionSelect').val(),'customer_text_area':$('#inquirycustomerCommentTxtarea').val(),
                               'rDate':$('#rmdDate').val(),},
			dataType: 'json',
			success: function(response){
                            console.log(response);
                            if(response.status=='success'){
                                $('#InquiryEditMessage').html('<p class="uk-alert uk-alert-success"> Followup is successfully changed. please wait still the page reloads.</p>');
                               setTimeout(function(){
                                    //console.log(window.location.href.split('?')[0]);
				   var path= window.location.href.split('?')[0];
                                   window.location.href = path+'?tab=ivfollowup';
                                   window.location.reload(1);
				}, 3000);
                            }
                        }
             });
 } else{
     $('#InquiryEditMessage').html('<p class="uk-alert uk-alert-danger">please fill all required details</p>');
     
 }
});


function getRetentionData(retentionId){
            $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getRetentionHistoryById')}}",
                        data: {'retentionId':retentionId},
			dataType: 'json',
			success: function(response){
                         //console.log(response);
                         if(response.status=='success'){
                         var data="<table class='uk-table'>"+
                                    "<thead>"+
                                    "<tr>"+
                                    "<th class='uk-text-nowrap'>FollowupType</th>"+
                                    "<th class='uk-text-nowrap'>Quality</th>"+
                                    "<th class='uk-text-nowrap'>Description</th>"+
                                    "<th class='uk-text-nowrap'>CommentedBy</th>"+
                                    "</tr>"+
                                    "</thead>";
                           for(var i=0;i<response.data.length;i++){
                                data+="<tr role='row'>"+
                                        "<td>"+response.data[i]['followup_status']+"</td><td>"+response.data[i]['comment_type']+"</td>"+
                                        "<td>"+response.data[i]['log_text']+"</td>"+
                                        "<td>"+response.data[i]['commentor_name']+"</td>"+
                                        "</tr>";
                            }
                            data+="</table>";
                            //console.log(data);
                            $('#Retentionhistorybody').html(data);
                            $('#retentionhistory').modal('show');
                        }
                      }
             });
}

function getInquiryData(inquiryId){
     $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getInquiryHistoryById')}}",
                        data: {'inquiryId':inquiryId},
			dataType: 'json',
			success: function(response){
                         //console.log(response);
                         if(response.status=='success'){
                         var data="<table class='uk-table'>"+
                                    "<thead>"+
                                    "<tr>"+
                                    "<th class='uk-text-nowrap'>FollowupType</th>"+
                                    "<th class='uk-text-nowrap'>Quality</th>"+
                                    "<th class='uk-text-nowrap'>Description</th>"+
                                    "<th class='uk-text-nowrap'>CommentedBy</th>"+
                                    "</tr>"+
                                    "</thead>";
                           for(var i=0;i<response.data.length;i++){
                                data+="<tr role='row'>"+
                                        "<td>"+response.data[i]['followup_status']+"</td><td>"+response.data[i]['comment_type']+"</td>"+
                                        "<td>"+response.data[i]['log_text']+"</td>"+
                                        "<td>"+response.data[i]['commentor_name']+"</td>"+
                                        "</tr>";
                            }
                            data+="</table>";
                            //console.log(data);
                            $('#Inquiryhistorybody').html(data);
                            $('#inquiryhistory').modal('show');
                        }
                      }
             });
}

function viewhistory(membershipId){

    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/getFollowupByMembershipId')}}",
                        data: {'membership_id':membershipId},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            var data="<table class='uk-table'>"+
                                    "<thead>"+
                                    "<tr>"+
                                    "<th class='uk-text-nowrap'>FollowupType</th>"+
                                    "<th class='uk-text-nowrap'>Quality</th>"+
                                    "<th class='uk-text-nowrap'>Description</th>"+
                                    "<th class='uk-text-nowrap'>CommentedBy</th>"+
                                    "</tr>"+
                                    "</thead>";
                            for(var i=0;i<response.historyData.length;i++){
                                data+="<tr role='row'>"+
                                        "<td>"+response.historyData[i]['followup_status']+"</td><td>"+response.historyData[i]['comment_type']+"</td>"+
                                        "<td>"+response.historyData[i]['log_text']+"</td>"+
                                        "<td>"+response.historyData[i]['commentor_name']+"</td>"+
                                        "</tr>";
                            }
                            data+="</table>";
                            
                            $('#Membershiphistorybody').html(data); 
                            $('#membershiphistory').modal('show');
                        }
            });  
    
}

$('#inquiryeditstatusSelect').change(function(){
if($('#inquiryeditstatusSelect').val()==='CLOSE_CALL'){
    $('#inquiryEditMessage').html('<p class="uk-alert uk-alert-warning">you are about to close the Call</p>');
    $('#remdDate').hide();
}else{
    $('#remdDate').show();
     $('#inquiryEditMessage').html('');
}

});

$('#pendingclose').click(function(){
   $('#pendingamountpay').modal('hide');
});


$('#retentioneditstatusSelect').change(function(){
if($('#retentioneditstatusSelect').val()=='CLOSE_CALL'){
   
   $('#renDate').hide(); 
}else{
   $('#renDate').show();
}
});


$("input[name='birthdayPaymentTypeRadio']").change(function(){
        
      if($("input[name='birthdayPaymentTypeRadio']:checked").val()==='card'){
          
           //deactivate previous required
          $('#birthdayChequeBankName').prop('required',false);
          $('#birthdayChequeNumber').prop('required',false);
          
         
        //activate present
          
          
         // $('#birthdayPartyCreateBtn').addClass('disabled');
          $('#birthdayCardType').prop('required',true);
          $('#birthdayCardBankName').prop('required',true);
          $('#birthdayCard4digits').prop('required',true);
          $('#cardRecieptNumber').prop('required',true);
         $('#birthdayCardDetailsDiv').css('display','block');
         $('#birthdayChequeDetails').css('display','none');
         
      }
     if($("input[name='birthdayPaymentTypeRadio']:checked").val()==='cheque'){
         //deactivate previous required
          $('#birthdayCardType').prop('required',false);
          $('#birthdayCardBankName').prop('required',false);
          $('#birthdayCard4digits').prop('required',false);
          $('#cardRecieptNumber').prop('required',false);
          
          //for present data
          $('#birthdayChequeBankName').prop('required',true);
          $('#birthdayChequeNumber').prop('required',true);
          $('#birthdayChequeDetails').css('display','block');
          $('#birthdayCardDetailsDiv').css('display','none');
         
      }
      if($("input[name='birthdayPaymentTypeRadio']:checked").val()==='cash'){
           //deactivating if previous exists
           $('#birthdayChequeBankName').prop('required',false);
           $('#birthdayChequeNumber').prop('required',false);
           $('#birthdayCardType').prop('required',false);
           $('#birthdayCardBankName').prop('required',false);
           $('#birthdayCard4digits').prop('required',false);
           $('#cardRecieptNumber').prop('required',false);
          
           
           $('#birthdayCardDetailsDiv').css('display','none');
           $('#birthdayChequeDetails').css('display','none');
          // $('#birthdayCardDetailsDiv').css('display','none');
      }
        
 
});

</script>
@stop
@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="{{url()}}/customers/memberslist" style="z-index:8;">Customers</a></li>
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
                                <div class="row">
                                <div class="col-md-5">
                                <h2 class="heading_b uk-margin-bottom"><span class="uk-text-truncate">
                                	{{$customer->customer_name}}{{$customer->customer_lastname}} 
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
                               <div class="col-md-5">
                                   <?php if(count($customer_student_data)>0){ ?>
                                    <table class='uk-table dataTable no-footer' id='enrolledtable'>
                                        <tbody>
                                        <tr>
                                        <th>StudnetName&nbsp; </th>
                                        <th>BatchName&nbsp; </th>
                                        <th>Startdate&nbsp; </th>
                                        <th>Enddate&nbsp; </th>
                                        </tr>
                                        <?php for($i=0;$i<count($customer_student_data);$i++){?>
                                        <tr>
                                            <td><a  style="color:white" href="{{url()}}/students/view/{{$customer_student_data[$i]->id}}">{{$customer_student_data[$i]->student_name}}&nbsp;</a></td>
                                            <?php if(count($customer_student_data[$i]->student_classes_data)>0){?>
                                            <td>{{$customer_student_data[$i]->student_classes_data[0]['batch_name']}}&nbsp;</td>
                                            <td>{{$customer_student_data[$i]->student_classes_data[0]['enrollment_start_date']}}&nbsp;</td>
                                            <td>{{$customer_student_data[$i]->student_classes_data[0]['enrollment_end_date']}}&nbsp;
                                            </td>
                                            <?php }?>
                                        </tr>
                                        <?php }?>
                                        </tbody>
                                        
                                    </table>
                                   <?php }?>
                                </div>
                                </div>
                                
                            </div>
                            
                            <a class="md-fab md-fab-small md-fab-accent" id="editCustomerBtn">
                                    <i class="material-icons">&#xE150;</i>
                                </a>
                            
                        </div>
                        <div class="user_content">
                            <ul id="user_profile_tabs" class="uk-tab" data-uk-tab="{connect:'#user_profile_tabs_content', animation:'slide-horizontal'}" data-uk-sticky="{ top: 48, media: 960 }">
                                <li id="customerInfoTabMenu" class="uk-active"><a href="#">About</a></li>
                               <!-- <li id="customerMembershipInfoTabMenu" class=""><a href="#">Membership</a></li> -->
                                <li id="studentInfoTabMenu" class=""><a href="#">Kids</a></li> 
                                <li id="birthdayPartyInfoTabMenu"><a>Birthday Parties</a></li>
                                <li id="commentsInfoTabMenu" class="" data-target="#commentsandlogsdivTab"><a href="#" data-target="#commentsandlogsdivTab">Comments and logs</a></li> 
                                <li id="followupTabMenu" class="" data-target="#followupTab"><a href="#" data-target="#followupTab">Followup</a></li> 
                               
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
                                <!--
                                <li id="customerMembershipInfoTab">
                                
                                <?php 
                                	/* echo '<pre>';
                               		print_r($customerMembership);
                                	echo '</pre>'; */
                                	
                                	//if($customerMembership){
                                
                                ?>
                                		<ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons">verified_user</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">{{--$customerMembership->name--}}  </span>
                                                        <span class="uk-text-small uk-text-muted">Current membership</span>
                                                    </div>
                                                </li>
                                         </ul>
                                                
                                
                                
                                <?php 
                                	//}else{
                                
                                ?>
                                
                                
                                <p class="uk-alert uk-alert-warning">No membership found.</p>
                                <?php //$url = '/customers/view/'.$customer->id; 
                                ?>
                                {{-- Form::open(array('files'=> true,'url' => $url, 'id'=>"addCustomerForm", "class"=>"uk-form-stacked", 'method' => 'post')) --}}
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
                                //	}
                                ?>
                                -->
                                
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
		                                <!--
                                                <a class="md-fab md-fab-accent" id="addKids" href="#" style="float:right">
								            <i class="material-icons">&#xE145;</i>
                                                </a>
                                                -->
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
																class="req">*</span></label> <br/>(MM/DD/YYYY)<br clear="all" />
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
                                                                                                                                    
                                                                                                                                        <div id="birthdayPaymentType" class="uk-grid" data-uk-grid-margin>
                                                                                                                                        <div class="uk-width-medium-1-2">
                                                                                                                                      		<div class="parsley-row">
                                                                                                                                                	<input type="radio" name="birthdayPaymentTypeRadio" required
                                                                                                                                                                accept="" id="birthdayPaymentOptions" value="card" /> <label
                                                                                                                                                                accesskey=""for="birthdayPaymentOptions" class="inline-label">Card</label> <input required
                                                                                                                                                                accesskey=""accept=""type="radio" name="birthdayPaymentTypeRadio" id="birthdayPaymentOptions1"
                                                                                                                                                                alt="" value="cash" /> <label for="birthdayPaymentOptions1"
                                                                                                                                                                accesskey=""class="inline-label">Cash</label> <input type="radio" required
                                                                                                                                                                accept=""name="birthdayPaymentTypeRadio" id="birthdayPaymentOptions2" value="cheque" />
                                                                                                                                                        <label for="birthdayPaymentOptions2" class="inline-label">Cheque</label>
                                                                                                                                                </div>
                                                                                                                                        </div>
                                                                                                                                        <div class="uk-width-medium-1-2">
                                                                                                                                                
                                                                                                                                        </div>
                                                                                                                                        </div>
                                                                                                                                   
                                                                                                                                
                                                                                                                                    
                                                                                                                                        <div id="birthdayPaymentType" style="width: 100%">
                                                                                                                                            <div id="birthdayCardDetailsDiv" class="uk-grid" data-uk-grid-margin style="display:none">
                                                                                                                                                <div class="uk-width-medium-1-1">
                                                                                                                                                    <h4>Card details</h4>
                                                                                                                                                </div>
                                                                                                                                                <div class="uk-width-medium-1-2">
                                                                                                                                                    <div class="parsley-row">
                                                                                                                                                        <select name="birthdayCardType" id="birthdayCardType"
                                                                                                                                                                accesskey=""class="input-sm md-input"
                                                                                                                                                                autocomplete=""class="form-control input-sm md-input"
                                                                                                                                                                contenteditable=""autofocus=""style='padding: 0px; font-weight: bold; color: #727272;'>
                                                                                                                                                            <option value="master">Master card</option>
                                                                                                                                                            <option value="maestro">Maestro</option>
                                                                                                                                                            <option value="visa">Visa</option>
                                                                                                                                                            <option value="Rupay">Rupay</option>
                                                                                                                                                        </select>
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="uk-width-medium-1-2">
                                                                                                                                                    <div class="parsley-row">
                                                                                                                                                        <label for="birthdayCard4digits" class="inline-label">Last 4 digits
                                                                                                                                                            of your card<span class="req">*</span>
                                                                                                                                                        </label> <input id="card4digits" number name="birthdayCard4digits"
                                                                                                                                                           accept=""maxlength="4" type="text"
                                                                                                                                                           accesskey=""class="form-control input-sm md-input" />
                                                                                                                                                    </div>
                                                                                                                                                </div>
									
                                                                                                                                                <div class="uk-width-medium-1-2">
                                                                                                                                                    <div class="parsley-row">
                                                                                                                                                        <label for="birthdayCardBankName" class="inline-label">Bank Name of your card<span class="req">*</span>
                                                                                                                                                        </label> <input id="birthdayCardBankName" number name="birthdayCardBankName"
                                                                                                                                                               accept=""type="text"
                                                                                                                                                               accesskey=""class="form-control input-sm md-input" />
                                                                                                                                                    </div>
                                                                                                                                                </div>
									
                                                                                                                                                <div class="uk-width-medium-1-2">
                                                                                                                                                    <div class="parsley-row">
                                                                                                                                                        <label for="birthdayCardRecieptNumber" class="inline-label">Reciept number<span class="req">*</span>
                                                                                                                                                        </label> <input id="cardRecieptNumber" number name="birthdayCardRecieptNumber"
                                                                                                                                                        accept=""maxlength="4" type="text" 
                                                                                                                                                        accesskey=""class="form-control input-sm md-input" />
                                                                                                                                                    </div>
                                                                                                                                                </div>

                                                                                                                                            </div>
                                                                                                                                            <div id="birthdayChequeDetails" class="uk-grid" data-uk-grid-margin style="display:none">

                                                                                                                                                <div class="uk-width-medium-1-1">
                                                                                                                                                    <h4>Cheque details</h4>
                                                                                                                                                    <br clear="all"/>
                                                                                                                                                </div>
                                                                                                                                                <br clear="all"/><br clear="all"/>
                                                                                                                                                <div class="uk-width-medium-1-2">
                                                                                                                                                    <div class="parsley-row">
                                                                                                                                                        <label for="birthdayChequeBankName" class="inline-label">Bank name<span
                                                                                                                                                                accesskey=""class="req">*</span></label> <input id="birthdayChequeBankName"
                                                                                                                                                                       accept=""name="birthdayBankName" type="text"
                                                                                                                                                                       accesskey=""class="form-control input-sm md-input" />
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                                <div class="uk-width-medium-1-2">
                                                                                                                                                    <div class="parsley-row">
                                                                                                                                                        <label for="birthdayChequeNumber" class="inline-label">Cheque number<span
                                                                                                                                                                accesskey=""class="req">*</span></label> <input id="birthdayChequeNumber"
                                                                                                                                                                accept=""name="birthdayChequeNumber" type="text"
                                                                                                                                                                accesskey=""class="form-control input-sm md-input" />
                                                                                                                                                    </div>
                                                                                                                                                </div>
                                                                                                                                            </div>                                                                     
                                                                                                                                        </div>
                                                                                                                                        
                                                                                                                                    
                                                                                                                                </tr>
                                                                                                                                <tr>
                                                                                                                                    <td colspan="2">
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
                                                                                                                                    <td></td>
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
                                                                            <div class="uk-width-medium-1-3" >
								                 <div class="parsley-row" style="display:none;">
								                 	<label for="reminderTxtBox">Reminder date<span class="req">*</span></label> 
								                 	{{Form::text('reminderTxtBox', null,array('id'=>'reminderTxtBox', 'required', 'class' => ''))}}								                 	
								                 </div>
								            </div>
								            <div class="uk-width-medium-1-3">
							                 <div class="parsley-row" style="display:none;">
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
                                           <li id="followupTab">
                                               <div class="uk-grid" data-uk-grid-margin>
		                           			<div  class="uk-width-small-1-1" id="followupMsg">
		                           			
		                           			</div>
                                                                  <br clear="all">
                                                                  
                                                                
                                                                  
                                                                  <div class="uk-width-small-1-1" id="Msg"></div>
                                                                  
                                                                     <div class="uk-width-medium-1-3">
                                                                              <div class="parsley-row">
                                                                            	<label for="SeasonsCbx">Seasons<span
										class="req">*</span></label><br>
                                                                               <select id="SeasonsCbx"
										name="SeasonsCbx" required
										class='SeasonsCbx form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										
                                                                              </select>
							        	      </div>
							            </div>
                                                                  <div class="uk-width-medium-1-3" >
                                                                         <div class="parsley-row">
										<label for="followupkidCbx">select kid<span
											class="req">*</span></label> <br><select id="followupkidCbx"
											name="followupkidCbx" required
											class="followupkidCbx form-control input-sm md-input"
											style="padding: 0px; font-weight: bold; color: #727272;">
											<option value=""></option>
										</select>
									 </div>
                                                                    </div>
                                                                   <div class="uk-width-small-1-1"></div>
                                                                  <br clear="all">
                                                                    <div class="uk-width-medium-1-3">
                                                                             <div class="parsley-row">
								                 	<label for="followupcalllTypeCbx">Type of call<span class="req">*</span></label>
								                 	<!-- array('' => '', 'followup' => 'Followup', 'attended_iv' => 'Attended IV', 'iv_no_show' => 'IV No show', 'missed_call' => 'Missed Call') -->
								                 	{{ Form::select('followupcalllTypeCbx', array('' => '', 'INQUIRY' => 'Inquiry', 'RETENTION' => 'Retention', 'COMPLAINTS' => 'Complaints',/*'ENROLLMENT' => 'Enrollment','PAYMENT'=>'Payment',*/  ), null ,array('id'=>'followupcalllTypeCbx', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
								             </div>
                                                                        <br>
                                                                    </div>  
                                                                  
                                                                    <div class="uk-width-medium-1-3">
                                                                             <div class="parsley-row">
								                 	<label for="followupTypeCbx">Followup Type<span class="req">*</span></label>
								                 	<!-- array('' => '', 'followup' => 'Followup', 'attended_iv' => 'Attended IV', 'iv_no_show' => 'IV No show', 'missed_call' => 'Missed Call') -->
								                 	{{ Form::select('followupTypeCbx', array('' => '', 'SETUPIV' => 'SetUpIV', //'CANCELIV' => 'CancelIV','ATTENDEDIV' => 'AttendedIV', 'RESCHEDULEIV' => 'RescheduleIV','CLOSEIV' => 'CloseIV',
                                                                                                                                 'REMINDER_CALL' => 'ReminderCall','FOLLOW_CALL' => 'Follow Call','CALL_SPOUSE' => 'Call Spouse','NOT_AVAILABLE' => 'Not Available','NOT_INTERESTED' => 'Not Interested',
                                                                                                                                 'ENROLLED' => 'Enrolled',), null ,array('id'=>'followupTypeCbx', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
								             </div>
                                                                        <br>
                                                                    </div>
                                                                    <div class="uk-width-medium-1-3">
                                                                             <div class="parsley-row">
								                 	<label for="followupQualityTypeCbx">Quality<span class="req">*</span></label>
								                 	<!-- array('' => '', 'followup' => 'Followup', 'attended_iv' => 'Attended IV', 'iv_no_show' => 'IV No show', 'missed_call' => 'Missed Call') -->
								                 	{{ Form::select('followupQualityTypeCbx', array('' => '', 'VERYINTERESTED' => 'Very Interested', 'INTERESTED' => 'Interested', 'NOTSURE' => 'Not Sure','NEEDTOCHECK'=>'Need To Check','NOTINTERESTED' => 'Not Interested',  ), null ,array('id'=>'followupQualityTypeCbx', 'class' => 'input-sm md-input',  "required", "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
								             </div>
                                                                        <br>
                                                                    </div>
                                                                    
                                                                  <!--
                                                                    <div class="uk-width-medium-1-3">
                                                                         <div class="parsley-row">
										<label for="actionCbx">Action<span
											class="req">*</span></label> <select id="actionCbx"
											name="actionCbx" required
											class="actionCbx form-control input-sm md-input"
											style="padding: 0px; font-weight: bold; color: #727272; width: 100%">
											<option value=""></option>
										</select>
									 </div>
                                                                    </div>
                                                                  -->
                                                                  
                                                                  <div class="uk-width-small-1-1"></div> 
                                                                <!-- for default view -->
                                                                   <div class="uk-width-medium-1-1" id="defaultfollowup">
                                                                        
                                                                        <div class="md-card">
                                                                            <div class="md-card-content">
                                                                                <div class="uk-overflow-container">
                                                                                    <table class="uk-table" id="followuptable"> 
                                                                                     <thead>
                                                                                         <tr>
                                                                                             <th class="uk-text-nowrap">KidName</th>
                                                                                             <th class="uk-text-nowrap">CallType</th>
                                                                                             <th class="uk-text-nowrap">IV/Birthday/Payment Date</th>
                                                                                             <th class="uk-text-nowrap">Followup</th>
                                                                                             <!--<th class="uk-text-nowrap">Description</th>-->
                                                                                             <th class="uk-text-nowrap">ReminderDate</th>
                                                                                             <th class="uk-text-nowrap">Action</th>
                                                                                            
                                                                                         </tr>
                                                                                     </thead>
                                                                                     <tbody>
                                                                                       <?php  if(isset($iv_data)){ for($i=0;$i<count($iv_data);$i++){?>
                                                                                         <tr>
                                                                                             <td>{{ $iv_data[$i]['student_name']}}</td>
                                                                                             <td>Inrovisit:{{ $iv_data[$i]['comment_data']['followup_type']}}</td>
                                                                                             <td> {{$iv_data[$i]['iv_date']}}</td>
                                                                                             <td>{{$iv_data[$i]['comment_data']['comment_type']}}</td>
                                                                                             <!--<td>{{$iv_data[$i]['comment_data']['log_text']}}</td>-->
                                                                                             <td>{{$iv_data[$i]['comment_data']['reminder_date']}}</td>
                                                                                             <td> <a href="#" class="btn btn-xs btn-warning" onclick="getivdata({{$iv_data[$i]['id']}})" >History</a>
                                                                                                   <a href="#" class="btn btn-xs btn-primary" onclick="selectstatus({{$iv_data[$i]['id']}},'{{$iv_data[$i]['comment_data']['followup_status']}}','{{$iv_data[$i]['comment_data']['comment_type']}}')">Edit</a>
                                                                                             </td>
                                                                                         </tr>
                                                                                         
                                                                                       <?php }} if(isset($birthday_data)){ for($i=0;$i<count($birthday_data);$i++){ ?>
                                                                                         <tr>
                                                                                             <td>{{ $birthday_data[$i]['student_name']}}</td>
                                                                                             <td>Birthday:{{$birthday_data[$i]['comment_data']['followup_type']}}</td>
                                                                                             <td>{{$birthday_data[$i]['birthday_party_date']}}</td>
                                                                                             <td>{{$birthday_data[$i]['comment_data']['comment_type']}}</td>
                                                                                             <!--<td>{{$birthday_data[$i]['comment_data']['log_text']}}</td>-->
                                                                                             <td>{{$birthday_data[$i]['comment_data']['reminder_date']}}</td>
                                                                                             <td> <a href="#" class="btn btn-xs btn-warning" onclick="getbirthdaydata({{$birthday_data[$i]['id']}})" >History</a>
                                                                                                   <a href="#" class="btn btn-xs btn-primary" onclick="selectbirthdaystatus({{$birthday_data[$i]['id']}},'{{$birthday_data[$i]['comment_data']['followup_status']}}','{{$birthday_data[$i]['comment_data']['comment_type']}}')">Edit</a>
                                                                                             </td>
                                                                                         </tr>
                                                                                       <?php }} ?>
                                                                                       <?php if(isset($complaint_data)){ for($i=0;$i<count($complaint_data);$i++){?> 
                                                                                         <tr>
                                                                                             <td>{{$complaint_data[$i]['student_name']}}</td>
                                                                                             <td>{{$complaint_data[$i]['comments']['followup_type']}}</td>
                                                                                             <td></td>
                                                                                             <td>{{$complaint_data[$i]['comments']['comment_type']}}</td>
                                                                                             <td>{{$complaint_data[$i]['comments']['reminder_date']}}</td>
                                                                                             <td>
                                                                                                 <a href="#" class="btn btn-xs btn-warning" onclick="getComplaintData({{$complaint_data[$i]['id']}})" >History</a>
                                                                                                 <a href="#" class="btn btn-xs btn-primary" onclick="selectComplaintstatus({{$complaint_data[$i]['id']}},'{{$complaint_data[$i]['comments']['followup_status']}}','{{$complaint_data[$i]['comments']['comment_type']}}')">Edit</a>
                                                                                             </td>
                                                                                         </tr>
                                                                                       <?php }}?>
                                                                                       <?php if(isset($retention_data)){ for($i=0;$i<count($retention_data);$i++){?> 
                                                                                         <tr>
                                                                                             <td>{{$retention_data[$i]['student_name']}}</td>
                                                                                             <td>{{$retention_data[$i]['comments']['followup_type']}}</td>
                                                                                             <td></td>
                                                                                             <td>{{$retention_data[$i]['comments']['comment_type']}}</td>
                                                                                             <td>{{$retention_data[$i]['comments']['reminder_date']}}</td>
                                                                                             <td>
                                                                                                 <a href="#" class="btn btn-xs btn-warning" onclick="getRetentionData({{$retention_data[$i]['id']}})" >History</a>
                                                                                                 <a href="#" class="btn btn-xs btn-primary" onclick="selectRetentionstatus({{$retention_data[$i]['id']}},'{{$retention_data[$i]['comments']['followup_status']}}','{{$retention_data[$i]['comments']['comment_type']}}')">Edit</a>
                                                                                             </td>
                                                                                         </tr>
                                                                                       <?php }}?>
                                                                                       <?php if(isset($inuiry_data)){ for($i=0;$i<count($inuiry_data);$i++){?> 
                                                                                         <tr>
                                                                                             <td></td>
                                                                                             <td>{{$inuiry_data[$i]['comments']['followup_type']}}</td>
                                                                                             <td></td>
                                                                                             <td>{{$inuiry_data[$i]['comments']['comment_type']}}</td>
                                                                                             <td>{{$inuiry_data[$i]['comments']['reminder_date']}}</td>
                                                                                             <td>
                                                                                                 <a href="#" class="btn btn-xs btn-warning" onclick="getInquiryData({{$inuiry_data[$i]['id']}})" >History</a>
                                                                                                 <a href="#" class="btn btn-xs btn-primary" onclick="selectInquirystatus({{$inuiry_data[$i]['id']}},'{{$inuiry_data[$i]['comments']['followup_status']}}','{{$inuiry_data[$i]['comments']['comment_type']}}')">Edit</a>
                                                                                             </td>
                                                                                         </tr>
                                                                                       <?php }}?>
                                                                                       <?php if(isset($enrollmentFollowupData)){ for($i=0;$i<count($enrollmentFollowupData);$i++){?> 
                                                                                         <tr>
                                                                                             <td>{{$enrollmentFollowupData[$i]['student_name']}}</td>
                                                                                             <td>{{$enrollmentFollowupData[$i]['comments']['followup_type']}}</td>
                                                                                             <td>{{$enrollmentFollowupData[$i]['payment_date']}}</td>
                                                                                             <td>{{$enrollmentFollowupData[$i]['comments']['comment_type']}}</td>
                                                                                             <td>{{$enrollmentFollowupData[$i]['comments']['reminder_date']}}</td>
                                                                                             <td>
                                                                                                 <a href="#" class="btn btn-xs btn-warning" onclick="getEnrollmentData({{$enrollmentFollowupData[$i]['id']}})" >History</a>
                                                                                                 <a href="#" class="btn btn-xs btn-primary" onclick="selectEnrollmetnstatus({{$enrollmentFollowupData[$i]['id']}},'{{$enrollmentFollowupData[$i]['comments']['followup_status']}}','{{$enrollmentFollowupData[$i]['comments']['comment_type']}}')">Edit</a>
                                                                                             </td>
                                                                                         </tr>
                                                                                       <?php }}?>
                                                                                       <?php if(isset($membership_followup_data)){ for($i=0;$i<count($membership_followup_data);$i++){?> 
                                                                                         <tr>
                                                                                             <td></td>
                                                                                             <td>{{$membership_followup_data[$i]['followup_type']}}</td>
                                                                                             <td>{{$membership_followup_data[$i]['membership_end_date']}}</td>
                                                                                             <td>{{$membership_followup_data[$i]['comment_type']}}</td>
                                                                                             <td>{{$membership_followup_data[$i]['reminder_date']}}</td>
                                                                                             <td>
                                                                                                 <a href="#" class="btn btn-xs btn-warning" onclick="viewhistory({{$membership_followup_data[$i]['membership_followup_id']}})" >History</a>
                                                                                                 <a href="#" class="btn btn-xs btn-primary" onclick="selectmembershipstatus({{$membership_followup_data[$i]['membership_followup_id']}},'{{$membership_followup_data[$i]['followup_status']}}','{{$membership_followup_data[$i]['comment_type']}}')">Edit</a>
                                                                                             </td>
                                                                                         </tr>
                                                                                       <?php }}?>
                                                                                     </tbody>
                                                                                     </table>
                                                                                  </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                 
                                                                <!-- SET UP INTROVISIT -->     	
                                                                  <div class="uk-width-medium-1-3 introvisitfollowup" id="introvisitfollowup" style="display:none">
								          	<div class="parsley-row">
										<label for="eligibleClassesCbx">Eligible Classess<span
											class="req">*</span></label> <select id="eligibleClassIntro"
											name="eligibleClassesCbx" required
											class="eligibleClassesCbx form-control input-sm md-input"
											style="padding: 0px; font-weight: bold; color: #727272; width: 100%">
											<option value=""></option>
										</select>
								        	</div>
								        </div>
                                                                       
								   <div class="uk-width-medium-1-3 introvisitfollowup" id="introvisitfollowup" style="display:none">
								            	<div class="parsley-row">
										<label for="introbatchCbx">Batch<span class="req">*</span></label>
										<select id="introbatchCbx" name="introbatchCbx" required
											class='form-control input-sm md-input'
											style="padding: 0px; font-weight: bold; color: #727272;">
											<option value=""></option>
										</select>
								          	</div>
								        </div>
								<div class="uk-width-medium-1-3 introvisitfollowup" id="introvisitfollowup" style="display:none">
									<div class="parsley-row" style="margin-top: -20px">
										<label for="introVisitTxtBox">Introductory visit date<span
											class="req">*</span></label> <br>
										{{Form::text('introVisitTxtBox',
										null,array('id'=>'introVisitTxtBox', 'required'=>'', 'class' =>
										''))}}
									</div>
								</div>
                                                                  <div class="uk-width-medium-1-1 introvisitfollowup" id="introvisitfollowup" style="display:none">
                                                                      <div class="parsley-row">
										<label for="customerCommentTxt">Comment<span class="req">*</span></label>
										{{ Form::textarea('customerCommentTxt', null,
										['id'=>'customerCommentTxt', 'size' => '50x2',
										'class' => 'form-control input-sm md-input']) }}
									</div>
									<br>
                                                                  </div>
                                                                          <div class="uk-width-medium-1-3 introvisitfollowup" id="introvisitfollowup" style="display:none"></div>
                                                                          <div class="uk-width-medium-1-3 introvisitfollowup" id="introvisitfollowup" style="display:none"></div>
                                                                          <div class="uk-width-medium-1-3 introvisitfollowup" id="introvisitfollowup" style="display:none">
                                                                              <button type="submit" class="btn btn-primary introvisitfollowup" style="display:none; float:right"
										id="addIntroVisitSubmit">Add Introductory Visit</button>            
                                                                          </div>
                                                                          
                                                                          
                                                                          
                                                                  <!-- SET UP INTROVISIT END-->
                                                                  
                                                                  <!-- Other Followups Starts from here-->
                                                                  <div class="uk-width-medium-1-1 otherFollowups" id="otherFollowups" style="display:none">
							                 <div class="parsley-row">
								           <label for="commentsTxt">Comments<span class="req">*</span></label>
							                	{{ Form::textarea('otherCommentTxtarea', null, ['id'=>'otherCommentTxtarea',
								                'size' => '60x3', 'class' => 'form-control input-sm md-input'])
							                       	}}
							                 </div>
					                          </div>
                                                                  <div class="uk-width-medium-1-1 otherFollowups" id="otherFollowups" style="display:none">
							          &nbsp;
                                                                  </div>
                                                                  <div class="uk-width-medium-1-1 otherFollowups" id="otherFollowups" style="display:none">
							          &nbsp;
                                                                  </div>
                                                                  <div class="uk-width-medium-1-3 otherFollowups" id="reminderfollowupDate" style="display:none">
							        	<div class="parsley-row" style="margin-top: -23px;">
								        	<label for="remindDate">Reminder-date<span
										class="req">*</span></label><br>
									      {{Form::text('remindDate',
									       null,array('id'=>'remindDate', 'required'=>'','class' =>
									      'uk-form-width-medium'))}}

								        </div>
							
                                                                   </div>
                                                                   <div class="uk-width-medium-1-3 otherFollowups" id='otherFollowups' style="display:none"></div>
                                                                 
                                                                  <div class="uk-width-medium-1-3 otherFollowups" id='otherFollowups' style="display:none">
                                                                  
                                                                      <button type="submit" class="btn btn-primary otherFollowups" style="display:none; float:right"
										id="addOtherFollowupSubmit">Add Followups</button> 
                                                                  </div>
                                                                  
                                                                          
                                                                  
                                                                  
                                                                  <!-- Other Followups Ends Here-->
                                                                  
                                                                  
                                                                       </div> 
								         </div>
		                           		<br clear="all"/>
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
				                 	<label for="nickname">Nickname</label>
				                 	{{Form::text('nickname', null,array('id'=>'nickname',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row" style="margin-top:-30px;">
				                 	<label for="studentDob">Date of birth</label> <br/>(MM/DD/YYYY)
				                 	{{Form::text('studentDob', null,array('id'=>'studentDob', 'class' => '','required'))}}
				                 </div>
				            </div>		
				            		            				            
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="studentGender">Gender</label>
				                 	<select id="studentGender" name="studentGender" class="form-control input-sm md-input" style="padding:0px; font-weight:bold;color: #727272;">
				                 		<option value=""></option>
				                 		<option value="male">Male</option>
				                 		<option value="female">Female</option>				                 	
				                 	</select>
				                 	
				                 </div>
				            </div>	
				        	
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="location">Location</label>
				                 	{{Form::text('location', null,array('id'=>'location',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>	
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="school">School</label>
				                 	{{Form::text('school', null,array('id'=>'school',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>		             				            
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="hobbies">Hobbies</label>
				                 	{{Form::hidden('hobbies', 'Playing',array('id'=>'hobbies',  'class' => 'form-control input-sm md-input','style'=>''))}}
				                 </div>
				            </div>				        	
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="emergencyContact">Emergency contact</label>
				                 	{{Form::number('emergencyContact', null,array('id'=>'emergencyContact',  'class' => 'form-control input-sm md-input', 'style'=>'padding:0px'))}}
				                 </div>
				            </div>
			             	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="remarks">Remarks</label>
				                 	{{Form::text('remarks', null,array('id'=>'remarks', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>			            
				        </div>
				        <div class="uk-grid" data-uk-grid-margin>				        
				        	<div class="uk-width-medium-1-1">
				                 <div class="parsley-row">
				                 	<label for="healthIssue">Health Issues</label>
				                 	{{ Form::textarea('healthIssue', null, ['id'=>'healthIssue', 'size' => '10x3', 'class' => 'form-control input-sm md-input']) }}
				                 </div>
				            </div>
				        </div>
				        <div class="uk-grid">
                            <div class="uk-width-1-1">
                                <button type="submit" id="saveKidsBtn" class="md-btn md-btn-primary">Add Student</button>
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
                                            <div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="customerName">Customer Firstname<span class="req">*</span></label>
				                 	{{Form::text('customerName', "$customer->customer_name",array('id'=>'customerName', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
                                            <div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="customerLastName">Customer Lastname<span class="req">*</span></label>
				                 	{{Form::text('customerLastName', "$customer->customer_lastname",array('id'=>'customerLastName', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
                                        </div>
			      	 	<div class="uk-grid" data-uk-grid-margin>
			             
				            <div class="uk-width-medium-1-2">
				                 <div class="parsley-row">
				                 	<label for="customerEmail">Customer email<span class="req">*</span></label>
				                 	{{Form::email('customerEmail', $customer->customer_email,array('id'=>'customerEmail', 'required', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-2">
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
				                 	<label for="building">Building</label>
				                 	{{Form::text('building', $customer->building,array('id'=>'building',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="apartment">Apartment</label>
				                 	{{Form::text('apartment', $customer->apartment_name,array('id'=>'apartment',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>	
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="lane">Lane</label>
				                 	{{Form::text('lane', $customer->lane,array('id'=>'lane',  'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>			             				            
				        </div>
				        <br clear="all"/><br clear="all"/>
				        <div class="uk-grid" data-uk-grid-margin>
				        	<div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="locality">Locality</label>
				                 	{{Form::text('locality', $customer->locality,array('id'=>'locality', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>				        	
				        	 <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="state">State</label>
				                 	{{ Form::select('state', array('' => '') + $provinces, $customer->state ,array('id'=>'state', 'class' => 'input-sm md-input', "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>
				            <div class="uk-width-medium-1-3">
				                 <div class="parsley-row">
				                 	<label for="state">City</label>
				                 	{{ Form::select('city', array('' => ''), $customer->city ,array('id'=>'city', 'class' => 'input-sm md-input',   "placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>		            
				        </div>
				        <div class="uk-grid" data-uk-grid-margin>				        
				        	<div class="uk-width-medium-1-3"> 
				                  <div class="parsley-row">
				                 	<label for="zipcode">Zipcode</label>
				                 	{{Form::text('zipcode', $customer->zipcode,array('id'=>'zipcode', 'class' => 'form-control input-sm md-input'))}}
				                 </div>
				            </div>    
				            <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	<label for="source">Source</label>
				                 	
				                 	{{ Form::select('source', array('' => '', 'word of moutn' => 'Word of Mouth', 'grass roots' => 'Grassroots', 'walkin' => 'Walkin', 'events' => 'Events'), $customer->source ,array('id'=>'source', 'class' => 'input-sm md-input',"placeholder"=>"Institution type", "style"=>'padding:0px; font-weight:bold;color: #727272;')) }}
				                 </div>
				            </div>  
				            <div class="uk-width-medium-1-3">    
				                  <div class="parsley-row">
				                 	<label for="referredBy">Referred by</label>
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



<!-- for pending birthday modal -->
<div id="pendingamountpay" class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;">
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="pendingamountpayheader">
          
            <h4 class="modal-title">PendingAmountPay</h4>
        </div>
        <div class="modal-body" id="pendingamountpaybody">
            <div class="msg" id="msg"></div>
            <h4>Existing Order</h4>
            <table  id="pendingamounttable" class="uk-table table-striped table-condensed"> 
                <thead> 
                    <tr>
                        <th>Particulars</th>
                        <th>Qty</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tr>    
                    <td>
                        Additional No of guests
                    </td>    
                    <td>
                        
                        <input type="number" id="additionalguestNo" class="additionalguestNo form-control input-sm " name="additionalguestNo" readonly value="0"/>
                    </td>
                    <td>
                        <input type="number" id="additionalguestcost" class="additionalguestcost form-control input-sm " name="additionalguestcost" readonly value="0"/>
                    </td>
                </tr>
                <tr>    
                    <td>
                        Additional Half Hours
                    </td>    
                    <td>
                        <input type="number" id="additionalhalfhours" class="additionalhalfhours form-control input-sm " name="additionalhalfhours" readonly value="0" />
                    </td>
                    <td>
                        <input type="number" id="additionalhalfhourscost" class="additionalhalfhourscost form-control input-sm " name="additionalhalfhourscost" readonly value="0">
                    </td>
                </tr>
                <tr>
                    <td>Advance paid</td>
                    <td></td>
                    <td><input type="number" id="advancepaid" class="advancepaid form-control input-sm " name="advancepaid" readonly value="0"></td>
                </tr>
                <tr>
                    <td>Amount Pending</td>
                    <td></td>
                    <td><input type="number" id="amountpending" class="advancepaid form-control input-sm " name="advancepaid" readonly value="0"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>Tax Amount</td>
                    <td><input type="number" id="taxamount" class="taxamount form-control input-sm " name="taxamount" readonly value="0"></td>
                </tr>
                <tr>
                    <td></td>
                    <td>TotalAmount Pending</td>
                    <td><input type="number" id="amountPendingAfterTax" class="amountPendingAfterTax form-control input-sm " name="amountPendingAfterTax" readonly value="0"></td>
                </tr>
                <tr>
                    <td> 
                        <div class="parsley-row"> 
                        <input type="checkbox" class="" id="changeorder" name="changeorder"/>
                        <label class="">change order</label> 
                         </div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        <div  class="uk-grid" data-uk-grid-margin>
              <div class="uk-width-medium-1-1" >
               <h4>Select Payment Type</h4>
              </div>
            </div>
         <div id="receiveBirthdayPaymentType" class="uk-grid" data-uk-grid-margin>     
            <div class="uk-width-medium-1-3">
                <div class="parsley-row">
                    <input type="radio" name="birthdayPaymentReceiveTypeRadio" required
                        id="birthdayPaymentOptionsReceive_1" value="card" /> 
                    <label for="birthdayPaymentOptionsReceive_1" class="inline-label">Card</label> 
                   <input type="radio" name="birthdayPaymentReceiveTypeRadio" id="birthdayPaymentOptionsReceive_2"
                        value="cash" /> 
                    <label for="birthdayPaymentOptionsReceive_2" class="inline-label">Cash</label>
                    <input type="radio" name="birthdayPaymentReceiveTypeRadio" id="birthdayPaymentOptionsReceive_3" value="cheque" />
                    <label for="birthdayPaymentOptionsReceive_3" class="inline-label">Cheque</label>
                </div>
            </div>
            <div class="uk-width-medium-1-3">
                <input type="hidden" id="birthdayPending_id" value=""/>
                <input type="hidden" id="birthdaypending_amt" value=""/>
            </div>
         </div>
          
           <div id="birthdayPaymentReceiveType" style="width: 100%">
		<div id="receiveBirthdayCardDetailsDiv" class="uk-grid" data-uk-grid-margin>
		    <div class="uk-width-medium-1-1">
                        <h4>Card details</h4>
                    </div>
                    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <select name="birthdayReceivecardType" id="birthdayReceivecardType"
				class="input-sm md-input"
				class="form-control input-sm md-input"
			        style='padding: 0px; font-weight: bold; color: #727272;'>
                                <option value="master">Master card</option>
				<option value="maestro">Maestro</option>
				<option value="visa">Visa</option>
                                <option value="rupay">Rupay</option>
                            </select>
			</div>
                    </div>
                    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <label for="birthdayCard4digits" class="inline-label">Last 4 digits
                               of your card<span class="req">*</span>
			    </label> 
                            <input id="birthdayReceivecard4digits" number name="birthdayReceivecard4digits"
			    maxlength="4" type="text" class="form-control input-sm md-input" />
			</div>
		    </div>
	            <br clear="all"/><br clear="all"/>						
                    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <label for="birthdayReceivecardBankName" class="inline-label">Bank Name of your card<span class="req">*</span>
			    </label> 
                            <input id="birthdayReceivecardBankName" number name="birthdayReceivecardBankName"	 type="text"
			     class="form-control input-sm md-input" />
			</div>
		    </div>
									
		    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
			    <label for="birthdayReceivecardRecieptNumber" class="inline-label">Reciept number<span class="req">*</span>
			    </label> 
                            <input id="birthdayReceivecardRecieptNumber" number name="birthdayReceivecardRecieptNumber"
				 type="text" class="form-control input-sm md-input" />
                        </div>
                    </div>

		</div>
                <div id="birthdayReceiveChequeDetailsDiv" class="uk-grid" data-uk-grid-margin>

                    <div class="uk-width-medium-1-1">
			<h4>Cheque details</h4>
                            <br clear="all"/>
                    </div>
                    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <label for="birthdayReceivechequeBankName" class="inline-label">Bank name<span
				class="req">*</span></label> <input id="birthdayReceivechequeBankName"
				name="birthdayReceivebankName" type="text"
                                accept=""class="form-control input-sm md-input" />
			</div>
                    </div>
		    <div class="uk-width-medium-1-2">
                        <div class="parsley-row">
                            <label for="birthdayReceivechequeNumber" class="inline-label">Cheque number<span
				class="req">*</span></label> <input id="birthdayReceivechequeNumber"
				name="birthdayReceivechequeNumber" type="text"
				class="form-control input-sm md-input" />
                        </div>
		    </div>
		</div>
								
								
                <div id="birthdayReceiveemailEnrollPrintDiv" class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
			<h4>Invoice option</h4>
		    </div>
		    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <input id="birthdayReceiveinvoicePrintOption" name="birthdayReceiveinvoicePrintOption"  value="yes"  type="checkbox"  class="checkbox-custom" />
                                <label for="birthdayReceiveinvoicePrintOption"  class="checkbox-custom-label">Print Invoice<span
                                    class="req">*</span></label> 
			</div>
                    </div>
                    <div class="uk-width-medium-1-2" id='birthdayReceiveemail' style="display:none">
			<div class="parsley-row">
                            <input id="birthdayReceiveemailOption" name="birthdayReceiveemailOption" type="checkbox"  value="yes" class="checkbox-custom"  />
				<label for="birthdayReceiveemailOption" class="checkbox-custom-label">Email Invoice<span
                                    class="req">*</span></label> 
                        </div>
                    </div>
		</div>

            </div>

            
            
            
        </div>
          <div class="modal-footer" id="pendingamountpayfooter">
          <button type="button" id="pendingamountpayadd" class="btn btn-primary" >Receive pending amount</button>
          <button type="button" id="pendingclose" class="btn btn-default" >Close</button>
          
        </div>
      </div>
      
    </div>
  </div>


 <!-- history iv Modal -->
  <div id='ivhistory' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="ivhistoryheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Introvisit History</h4>
        </div>
        <div class="modal-body" id="ivhistorybody">
          <p></p>
        </div>
        <div class="modal-footer" id="ivhistoryfooter">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
 
 
 
 
 
<!-- Edit Introvisit Modal Div  -->
<div id="editIntrovisitModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="editIntrovisitModalTitle" class="modal-title">
					Edit Introvisit
				</h4>
			</div>
			<div class="modal-body">
				
				<div id="introVisitEditMessage"></div> <br clear="all" /> <br
						clear="all" />
				
				 <div id="ivEditForm">
                                         <div class="uk-width-small-1-1">
                                             <div class="parsley-row">
                                                 <input type="hidden" id="iv_id" value=""/>
                                             </div>
                                         </div>
                                     <div class="uk-grid" data-uk-grid-margin>
                                         
                                        <div class="uk-width-medium-1-3" id="introvisitEditDiv">
						<div class="parsley-row">
							<label for="iveditstatusSelect" class="inline-label">FollowupType<span
												class="req">*</span></label>
							<select name="iveditstatusSelect" id="iveditstatusSelect" class="input-sm md-input" data-iveditid=""  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value="ACTIVE/SCHEDULED">Active/Scheduled</option>
								<option value="ATTENDED/CELEBRATED">Attended</option>
								<option value="REMINDER_CALL">Reminder Call</option>
                                                                <option value="FOLLOW_CALL">Follow Call</option>
                                                                <option value="CALL_SPOUSE">Call Spouse</option>
                                                                <option value="NOT_AVAILABLE">Not Available</option>
                                                                <option value="NOT_INTERESTED">Not Interested</option>
                                                                <option value="ENROLLED">Enrolled</option>
                                                                
							</select>
						</div>
					</div> 
                                        <div class="uk-width-medium-1-3" id="introvisitEditDiv">
						<div class="parsley-row">
							<label for="iveditActionSelect" class="inline-label">Quality<span
												class="req">*</span></label>
							<select name="iveditActionSelect" id="iveditActionSelect" class="input-sm md-input" data-iveditid=""  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value=" "></option>
                                                                <option value="VERYINTERESTED">Very Interested</option>
								<option value="INTERESTED">Interested</option>
								<option value="NOTSURE">Not Sure</option>
                                                                <option value="NEEDTOCHECK">Need To Check</option>
								<option value="NOTINTERESTED">Not Interested</option>
                                                        </select>
						</div>
					</div>
                                         
                                         <div class="uk-width-medium-1-3" id="reminderDate">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="Reminder-date">Reminder-date<span
										class="req">*</span></label><br>
									{{Form::text('Reminder-date',
									null,array('id'=>'Reminder-date', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                         
                                  </div>
                                     <!--
                                     <div class="uk-grid" data-uk-grid-margin id="">
                                         <div class="uk-width-medium-1-3" id="reschedule" style="display:none">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="Reschedule-date">Reschedule-date<span
										class="req">*</span></label><br>
									{{Form::text('Reschedule-date',
									null,array('id'=>'Reschedule-date', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                     </div>
                                     -->
                                     
                            </div>
                            <br>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1" id="comment_hide">
							<div class="parsley-row">
								<label for="healthIssue">Comments<span class="req">*</span></label>
								{{ Form::textarea('ivcustomerCommentTxtarea', null, ['id'=>'ivcustomerCommentTxtarea',
								'size' => '60x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
						</div>
					</div>
				</div>	
				
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="saveIntroVisitBtn">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div>

	</div>
</div>
<!-- Edit Introvisit Modal Div -->



<!-- Edit Birthday Modal Div  -->
<div id="editBirthdayModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="editBirthdayModalTitle" class="modal-title">
					Edit Birthday Followup
				</h4>
			</div>
			<div class="modal-body">
				
				<div id="birthdayEditMessage"></div> <br clear="all" /> <br
						clear="all" />
				
				 <div id="birthdayEditForm">
                                         <div class="uk-width-small-1-1">
                                             <div class="parsley-row">
                                                 <input type="hidden" id="birthday_id" value=""/>
                                             </div>
                                         </div>
                                     <div class="uk-grid" data-uk-grid-margin>
                                         
                                        <div class="uk-width-medium-1-3" id="birthdayEditDiv">
						<div class="parsley-row">
							<label for="biryhdayeditstatusSelect" class="inline-label">FollowupType<span
												class="req">*</span></label>
							<select name="birthdayeditstatusSelect" id="birthdayeditstatusSelect" class="input-sm md-input"  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value="ACTIVE/SCHEDULED">Active/Scheduled</option>
								<option value="REMINDER_CALL">Reminder Call</option>
                                                                <option value="FOLLOW_CALL">Follow Call</option>
                                                                <option value="CALL_SPOUSE">Call Spouse</option>
                                                                <option value="NOT_AVAILABLE">Not Available</option>
                                                                <option value="NOT_INTERESTED">Not Interested</option>
                                                                <option value="ATTENDED/CELEBRATED">Celebrated</option>
							</select>
						</div>
					</div> 
                                        <div class="uk-width-medium-1-3" id="birthdayEditDiv">
						<div class="parsley-row">
							<label for="birthdayActionSelect" class="inline-label">Quality<span
												class="req">*</span></label>
							<select name="birthdayActionSelect" id="birthdayActionSelect" class="input-sm md-input" data-iveditid=""  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value=" "></option>
                                                                <option value="VERYINTERESTED">Very Interested</option>
								<option value="INTERESTED">Interested</option>
								<option value="NOTSURE">Not Sure</option>
                                                                <option value="NEEDTOCHECK">Need To Check</option>
								<option value="NOTINTERESTED">Not Interested</option>
                                                        </select>
						</div>
					</div>
                                         
                                         <div class="uk-width-medium-1-3" id="birthdayreminder">
								<div class="parsley-row" id="birthdayreminder" style="margin-top: -23px;">
									<label for="birthdayReminderDate">Reminder-date<span
										class="req">*</span></label><br>
									{{Form::text('birthdayReminderDate',
									null,array('id'=>'birthdayReminderDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                         
                                  </div>
                                     <!--
                                     <div class="uk-grid" data-uk-grid-margin id="">
                                         <div class="uk-width-medium-1-3" id="reschedule" style="display:none">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="Reschedule-date">Reschedule-date<span
										class="req">*</span></label><br>
									{{Form::text('Reschedule-date',
									null,array('id'=>'Reschedule-date', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                     </div>
                                     -->
                                     
                            </div>
                            <br>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1" id="comment_hide">
							<div class="parsley-row">
								<label for="birthdaycomment">Comments<span class="req">*</span></label>
								{{ Form::textarea('birthdayCommentArea', null, ['id'=>'birthdayCommentArea',
								'size' => '60x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
						</div>
					</div>
				</div>	
				
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="saveBirthdayBtn">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div>

	</div>
</div>
<!-- Edit Birthday Modal Div -->



<!-- Edit Complaint Modal Div  -->
<div id="editComplaintModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="editComplaintModalTitle" class="modal-title">
					Edit Complaint
				</h4>
			</div>
			<div class="modal-body">
				
				<div id="complaintEditMessage"></div> <br clear="all" /> <br
						clear="all" />
				
				 <div id="complaintEditForm">
                                         <div class="uk-width-small-1-1">
                                             <div class="parsley-row">
                                                 <input type="hidden" id="complaint_id" value=""/>
                                             </div>
                                         </div>
                                     <div class="uk-grid" data-uk-grid-margin>
                                         
                                        <div class="uk-width-medium-1-3" id="complaintEditDiv">
						<div class="parsley-row">
							<label for="complaintstatusSelect" class="inline-label">FollowupType<span
												class="req">*</span></label>
							<select name="complaintstatusSelect" id="complainteditstatusSelect" class="input-sm md-input" data-iveditid=""  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value="ACTIVE/SCHEDULED">Active/Scheduled</option>
						<!--		<option value="ATTENDED/CELEBRATED">Attended</option> -->
								<option value="REMINDER_CALL">Reminder Call</option>
                                                                <option value="FOLLOW_CALL">Follow Call</option>
                                                                <option value="CALL_SPOUSE">Call Spouse</option>
                                                                <option value="NOT_AVAILABLE">Not Available</option>
                                                <!--            <option value="NOT_INTERESTED">Not Interested</option> -->
                                                                <option value="CLOSE_CALL">Close Call</option>
                                                                
							</select>
						</div>
					</div> 
                                        <div class="uk-width-medium-1-3" id="complaintEditDiv">
						<div class="parsley-row">
							<label for="complainteditActionSelect" class="inline-label">Quality<span
												class="req">*</span></label>
							<select name="complainteditActionSelect" id="complainteditActionSelect" class="input-sm md-input"  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value=" "></option>
                                                                <option value="VERYINTERESTED">Very Interested</option>
								<option value="INTERESTED">Interested</option>
								<option value="NOTSURE">Not Sure</option>
                                                                <option value="NEEDTOCHECK">Need To Check</option>
								<option value="NOTINTERESTED">Not Interested</option>
                                                        </select>
						</div>
					</div>
                                         
                                         <div class="uk-width-medium-1-3" id="remDate">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="rDate">Reminder-date<span
										class="req">*</span></label><br>
									{{Form::text('rDate',
									null,array('id'=>'rDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                         
                                  </div>
                                     
                            </div>
                            <br>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1" id="comment">
							<div class="parsley-row">
								<label for="complaint">Comments<span class="req">*</span></label>
								{{ Form::textarea('complaintcustomerCommentTxtarea', null, ['id'=>'complaintcustomerCommentTxtarea',
								'size' => '60x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
						</div>
					</div>
				</div>	
				
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="saveComplaintBtn">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div>

	</div>
</div>
<!-- Edit COmplaint Modal Div -->



<!-- Edit Retention  Modal Div  -->
<div id="editRetentionModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="editRetentionModalTitle" class="modal-title">
					Edit Retention
				</h4>
			</div>
			<div class="modal-body">
				
				<div id="retentionEditMessage"></div> <br clear="all" /> <br
						clear="all" />
				
				 <div id="retentionEditForm">
                                         <div class="uk-width-small-1-1">
                                             <div class="parsley-row">
                                                 <input type="hidden" id="retention_id" value=""/>
                                             </div>
                                         </div>
                                     <div class="uk-grid" data-uk-grid-margin>
                                         
                                        <div class="uk-width-medium-1-3" id="retentionEditDiv">
						<div class="parsley-row">
							<label for="retentionstatusSelect" class="inline-label">FollowupType<span
												class="req">*</span></label>
							<select name="retentionstatusSelect" id="retentioneditstatusSelect" class="input-sm md-input"   class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value="ACTIVE/SCHEDULED">Active/Scheduled</option>
						<!--		<option value="ATTENDED/CELEBRATED">Attended</option> -->
								<option value="REMINDER_CALL">Reminder Call</option>
                                                                <option value="FOLLOW_CALL">Follow Call</option>
                                                                <option value="CALL_SPOUSE">Call Spouse</option>
                                                                <option value="NOT_AVAILABLE">Not Available</option>
                                                <!--            <option value="NOT_INTERESTED">Not Interested</option> -->
                                                                <option value="CLOSE_CALL">Close Call</option>
                                                                
							</select>
						</div>
					</div> 
                                        <div class="uk-width-medium-1-3" id="retentionEditDiv">
						<div class="parsley-row">
							<label for="retentioneditActionSelect" class="inline-label">Quality<span
												class="req">*</span></label>
							<select name="retentioneditActionSelect" id="retentioneditActionSelect" class="input-sm md-input"  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value=" "></option>
                                                                <option value="VERYINTERESTED">Very Interested</option>
								<option value="INTERESTED">Interested</option>
								<option value="NOTSURE">Not Sure</option>
                                                                <option value="NEEDTOCHECK">Need To Check</option>
								<option value="NOTINTERESTED">Not Interested</option>
                                                        </select>
						</div>
					</div>
                                         
                                         <div class="uk-width-medium-1-3" id="renDate">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="rmDate">Reminder-date<span
										class="req">*</span></label><br>
									{{Form::text('rmDate',
									null,array('id'=>'rmDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                         
                                  </div>
                                     
                            </div>
                            <br>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1" id="comment">
							<div class="parsley-row">
								<label for="retention">Comments<span class="req">*</span></label>
								{{ Form::textarea('retentioncustomerCommentTxtarea', null, ['id'=>'retentioncustomerCommentTxtarea',
								'size' => '60x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
						</div>
					</div>
				</div>	
				
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="saveRetentionBtn">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div>

	</div>
</div>
<!-- Edit Retention Modal Div -->



<!-- Edit Inquiry  Modal Div  -->
<div id="editInquiryModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="editInquiryModalTitle" class="modal-title">
					Edit Inquiry
				</h4>
			</div>
			<div class="modal-body">
				
				<div id="InquiryEditMessage"></div> <br clear="all" /> <br
						clear="all" />
				
				 <div id="InquiryEditForm">
                                         <div class="uk-width-small-1-1">
                                             <div class="parsley-row">
                                                 <input type="hidden" id="inquiry_id" value=""/>
                                             </div>
                                         </div>
                                     <div class="uk-grid" data-uk-grid-margin>
                                         
                                        <div class="uk-width-medium-1-3" id="inquiryEditDiv">
						<div class="parsley-row">
							<label for="inquirystatusSelect" class="inline-label">FollowupType<span
												class="req">*</span></label>
							<select name="inquirystatusSelect" id="inquiryeditstatusSelect" class="input-sm md-input"   class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value="ACTIVE/SCHEDULED">Active/Scheduled</option>
						<!--		<option value="ATTENDED/CELEBRATED">Attended</option> -->
								<option value="REMINDER_CALL">Reminder Call</option>
                                                                <option value="FOLLOW_CALL">Follow Call</option>
                                                                <option value="CALL_SPOUSE">Call Spouse</option>
                                                                <option value="NOT_AVAILABLE">Not Available</option>
                                                <!--            <option value="NOT_INTERESTED">Not Interested</option> -->
                                                                <option value="CLOSE_CALL">Close Call</option>
                                                                
							</select>
						</div>
					</div> 
                                        <div class="uk-width-medium-1-3" id="retentionEditDiv">
						<div class="parsley-row">
							<label for="inquiryeditActionSelect" class="inline-label">Quality<span
												class="req">*</span></label>
							<select name="inquiryeditActionSelect" id="inquiryeditActionSelect" class="input-sm md-input"  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value=" "></option>
                                                                <option value="VERYINTERESTED">Very Interested</option>
								<option value="INTERESTED">Interested</option>
								<option value="NOTSURE">Not Sure</option>
                                                                <option value="NEEDTOCHECK">Need To Check</option>
								<option value="NOTINTERESTED">Not Interested</option>
                                                        </select>
						</div>
					</div>
                                         
                                         <div class="uk-width-medium-1-3" id="remdDate">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="rmdDate">Reminder-date<span
										class="req">*</span></label><br>
									{{Form::text('rmdDate',
									null,array('id'=>'rmdDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                         
                                  </div>
                                     
                            </div>
                            <br>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1" id="comments">
							<div class="parsley-row">
								<label for="inquiry">Comments<span class="req">*</span></label>
								{{ Form::textarea('inquirycustomerCommentTxtarea', null, ['id'=>'inquirycustomerCommentTxtarea',
								'size' => '60x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
						</div>
					</div>
				</div>	
				
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="saveInquiryBtn">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div>

	</div>
</div>
<!-- Edit Inquiry Modal Div -->


<!-- Edit Enrollment  Modal Div  -->
<div id="editEnrollmentModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="editEnrollmentModalTitle" class="modal-title">
					Edit Enrollment
				</h4>
			</div>
			<div class="modal-body">
				
				<div id="enrollmentEditMessage"></div> <br clear="all" /> <br
						clear="all" />
				
				 <div id="enrollmentEditForm">
                                         <div class="uk-width-small-1-1">
                                             <div class="parsley-row">
                                                 <input type="hidden" id="payment_due_id" value=""/>
                                             </div>
                                         </div>
                                     <div class="uk-grid" data-uk-grid-margin>
                                         
                                        <div class="uk-width-medium-1-3" id="enrollmentEditDiv">
						<div class="parsley-row">
							<label for="enrollmentstatusSelect" class="inline-label">FollowupType<span
												class="req">*</span></label>
							<select name="enrollmentstatusSelect" id="enrollmenteditstatusSelect" class="input-sm md-input"   class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
						<!--		<option value="ACTIVE/SCHEDULED">Active/Scheduled</option>
								<option value="ATTENDED/CELEBRATED">Attended</option> -->
								<option value="REMINDER_CALL">Reminder Call</option>
                                                                <option value="FOLLOW_CALL">Follow Call</option>
                                                                <option value="CALL_SPOUSE">Call Spouse</option>
                                                                <option value="NOT_AVAILABLE">Not Available</option>
                                                                <option value="NOT_INTERESTED">Not Interested</option> 
                                                                <option value="CLOSE_CALL">Close Call</option>
                                                                
							</select>
						</div>
					</div> 
                                        <div class="uk-width-medium-1-3" id="enrollmentEditDiv">
						<div class="parsley-row">
							<label for="enrollmenteditActionSelect" class="inline-label">Quality<span
												class="req">*</span></label>
							<select name="enrollmenteditActionSelect" id="enrollmenteditActionSelect" class="input-sm md-input"  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value=" "></option>
                                                                <option value="VERYINTERESTED">Very Interested</option>
								<option value="INTERESTED">Interested</option>
								<option value="NOTSURE">Not Sure</option>
                                                                <option value="NEEDTOCHECK">Need To Check</option>
								<option value="NOTINTERESTED">Not Interested</option>
                                                        </select>
						</div>
					</div>
                                         
                                         <div class="uk-width-medium-1-3" id="eReminderDate">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="enrollmentReminderDate">Reminder-date<span
										class="req">*</span></label><br>
									{{Form::text('enrollmentReminderDate',
									null,array('id'=>'enrollmentReminderDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                         
                                  </div>
                                     
                            </div>
                            <br>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1" id="enrollmentcomments">
							<div class="parsley-row">
								<label for="enrollment">Comments<span class="req">*</span></label>
								{{ Form::textarea('enrollmentcustomerCommentTxtarea', null, ['id'=>'enrollmentcustomerCommentTxtarea',
								'size' => '60x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
						</div>
					</div>
				</div>	
				
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="saveEnrollmentBtn">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div>

	</div>
</div>
<!-- Edit Inquiry Modal Div -->


<!-- Edit Membership  Modal Div  -->
<div id="editMembershipModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 id="editMembershipModalTitle" class="modal-title">
					Edit Membership Followup
				</h4>
			</div>
			<div class="modal-body">
				
				<div id="membershipEditMessage"></div> <br clear="all" /> <br
						clear="all" />
				
				 <div id="membershipEditForm">
                                         <div class="uk-width-small-1-1">
                                             <div class="parsley-row">
                                                 <input type="hidden" id="membership_id" value=""/>
                                             </div>
                                         </div>
                                     <div class="uk-grid" data-uk-grid-margin>
                                         
                                        <div class="uk-width-medium-1-3" id="membershipEditDiv">
						<div class="parsley-row">
							<label for="membershipstatusSelect" class="inline-label">FollowupType<span
												class="req">*</span></label>
							<select name="membershipstatusSelect" id="membershipeditstatusSelect" class="input-sm md-input"   class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
						<!--		<option value="ACTIVE/SCHEDULED">Active/Scheduled</option>
								<option value="ATTENDED/CELEBRATED">Attended</option> -->
								<option value="REMINDER_CALL">Reminder Call</option>
                                                                <option value="FOLLOW_CALL">Follow Call</option>
                                                                <option value="CALL_SPOUSE">Call Spouse</option>
                                                                <option value="NOT_AVAILABLE">Not Available</option>
                                                                <option value="NOT_INTERESTED">Not Interested</option> 
                                                                <option value="CLOSE_CALL">Close Call</option>
                                                                
							</select>
						</div>
					</div> 
                                        <div class="uk-width-medium-1-3" id="membershipEditDiv">
						<div class="parsley-row">
							<label for="membershipeditActionSelect" class="inline-label">Quality<span
												class="req">*</span></label>
							<select name="membershipeditActionSelect" id="membershipeditActionSelect" class="input-sm md-input"  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value=" "></option>
                                                                <option value="VERYINTERESTED">Very Interested</option>
								<option value="INTERESTED">Interested</option>
								<option value="NOTSURE">Not Sure</option>
                                                                <option value="NEEDTOCHECK">Need To Check</option>
								<option value="NOTINTERESTED">Not Interested</option>
                                                        </select>
						</div>
					</div>
                                         
                                         <div class="uk-width-medium-1-3" id="membeshipReminderDate">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="membershipReminderDate">Reminder-date<span
										class="req">*</span></label><br>
									{{Form::text('membershipReminderDate',
									null,array('id'=>'membershipReminderDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							
                                         </div>
                                         
                                  </div>
                                     
                            </div>
                            <br>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1" id="membershipcomments">
							<div class="parsley-row">
								<label for="membershipComments">Comments<span class="req">*</span></label>
								{{ Form::textarea('membeshipcustomerCommentTxtarea', null, ['id'=>'membershipcustomerCommentTxtarea',
								'size' => '60x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
						</div>
					</div>
				</div>	
				
			
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="saveMembershipBtn">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div>

	</div>
</div>
<!-- Edit Membership Modal Div -->





 <!-- history Birthday Modal -->
  <div id='birthdayhistory' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="birthdayhistoryheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Birthday History</h4>
        </div>
        <div class="modal-body" id="birthdayhistorybody">
          <p></p>
        </div>
        <div class="modal-footer" id="birthdayhistoryfooter">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
 
  <!-- history Complaint Modal -->
  <div id='complianthistory' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="complainthistoryheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Complaint History</h4>
        </div>
        <div class="modal-body" id="Complianthistorybody">
          <p></p>
        </div>
        <div class="modal-footer" id="complainthistoryfooter">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
   
  <!-- history Retention Modal -->
  <div id='retentionhistory' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="retentionhistoryheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Retention History</h4>
        </div>
        <div class="modal-body" id="Retentionhistorybody">
          <p></p>
        </div>
        <div class="modal-footer" id="retentionhistoryfooter">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
  
  
  
   <!-- history Inquiry Modal -->
  <div id='inquiryhistory' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="inquiryhistoryheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Inquiry History</h4>
        </div>
        <div class="modal-body" id="Inquiryhistorybody">
          <p></p>
        </div>
        <div class="modal-footer" id="inquiryhistoryfooter">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
 
 
   <!-- history Membership followup Modal -->
  <div id='membershiphistory' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="membershiphistoryheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Membership History</h4>
        </div>
        <div class="modal-body" id="Membershiphistorybody">
          <p></p>
        </div>
        <div class="modal-footer" id="membershiphistoryfooter">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
   
   
   
   
   <!-- history Enrollment  Modal -->
  <div id='enrollmentyhistory' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="enrollmenthistoryheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Enrollment History</h4>
        </div>
        <div class="modal-body" id="enrollmenthistorybody">
          <p></p>
        </div>
        <div class="modal-footer" id="enrollmenthistoryfooter">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>
    
 <!-- Receive due for birthday Modal -->
  <div id='receiveBirthdayDue' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="receiveBirthdayDueheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Receive Birthday Due</h4>
        </div>
        <div class="modal-body" id="receiveBirthdayDuebody">
            <div  class="uk-grid" data-uk-grid-margin>
                      <div class="uk-width-medium-1-1" id="receiveBirthdayDueMsg"></div>
            </div>
         
								
          
       </div>     
          
        <div class="modal-footer" id="birthdayReceiveduefooter">
          <button type="submit" class="btn btn-primary" id="birthdayReceivepayment">Receive Payment</button>
          <button type="button" class="btn btn-default" id='birthdayReceivedueclose'>Close</button>
        </div>
      
      
    </div>
  </div>

@stop