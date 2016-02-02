@extends('layout.master') @section('libraryCSS')
<link
	href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'
	rel='stylesheet' />
<link rel="stylesheet"
	href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css" />
<link rel="stylesheet"
	href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css" />
@stop

<?php 
	/* echo '<pre>';
	print_r($student[]);
	echo '</pre>';
	exit(); */

	
	
	
	$student = $student['0'];
	
	/* echo $student->student_date_of_birth."<br>";
	$dob = date('Y-m-d', strtotime($student->student_date_of_birth));
	
	$datetime1  = new DateTime($dob);
	$datetime2  = new DateTime(date('Y-m-d'));
	
	
	$interval = $datetime1->diff($datetime2);
	echo $interval->format('%y years %m months and %d days');
	 */
	//exit();
?>

@section('libraryJS')

<!-- <script src='https://code.jquery.com/jquery-1.11.3.js'></script>
<script src='http://momentjs.com/downloads/moment.min.js'></script>
<script src='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.js'></script> -->
<script src="{{url()}}/assets/js/pages/validator.js"></script>
<script src="{{url()}}/assets/js/kendoui_custom.min.js"></script>
<script src="{{url()}}/assets/js/pages/kendoui.min.js"></script>

<script
	src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>

<script type="text/javascript">

var studentName   = "{{$student->student_name}}";
var studentId     = "{{$student->id}}";
var studentGender = "{{$student->student_gender}}";
var studentAge    = "{{date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->y;}}";
var isEligibleTwenty = "{{$discountEligibility['eligibleForTwenty']}}";
var isEligibleFifty = "{{$discountEligibility['eligibleForFifty']}}";

var ageYear  = '<?php echo date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->y;?>';
var ageMonth = '<?php echo date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->m;?>';
getEligibleClasses()

$('#studentDob').kendoDatePicker();

$("#editKidBtn").click(function (){
	$("#KidsformBody").show();
	$("#messageStudentAddDiv").html("");
	getStudentDetails();
	$("#addKidsModal").modal('show');
});

$("#kidsAddForm").submit(function (event){
	event.preventDefault();
	saveKids(event);
});


$("#reminderTxtBox").kendoDatePicker();
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
          	  	console.log(response);      	 	
    			if(response.status == "clear"){
    				$("#introVisitAddMessage").html('<p class="uk-alert uk-alert-danger">Please select another day. Batch chosen does not have schedule on selected date.</p>');
    				//$("#KidsformBody").hide();
    			}else{
    				$("#introVisitAddMessage").html("");
    			}
    	     	
            }
        });
    }
});







function saveKids(event){

	var postData = {'studentName'       : $('#studentName').val(),
			     	'nickname'           : $('#nickname').val(),
			     	'studentDob'         : $('#studentDob').val(),
			     	'studentGender'      : $('#studentGender').val(),
			     	'school'             : $('#school').val(),
			     	'location'           : $('#location').val(),
			     	'hobbies'            : $('#hobbies').val(),
			     	'emergencyContact'   : $('#emergencyContact').val(),	     	
			     	'remarks'            : $('#remarks').val(),
			     	'healthIssue'        : $('#healthIssue').val(),
			     	'studentId'          : studentId
			     	};

	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/saveKids')}}",
        data: postData,
        dataType:"json",
        success: function (response)
        {
      	  	console.log(response);      	 	
			if(response.status == "success"){
				$("#messageStudentAddDiv").html('<p class="uk-alert uk-alert-success">Kid details has been updated successfully. Please wait till this page reloads</p>');
				$("#KidsformBody").hide();

				setTimeout(function(){
				   window.location.reload(1);
				}, 5000);
			}else{
				$("#messageStudentAddDiv").html('<p class="uk-alert uk-alert-danger">Sorry! Kid details could not be updated.</p>');
				$("#KidsformBody").hide();
			}
				
	     	
        }
    });
}

function getStudentDetails(){

	$("#kidsAddForm .md-input-wrapper").addClass('md-input-filled');

	 $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/getStudentById')}}",
        data: {'studentId':studentId},
        dataType:"json",
        success: function (response)
        {
      	  	console.log(response);      	 	
	      	$('#studentName').val(response.student_name);
	     	$('#nickname').val(response.nickname);
	     	$('#studentDob').val(response.student_date_of_birth);
	     	$('#studentGender').val(response.student_gender);
	     	$('#school').val(response.school);
	     	$('#location').val(response.location);
	     	$('#hobbies').val(response.hobbies);
	     	$('#emergencyContact').val(response.emergency_contact);	     	
	     	$('#remarks').val(response.remarks);
	     	$('#healthIssue').val(response.health_issue);

	     	
	     	//$("#kidsAddForm select").addClass('md-input-filled');

	     	
        }
    });
	
}

$("#sessionsTable").hide();

$("#addEnrollment").click(function(){ 

	if(studentAge >= 12){

		$("#messageErrorDiv").html('<p class="uk-alert uk-alert-danger">Kids more than age of 12 are not eligible to enroll.</p>');
		$('#errorModal').modal('show');

	}else{
		$("#enrollmentModal").modal('show');
		$("#formBody").show();
		$("#messageStudentEnrollmentDiv").html("");
		getEligibleClasses();

	}
});

$("#paymentOptions").hide();
$("#paymentType").hide();
$("#singlePayAmountDiv").hide();
$("#biPayAmountDiv").hide();
$("#singlePayDiv").hide();
$("#bipayDiv").hide();
$("#multipayDiv").hide();
$("#enrollmentOptions").click(function (){

	//if($("#enrollmentOptions").val() == "enrollandpay" ){

		$("#selectedSessions").val(availableSessionCount);
		$.ajax({
	        type: "POST",
	        url: "{{URL::to('/quick/getPaymentTypes')}}",
	        data: {'availableSession': availableSessionCount},
	        dataType:"json",
	        success: function (response)
	        {
	      		console.log(response);
				var payments = response.payments;

				console.log(payments.multipay.eligible);

				$("#singlePayDiv").show();
				$("#singlePayAmount").val(response.payments.singlepay);
				$("#singlePayAmountDiv").show();

				
				$("#paymentType").show();
				$("#paymentOptions").show();
				if(payments.bipay.eligible == "YES"){


					$("#biPayAmountDiv").show();
					
					$("#bipayDiv").show();
					$("#bipayDivInputs").empty();
					var bipaystring = "";
					var i = 1;
					$.each(payments.bipay.pays, function (index, item) {

						if(item.amount != "undefined"){
			      		  bipaystring += '<input id="bipayAmount'+i+'" name="bipayAmount'+i+'" readonly value="'+item.amount+'" style="float:left;width:60px;" class="form-control input-sm md-input"/>';   
			      		  i++;     
						}       
		            });
					$("#bipayDivInputs").append(bipaystring);
					$("#biPayRadio").prop('disabled',false)
					$("#biPayRadio").removeClass('disabled')
					$("#biPayRadiolabel").removeClass('disabled')

				}else{

					$("#biPayAmountDiv").show();
					
					$("#bipayDiv").show();
					$("#bipayDivInputs").empty();

					//singlePayRadio biPayRadio multiPayRadio
					
					$("#biPayRadio").attr('disabled','true')
					$("#biPayRadio").addClass('disabled')
					$("#biPayRadiolabel").addClass('disabled')
				}

				
				if(payments.multipay.eligible === "YES"){

					$("#multipayDiv").show();
					$("#multipayDivInputs").empty();
					var m = 1;
					var multipaystring = "";
					$.each(payments.multipay.pays, function (index, item) {
						if(item.amount != "undefined"){
						 multipaystring += '<input id="multipayAmount'+m+'" name="multipayAmount'+m+'" readonly value="'+item.amount+'"  style="float:left;width:60px;" class="form-control input-sm md-input"/>'; 
						 m++;             
						}
		            });
					$("#multipayDivInputs").append(multipaystring);
					$("#multiPayRadio").prop('disabled',false);
					$("#multiPayRadio").removeClass('disabled');
					$("#multiPayRadiolabel").removeClass('disabled');


				}else{

					$("#multipayDiv").show();
					$("#multipayDivInputs").empty();
					$("#multiPayRadio").attr('disabled','true');
					$("#multiPayRadio").addClass('disabled');
					$("#multiPayRadiolabel").addClass('disabled');
				}
	      		
	        }
	    });
















		
		
	//}
	
});

$("#cardDetailsDiv").hide();
$("#chequeDetailsDiv").hide();
$("input[name='paymentTypeRadio']").change(function (){

	var selectedPaymentType = $("input[type='radio'][name='paymentTypeRadio']:checked").val();
	if(selectedPaymentType == "card"){
		$("#chequeDetailsDiv").hide();
		$("#cardDetailsDiv").show();


		$("#cardType").attr("required",true);
		$("#card4digits").attr("required",true);
		$("#cardBankName").attr("required",true);
		$("#cardRecieptNumber").attr("required",true);


		$("#chequeBankName").attr("required",false);
		$("#chequeNumber").attr("required",false);

	}else if(selectedPaymentType == "cheque"){
		$("#chequeDetailsDiv").show();
		$("#cardDetailsDiv").hide();

		$("#cardType").attr("required",false);
		$("#card4digits").attr("required",false);
		$("#cardBankName").attr("required",false);
		$("#cardRecieptNumber").attr("required",false);


		$("#chequeBankName").attr("required",true);
		$("#chequeNumber").attr("required",true);
	}
	else if(selectedPaymentType == "cash"){
		$("#chequeDetailsDiv").hide();
		$("#cardDetailsDiv").hide();

		$("#cardType").attr("required",false);
		$("#card4digits").attr("required",false);
		$("#cardBankName").attr("required",false);
		$("#cardRecieptNumber").attr("required",false);


		$("#chequeBankName").attr("required",false);
		$("#chequeNumber").attr("required",false);
	}
	
});






$("#finalPaymentDiv").hide();

$("input[name='paymentOptionsRadio']").change(function (){

	console.log("payment types");
	$("#finalPaymentDiv").show();

	var selected = $("input[type='radio'][name='paymentOptionsRadio']:checked").val();
	
	if(selected == "singlepay"){
		console.log(availableSessionCount);
		
		$("#singlePayAmountDiv").show();
		$("#singlePayAmount").val((availableSessionCount*500));
		$("#totalAmountToPay").val(availableSessionCount*500);
		$("#totalAmountToPaytotals").val(availableSessionCount*500);
			
	}else if(selected == "bipay"){
		$("#biPayAmountDiv").show();
		$("#totalAmountToPay").val($("#bipayAmount1").val());
		$("#totalAmountToPaytotals").val($("#bipayAmount1").val());
	}
	else if(selected == "multipay"){
		$("#biPayAmountDiv").show();
		$("#totalAmountToPay").val($("#multipayAmount1").val());
		$("#totalAmountToPaytotals").val($("#multipayAmount1").val());
	}

	$("#selectedPaymentMethod").html(selected);

	<?php if(!$customermembership){?>
		$("#membershipAmount").val("2000");
		$("#membershipAmounttotals").val("2000");
	<?php }?>


/* if(isEligibleTwenty == 'YES'){

		
		percentAmount = ($("#totalEnrollmentAmount").val()*20/100);

		if(percentAmount < parseInt($("#totalAmountToPaytotals").val())){
			//applyDiscountOnLastPayment();

			$("#discountPercentage").val('20');
			$("#discountText").html("20% discount applied");
			
		}else{

			$("#discountTextBox").val('0');
		}
		
		
	}

	if(isEligibleFifty == 'YES'){
		percentAmount = ($("#totalEnrollmentAmount").val()*50/100);

		if(percentAmount < parseInt($("#totalAmountToPaytotals").val())){
			$("#discountPercentage").val('50');
			$("#discountText").html("50% discount applied");
		}else{

			$("#discountTextBox").val('0');
		}
	} */
	calculateFinalAmount()
	

	$("#paymentType").show();
	
	
});

$(document).on('change', "#discountPercentage", function() {

	calculateFinalAmount()
	
});



function calculateFinalAmount(){

	<?php if(!$customermembership){?>
	var finalAmount = (parseInt($("#totalAmountToPay").val())+parseInt($("#membershipAmount").val()));
	<?php }else{?>
	var finalAmount = (parseInt($("#totalAmountToPay").val()));
	<?php }?>
	console.log(finalAmount);

	var discountPercentage  =  $("#discountPercentage").val();

	var percentAmount = ($("#totalAmountToPaytotals").val()*discountPercentage/100);
	$("#discountTextBox").val("-"+percentAmount);

	/* var percentAmount = 0;
	$("#discountPercentage").val('0');
	$("#discountText").html("No discount applied");
	console.log($("#totalAmountToPaytotals").val());
	if(isEligibleTwenty == 'YES'){

		
		percentAmount = ($("#totalEnrollmentAmount").val()*20/100);

		if(percentAmount < parseInt($("#totalAmountToPaytotals").val())){
			//applyDiscountOnLastPayment();

			$("#discountPercentage").val('20');
			$("#discountText").html("20% discount applied");
			$("#discountTextBox").val("-"+percentAmount);
		}else{

			$("#discountTextBox").val('0');
		}
		
		
	}

	if(isEligibleFifty == 'YES'){
		percentAmount = ($("#totalEnrollmentAmount").val()*50/100);

		if(percentAmount < parseInt($("#totalAmountToPaytotals").val())){
			$("#discountPercentage").val('50');
			$("#discountText").html("50% discount applied");
			$("#discountTextBox").val("-"+percentAmount);
		}else{

			$("#discountTextBox").val('0');
		}
	} */

	
	
	finalAmount = (finalAmount-percentAmount);
	
	$("#subtotal").val(finalAmount);
	
	var tax = Math.floor(((14.5/100)*parseInt(finalAmount)))
	$("#taxAmount").val(tax);
	$("#grandTotal").val((tax+finalAmount));
	
}


function applyDiscountOnLastPayment(){

	var selectedOption = $("input[type='radio'][name='paymentOptionsRadio']:checked").val();
}




$(document).on('change', "#membershipType", function() {
	console.log($(this).val());

	if($(this).val() == '1'){
		
		$("#membershipAmount").val("2000");
		$("#membershipAmounttotals").val("2000");
		
	}else if($(this).val() == '2'){
		$("#membershipAmount").val("5000");
		$("#membershipAmounttotals").val("5000");
	}

	calculateFinalAmount()
})




$("#closeEnrollmentModal").click(function (){

	$("#batchCbx").val("");
	$("#eligibleClassesCbx").val("");
	$("#paymentOptions").hide();
	$("#sessionsTable").hide();
	$("#enrollmentOptions").val("enroll");

	if($(this).data('closemode') == 'print'){
			   window.location.reload(1);
	}
	
});


$("#enrollNow").hide();
$('#getSchedulesSessions').click(function (){

	if($('#batchCbx').val() != "" && $('#enrollmentStartDate').val() != "" && $("#eligibleClassesCbx").val() != ""){

		$("#messageStudentEnrollmentDiv").html('');
		getSessionsForClasses()
		$("#enrollNow").show();

	}else{
		$("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger">Please fill up required Fields.</p>');
	}
});


var todaysDate = new Date();
$("#enrollmentStartDate").kendoDatePicker({
	/* change:function (){
		prepareGetClasses()
	}, */
	//min: todaysDate
});


$("#enrollmentEndDate").kendoDatePicker({
	change:function (){
		prepareGetClasses()
	}
	
});

function prepareGetClasses(){
	$("#paymentOptions").hide();
	$("#finalPaymentDiv").hide();
	$("input[name='paymentOptionsRadio']").attr('checked', false);

	
	if($('#batchCbx').val() != "" && $('#enrollmentStartDate').val() != "" && $('#enrollmentEndDate').val() != "" && $("#eligibleClassesCbx").val() != ""){

		$("#messageStudentEnrollmentDiv").html('');
		getSessionsForClasses()
		$("#enrollNow").show();

	}else{
		$("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger">Please fill up required Fields.</p>');
	}
	
}

$("#enrollKidForm").validator().on('submit', function (e) {
	  if (e.isDefaultPrevented()) {
		    // handle the invalid form...
	  } else {
	    // everything looks good!
	    //alert("introvisit");
	    e.preventDefault();
	    enrollnow(event);	

	  }
});


/* $("#enrollKidForm").submit(function (event){
	enrollnow(event);	
});
 */
$(".eligibleClassesCbx").change(function (){
	console.log(this.id);
	var selector = "";
	if(this.id == "eligibleClassIntro"){
		selector = "introbatchCbx";
		from = this;
	}else{
		var classId = $(this).val();
		$.ajax({
	        type: "POST",
	        url: "{{URL::to('/quick/checkenrollmentExists')}}",
	        data: {'classId': classId, "studentId":studentId},
	        dataType:"json",
	        success: function (response)
	        {
	      	   if(response.status == "exist"){
	      		 $("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger"> Course already enrolled.</p>');
	      	   }else{
	      		 $("#messageStudentEnrollmentDiv").html('');
	      	   }
	        }
	    });
		selector = "batchCbx";
		from = this;
	}
	getBatchesBasedOnClasses(selector,from);
});







function getEligibleClasses(){

	  $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/eligibleClassess')}}",
        data: {'ageYear': ageYear, 'ageMonth': ageMonth, 'gender':studentGender},
        dataType:"json",
        success: function (response)
        {
          console.log(response);
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




function getBatchesBasedOnClasses(selector, from){
console.log($(from).val());
	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/batchesByClass')}}",
        data: {'classId': $(from).val()},
        dataType:"json",
        success: function (response)
        {      	   
      	  $('#'+selector).empty();      	  
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  console.log(index+" = "+item);
      		  $string += '<option value='+item.id+'>'+item.batch_name+' '+item.day+'  '+item.start_time+' - '+item.end_time+' '+item.instructor+'</option>';               
            });
      	  $('#'+selector).append($string); 
      	  //$('#introbatchCbx').append($string);
        }
    }); 
}




var availableSessionCount = 0;
function getSessionsForClasses(){

	

		$.ajax({
	        type: "POST",
	        url: "{{URL::to('/quick/getBatcheSchedules')}}",
	        data: {'batchId': $('#batchCbx').val(), "enrollmentStartDate":$('#enrollmentStartDate').val(), "enrollmentEndDate":$('#enrollmentEndDate').val()},
	        dataType:"json",
	        success: function (response)
	        {      	   
	            $("#availableSessions").html(response.availableSession);
	            $("#totalAmount").html(response.amountTotal);

	            $("#totalEnrollmentAmount").val(response.amountTotal);
	            availableSessionCount = response.availableSession;

				
				

	            
	        	$("#sessionsTable").show();
	        }
	    }); 

	
}

function enrollnow(){
	
	event.preventDefault();
	$("#enrollNow").hide();
	$("#KidsformBody").hide();
	$("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-info">Enrolling student.Please wait till process is completed.</p>');
	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/enrollkid')}}",
        data:$("#enrollKidForm").serialize(),
        //data: {'classId': $("#eligibleClassesCbx").val(), 'batchId':$("#batchCbx").val(), "studentId":studentId},
        dataType:"json",
        success: function (response)
        {
        	if(response.status == "success"){


            	if(response.printUrl == ""){
					$("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-success">Student has been successfully enrolled. Please wait till this page reloads</p>');
					setTimeout(function(){
					   window.location.reload(1);
					}, 5000);
            	}else{

					var printvars = '<a target="_blank" href="'+response.printUrl+'" class="btn btn-primary">Print</a>';
            		$("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-success">Student has been successfully enrolled. Please click the print button below.</p>'+printvars);

            		$("#closeEnrollmentModal").data("closemode",'print');
            	}
        	}else{
				$("#.").hide();
        		$("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger">Sorry! Student could not be enrolled.</p>');
        	}     	   
        }
    }); 
	
}


$('#addIntrovisitForm').validator().on('submit', function (e) {
  if (e.isDefaultPrevented()) {
    // handle the invalid form...
  } else {
    // everything looks good!
    //alert("introvisit");
    e.preventDefault();
	  $.ajax({
	        type: "POST",
	        url: "{{URL::to('/quick/addIntroVisit')}}",
	        data:$("#addIntrovisitForm").serialize(),
	        //data: {'classId': $("#eligibleClassesCbx").val(), 'batchId':$("#batchCbx").val(), "studentId":studentId},
	        dataType:"json",
	        success: function (response)
	        {
	        	if(response.status == "success"){
	            	$("#formBody").hide();
					$("#introVisitAddMessage").html('<p class="uk-alert uk-alert-success">Introductory visit was added successfully. Please wait till this page reloads</p>');
					setTimeout(function(){
					   window.location.reload(1);
					}, 5000);
	        	}else{
	            	
					$("#formBody").hide();
	        		$("#introVisitAddMessage").html('<p class="uk-alert uk-alert-danger">Sorry! Introductory visit could not be enrolled.</p>');
	        	}     	   
	        }
	    }); 
  }


//event.preventDefault();

	
	

	
});




$(".ivEdit").click(function (){


	var ivId = $(this).data('ivid');
	var ivStatus = $(this).data('ivstatus');
	$("#iveditSelect").data('iveditid',ivId);


	$("#ivEditForm").show();
	
	$("#iveditSelect").val(ivStatus);

	$("#introvisitEditDiv").show();
	$("#saveIntroVisitBtn").show();
	$("#editIntrovisitModal").modal('show');


	
	
	
});



$("#saveIntroVisitBtn").click(function (){
	saveIv();
	
});

function saveIv(){


	var ivid = $("#iveditSelect").data('iveditid');
	var ivstatus = $("#iveditSelect").val();

	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/editIntrovisit')}}",
        data:{'status':ivstatus,'id':ivid, "customerCommentTxtarea":$("#ivcustomerCommentTxtarea").val()},
        //data: {'classId': $("#eligibleClassesCbx").val(), 'batchId':$("#batchCbx").val(), "studentId":studentId},
        dataType:"json",
        success: function (response)
        {
        	if(response.status == "success"){

        		$("#ivEditForm").hide();
            	$("#introvisitEditDiv").hide();
            	$("#saveIntroVisitBtn").hide();
				$("#introVisitEditMessage").html('<p class="uk-alert uk-alert-success">Introductory visit was added successfully. Please wait till this page reloads</p>');
				setTimeout(function(){
				   window.location.reload(1);
				}, 5000);
				
        	}else{
        		$("#ivEditForm").hide();
				$("#introvisitEditDiv").hide();
        		$("#introVisitEditMessage").html('<p class="uk-alert uk-alert-danger">Sorry! Introductory visit could not be enrolled.</p>');
        	}     	   
        }
    }); 
}
function ReceivePendingDue(pendingId,pendingAmount,discount){
 var amountAfterDiscount=((discount/100)*parseInt(pendingAmount));   
       $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/creatependingorder')}}",
                        data: {'pending_id':pendingId,'pendingamount':amountAfterDiscount},
			dataType: 'json',
			success: function(response){
                            console.log(response.status);
                            $('#payments').empty();
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
@stop @section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index: 9;"><span></span>Home</a></li>
		<li><a href="{{url()}}/students" style="z-index: 8;">Students</a></li>
		<li><a href="#" style="z-index: 7;">{{$student->student_name}}</a></li>
	</ul>
</div>
<br clear="all" />



<div class="uk-grid" data-uk-grid-margin data-uk-grid-match
	id="user_profile">
	<div class="uk-width-large-10-10">
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
					<img
						src="{{url()}}/upload/profile/student/{{$student->profile_image}}" />
				</div>
				<div class="user_heading_content">
					<h2 class="heading_b uk-margin-bottom">
						<span class="uk-text-truncate"> {{$student->student_name}}</span><span
							class="sub-heading"><a
							href="{{url()}}/customers/view/{{$student->customers->id}}"
							style="color: #FFF;">({{$student->customers->customer_name}})</a></span>
					</h2>
					<ul class="user_stats">
						<li>
							<h4 class="heading_a">
                                        <?php echo date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->y;?> years
                                                        <?php echo date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->m;?> months 
                                                         
                                        
                                        <span class="sub-heading">Age</span>
							</h4>
						</li>
						<li>
							<h4 class="heading_a">
								{{$student->student_gender}} <span class="sub-heading">Gender</span>
							</h4>
						</li>
						<li>
							<h4 class="heading_a">
								{{date('d M Y',strtotime($student->student_date_of_birth))}} <span
									class="sub-heading">Date of birth</span>
							</h4>
						</li>
					</ul>
				</div>
				<a class="md-fab md-fab-small md-fab-accent" id="editKidBtn"> <i
					class="material-icons">&#xE150;</i>
				</a>
			</div>
			<div class="user_content">
				<ul id="user_profile_tabs" class="uk-tab"
					data-uk-tab="{connect:'#user_profile_tabs_content', animation:'slide-horizontal'}"
					data-uk-sticky="{ top: 48, media: 960 }">
					<li class="uk-active"><a href="#about">About</a></li>
					<li class=""><a href="#introvisit">Intro Visit</a></li>
					<li class=""><a href="#enrollments">Enrollments</a></li>
					<li class=""><a href="#payments">Payments</a></li>
				</ul>
				<ul id="user_profile_tabs_content" class="uk-switcher uk-margin">
					<li id="introvisitpage">
						<div class="uk-grid uk-margin-medium-top uk-margin-large-bottom"
							data-uk-grid-margin>
							<div class="uk-width-large-1-2">
								<h4 class="heading_c uk-margin-small-bottom">Kid's Information</h4>
								<ul class="md-list md-list-addon">
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->student_name}} </span>
											<span class="uk-text-small uk-text-muted">Name</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->nickname}}</span> <span
												class="uk-text-small uk-text-muted">Nick Name</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->student_gender}}</span>
											<span class="uk-text-small uk-text-muted">Gender</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{date('d/m/Y',
												strtotime($student->student_date_of_birth))}}</span> <span
												class="uk-text-small uk-text-muted">Date of birth</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">
                                                        <?php echo date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->y;?> years
                                                        <?php echo date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->m;?> months 
                                                         
                                                        
                                                       </span> <span
												class="uk-text-small uk-text-muted">Age</span>
										</div>
									</li>

									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->school}}</span> <span
												class="uk-text-small uk-text-muted">School</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->location}}</span> <span
												class="uk-text-small uk-text-muted">Location</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->hobbies}}</span> <span
												class="uk-text-small uk-text-muted">Hobbies</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->emergency_contact}}</span>
											<span class="uk-text-small uk-text-muted">Emergency Contact</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->remarks}}</span> <span
												class="uk-text-small uk-text-muted">Remarks</span>
										</div>
									</li>
									<li>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->health_issue}}</span>
											<span class="uk-text-small uk-text-muted">Health Issue</span>
										</div>
									</li>




								</ul>
								<h3>Profile Picture</h3>
								<ul>

									<li>@if (Session::has('imageUploadMessage'))
										<div class="uk-alert uk-alert-success" data-uk-alert>
											<a href="#" class="uk-alert-close uk-close"></a> {{
											Session::get('imageUploadMessage') }}
										</div> <br clear="all" /> @endif
										<div class="md-list-content">
											{{Form::open(array('files'=> true,
											'url'=>'students/profile/picture'))}} <span
												class="md-list-heading">{{Form::file('profileImage')}}</span>
											<span class="uk-text-small uk-text-muted">Change Profile
												Picture</span> <input name="studentId"
												value="{{$student->id}}" type="hidden" /> <input
												type="submit" class="btn btn-sm btn-primary"
												value="Upload Profile Picture" /> {{Form::close()}}
										</div> <br clear="all" />
									</li>

								</ul>


								<h4 class="heading_c uk-margin-small-bottom">Parent's
									Information</h4>
								<ul class="md-list md-list-addon">
									<li>
										<div class="md-list-addon-element">
											<i class="md-list-addon-icon material-icons">&#xE158;</i>
										</div>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->customers->customer_email}}
											</span> <span class="uk-text-small uk-text-muted">Email</span>
										</div>
									</li>
									<li>
										<div class="md-list-addon-element">
											<i class="md-list-addon-icon material-icons">&#xE0CD;</i>
										</div>
										<div class="md-list-content">
											<span class="md-list-heading">{{$student->customers->mobile_no}}</span>
											<span class="uk-text-small uk-text-muted">Phone</span>
										</div>
									</li>
								</ul>
							</div>
					
					</li>

					<li id="introvisit">
						<h3>Add Introductory Visit</h3>
						<div id="introVisitAddMessage"></div> <br clear="all" /> <br
						clear="all" />

						
						
						<form action="" method="post" id="addIntrovisitForm">
							<div class="uk-grid" data-uk-grid-margin>
								<input name="studentIdIntroVisit" type="hidden" 
									value="{{$student->id}}" /> <input name="customerId"
									type="hidden" id="customerIdAddIntrovisit"
									value="{{$student->customers->id}}" />




								<div class="uk-width-medium-1-3">
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



								<div class="uk-width-medium-1-3">
									<div class="parsley-row">
										<label for="introbatchCbx">Batch<span class="req">*</span></label>
										<select id="introbatchCbx" name="introbatchCbx" required
											class='form-control input-sm md-input'
											style="padding: 0px; font-weight: bold; color: #727272;">
											<option value=""></option>
										</select>
									</div>
								</div>
								<div class="uk-width-medium-1-3">
									<div class="parsley-row" style="margin-top: -20px">
										<label for="introVisitTxtBox">Introductory visit date<span
											class="req">*</span></label> <br>
										{{Form::text('introVisitTxtBox',
										null,array('id'=>'introVisitTxtBox', 'required'=>'', 'class' =>
										''))}}
									</div>
								</div>
							</div>
							<br>




							<div class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-medium-1-1">
									<div class="parsley-row">
										<label for="customerCommentTxtarea">Comment<span class="req">*</span></label>
										{{ Form::textarea('customerCommentTxtarea', null,
										['id'=>'customerCommentTxtarea', 'size' => '50x2',
										'class' => 'form-control input-sm md-input']) }}
									</div>
									<br>
								</div>
							</div>
							<div class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-medium-1-2">
									<div class="parsley-row">
										<label for="reminderTxtBox">Reminder date<span class="req">*</span></label>
										{{Form::text('reminderTxtBox',
										null,array('id'=>'reminderTxtBox',  'class' =>
										''))}}
									</div>
								</div>

							</div>
							<br clear="all" /> <br clear="all" /> <br clear="all" />
							<div class="uk-width-medium-1-2">
								<div class="parsley-row">
									<button type="submit" class="btn btn-primary"
										id="addIntroVisitSubmit">Add Introductory Visit</button>
								</div>
							</div>
						</form> 
						
						
						
						<br clear="all" /> <br clear="all" />
						<h4 class="heading_c uk-margin-small-bottom">Scheduled
							Introductory Visits</h4>
						<ul class="md-list">
                                         
                                         	<?php 
                                         	/* echo '<pre>';                                         	
                                         	print_r($scheduledIntroVisits);
                                         	echo '</pre>';   */
                                         	if(isset($scheduledIntroVisits)){
	                                         	
                                         	foreach ($scheduledIntroVisits as $scheduledIntroVisit){?>
											 <li>
												<div class="md-list-content">
													<span class="md-list-heading"> 
														<a href="#" class="iv" data-ivid="{{$scheduledIntroVisit->id}}">
															Intro visit scheduled for {{$scheduledIntroVisit->Classes->class_name}} 
															<?php if(isset($scheduledIntroVisit->Batches) && sizeof($scheduledIntroVisit->Batches) > 0){?>
															({{$scheduledIntroVisit->Batches->batch_name}})
															<?php }?>
														</a>
														<a href="#" class="btn btn-xs btn-primary ivEdit" data-ivstatus="{{$scheduledIntroVisit->status}}" data-ivid="{{$scheduledIntroVisit->id}}">
															Edit
														</a>
														
														
														
													</span> 
													<span class="uk-badge uk-badge-success" style="float: left"> 
														{{date('d M Y',strtotime($scheduledIntroVisit->iv_date))}} 
													</span>
													<span class="uk-badge uk-badge-success" style="float: left"> 
														{{$scheduledIntroVisit->status}}
													</span>
												</div>
											</li>
											<?php }                           		
                                         	}
	                                         ?>
                                         </ul>








					</li>
					<li id="enrollments">
						<h4 class="heading_c uk-margin-small-bottom">Enrolled Classes</h4>
						<ul class="md-list">
                                         	<?php foreach ($studentEnrollments as $enrollment){?>
	                                         <li>
								<div class="md-list-content">
									<span class="md-list-heading"><a href="#">{{$enrollment->Classes->class_name}}</a></span>
									<span class="uk-text-small uk-text-muted">{{date('d M
										Y',strtotime($enrollment->created_at))}}</span>
								</div>
							</li>
	                                         <?php }?>
                                         </ul>
                                         	
                                         
                                        <?php 
                                        $studentAgeCheck = date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->y;
                                        if($studentAgeCheck <= 12){
                                        ?>
                                		
										<a class="md-fab md-fab-accent" id="addEnrollment"
						style="float: right;"> <i class="material-icons">&#xE03B;</i>
					</a>
										<?php }?>
                                </li>
					<li id="payments">

						<h4 class="heading_c uk-margin-small-bottom">Payments for Enrolled
							Classes</h4>
								<ul class="md-list">
											<?php foreach ($paymentDues as $payments){?>
											 <li>
												<div class="md-list-content">
													<span class="md-list-heading"> 
														<a href="#">
															<?php if(isset($payments->Batches) && sizeof($payments->Batches)>0){?>
																{{$payments->Batches->batch_name}}
															<?php }?>
														</a>
											
														Rs. {{$payments->payment_due_amount}}
											
											
											
													</span> <span class="uk-badge uk-badge-success"
														style="float: left"> {{ucfirst($payments->payment_status)}} </span>
											
												</div>
											</li>
											<?php }?>
                                                                                        <div class="uk-grid" data-uk-grid-margin data-uk-grid-match="{target:'.md-card-content'}">

                                                                                         <div class="uk-width-medium-1-1">

                                                                                                       <div class="md-card">
                                                                                                                   <div class="md-card-content">
                                                                                                                         <div class="uk-overflow-container">

                                                                                                                           </br>

                                                                                                                            <h4 class="heading_c uk-margin-small-bottom">Payments made</h4>
                                                                                                                            <table class="uk-table dashboardTable" id="payment-details" >
                                                                                                                                <thead>
                                                                                                                                    <tr>
                                                                                                                                    <th class="uk-text-nowrap">Enrolled class</th>
                                                                                                                                    <th class="uk-text-nowrap">class start date</th>
                                                                                                                                    <th class="uk-text-nowrap">class end date</th>
                                                                                                                                    <th class="uk-text-nowrap">sessions</th>
                                                                                                                                    <th class="uk-text-nowrap">Amount</th>
                                                                                                                                    <th class="uk-text-nowrap">payment type</th>
                                                                                                                                    
                                                                                                                                    <th class="uk-text-nowrap">Received by</th>
                                                                                                                                    <th class="uk-text-nowrap">option</th>
                                                                                                                                    
                                                                                                                                    </tr>
                                                                                                                                </thead>
                                                                                                                                <tbody>
                                                                                                                                    <?php if(isset($paidAmountdata[0])){ for($i=0;$i<sizeof($paidAmountdata);$i++){ ?>
                                                                                                                                        <tr>
                                                                                                                                            <td>{{$paidAmountdata[$i]['class_name']}}</td>
                                                                                                                                            <td>{{$paidAmountdata[$i]['start_order_date']}}</td>
                                                                                                                                            <td>{{$paidAmountdata[$i]['end_order_date']}}</td>
                                                                                                                                            <td>{{$paidAmountdata[$i]['selected_order_sessions']}}</td>
                                                                                                                                            <td>{{$paidAmountdata[$i]['payment_due_amount']}}</td>
                                                                                                                                            <td>{{$paidAmountdata[$i]['payment_type']}}</td>
                                                                                                                                            <td>{{$paidAmountdata[$i]['receivedname']}}</td>
                                                                                                                                            <td><a id='Print' target="_blank" class="btn btn-primary btn-xs" href="{{$paidAmountdata[$i]['printurl']}}">Print</a></td>
                                                                                                             
                                                                                                                                            
                                                                                                                                        </tr>
                                                                                                                                     <?php }} ?>
                                                                                                                                           <?php if(!isset($paidAmountdata[0])){?>
                                                                                                                                         <tr><td>------Not Enrolled to any class------- </td></tr>
                                                                                                                                    <?php }?>
                                                                                                                                </tbody>
                                                                                                                            </table>
                                                                                                                         </div>
                                                                                                                       </div>

                                                                                                              
                                                                                                        </div>
                                                                                             </div>
                                                                                             
                                                                                            <div class="uk-width-medium-1-1">
                                                                                                       
                                                                                                            <div class="md-card">
                                                                                                                <div class="md-card-content">
                                                                                                                  <div class="uk-overflow-container">
                                                                                                                           </br>
                                                                                                                            <h4 class="heading_c uk-margin-small-bottom">Payments Dues</h4>
                                                                                                                            <table class="uk-table dashboardTable" id="payment-details" >
                                                                                                                                <thead>
                                                                                                                                    <tr>
                                                                                                                                    <th class="uk-text-nowrap">Enrolled class</th>
                                                                                                                                    <th class="uk-text-nowrap">class start date</th>
                                                                                                                                    <th class="uk-text-nowrap">class end date</th>
                                                                                                                                    <th class="uk-text-nowrap">sessions</th>
                                                                                                                                    <th class="uk-text-nowrap">payment type</th>
                                                                                                                                    <th class="uk-text-nowrap">Pending Amount</th>
                                                                                                                                    <th class="uk-text-nowrap">Received by</th>
                                                                                                                                    <th class="uk-text-nowrap">option</th>
                                                                                                                                    
                                                                                                                                    </tr>
                                                                                                                                </thead>
                                                                                                                                <tbody>
                                                                                                                                     <?php if(isset($order_due_data[0])){ for($i=0;$i<sizeof($order_due_data);$i++){ ?>
                                                                                                                                        <tr>
                                                                                                                                            <td>{{$order_due_data[$i]['class_name']}}</td>
                                                                                                                                            <td>{{$order_due_data[$i]['start_order_date']}}</td>
                                                                                                                                            <td>{{$order_due_data[$i]['end_order_date']}}</td>
                                                                                                                                            <td>{{$order_due_data[$i]['selected_order_sessions']}}</td>
                                                                                                                                            <td>{{$order_due_data[$i]['payment_type']}}</td>
                                                                                                                                            <td>{{$order_due_data[$i]['payment_due_amount']}}</td>
                                                                                                                                            <td>{{$order_due_data[$i]['receivedname']}}</td>
                                                                                                                                            <td>
                                                                                                                                                <a id='Receive_due_enrollment'  class="btn btn-primary btn-xs" onclick="ReceivePendingDue({{$order_due_data[$i]['id']}},{{$order_due_data[$i]['payment_due_amount']}},{{$order_due_data[$i]['discount_applied']}})">Receive</a>
                                                                                                                                            </td>
                                                                                                             
                                                                                                                                            
                                                                                                                                        </tr>
                                                                                                                                     <?php }} ?>
                                                                                                                                           <?php if(!isset($order_due_data[0])){?>
                                                                                                                                         <tr><td>------Not Enrolled to any class------- </td></tr>
                                                                                                                                    <?php }?>
                                                                                                                                </tbody>
                                                                                                                         </table>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                             </div>
                                                                                                </div>
                                                                                             
                                                                                            </div>
                                                                    </ul>  
					</li>
					<li id="birthdays">

						<h3 class="heading_c uk-margin-small-bottom">Birthdays</h3> <br
						clear="all" />
						<div class="md-card-content large-padding">

							<div class="uk-grid" data-uk-grid-margin id="addbirthday">
								<div class="uk-width-medium-1-1">
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
						
					</li>


				</ul>
			</div>
		</div>
	</div>

</div>

<!-- 
15
2 half 
1000  
-->


<div id="enrollmentModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Enroll Kids</h4>
			</div>
			<form id="enrollKidForm" method="post"
				action="{{url()}}/quick/enrollkid">
				<div class="modal-body">
					<div id="messageStudentEnrollmentDiv"></div>
					<div id="KidsformBody">
						<input type="hidden" name="discountOnLastInstallment" id="discountOnLastInstallment" value="no"/>
						<br clear="all" /> <input name="studentId" type="hidden"
							value="{{$student->id}}" /> <input name="customerId"
							type="hidden" value="{{$student->customers->id}}" /> <input
							id="selectedSessions" name="selectedSessions" type="hidden"
							value="" />
						<div class="uk-grid" data-uk-grid-margin>
							<div class="uk-width-medium-1-4">
								<div class="parsley-row">
									<label for="eligibleClassesCbx">Eligible Classess<span
										class="req">*</span></label> <select id="eligibleClassesCbx"
										name="eligibleClassesCbx" required
										class='eligibleClassesCbx form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										<option value=""></option>
									</select>
								</div>
							</div>
							<div class="uk-width-medium-1-4">
								<div class="parsley-row">
									<label for="hobbies">Batch<span class="req">*</span></label> <select
										id="batchCbx" name="batchCbx" required
										class='form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										<option value=""></option>
									</select>
								</div>
							</div>
							<div class="uk-width-medium-1-4" id="">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="enrollmentStartDate">Preferred Start date<span
										class="req">*</span></label><br>
									{{Form::text('enrollmentStartDate',
									null,array('id'=>'enrollmentStartDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

								</div>
							</div>
							<div class="uk-width-medium-1-4" id="">
								<div class="parsley-row" style="margin-top: -23px;">
									<label for="enrollmentEndDate">Preferred End date<span class="req">*</span></label><br>
									{{Form::text('enrollmentEndDate', null,array('id'=>'enrollmentEndDate', 'required'=>'', 'class' => 'uk-form-width-medium'))}}
								</div>
							</div>

						</div>
						<br clear="all" />
						<table id="sessionsTable" class="uk-table">
							<!-- <caption>Table caption</caption> -->
							<thead>
								<tr>
									<th>Sessions Available</th>
									<th>Amount per session</th>
									<th>Total Amount</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td><span id="availableSessions"></span></td>
									<td><span id="amountPerSesssion">500</span></td>
									<td><span id="totalAmount"></span></td>
									<td>
										<button type="button" class="btn btn-sm btn-primary"
											id="enrollmentOptions">View payment options</button> <!-- <button type="button" class="btn btn-sm btn-primary" id="enrollNow" >Enroll now</button>
		       							<button type="button" class="btn btn-sm btn-success" id="enrollAndPay">Enroll and pay</button> -->
										<!-- <select id="enrollmentOptions" class="input-sm md-input" style='padding:0px; font-weight:bold;color: #727272;'>
	                                		<option value="enroll">Enroll</option>
	                                		<option value="enrollandpay">Enroll And Pay</option>
	                                		
	                                		
	                                	</select> -->
									</td>
								</tr>
							</tbody>
						</table>

						<div id="paymentOptions" class="uk-grid" data-uk-grid-margin>


							<div class="uk-width-medium-1-3">
								<div class="parsley-row">
									<div id="singlePayDiv">
										<input type="radio" name="paymentOptionsRadio" class="radio-custom" id="singlePayRadio" value="singlepay" /> 
										<label id="singlePayRadiolabel" for="singlePayRadio" class="radio-custom-label">Single Pay</label>
										<div id="singlePayAmountDiv" class="uk-grid"
											data-uk-grid-margin>
											<div class="uk-width-medium-1-2">
												<div class="parsley-row" id="singlepayDivInputs"
													style="margin-left: 20px;">

													<input type="text" name="singlePayAmount" required
														id="singlePayAmount" value=""
														class="form-control input-sm md-input" />

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="uk-width-medium-1-3">
								<div class="parsley-row">

									<div id="bipayDiv">
										<input type="radio" name="paymentOptionsRadio" id="biPayRadio" class="radio-custom" value="bipay" /> 
										<label id="biPayRadiolabel" for="biPayRadio" class="radio-custom-label">Bipay</label>
										<div id="biPayAmountDiv" class="uk-grid" data-uk-grid-margin>
											<div class="uk-width-medium-1-3">
												<div class="parsley-row" id="bipayDivInputs"
													style="margin-left: 20px;"></div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="uk-width-medium-1-3">
								<div class="parsley-row">
									<div id="multipayDiv">
										<input type="radio" name="paymentOptionsRadio" class="radio-custom"  id="multiPayRadio" value="multipay" /> 
										<label id="multiPayRadiolabel" for="multiPayRadio" class="radio-custom-label">Multipay</label>
										<div class="uk-width-medium-1-3">
											<div class="parsley-row" id="multipayDivInputs"
												style="margin-left: 20px;"></div>
										</div>
									</div>
								</div>
							</div>
						</div>


						<div id="finalPaymentDiv">


							<table id="paymentTable"
								class="uk-table table-striped table-condensed">
								<!-- <caption>Table caption</caption> -->
								<thead>
									<tr>
										<th>Payments</th>
										<th>Amount To pay</th>
										<th>Totals</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td><span id="selectedPaymentMethod"></span></td>
										<td><input type="text" name="totalAmountToPay"
											id="totalAmountToPay" readonly value=""
											class="form-control input-sm md-input" /></td>
										<td><input type="text" name="totalAmountToPaytotals"
											id="totalAmountToPaytotals" readonly value=""
											class="form-control input-sm md-input" /></td>
									</tr>
									<?php if(!$customermembership){?>
									<tr>

										<td>
											<select id="membershipType" name="membershipType" class="input-sm md-input"
												style='padding: 0px; font-weight: bold; color: #727272;'>
													<option value="1">Annual</option>
													<option value="2">Lifetime</option>
											</select>
										</td>
										<td>
											<input type="text" name="membershipAmount"
												id="membershipAmount" readonly value=""
												class="form-control input-sm md-input" /></td>
										<td>
											<input type="text" name="membershipAmounttotals"
												id="membershipAmounttotals" readonly value=""
												class="form-control input-sm md-input" />
											</td>
									</tr>
									<?php }?>
									<tr>
										<td colspan="2" style="text-align: right; font-weight: bold">
										<select name="discountPercentage" id="discountPercentage" class="input-sm md-input"
												style='padding: 0px; font-weight: bold; color: #727272; width:50%; float:right'>
											<option value="0">Select discount percentage</option>
											<option value="10">10%  discount</option>
											<option value="20">20%  discount</option>
											<option value="30">30%  discount</option>
											<option value="40">40%  discount</option>
											<option value="50">50%  discount</option>
										</select>
										<span id="discountText">
										
										</span></td>
										<td><input style="font-weight: bold" type="text"
											name="discountTextBox" id="discountTextBox" readonly value=""
											class="form-control input-sm md-input" />
											<!-- <input type="hidden" name="discountPercentage" id="discountPercentage" value=""/> -->
											
											
										</td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: right; font-weight: bold">Subtotal</td>
										<td><input style="font-weight: bold" type="text"
											name="subtotal" id="subtotal" readonly value=""
											class="form-control input-sm md-input" />
											<input type="hidden" name="totalEnrollmentAmount" id="totalEnrollmentAmount"/>	
											
										</td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: right; font-weight: bold">Tax</td>
										<td><input style="font-weight: bold" type="text"
											name="taxAmount" id="taxAmount" value="" readonly
											class="form-control input-sm md-input" /></td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: right; font-weight: bold">Grand
											Total</td>
										<td><input style="font-weight: bold" type="text"
											name="grandTotal" id="grandTotal" value="" readonly
											class="form-control input-sm md-input"
											style="font-weight:bold" /></td>
									</tr>
								</tbody>
							</table>


							<div id="paymentType" class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-medium-1-3">
									<div class="parsley-row">
										<input type="radio" name="paymentTypeRadio" required
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
							<div id="paymentType" style="width: 100%">
								<div id="cardDetailsDiv" class="uk-grid" data-uk-grid-margin>
									<div class="uk-width-medium-1-1">
										<h4>Card details</h4>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
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
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="cardBankName" class="inline-label">Bank Name of your card<span class="req">*</span>
											</label> <input id="cardBankName" number name="cardBankName"
												maxlength="4" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="cardRecieptNumber" class="inline-label">Reciept number<span class="req">*</span>
											</label> <input id="cardRecieptNumber" number name="cardRecieptNumber"
												maxlength="4" type="text" 
												class="form-control input-sm md-input" />
										</div>
									</div>

								</div>
								<div id="chequeDetailsDiv" class="uk-grid" data-uk-grid-margin>

									<div class="uk-width-medium-1-1">
										<h4>Cheque details</h4>
										<br clear="all"/>
									</div>
									<br clear="all"/><br clear="all"/>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeBankName" class="inline-label">Bank name<span
												class="req">*</span></label> <input id="chequeBankName"
												name="bankName" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeNumber" class="inline-label">Cheque number<span
												class="req">*</span></label> <input id="chequeNumber"
												name="chequeNumber" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
								</div>
								
								
								<div id="emailEnrollPrintDiv" class="uk-grid" data-uk-grid-margin>

									<div class="uk-width-medium-1-1">
										<h4>Invoice option</h4>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											
											<input id="invoicePrintOption" name="invoicePrintOption"  value="yes"  type="checkbox"  class="checkbox-custom" />
											<label for="invoicePrintOption"  class="checkbox-custom-label">Print Invoice<span
												class="req">*</span></label> 
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<input id="emailOption" name="emailOption" type="checkbox"  value="yes" class="checkbox-custom"  />
											<label for="emailOption" class="checkbox-custom-label">Email Invoice<span
												class="req">*</span></label> 
										</div>
									</div>
								</div>

							</div>












						</div>



					</div>
				</div>
				<div class="modal-footer">
					<!-- <button type="button" class="btn btn-default" id="getSchedulesSessions" >Fetch Number of sessions</button> -->
					<button type="submit" class="btn btn-default" id="enrollNow">Enroll
						now</button>
					<button type="button" class="btn btn-default"
						id="closeEnrollmentModal" data-dismiss="modal">Close</button>
				</div>

			</form>
		</div>

	</div>
</div>





<!-- Error Div  -->
<div id="errorModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					Something wrong!!!
				</h4>
			</div>
			<div class="modal-body">
				<div id="messageErrorDiv"></div>
				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			
		</div>

	</div>
</div>
<!-- Error Div -->


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
					<div class="uk-width-medium-1-2" id="introvisitEditDiv">
						<div class="parsley-row">
							<label for="iveditSelect" class="inline-label">Status<span
												class="req">*</span></label>
							<select name="iveditSelect" id="iveditSelect" class="input-sm md-input" data-iveditid=""  class="form-control input-sm md-input" style='padding: 0px; font-weight: bold; color: #727272;'>
								<option value="ACTIVE">Active</option>
								<option value="ATTENDED">Attended</option>
								<option value="NO_SHOW">No show</option>
								<option value="IN_ACTIVE">In active</option>
							</select>
						</div>
					</div>
					 <br clear="all" /> <br clear="all" />
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="healthIssue">Comments<span class="req">*</span></label>
								{{ Form::textarea('ivcustomerCommentTxtarea', null, ['id'=>'ivcustomerCommentTxtarea',
								'size' => '60x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
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







<!-- Add Kids  -->
<div id="addKidsModal" class="modal fade" role="dialog"
	style="margin-top: 50px; z-index: 99999;">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					Edit Kids(<span id="kidNameInPopup"></span>)
				</h4>
			</div>
			<div class="modal-body">
				<div id="messageStudentAddDiv"></div>
				<div id="formBody">
					<form id="kidsAddForm" method="post>
				      		<br  clear="all" />
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="customerName">Kid Name<span class="req">*</span></label>
								{{Form::text('studentName', null,array('id'=>'studentName',
								'required', 'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="nickname">Nickname<span class="req">*</span></label>
								{{Form::text('nickname', null,array('id'=>'nickname',
								'required', 'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="studentDob">Date of birth<span class="req">*</span></label>
								{{Form::text('studentDob',
								null,array('id'=>'studentDob','required', 'class' => ''))}}
							</div>
						</div>
					</div>
					<br clear="all" />
					<br clear="all" />
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="studentGender">Gender<span class="req">*</span></label>
								<select id="studentGender" name="studentGender"
									class="form-control input-sm md-input"
									style="padding: 0px; font-weight: bold; color: #727272;">
									<option value=""></option>
									<option value="male">Male</option>
									<option value="female">Female</option>
								</select>

							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="school">School<span class="req">*</span></label>
								{{Form::text('school', null,array('id'=>'school', 'required',
								'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="location">Location<span class="req">*</span></label>
								{{Form::text('location', null,array('id'=>'location',
								'required', 'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
					</div>
					<br clear="all" />
					<br clear="all" />
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="hobbies">Hobbies<span class="req">*</span></label>
								{{Form::text('hobbies', null,array('id'=>'hobbies', 'required',
								'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="emergencyContact">Emergency contact<span class="req">*</span></label>
								{{Form::text('emergencyContact',
								null,array('id'=>'emergencyContact', 'required', 'class' =>
								'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="remarks">Remarks<span class="req">*</span></label>
								{{Form::text('remarks', null,array('id'=>'remarks', 'required',
								'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
					</div>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="healthIssue">Health Issues<span class="req">*</span></label>
								{{ Form::textarea('healthIssue', null, ['id'=>'healthIssue',
								'size' => '10x3', 'class' => 'form-control input-sm md-input'])
								}}
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" id="saveKidsBtn" class="md-btn md-btn-primary">Save</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
			</form>
		</div>

	</div>
</div>
<!-- Add Kids -->


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
