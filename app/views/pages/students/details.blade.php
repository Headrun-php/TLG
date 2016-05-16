@extends('layout.master') @section('libraryCSS')
<link
	href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css'
	rel='stylesheet' />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
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
//getEligibleClasses()
var selectedNoOfClass=0;
var DiscountPercentage=0;
var DiscountAmount=0;
var selectedNoOfClass1=0;
var selectedNoOfClass2=0;
var selectedNoOfClass3=0;
var firstselectedNoOfClass=0;
var secondselectedNoOfClass=0;
var thirdselectedNoOfClass=0;

var estimate_master_no=0;
var totalCostForpay;

var batch1ClassCost=0;
var batch2ClassCost=0;
var batch3ClassCost=0;

var enddate1='';
var enddate2='';
var enddate3='';

var estimate_id1=0;
var estimate_id2=0;
var estimate_id3=0;

// for payments

var bipay=[];
var multipay=[];

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

$("#OrderDate").kendoDateTimePicker();
$("#OrderDate2").kendoDateTimePicker();
$("#OrderDate3").kendoDateTimePicker();
$("#OrderDate4").kendoDateTimePicker();
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
          	  	//console.log(response);      	 	
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
      	  //	console.log(response);      	 	
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
      	  	//console.log(response);      	 	
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
                if($('input[name="enrollmentClassesSelect"]').is(':checked')){
                 if($("input[name='enrollmentClassesSelect']:checked").val()!='custom'){
                    selectedNoOfClass=parseInt($("input[name='enrollmentClassesSelect']:checked").val());
                    DiscountPercentage=parseFloat($("input[name='enrollmentClassesSelect']:checked").attr('discountpercentage'));
                    var position=parseInt($("input[name='enrollmentClassesSelect']:checked").attr('position'));
                    //DiscountAmount=parseInt($('#discountAmount'+position).val());
                  }else{
                    selectedNoOfClass=parseInt($('#customEnrollmemtNoofClass').val());
                    DiscountPercentage=parseFloat($('#customEnrollmemtDiscountPercentage').val());
                    //DiscountAmount=parseInt($('#customEnrollmemtDiscount').val());
                  }
                $('#enrollmentcontinue2').hide();
                $('#enrollmentcontinue3').hide();
             
                $('#enrollmentMsg').html("");
		//$("#enrollmentModal").modal('show');
                $("#enrollmentModal").modal({
                    backdrop: 'static',
                    keyboard: false 
                });
                
		$("#formBody").show();
		$("#messageStudentEnrollmentDiv").html("");
		getSeasons();
                getEligibleClasses();
                }else{
                    $('#enrollmentMsg').html("<p class='uk-alert uk-alert-danger'>please select the Number of Classes </p>");
                }
               

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
                $('#enrollNow').addClass('disabled');
	//if($("#enrollmentOptions").val() == "enrollandpay" ){
                if((firstselectedNoOfClass+secondselectedNoOfClass+thirdselectedNoOfClass)===selectedNoOfClass){
		$("#selectedSessions").val(selectedNoOfClass);
                
                
                // working out for singlepay
                 
                 
                totalCostForpay=(firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost);
                //console.log(totalCostForpay);
                $("#singlePayDiv").show();
                $("#singlePayAmount").val(totalCostForpay);
                $("#singlePayAmountDiv").show();
                $("#paymentType").show();
                $("#paymentOptions").show(); 
            
                // working out for bipay
                
                if(selectedNoOfClass > 5){
                    var totalCostForBipay=totalCostForpay;
                    var totalnoofclasses=firstselectedNoOfClass+secondselectedNoOfClass+thirdselectedNoOfClass;
                    //** working out for Even or odd Number of classes **//
                    if(totalnoofclasses%2==0){
                        var firstInstallmentsessionNo=totalnoofclasses/2;
                        var secondInstallmentsessionNo=totalnoofclasses/2;
                    }else if(totalnoofclasses%2!=0){
                        var firstInstallmentsessionNo=parseInt(totalnoofclasses/2);
                        var secondInstallmentsessionNo=parseInt(totalnoofclasses/2)+1;
                        
                    }
                    //** working out for different seasons and for diffrent cases **//
                    if(firstselectedNoOfClass!=0 &&secondselectedNoOfClass==0 && thirdselectedNoOfClass==0){
                            //** working out for 1 batch **//
                            bipay[1]=firstInstallmentsessionNo*batch1ClassCost;
                            bipay[2]=secondInstallmentsessionNo*batch1ClassCost;
                            
                        }else if(firstselectedNoOfClass!=0 && secondselectedNoOfClass!=0 && thirdselectedNoOfClass==0){
                            //**  working out for 2 batches**//
                            if(firstselectedNoOfClass==firstInstallmentsessionNo){
                                bipay[1]=firstInstallmentsessionNo*batch1ClassCost;
                                bipay[2]=secondInstallmentsessionNo*batch2ClassCost;
                            }else if(firstselectedNoOfClass<firstInstallmentsessionNo ){
                                bipay[1]=(firstselectedNoOfClass*batch1ClassCost)+((firstInstallmentsessionNo-firstselectedNoOfClass)*batch2ClassCost);
                                bipay[2]=secondInstallmentsessionNo*batch2ClassCost;
                            }else if(firstselectedNoOfClass>firstInstallmentsessionNo){
                                bipay[1]=(firstInstallmentsessionNo*batch1ClassCost);
                                bipay[2]=secondselectedNoOfClass*batch2ClassCost+((firstselectedNoOfClass-firstInstallmentsessionNo)*batch1ClassCost);
                            }
                            $("input[name='bipaybatch1availablesession']").val(firstselectedNoOfClass);
                            $("input[name='bipaybatch2availablesession']").val(secondselectedNoOfClass);
                    
                        }else if(firstselectedNoOfClass!=0 && secondselectedNoOfClass!=0 && thirdselectedNoOfClass!=0){
                            //** working out for 3 batches **//
                            if(firstselectedNoOfClass < firstInstallmentsessionNo){
                                 // ** checking B1 + B2 lies in first Installment **//
                                 if((firstselectedNoOfClass+secondselectedNoOfClass)==firstInstallmentsessionNo){
                                     bipay[1]=(firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost);
                                     bipay[2]=(thirdselectedNoOfClass*batch3ClassCost);
                                 }else
                                 // ** checking B1 + B2 lies in second Installment **//
                                 if((firstselectedNoOfClass+secondselectedNoOfClass)>firstInstallmentsessionNo){
                                     bipay[1]=((firstselectedNoOfClass*batch1ClassCost)+((firstInstallmentsessionNo-firstselectedNoOfClass)*batch2ClassCost));
                                     bipay[2]=((secondInstallmentsessionNo-thirdselectedNoOfClass)*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost);       
                                 }else
                                 // ** checking B1 + B2 lies in first Installment **//
                                 if((firstselectedNoOfClass+secondselectedNoOfClass)<firstInstallmentsessionNo){
                                     bipay[1]=(firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost)+((firstInstallmentsessionNo-(firstselectedNoOfClass+secondselectedNoOfClass))*batch3ClassCost);
                                     bipay[2]=(secondInstallmentsessionNo*batch3ClassCost);
                                }
                            
                            }else if(firstselectedNoOfClass > firstInstallmentsessionNo){
                                //** if B1 > first installment and B2 and B3 lies in  2nd installment **//
                                  bipay[1]=firstInstallmentsessionNo*batch1ClassCost;
                                  bipay[2]=(secondselectedNoOfClass*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost)+((secondInstallmentsessionNo-(secondselectedNoOfClass+thirdselectedNoOfClass))*batch1ClassCost);
                                                                   
                            }else if((firstselectedNoOfClass)==firstInstallmentsessionNo){
                                //** if B1==first installment and B2 and B3 lies in  2nd installment **//
                                bipay[1]=(firstselectedNoOfClass*batch1ClassCost);
                                bipay[2]=(secondselectedNoOfClass*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost);
                            }
                                $("input[name='bipaybatch1availablesession']").val(firstselectedNoOfClass);
                                $("input[name='bipaybatch2availablesession']").val(secondselectedNoOfClass);
                                $("input[name='bipaybatch3availablesession']").val(thirdselectedNoOfClass);
                        }
                    
                   /*     
                    if(totalCostForBipay%2===0){
                        bipay[1]=totalCostForBipay/2;
                        bipay[2]=totalCostForBipay/2;
                    }else{
                        bipay[1]=parseInt(totalCostForBipay/2);
                        bipay[2]=(parseInt(totalCostForBipay/2))+1;
                    }
                    */
                    $("#biPayAmountDiv").show();
                    $("#bipayDiv").show();
                    $("#bipayDivInputs").empty();
                    var bipaystring = "";
                    
                    for(var i=1;i<bipay.length;i++){
                        bipaystring += '<input id="bipayAmount'+i+'" name="bipayAmount'+i+'" readonly value="'+bipay[i]+'" style="float:left;width:60px;" class="form-control input-sm md-input"/>';
                    }
                    //console.log(bipaystring);
                    $("#bipayDivInputs").append(bipaystring);
		    $("#biPayRadio").prop('disabled',false);
		    $("#biPayRadio").removeClass('disabled');
		    $("#biPayRadiolabel").removeClass('disabled');
                    
                    
                }else{

					$("#biPayAmountDiv").show();
					
					$("#bipayDiv").show();
					$("#bipayDivInputs").empty();

					//singlePayRadio biPayRadio multiPayRadio
					
					$("#biPayRadio").attr('disabled','true');
					$("#biPayRadio").addClass('disabled');
					$("#biPayRadiolabel").addClass('disabled');
		}
                //console.log(bipay);
                
                // working for multipay
                //
                
                if(selectedNoOfClass > 20){
                $("#multipayDiv").show();
                $("#multipayDivInputs").empty();
                var round=0;
                var modulus=0;
                var multipayamt={};
                var firstselectedNoOfClassCost=0;
                if(selectedNoOfClass==40){
                    multipay[0]=10;
                    multipay[1]=10;
                    multipay[2]=10;
                    multipay[3]=10;
                    
                    
                }else if(selectedNoOfClass > 30 && selectedNoOfClass < 40 ){
                    modulus=selectedNoOfClass%30;
                    multipay[0]=modulus;
                    multipay[1]=10;
                    multipay[2]=10;
                    multipay[3]=10;
                }else if(selectedNoOfClass > 20 && selectedNoOfClass < 30){
                    modulus=selectedNoOfClass%20;
                    multipay[0]=modulus;
                    multipay[1]=10;
                    multipay[2]=10;
                }else if(selectedNoOfClass == 30 ){
                    multipay[0]=10;
                    multipay[1]=10;
                    multipay[2]=10;
                }
                
                    if(firstselectedNoOfClass== 40 && secondselectedNoOfClass==0 && thirdselectedNoOfClass==0){
                        //** working for only 1 batch **//
                        multipayamt[0]=multipay[0]*batch1ClassCost;
                        multipayamt[1]=multipay[1]*batch1ClassCost;
                        multipayamt[2]=multipay[2]*batch1ClassCost;
                        multipayamt[3]=multipay[3]*batch1ClassCost;
                        
                    }else if(firstselectedNoOfClass!=0 && secondselectedNoOfClass==0 && thirdselectedNoOfClass==0){
                        //**working out for 1 batch **//
                        multipayamt[0]=multipay[0]*batch1ClassCost;
                        multipayamt[1]=multipay[1]*batch1ClassCost;
                        multipayamt[2]=multipay[2]*batch1ClassCost;
                        if(typeof multipay[3]!=='undefined'){
                                 multipayamt[3]=multipay[3]*batch1ClassCost;
                        }
                        
                    }else if(secondselectedNoOfClass!=0 && firstselectedNoOfClass!=0 && thirdselectedNoOfClass==0){
                        //** working for 2 batches **//
                        var totalClassesSelected=firstselectedNoOfClass+secondselectedNoOfClass;
                        if(firstselectedNoOfClass < multipay[0]){
                            //** batch change lies in first multipay **//
                            firstselectedNoOfClassCost=firstselectedNoOfClass*batch1ClassCost;
                            multipayamt[0]=(multipay[0]-firstselectedNoOfClass)*batch2ClassCost;
                            multipayamt[0]=multipayamt[0]+firstselectedNoOfClassCost;
                            multipayamt[1]=multipay[1]*batch2ClassCost;
                            multipayamt[2]=multipay[2]*batch2ClassCost;
                            if(typeof multipay[3]!=='undefined'){
                                 multipayamt[3]=multipay[3]*batch2ClassCost;
                            }
                        }else if(firstselectedNoOfClass > multipay[0] &&  firstselectedNoOfClass < (multipay[0]+multipay[1]) ){
                            var temp=firstselectedNoOfClass;
                            temp=temp-multipay[0];
                            firstselectedNoOfClassCost=temp*batch1ClassCost;
                            multipayamt[0]=multipay[0]*batch1ClassCost;
                            multipayamt[1]=((multipay[1]-temp)*batch2ClassCost)+firstselectedNoOfClassCost;
                            multipayamt[2]=multipay[2]*batch2ClassCost;
                            if(typeof multipay[3]!=='undefined'){
                            multipayamt[3]=multipay[3]*batch2ClassCost;
                            }
                        }else if(firstselectedNoOfClass > (multipay[0]+multipay[1]) && firstselectedNoOfClass < (multipay[0]+multipay[1]+multipay[2])){
                            var temp=firstselectedNoOfClass;
                            temp=temp-(multipay[0]+multipay[1]);
                            firstselectedNoOfClassCost=temp*batch1ClassCost;
                            multipayamt[0]=multipay[0]*batch1ClassCost;
                            multipayamt[1]=multipay[1]*batch1ClassCost;
                            multipayamt[2]=((multipay[2]-temp)*batch2ClassCost)+firstselectedNoOfClassCost;
                            if(typeof multipay[3]!=='undefined'){
                            multipayamt[3]=multipay[3]*batch2ClassCost;
                            }
                        }else if(firstselectedNoOfClass > (multipay[0]+multipay[1]+multipay[2]) && firstselectedNoOfClass < (multipay[0]+multipay[1]+multipay[2]+multipay[3]) ){
                            var temp =firstselectedNoOfClass;
                            temp=temp-(multipay[0]+multipay[1]+multipay[2]); 
                            firstselectedNoOfClassCost=temp*batch1ClassCost;
                            multipayamt[0]=multipay[0]*batch1ClassCost;
                            multipayamt[1]=multipay[1]*batch1ClassCost;
                            multipayamt[2]=multipay[2]*batch1ClassCost;
                            multipayamt[3]=firstselectedNoOfClassCost+((multipay[3]-temp)*batch2ClassCost);
                        }else if(firstselectedNoOfClass==multipay[0]){
                            //** when b1 == pay1 **//
                            multipayamt[0]=multipay[0]*batch1ClassCost;
                            multipayamt[1]=multipay[1]*batch2ClassCost;
                            multipayamt[2]=multipay[2]*batch2ClassCost;
                            if(typeof multipay[3]!=='undefined'){
                                 multipayamt[3]=multipay[3]*batch2ClassCost;
                            }
                        }else if((firstselectedNoOfClass)==(multipay[0]+multipay[1])){
                            //** when b1=pay1+pay2 **//
                           
                            multipayamt[0]=multipay[0]*batch1ClassCost;
                            multipayamt[1]=multipay[1]*batch1ClassCost;
                            multipayamt[2]=multipay[2]*batch2ClassCost;
                            if(typeof multipay[3]!=='undefined'){
                                 multipayamt[3]=multipay[3]*batch2ClassCost;
                            }
                        }else if(firstselectedNoOfClass==(multipay[0]+multipay[1]+multipay[2])){
                            //** when b1=pay1+pay2+pay3 **//
                            multipayamt[0]=multipay[0]*batch1ClassCost;
                            multipayamt[1]=multipay[1]*batch1ClassCost;
                            multipayamt[2]=multipay[2]*batch1ClassCost;
                            if(typeof multipay[3]!=='undefined'){
                                 multipayamt[3]=multipay[3]*batch2ClassCost;
                            }
                        }
                        
                        
                    }else if(secondselectedNoOfClass!=0 && firstselectedNoOfClass!=0 && thirdselectedNoOfClass!=0){
                        //** working out for 3 batches **//
                        var totalClassesSelected=firstselectedNoOfClass+secondselectedNoOfClass+thirdselectedNoOfClass;
                        if(firstselectedNoOfClass < multipay[0]){
                            if((firstselectedNoOfClass+secondselectedNoOfClass) < multipay[0]){
                                //** working on first batch change lies in first payment **//
                                var thirdbatchFirstpaymentAmount=(multipay[0]-(firstselectedNoOfClass+secondselectedNoOfClass))*batch3ClassCost;
                                multipayamt[0]=(firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost)+thirdbatchFirstpaymentAmount;
                                multipayamt[1]=multipay[1]*batch3ClassCost;
                                multipayamt[2]=multipay[2]*batch3ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=multipay[3]*batch3ClassCost;
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass)== multipay[0]){
                                //** working on firstbatch+secondbatch=firstmultipay *//
                                multipayamt[0]=(firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost);
                                multipayamt[1]=multipay[1]*batch3ClassCost;
                                multipayamt[2]=multipay[2]*batch3ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=multipay[3]*batch3ClassCost;
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass) < (multipay[0]+multipay[1])){
                                //** working on firstbatch+secondbatch < 2nd multipay **//
                                multipayamt[0]=(firstselectedNoOfClass*batch1ClassCost)+((multipay[0]-firstselectedNoOfClass)*batch2ClassCost);
                                multipayamt[1]=((secondselectedNoOfClass-(multipay[0]-firstselectedNoOfClass))*batch2ClassCost)+((multipay[1]-(secondselectedNoOfClass-(multipay[0]-firstselectedNoOfClass)))*batch3ClassCost);
                                multipayamt[2]=multipay[2]*batch3ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=multipay[3]*batch3ClassCost;
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass)== (multipay[0]+multipay[1])){
                                //** working on firstbatch+secondbatch == 2nd multipay **//
                                multipayamt[0]=(firstselectedNoOfClass*batch1ClassCost)+((multipay[0]-firstselectedNoOfClass)*batch2ClassCost);
                                multipayamt[1]=multipay[1]*batch2ClassCost;
                                multipayamt[2]=multipay[2]*batch3ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=multipay[3]*batch3ClassCost;
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass) < (multipay[0]+multipay[1]+multipay[2])){
                                //** working on firstbatch+secondbatch < 3nd multipay **//
                                multipayamt[0]=(firstselectedNoOfClass*batch1ClassCost)+((multipay[0]-firstselectedNoOfClass)*batch2ClassCost);
                                multipayamt[1]=multipay[1]*batch2ClassCost;
                                multipayamt[2]=(((firstselectedNoOfClass+secondselectedNoOfClass)-(multipay[0]+multipay[1]))*batch2ClassCost)+((multipay[2]-((firstselectedNoOfClass+secondselectedNoOfClass)-(multipay[0]+multipay[1])))*batch3ClassCost);
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=multipay[3]*batch3ClassCost;
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass) == (multipay[0]+multipay[1]+multipay[2])){
                                //** working on firstbatch+secondbatch = 3nd multipay **//
                                multipayamt[0]=(firstselectedNoOfClass*batch1ClassCost)+((multipay[0]-firstselectedNoOfClass)*batch2ClassCost);
                                multipayamt[1]=multipay[1]*batch2ClassCost;
                                multipayamt[2]=multipay[2]*batch2ClassCost;
                                 if(typeof multipay[3]!=='undefined'){
                                     multipayamt[3]=multipay[3]*batch3ClassCost;
                                 } 
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass)<(multipay[0]+multipay[1]+multipay[2]+multipay[3])){
                                //** working on firstbatch+secondbatch < 4th multipay **//
                                multipayamt[0]=(firstselectedNoOfClass*batch1ClassCost)+((multipay[0]-firstselectedNoOfClass)*batch2ClassCost);
                                multipayamt[1]=multipay[1]*batch2ClassCost;
                                multipayamt[2]=multipay[2]*batch2ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                     multipayamt[3]=((multipay[3]-thirdselectedNoOfClass)*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost);
                                }
                            } 
                        }else if(firstselectedNoOfClass == multipay[0]){
                                    //** first batch== multipay1**//
                            if((firstselectedNoOfClass+secondselectedNoOfClass) < (multipay[0]+multipay[1])){
                                //** first batch change lies in 2nd pay **//
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=(secondselectedNoOfClass*batch2ClassCost)+((multipay[1]-secondselectedNoOfClass)*batch3ClassCost);
                                multipayamt[2]=multipay[2]*batch3ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                     multipayamt[3]=(multipay[3]*batch3ClassCost);
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass)==(multipay[0]+multipay[1])){
                                //** first batch+ sec batch == 1stpay+2nd pay **//
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=multipay[1]*batch2ClassCost;
                                multipayamt[2]=multipay[2]*batch3ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                     multipayamt[3]=(multipay[3]*batch3ClassCost);
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass) < (multipay[0]+multipay[1]+multipay[2])){
                                //** first batch+sec batch < 1+2+3 payments change lies in 3rd pay **//
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=multipay[1]*batch2ClassCost;
                                multipayamt[2]=((secondselectedNoOfClass-multipay[1])*batch2ClassCost)+((multipay[2]-(secondselectedNoOfClass-multipay[1]))*batch3ClassCost);
                                if(typeof multipay[3]!=='undefined'){
                                     multipayamt[3]=(multipay[3]*batch3ClassCost);
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass)==(multipay[0]+multipay[1]+multipay[2])){
                                //** first batch + sec btach == 1st+2nd+3rd pay **//
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=multipay[1]*batch2ClassCost;
                                multipayamt[2]=multipay[2]*batch2ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                     multipayamt[3]=(multipay[3]*batch3ClassCost);
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass)<(multipay[0]+multipay[1]+multipay[2]+multipay[3])){
                                //** first batch+sec batch  3rd pay **//
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=multipay[1]*batch2ClassCost;
                                multipayamt[2]=multipay[2]*batch2ClassCost;
                                 if(typeof multipay[3]!=='undefined'){
                                     multipayamt[3]=((secondselectedNoOfClass-(multipay[1]+multipay[2]))*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost);
                                }
                            }
                        }else if(firstselectedNoOfClass > multipay[0] && firstselectedNoOfClass <  (multipay[0]+multipay[1])){
                            if((firstselectedNoOfClass+secondselectedNoOfClass) < (multipay[0]+multipay[1]) ){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=(secondselectedNoOfClass*batch2ClassCost)+((firstselectedNoOfClass-multipay[0])*batch1ClassCost);
                                multipayamt[2]=(multipay[1]-((firstselectedNoOfClass-multipay[0])+secondselectedNoOfClass))*batch3ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                 multipayamt[3]=(multipay[3]*batch3ClassCost);
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass)==(multipay[0]+multipay[1])){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=(secondselectedNoOfClass*batch2ClassCost)+((multipay[1]-secondselectedNoOfClass)*batch1ClassCost);
                                multipayamt[2]=multipay[2]*batch3ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                 multipayamt[3]=(multipay[3]*batch3ClassCost);
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass) < (multipay[0]+multipay[1]+multipay[2])){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=(secondselectedNoOfClass-multipay[0])*batch1ClassCost+((multipay[1]-(secondselectedNoOfClass-multipay[0]))*batch2ClassCost);
                                multipayamt[2]=((thirdselectedNoOfClass-multipay[3])*batch3ClassCost)+(multipay[2]-(thirdselectedNoOfClass-multipay[3])*batch2ClassCost);
                                if(typeof multipay[3]!=='undefined'){
                                 multipayamt[3]=(multipay[3]*batch3ClassCost);
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass) == (multipay[0]+multipay[1]+multipay[2])){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=((firstselectedNoOfClass-multipay[0])*batch1ClassCost)+((secondselectedNoOfClass-multipay[2])*batch2ClassCost);
                                multipayamt[2]=multipay[2]*batch2ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                  multipayamt[3]=multipay[3]*batch3ClassCost;
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass) < (multipay[0]+multipay[1]+multipay[2]+multipay[3])){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=((firstselectedNoOfClass-multipay[0])*batch1ClassCost)+(((multipay[1]+multipay[0])-firstselectedNoOfClass)*batch2ClassCost);
                                multipayamt[2]=multipay[2]*batch2ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                multipayamt[3]=((multipay[3]-thirdselectedNoOfClass)*batch2ClassCost)+((multipay[3]-(multipay[3]-thirdselectedNoOfClass))*batch3ClassCost);
                                }
                            }   
                        }else if(firstselectedNoOfClass == (multipay[0]+multipay[1])){
                            if((firstselectedNoOfClass+secondselectedNoOfClass) < (multipay[0]+multipay[1]+multipay[2])){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=multipay[1]*batch1ClassCost;
                                multipayamt[2]=(secondselectedNoOfClass*batch2ClassCost)+((multipay[2]-secondselectedNoOfClass)*batch3ClassCost);
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=multipay[3]*batch3ClassCost;
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass)==(multipay[0]+multipay[1]+multipay[2])){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=multipay[1]*batch1ClassCost;
                                multipayamt[2]=multipay[2]*batch2ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=multipay[3]*batch3ClassCost;
                                }
                            }else if((firstselectedNoOfClass+secondselectedNoOfClass) > (multipay[0]+multipay[1]+multipay[2])){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=multipay[1]*batch1ClassCost;
                                multipayamt[2]=multipay[2]*batch2ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=((multipay[3]-thirdselectedNoOfClass)*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost);
                                }
                            }
                        }else if(firstselectedNoOfClass == (multipay[0]+multipay[1]+multipay[2])){
                            if((firstselectedNoOfClass+secondselectedNoOfClass) > (multipay[0]+multipay[1]+multipay[2])){
                                multipayamt[0]=multipay[0]*batch1ClassCost;
                                multipayamt[1]=multipay[1]*batch1ClassCost;
                                multipayamt[2]=multipay[2]*batch1ClassCost;
                                if(typeof multipay[3]!=='undefined'){
                                    multipayamt[3]=((secondselectedNoOfClass)*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost);
                                }
                            }
                        }
                        
                    }
                
                
                
                
                var multipaystring = "";
                for(var i=0;i<multipay.length;i++){
                    multipaystring += '<input id="multipayAmount'+(i+1)+'" name="multipayAmount'+multipay[i]+'" readonly value="'+multipayamt[i]+'"  style="float:left;width:60px;" class="form-control input-sm md-input"/>';
                }
                //console.log(multipaystring);
                
                /*
					var m = 1;
					
					$.each(payments.multipay.pays, function (index, item) {
						if(item.amount != "undefined"){
						 multipaystring += '<input id="multipayAmount'+m+'" name="multipayAmount'+m+'" readonly value="'+item.amount+'"  style="float:left;width:60px;" class="form-control input-sm md-input"/>'; 
						 m++;             
						}
		            });
                    */
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



                
                
                $('#enrollNow').addClass('disabled');
                


					
					
					
				
                
                
                /*
		$.ajax({
	        type: "POST",
	        url: "{{URL::to('/quick/getPaymentTypes')}}",
	        data: {'availableSession': selectedNoOfClass,'batchId':$('#batchCbx').val(),},
	        dataType:"json",
	        success: function (response)
	        {
	      		//console.log(response);
				var payments = response.payments;

				//console.log(payments.multipay.eligible);

				$("#singlePayDiv").show();
				$("#singlePayAmount").val(response.payments.singlepay);
				$("#singlePayAmountDiv").show();

				
				$("#paymentType").show();
				$("#paymentOptions").show(); 
             */
                                /*
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
                                */
                /*                
                                if(payments.modifiedbipay.elligible == "YES"){


					$("#biPayAmountDiv").show();
					
					$("#bipayDiv").show();
					$("#bipayDivInputs").empty();
					var bipaystring = "";
					var i = 1;
					$.each(payments.modifiedbipay.pays, function (index, item) {

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
                                
	      	    $('#enrollNow').addClass('disabled');	
	        }
                
	    });

*/














		
		
	}
	
});

$("#cardDetailsDiv").hide();
$("#chequeDetailsDiv").hide();
$('#cardDetailsDiv2').hide();
$('#chequeDetailsDiv2').hide();
$('#cardDetailsDiv3').hide();
$('#chequeDetailsDiv3').hide();
$('#cardDetailsDiv4').hide();
$('#chequeDetailsDiv4').hide();
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

	//console.log("payment types");
	$("#finalPaymentDiv").show();

	var selected = $("input[type='radio'][name='paymentOptionsRadio']:checked").val();
	
	if(selected == "singlepay"){
		//console.log(availableSessionCount);
		
		$("#singlePayAmountDiv").show();
		$("#singlePayAmount").val(totalCostForpay);
		$("#totalAmountToPay").val(totalCostForpay);
		$("#totalAmountToPaytotals").val(totalCostForpay);
			
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
	calculateFinalAmount();
	

	$("#paymentType").show();
	
	
});
//
//$(document).on('change', "#discountPercentage", function() {
//
//	calculateFinalAmount()
//	
//});



function calculateFinalAmount(){
        var second_child_discount_amt=0;
	var second_class_discount_amt=0;
        <?php if(!$customermembership){?>
	var finalAmount = (parseFloat($("#totalAmountToPay").val())+parseFloat($("#membershipAmount").val()));
	<?php }else{?>
	var finalAmount = (parseFloat($("#totalAmountToPay").val()));
	<?php }?>
	//console.log(finalAmount);
        //var discountPercentage  =  parseInt($("input[name='enrollmentClassesSelect']:checked").attr('discountpercentage'));
                            
                            if(DiscountAmount==0){
                                    var percentAmount = parseFloat($("#totalAmountToPaytotals").val()*DiscountPercentage/100);
                                    $('#discount').html('<p>Discount:'+DiscountPercentage+"%</p>");
                            }
//                            else{
//                                    var percentAmount=DiscountAmount;
//                                    $('#discount').html("<p>Discount:Amount</p>");
//                            }
                        
                                $("#discountTextBox").val("-"+percentAmount);
                                
                        	finalAmount = parseFloat(finalAmount-percentAmount);
                                //lol
                                <?php if($discount_second_child_elligible){ ?>
                                    
                                    $('#second_child_discount').html('<p>Sibling Consideration:'+{{$discount_second_child}}+'%</p>');
                                    second_child_discount_amt=parseFloat(finalAmount*{{$discount_second_child}}/100);
                                    $('#second_child_amount').val('-'+second_child_discount_amt);
                                    finalAmount=parseFloat(finalAmount-second_child_discount_amt);
                                <?php } ?>
                                  
                                <?php if($discount_second_class_elligible){ ?>
                                    
                                    $('#second_class_discount').html('<p>Multi Classes:'+{{$discount_second_class}}+'%</p>');
                                    second_class_discount_amt=parseFloat(finalAmount*{{$discount_second_class}}/100);
                                    $('#second_class_amount').val('-'+second_class_discount_amt);
                                    finalAmount=parseFloat(finalAmount-second_class_discount_amt);
                                <?php } ?>
                                    
                        	$("#subtotal").val(Math.round(finalAmount*100)/100);
                                var tax =(finalAmount*14.5/100);
                                tax=Math.round(tax*100)/100;
                              
                                $("#taxAmount").val(tax);
                                finalAmount=finalAmount-tax;
                                finalAmount=Math.round(finalAmount*100)/100;
                        	$("#grandTotal").val(finalAmount);
                                
                                $('#discountPercentage').val(DiscountPercentage);
                               
        /*
            $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/discount/getdiscount')}}",
                        data: {'studentId':studentId,'seasonId':$('#SeasonsCbx').val()},
			dataType: 'json',
			success: function(response){
                            console.log(response.status);
                            console.log(response.discount);
                            
                              
                        }
             }) ;

	*/
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

	
	
	
}


function applyDiscountOnLastPayment(){

	var selectedOption = $("input[type='radio'][name='paymentOptionsRadio']:checked").val();
}




$(document).on('change', "#membershipType", function() {
	//console.log($(this).val());

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
        $('#enrollmentStartDate').val('');
        $('#enrollmentEndDate').val('');
	$("#paymentOptions").hide();
	$("#sessionsTable").hide();
	$("#enrollmentOptions").val("enroll");

	if($(this).data('closemode') == 'print'){
			   window.location.reload(1);
	}
	
});

$('#enrollmentStartDate').change(function(){
   $('#batch1Msg').html('');
   $('#batchCbx').val('');
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
            format: "yyyy-MM-dd",
	// change:function (){
//		prepareGetClasses()
//	} 
	//min: todaysDate
});


$("#enrollmentEndDate").kendoDatePicker({
	change:function (){
		prepareGetClasses();
	}
	
});


function prepareGetClasses(){
	$("#paymentOptions").hide();
	$("#finalPaymentDiv").hide();
	$("input[name='paymentOptionsRadio']").attr('checked', false);
       
	//checking for first case 1 season only
       // console.log('prepareclasses');
        if(firstselectedNoOfClass===selectedNoOfClass){
           // console.log('==')
            if($('#SeasonsCbx').val()!='' && $('#batchCbx').val() != "" && $('#enrollmentStartDate').val() != "" && $("#eligibleClassesCbx").val() != ""){
                    console.log('valid 1 season only');
                    $("#messageStudentEnrollmentDiv").html('');
                    $("#availableSessions").html(firstselectedNoOfClass);
                    $("#totalAmount").html((firstselectedNoOfClass*batch1ClassCost));
                    $("#amountPerSesssion").html(batch1ClassCost);
	            $("#totalEnrollmentAmount").val(firstselectedNoOfClass*batch1ClassCost);
	            $("#sessionsTable").show();
                    $("#enrollNow").show();
                    $('#enrollmentcontinue2').hide();
                    $('#enrollmentcontinue3').hide();
                    $('#enrollNow').addClass('disabled');
            }
        }else if((firstselectedNoOfClass+secondselectedNoOfClass)===selectedNoOfClass){
            if($('#SeasonsCbx').val()!='' && $('#batchCbx').val() != "" && $('#enrollmentStartDate').val() != "" && $("#eligibleClassesCbx").val() != "" &&
               $('#SeasonsCbx2').val()!='' && $('#batchCbx2').val() != "" && $("#eligibleClassesCbx2").val() != "" ){
                    console.log('valid 2 seasons only');
                    $("#messageStudentEnrollmentDiv").html('');
                    $("#availableSessions").html(firstselectedNoOfClass+'+'+secondselectedNoOfClass+'='+(firstselectedNoOfClass+secondselectedNoOfClass));
                    $("#totalAmount").html((firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost));
                    $("#amountPerSesssion").html('('+batch1ClassCost+')('+batch2ClassCost+')');
	            $("#totalEnrollmentAmount").val((firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost));
	            $("#sessionsTable").show();
                    $("#enrollNow").show();
                    $('#enrollmentcontinue3').hide();
                    $('#enrollNow').addClass('disabled');
            }
        }else if((firstselectedNoOfClass+secondselectedNoOfClass+thirdselectedNoOfClass)===selectedNoOfClass){
                    console.log('valid 3 seasons only');
                    $("#messageStudentEnrollmentDiv").html('');
                    $("#availableSessions").html(firstselectedNoOfClass+'+'+secondselectedNoOfClass+'+'+thirdselectedNoOfClass+'='+(firstselectedNoOfClass+secondselectedNoOfClass+thirdselectedNoOfClass));
                    $("#totalAmount").html((firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost));
                    $("#amountPerSesssion").html('('+batch1ClassCost+')('+batch2ClassCost+')('+batch3ClassCost+')');
	            $("#totalEnrollmentAmount").val((firstselectedNoOfClass*batch1ClassCost)+(secondselectedNoOfClass*batch2ClassCost)+(thirdselectedNoOfClass*batch3ClassCost));
	            $("#sessionsTable").show();
                    $("#enrollNow").show();
                    $('#enrollNow').addClass('disabled');
        }
        
        
       /* 
	if($('#batchCbx').val() != "" && $('#enrollmentStartDate').val() != "" && $('#enrollmentEndDate').val() != "" && $("#eligibleClassesCbx").val() != ""){

		$("#messageStudentEnrollmentDiv").html('');
		getSessionsForClasses();
		$("#enrollNow").show();
                $('#enrollNow').addClass('disabled');
	}else{
		$("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger">Please fill up required Fields.</p>');
	}
	*/
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
          if($('#SeasonsCbx').val()==''){
        $("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger"> please select the season.</p>');
    }else{
	//console.log(this.id);
	var selector = "";
	if(this.id == "eligibleClassIntro"){
		selector = "introbatchCbx";
		from = this;
	}else{
		var classId = $(this).val();
		/*$.ajax({
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
	    });*/
		selector = "batchCbx";
		from = this;
	}
	getBatchesBasedOnClasses(selector,from,$('#SeasonsCbx').val());
        $('#enrollmentcontinue2').hide();
        $('#enrollmentcontinue3').hide();
        $('#batch1Msg').html('');
        }
});
$(".eligibleClassesCbx2").change(function (){
          if($('#SeasonsCbx2').val()==''){
        $("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger"> please select the season.</p>');
    }else{
	//console.log(this.id);
	var selector = "";
	if(this.id == "eligibleClassIntro"){
		selector = "introbatchCbx";
		from = this;
	}else{
		var classId = $(this).val();
		/*$.ajax({
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
	    });*/
		selector = "batchCbx2";
		from = this;
	}
	getBatchesBasedOnClasses(selector,from,$('#SeasonsCbx2').val());
        $('#enrollmentcontinue3').hide();
        $('#batch2Msg').html('');
        }
});
$(".eligibleClassesCbx3").change(function (){
          if($('#SeasonsCbx3').val()==''){
        $("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger"> please select the season.</p>');
    }else{
	//console.log(this.id);
	var selector = "";
	if(this.id == "eligibleClassIntro"){
		selector = "introbatchCbx";
		from = this;
	}else{
		var classId = $(this).val();
		/*$.ajax({
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
	    });*/
		selector = "batchCbx3";
		from = this;
	}
	getBatchesBasedOnClasses(selector,from,$('#SeasonsCbx3').val());
        $('#batch3Msg').html('');
        }
});

function getSeasons(){
$.ajax({
			type: "POST",
			url: "{{URL::to('/quick/season/getSeasonsForEnrollment')}}",
                        data: {},
			dataType: 'json',
			success: function(response){
                            console.log(response.season_data);
                            $('#seasonMsgDiv').html("<p class='uk-alert uk-alert-warning'> Season StartDate: "+response.season_data[0]['start_date']+" End Date: "+response.season_data[0]['end_date']+"</p>");
                            $(".SeasonsCbx").empty(""); 
                            $(".SeasonsCbx2").empty(""); 
                            $(".SeasonsCbx3").empty(""); 
                            string = '';
                            for(var i=0;i<response.season_data.length;i++){
                                string += '<option value='+response.season_data[i]['id']+'>'+response.season_data[i]['season_name']+'</option>';
                            }
                            //$('#enrollmentEndDate').val(response.season_data[0]['end_date']);
                            $('.SeasonsCbx').append(string); 
                            $('.SeasonsCbx2').append(string);
                            $('.SeasonsCbx3').append(string);
                           // console.log(string);
                        }
             })  ;
}



function getEligibleClasses(){
    var yearAndMonth= (parseInt(ageYear*12)+parseInt(ageMonth));
          console.log(ageYear);
          console.log(ageMonth);
          console.log(yearAndMonth);
	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/eligibleClassess')}}",
        data: {'ageYear': ageYear, 'ageMonth': ageMonth, 'gender':studentGender,'yearAndMonth':yearAndMonth},
        dataType:"json",
        success: function (response)
        {
            
         // console.log(response);
      	  $(".eligibleClassesCbx").empty("");  
          //$(".eligibleClassesCbx2").empty(""); 
          //$(".eligibleClassesCbx3").empty(""); 
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  //console.log(index+" = "+item);
      		  $string += '<option value='+item.id+'>'+item.class_name+'</option>';               
            });
      	  $('.eligibleClassesCbx').append($string);
          //$('.eligibleClassesCbx2').append($string);
          //$('.eligibleClassesCbx3').append($string);
        }
    });
}

function getEligibleClassesForBatch2WithAgeChange(){
    $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/eligibleClassessForOtherBatches')}}",
        data: {'FutureAgeDate':enddate1,'studentDob':"{{$student->student_date_of_birth}}",},
        dataType:"json",
        success: function (response)
        {
      	  $(".eligibleClassesCbx2").empty("");  
          //$(".eligibleClassesCbx2").empty(""); 
          //$(".eligibleClassesCbx3").empty(""); 
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  //console.log(index+" = "+item);
      		  $string += '<option value='+item.id+'>'+item.class_name+'</option>';               
            });
      	  $('.eligibleClassesCbx2').append($string);
          $('#enrollmentbtnsdisplay1').css('display','none');
          //$('.eligibleClassesCbx2').append($string);
          //$('.eligibleClassesCbx3').append($string);
          $('#enrollmentcontinue2').show();
        
        }
    });
}

function getEligibleClassesForBatch2WithoutAgeChange(){
   var yearAndMonth= (parseInt(ageYear*12)+parseInt(ageMonth));
          //console.log(ageYear);
          //console.log(ageMonth);
          //console.log(yearAndMonth);
     $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/eligibleClassess')}}",
        data: {'ageYear': ageYear, 'ageMonth': ageMonth, 'gender':studentGender,'yearAndMonth':yearAndMonth},
        dataType:"json",
        success: function (response)
        {
      	  $(".eligibleClassesCbx2").empty("");  
          //$(".eligibleClassesCbx2").empty(""); 
          //$(".eligibleClassesCbx3").empty(""); 
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  //console.log(index+" = "+item);
      		  $string += '<option value='+item.id+'>'+item.class_name+'</option>';               
            });
      	  $('.eligibleClassesCbx2').append($string);
          $('#enrollmentbtnsdisplay1').css('display','none');
          //$('.eligibleClassesCbx2').append($string);
          //$('.eligibleClassesCbx3').append($string);
          $('#enrollmentcontinue2').show();
        
        }
    });
}

function getEligibleClassesForBatch3WithAgeChange(){
    $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/eligibleClassessForOtherBatches')}}",
        data: {'FutureAgeDate':enddate2,'studentDob':"{{$student->student_date_of_birth}}",},
        dataType:"json",
        success: function (response)
        {
      	  $(".eligibleClassesCbx3").empty("");  
          //$(".eligibleClassesCbx2").empty(""); 
          //$(".eligibleClassesCbx3").empty(""); 
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  //console.log(index+" = "+item);
      		  $string += '<option value='+item.id+'>'+item.class_name+'</option>';               
            });
      	  $('.eligibleClassesCbx3').append($string);
          $('#enrollmentbtnsdisplay2').css('display','none');
          $('#enrollmentcontinue3').show();
          //$('.eligibleClassesCbx2').append($string);
          //$('.eligibleClassesCbx3').append($string);
        
        }
    });
}

function getEligibleClassesForBatch3WithoutAgeChange(){
   var yearAndMonth= (parseInt(ageYear*12)+parseInt(ageMonth));
          //console.log(ageYear);
          //console.log(ageMonth);
          //console.log(yearAndMonth);
     $.ajax({
        type: "POST",
        url: "{{URL::to('/quick/eligibleClassess')}}",
        data: {'ageYear': ageYear, 'ageMonth': ageMonth, 'gender':studentGender,'yearAndMonth':yearAndMonth},
        dataType:"json",
        success: function (response)
        {
      	  $(".eligibleClassesCbx3").empty("");  
          //$(".eligibleClassesCbx2").empty(""); 
          //$(".eligibleClassesCbx3").empty(""); 
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  //console.log(index+" = "+item);
      		  $string += '<option value='+item.id+'>'+item.class_name+'</option>';               
            });
      	  $('.eligibleClassesCbx3').append($string);
          $('#enrollmentbtnsdisplay2').css('display','none');
          $('#enrollmentcontinue3').show();
          //$('.eligibleClassesCbx2').append($string);
          //$('.eligibleClassesCbx3').append($string);
        
        }
    });
}

function getBatchesBasedOnClasses(selector, from,seasonId){
  
//console.log($(from).val());
//console.log($('#SeasonsCbx').val());
	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/batchesByClassSeasonId')}}",
        data: {'classId': $(from).val(),'seasonId':seasonId},
        dataType:"json",
        success: function (response)
        {      	   
          //  console.log(response[0]['day']);
            
      	  $('#'+selector).empty();      	  
      	  $string = '<option value=""></option>';
      	  $.each(response, function (index, item) {
      		  $string += '<option value='+item.id+'>'+item.batch_name+' '+item.day+'  '+item.start_time+' - '+item.end_time+' '+item.instructor+'</option>';               
            });
      	  $('#'+selector).append($string);
          $('#'+selector).val();
          
      	  //$('#introbatchCbx').append($string);
        
        }
    });
    
}




var availableSessionCount = 0;
var eachClassCost=0;
function getSessionsForClasses(){

	

		$.ajax({
	        type: "POST",
	        url: "{{URL::to('/quick/getBatcheSchedules')}}",
	        data: {'batchId': $('#batchCbx').val(), "enrollmentStartDate":$('#enrollmentStartDate').val(), "enrollmentEndDate":$('#enrollmentEndDate').val(),'seasonId':$('#SeasonsCbx').val()},
	        dataType:"json",
	        success: function (response)
	        {   
                    //console.log(response);
                    
	            $("#availableSessions").html(response.availableSession);
	            $("#totalAmount").html(response.amountTotal);
                    eachClassCost = response.eachClassAmount;
                    $("#amountPerSesssion").html(eachClassCost);
	            $("#totalEnrollmentAmount").val(response.amountTotal);
	            availableSessionCount = response.availableSession;

				
				

	            
	        	$("#sessionsTable").show();
                        
	        }
	    }); 

	
}

function enrollnow(){
	//console.log($("#enrollKidForm").serialize());
	event.preventDefault();
	$("#enrollNow").hide();
        $('#MsgDiv').hide();
        $('#seasonMsgDiv').hide();
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
            console.log(response);
                /*
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
                        //$("#messageStudentEnrollmentDiv").hide();
        		$("#messageStudentEnrollmentDiv").html('<p class="uk-alert uk-alert-danger">Sorry! Student could not be enrolled.</p>');
        	} */    	   
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


	//var ivid = $("#iveditSelect").data('iveditid');
	//var ivstatus = $("#iveditSelect").val();

	$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/editIntrovisit')}}",
        data:{'status':ivstatus,'id':ivid, "customerCommentTxtarea":$("#ivcustomerCommentTxtarea").val(),'reschedule-date':$('#reschedule-date').val(),},
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
  $('#pending_id').val(pendingId);
  $('#pending_amt').val(pendingAmount);
  $('#pending_discount').val(discount);
  $('#receivepayment').addClass('disabled');
  $('#receiveCardDetailsDiv').css('display','none');
  $('#receiveChequeDetailsDiv').css('display','none');
  $('#receiveemailEnrollPrintDiv').css('display','none');
$('#receivedue').modal('show');

}



$('#receivepayment').click(function(){
 
    var amountAfterDiscount=(($('#pending_discount').val()/100)*parseInt($('#pending_amt').val()));
    if($("input[name=paymentReceiveTypeRadio]:checked").val()=='card'){
        if(($('#receivecard4digits').val()!='') && ($('#receivecardBankName').val()!='' && $('#receivecardRecieptNumber').val()!='')){
            //console.log('validation passed');
            var print=$('#receiveinvoicePrintOption').is(":checked");
            $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/creatependingorder')}}",
                        data: {'pending_id':$('#pending_id').val(),'pendingamount':amountAfterDiscount,
                               'cardType':$('#receivecardType').val(),'carddigits':$('#receivecard4digits').val(),
                               'bankName':$('#receivecardBankName').val(),'cardRecieptNumber':$('#receivecardRecieptNumber').val(),
                               'invoicePrint':print,'invoiceEmail':$('#receiveemailOption').is(":checked"),
                               'paymentType':'card',},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            //console.log(print);
                            $('#receiveduebody').empty();
                            var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                            if(print){                         
                              $('#receiveduebody').empty();
                              $('#receiveduebody').html("<p class='uk-alert uk-alert-success'>pending Due is succesfully received.</p><br>"+printvars);
                             $('#receivepayment').addClass('disabled');
                            }else{
                                $('#receiveduebody').empty();
                                $('#receiveduebody').html("<p class='uk-alert uk-alert-success'>pending Due is succesfully received.</p>");
                                $('#receivepayment').addClass('disabled');
                            }
                            
                            //$('#pendingamount').modal('show'); 
                            $('#receivedueclose').click(function(){
                                window.location.reload(1);        
                            });
                        }
             });
        }else{
            $('#receiveDueMsg').html('<p class="uk-alert uk-alert-warning"> please select all required details</p>');
        }
        
        
    }
    if($("input[name=paymentReceiveTypeRadio]:checked").val()=='cash'){
        //console.log('cash');
        var print=$('#receiveinvoicePrintOption').is(":checked");
        $('#receivepayment').addClass('disabled');
        $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/creatependingorder')}}",
                        data: {'pending_id':$('#pending_id').val(),'pendingamount':amountAfterDiscount,'paymentType':'cash','invoiceEmail':$('#receiveemailOption').is(":checked")},
			dataType: 'json',
			success: function(response){
                           // console.log(response.status);
                            
                            var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                            if(print){                         
                              $('#receiveduebody').empty();
                              $('#receiveduebody').html("<p class='uk-alert uk-alert-success'>pending Due is succesfully received.</p><br>"+printvars);
                             $('#receivepayment').addClass('disabled');
                            }else{
                                $('#receiveduebody').empty();
                                $('#receiveduebody').html("<p class='uk-alert uk-alert-success'>pending Due is succesfully received.</p>");
                                $('#receivepayment').addClass('disabled');
                            }
                            
                            //$('#pendingamount').modal('show'); 
                           $('#receivedueclose').click(function(){
                            window.location.reload(1);        
                            });
                        }
             });
    }
    if($("input[name=paymentReceiveTypeRadio]:checked").val()=='cheque'){
        if(($('#receivechequeBankName').val()!='') && ($('#receivechequeNumber').val()!='')){
            //console.log('validation passed');
            
            var print=$('#receiveinvoicePrintOption').is(":checked");
            $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/creatependingorder')}}",
                        data: {'pending_id':$('#pending_id').val(),'pendingamount':amountAfterDiscount,
                               'bankChequeName':$('#receivechequeBankName').val(),'chqueNo':$('#receivechequeNumber').val(),
                               'invoicePrint':print,'invoiceEmail':$('#receiveemailOption').is(":checked"),
                               'paymentType':'cheque',},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            
                            
                            var printvars = '<a target="_blank" href="'+response.printurl+'" class="btn btn-primary">Print</a>';
                            if(print){                         
                            $('#receiveduebody').empty();
                             $('#receiveduebody').html("<p class='uk-alert uk-alert-success'>pending Due is succesfully received.</p><br>"+printvars);
                             $('#receivepayment').addClass('disabled');
                            }else{
                                $('#receiveduebody').empty();
                                $('#receiveduebody').html("<p class='uk-alert uk-alert-success'>pending Due is succesfully received.</p>");
                                $('#receivepayment').addClass('disabled');
                            
                            }
                            
                            //$('#pendingamount').modal('show'); 
                            $('#receivedueclose').click(function(){
                            window.location.reload(1);        
                            });
                        }
             });
            
        }else{
            $('#receiveDueMsg').html('<p class="uk-alert uk-alert-warning"> please select all required details</p>');
        }
    }
    
    
       
});


$('#CustomerType').change(function(){
    if($('#singlePayRadio').prop('checked') == true){
                if($('#CustomerType').val()=='OldCustomer'){
                 $('#Order-date').css("display","block");
                 //console.log($('#OrderDate').val());
                }
                if($('#CustomerType').val()=='NewCustomer'){
                 $('#Order-date').css("display","none");
                }
    }
    if($('#biPayRadio').prop('checked') == true){
                if($('#CustomerType').val()=='NewCustomer'){
                
                 $('#Order-date').css("display","none");
                 $('#Order-date2').css('display','none');
                 $('#Order-date3').css('display','none');
                 $('#Order-date4').css('display','none');
                }
                if($('#CustomerType').val()=='OldCustomer'){
                    $('#Order-date').css('display','block');
                    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/checkBiPayOrderDate')}}",
                        data: {'bipayamount1':$('#bipayAmount1').val(),'bipayamount2':$('#bipayAmount2').val(),
                               'startdate':$('#enrollmentStartDate').val(),'batchid':$('#batchCbx').val(),
                               'enddate':$('#enrollmentEndDate').val(),'seasonid':$('#SeasonsCbx').val(),},
			dataType: 'json',
			success: function(response){
                            //console.log(response);
                            if(response.status=='true'){
                                //console.log(response);
                             
                            $('#Order-date2').css('display','block');
                            $('.Order-Date2').css('display','block');
                            $('#paymentType2').css('display','block');
                           }
                           if(response.status=='false'){
                               $('#Order-date2').css('display','none');
                               $('.Order-Date2').css('display','none');
                               $('#paymentType2').css('display','none');
                            
                           }
                        }
                    });
                }
    }
    if($('#multiPayRadio').prop('checked') == true){
                   
                if($('#CustomerType').val()=='NewCustomer'){
                 $('#Order-date').css("display","none");
                 $('#Order-date2').css('display','none');
                 $('#Order-date3').css('display','none');
                 $('#Order-date4').css('display','none');
               
                }
                if($('#CustomerType').val()=='OldCustomer'){
                    $('#Order-date').css('display','block');
                    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/checkmultiPayOrderDate')}}",
                        data: {'multipayAmount1':$('#multipayAmount1').val(),'multipayAmount2':$('#multipayAmount2').val(),
                               'multipayAmount3':$('#multipayAmount3').val(),'multipayAmount4':$('#multipayAmount4').val(),
                               'startdate':$('#enrollmentStartDate').val(),'batchid':$('#batchCbx').val(),
                               'enddate':$('#enrollmentEndDate').val(),'seasonid':$('#SeasonsCbx').val(),},
			dataType: 'json',
			success: function(response){
                            console.log('multipay ajax');
                            console.log(response);
                            
                            if(response.status=='two'){
                                 $('#Order-date2').css('display','block');
                                 $('.Order-Date2').css('display','block');
                                 $('#paymentType2').css('display','block');
                                 
                            }
                            if(response.status=='three'){
                                 $('#Order-date2').css('display','block');
                                 $('#Order-date3').css('display','block');
                                 $('.Order-Date2').css('display','block');
                                 $('#paymentType2').css('display','block');
                                 $('.Order-Date3').css('display','block');
                                 $('#paymentType3').css('display','block');
                     
                            }
                            if(response.status=='four'){
                                 $('#Order-date2').css('display','block');
                                 $('#Order-date3').css('display','block');
                                 $('#Order-date4').css('display','block');
                                 $('.Order-Date2').css('display','block');
                                 $('#paymentType2').css('display','block');
                                 $('.Order-Date3').css('display','block');
                                 $('#paymentType3').css('display','block');
                                 $('.Order-Date4').css('display','block');
                                 $('#paymentType4').css('display','block')
                            }
                            
                        }
                    });
                }
    }
});



$("input[name='paymentTypeRadioOldCustomer2']").change(function (){
    var selectedPaymentType2 = $("input[type='radio'][name='paymentTypeRadioOldCustomer2']:checked").val();
    if(selectedPaymentType2 == "card"){
		$("#chequeDetailsDiv2").hide();
		$("#cardDetailsDiv2").show();


		$("#cardType2").attr("required",true);
		$("#card4digits2").attr("required",true);
		$("#cardBankName2").attr("required",true);
		$("#cardRecieptNumber2").attr("required",true);


		$("#chequeBankName2").attr("required",false);
		$("#chequeNumber2").attr("required",false);

	}else if(selectedPaymentType2 == "cheque"){
		$("#chequeDetailsDiv2").show();
		$("#cardDetailsDiv2").hide();

		$("#cardType2").attr("required",false);
		$("#card4digits2").attr("required",false);
		$("#cardBankName2").attr("required",false);
		$("#cardRecieptNumber2").attr("required",false);


		$("#chequeBankName2").attr("required",true);
		$("#chequeNumber2").attr("required",true);
	}
	else if(selectedPaymentType2 == "cash"){
		$("#chequeDetailsDiv2").hide();
		$("#cardDetailsDiv2").hide();

		$("#cardType2").attr("required",false);
		$("#card4digits2").attr("required",false);
		$("#cardBankName2").attr("required",false);
		$("#cardRecieptNumber2").attr("required",false);


		$("#chequeBankName2").attr("required",false);
		$("#chequeNumber2").attr("required",false);
	}
});


$("input[name='paymentTypeRadioOldCustomer3']").change(function (){
    var selectedPaymentType3 = $("input[type='radio'][name='paymentTypeRadioOldCustomer3']:checked").val();
    if(selectedPaymentType3 == "card"){
		$("#chequeDetailsDiv3").hide();
		$("#cardDetailsDiv3").show();


		$("#cardType3").attr("required",true);
		$("#card4digits3").attr("required",true);
		$("#cardBankName3").attr("required",true);
		$("#cardRecieptNumber3").attr("required",true);


		$("#chequeBankName3").attr("required",false);
		$("#chequeNumber3").attr("required",false);

	}else if(selectedPaymentType3 == "cheque"){
		$("#chequeDetailsDiv3").show();
		$("#cardDetailsDiv3").hide();

		$("#cardType3").attr("required",false);
		$("#card4digits3").attr("required",false);
		$("#cardBankName3").attr("required",false);
		$("#cardRecieptNumber3").attr("required",false);


		$("#chequeBankName3").attr("required",true);
		$("#chequeNumber3").attr("required",true);
	}
	else if(selectedPaymentType3 == "cash"){
		$("#chequeDetailsDiv3").hide();
		$("#cardDetailsDiv3").hide();

		$("#cardType3").attr("required",false);
		$("#card4digits3").attr("required",false);
		$("#cardBankName3").attr("required",false);
		$("#cardRecieptNumber3").attr("required",false);


		$("#chequeBankName3").attr("required",false);
		$("#chequeNumber3").attr("required",false);
	}
});


$("input[name='paymentTypeRadioOldCustomer4']").change(function (){
    var selectedPaymentType4 = $("input[type='radio'][name='paymentTypeRadioOldCustomer4']:checked").val();
    if(selectedPaymentType4 == "card"){
		$("#chequeDetailsDiv4").hide();
		$("#cardDetailsDiv4").show();


		$("#cardType4").attr("required",true);
		$("#card4digits4").attr("required",true);
		$("#cardBankName4").attr("required",true);
		$("#cardRecieptNumber4").attr("required",true);


		$("#chequeBankName4").attr("required",false);
		$("#chequeNumber4").attr("required",false);

	}else if(selectedPaymentType4 == "cheque"){
		$("#chequeDetailsDiv4").show();
		$("#cardDetailsDiv4").hide();

		$("#cardType4").attr("required",false);
		$("#card4digits4").attr("required",false);
		$("#cardBankName4").attr("required",false);
		$("#cardRecieptNumber4").attr("required",false);


		$("#chequeBankName4").attr("required",true);
		$("#chequeNumber4").attr("required",true);
	}
	else if(selectedPaymentType4 == "cash"){
		$("#chequeDetailsDiv4").hide();
		$("#cardDetailsDiv4").hide();

		$("#cardType4").attr("required",false);
		$("#card4digits4").attr("required",false);
		$("#cardBankName4").attr("required",false);
		$("#cardRecieptNumber4").attr("required",false);


		$("#chequeBankName4").attr("required",false);
		$("#chequeNumber4").attr("required",false);
	}
});



$('#singlePayRadio').change(function(){
   // console.log('changed');
   $('#CustomerType').val("NewCustomer"); 
   $('#Order-date').css('display','none');
   $('#Order-date1').css('display','none');
   $('#Order-date2').css('display','none');
   $('#Order-date3').css('display','none');
});
$('#biPayRadio').change(function(){
   // console.log('changed');
     $('#CustomerType').val("NewCustomer");
     $('#Order-date').css('display','none');
   $('#Order-date1').css('display','none');
   $('#Order-date2').css('display','none');
   $('#Order-date3').css('display','none');
});
$('#multiPayRadio').change(function(){
   // console.log('changed');
     $('#CustomerType').val("NewCustomer");
    $('#Order-date').css('display','none');
   $('#Order-date1').css('display','none');
   $('#Order-date2').css('display','none');
   $('#Order-date3').css('display','none');
});


// for introvisit

//$(document).ready(function(){
//    $.ajax({
//			type: "POST",
//			url: "{{URL::to('/quick/season/getSeasonsForEnrollment')}}",
//                        data: {},
//			dataType: 'json',
//			success: function(response){
//                           // console.log(response.season_data);
//                            
//                            $(".SeasonsCbx").empty("");      	  
//                            string = '';
//                            for(var i=0;i<response.season_data.length;i++){
//                                string += '<option value='+response.season_data[i]['id']+'>'+response.season_data[i]['season_name']+'</option>';
//                            }
//                            
//                            $('.SeasonsCbx').append(string); 
//                            getEligibleClasses();
//                           // console.log(string);
//                        }
//             })  ;
//});
$('#SeasonsCbx').change(function(){
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/season/getSeasonDataBySeasonId')}}",
                        data: {'seasonId':$('#SeasonsCbx').val(),},
			dataType: 'json',
			success: function(response){
                            $('#seasonMsgDiv').html("<p class='uk-alert uk-alert-warning'> Season StartDate: "+response.data['start_date']+" End Date: "+response.data['end_date']+"</p>");
                          //  $('#enrollmentEndDate').val(response.end_date);
                            //getEligibleClasses();
                            $('#eligibleClassesCbx').val('');
                            $('#batch1Msg').html('');
                            $('#batchCbx').val('');
                            $('#enrollmentcontinue2').hide();
                            $('#enrollmentcontinue3').hide();
                        }
             }); 

});
$('#SeasonsCbx2').change(function(){
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/season/getSeasonDataBySeasonId')}}",
                        data: {'seasonId':$('#SeasonsCbx2').val(),},
			dataType: 'json',
			success: function(response){
                            $('#seasonMsgDiv').html("<p class='uk-alert uk-alert-warning'> Season StartDate: "+response.data['start_date']+" End Date: "+response.data['end_date']+"</p>");
                          //  $('#enrollmentEndDate').val(response.end_date);
                           $('#eligibleClassesCbx2').val('');
                           $('#batch2Msg').html('');
                           $('#enrollmentcontinue3').hide();
                        }
             }); 

});
$('#SeasonsCbx3').change(function(){
    $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/season/getSeasonDataBySeasonId')}}",
                        data: {'seasonId':$('#SeasonsCbx3').val(),},
			dataType: 'json',
			success: function(response){
                            $('#seasonMsgDiv').html("<p class='uk-alert uk-alert-warning'> Season StartDate: "+response.data['start_date']+" End Date: "+response.data['end_date']+"</p>");
                          //  $('#enrollmentEndDate').val(response.end_date);
                           $('#eligibleClassesCbx3').val('');
                           $('#batchCbx3').html('');
                           $('#batch3Msg').html('');
                        }
             }); 

});
$('#batchCbx').change(function(){

if($('#enrollmentStartDate').val()!=''){
$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/getBatchRemainingClassesByBatchId')}}",
        data: {'batchId':$('#batchCbx').val(),'preferredStartDate':$('#enrollmentStartDate').val(),},
        dataType:"json",
        success: function (response)
        {
            console.log(response);
            if(response.status=='success'){
                
                if(selectedNoOfClass <= response.classCount){
                    $('#batch1Msg').html('<p class="uk-alert uk-alert-success">ClassNo:'+selectedNoOfClass+'<i class="fa fa-trash" aria-hidden="true"></i></p>');
                    batch1ClassCost=response.classAmount;
                    $('#enrollmentcontinue2').hide();
                    $('#enrollmentcontinue3').hide();
                    firstselectedNoOfClass=selectedNoOfClass;
                     $.ajax({
                            type: "POST",
                            url: "{{URL::to('/quick/insertEstimateDetails')}}",
                            data: {'customer_id':{{$student->customer_id}},'student_id':studentId,'season_id':$('#SeasonsCbx').val(),
                                   'batch_id':$('#batchCbx').val(),'class_id':$('#eligibleClassesCbx').val(),
                                   'enroll_start_date':response.enrollment_start_date,'enroll_end_date':response.enrollment_end_date,
                                    'total_selected_classes':selectedNoOfClass,'no_of_available_classes':response.classCount,
                                    'no_of_opted_classes':selectedNoOfClass,'batch_amount':response.classAmount,'estimate_master_no':estimate_master_no},
                            dataType: 'json',
                            success: function(response){
                                $('#SeasonsCbx').attr('disabled',true);
                                $('#eligibleClassesCbx').attr('disabled',true);
                                $('#batchCbx').attr('disabled',true);
                                estimate_master_no=response.data['estimate_master_no'];
                                $('#estimate_master_no').val(response.data['estimate_master_no']);
                                estimate_id1=response.data['id'];
                                prepareGetClasses();
                            }
                        });     
                                
                     
                    //alert('completed');
                }else{
                    $('#batch1Msg').html('<p class="uk-alert uk-alert-success">ClassNo:'+response.classCount+'<i class="fa fa-trash" aria-hidden="true"></i></p>');
                    enddate1=response.lastdate;
                    firstselectedNoOfClass=response.classCount;
                    batch1ClassCost=response.classAmount;
                    // **** age changing after enrollment of batch 1 ****//
                    
                    
                    $('#enrollmentbtnsdisplay1').css('display','block');
                    $('#GoChangeAge').click(function(){
                        $.ajax({
                            type: "POST",
                            url: "{{URL::to('/quick/insertEstimateDetails')}}",
                            data: {'customer_id':{{$student->customer_id}},'student_id':studentId,'season_id':$('#SeasonsCbx').val(),
                                   'batch_id':$('#batchCbx').val(),'class_id':$('#eligibleClassesCbx').val(),
                                   'enroll_start_date':response.enrollment_start_date,'enroll_end_date':response.enrollment_end_date,
                                    'total_selected_classes':selectedNoOfClass,'no_of_available_classes':response.classCount,
                                    'no_of_opted_classes':response.classCount,'batch_amount':response.classAmount,'estimate_master_no':estimate_master_no},
                            dataType: 'json',
                            success: function(response){
                        
                                getEligibleClassesForBatch2WithAgeChange();
                                $('#SeasonsCbx').attr('disabled',true);
                                $('#eligibleClassesCbx').attr('disabled',true);
                                $('#batchCbx').attr('disabled',true);
                                estimate_master_no=response.data['estimate_master_no'];
                                $('#estimate_master_no').val(response.data['estimate_master_no']);
                                estimate_id1=response.data['id'];
                            }
                        });     
                    });
                    $('#GoParallelAge').click(function(){
                        
                        $.ajax({
                            type: "POST",
                            url: "{{URL::to('/quick/insertEstimateDetails')}}",
                            data: {'customer_id':{{$student->customer_id}},'student_id':studentId,'season_id':$('#SeasonsCbx').val(),
                                   'batch_id':$('#batchCbx').val(),'class_id':$('#eligibleClassesCbx').val(),
                                   'enroll_start_date':response.enrollment_start_date,'enroll_end_date':response.enrollment_end_date,
                                    'total_selected_classes':selectedNoOfClass,'no_of_available_classes':response.classCount,
                                    'no_of_opted_classes':response.classCount,'batch_amount':response.classAmount,'estimate_master_no':estimate_master_no},
                            dataType: 'json',
                            success: function(response){
                                getEligibleClassesForBatch2WithoutAgeChange();  
                                $('#SeasonsCbx').attr('disabled',true);
                                $('#eligibleClassesCbx').attr('disabled',true);
                                $('#batchCbx').attr('disabled',true);
                                estimate_master_no=response.data['estimate_master_no'];
                                $('#estimate_master_no').val(response.data['estimate_master_no']);
                                estimate_id1=response.data['id'];
                            }
                        });  
                        
                    });
                    
                    //$('#enrollmentcontinue2').show();
                }
                $('#eligibleClassesCbx2').val('');
                $('#batchCbx2').val('');
                $('batch2Msg').html('');
                
                //$('#batch1Msg').html('');
                
                 //$('#enrollmentcontinue2').hide();
                 //$('#enrollmentcontinue3').hide();
                //$('#MsgDiv').html('<p class="uk-alert uk-alert-success">selected class day:'+response.day+'.</p>');   
            }else{
                $('#batch1Msg').html('<p class="uk-alert uk-alert-danger">ClassNo:0</p>');
            }
        }
    });
}else{
$('#batch1Msg').html('<p class="uk-alert uk-alert-danger">select date</p>');
}
});


$('#batchCbx2').change(function(){
console.log(enddate1);
$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/getBatchRemainingClassesByBatchId')}}",
        data: {'batchId':$('#batchCbx2').val(),'preferredStartDate':enddate1,},
        dataType:"json",
        success: function (response)
        {
            console.log(response);
            if(response.status=='success'){
                
                if((selectedNoOfClass-firstselectedNoOfClass)<= response.classCount){
                    $('#batch2Msg').html('<p class="uk-alert uk-alert-success">ClassNo:'+(selectedNoOfClass-firstselectedNoOfClass)+'<i class="fa fa-trash" aria-hidden="true"></i></p>');
                    $('#enrollmentcontinue3').hide();
                    batch2ClassCost=response.classAmount;
                    secondselectedNoOfClass=(selectedNoOfClass-firstselectedNoOfClass);
                    $.ajax({
                            type: "POST",
                            url: "{{URL::to('/quick/insertEstimateDetails')}}",
                            data: {'customer_id':{{$student->customer_id}},'student_id':studentId,'season_id':$('#SeasonsCbx2').val(),
                                   'batch_id':$('#batchCbx2').val(),'class_id':$('#eligibleClassesCbx2').val(),
                                   'enroll_start_date':response.enrollment_start_date,'enroll_end_date':response.enrollment_end_date,
                                    'total_selected_classes':selectedNoOfClass,'no_of_available_classes':response.classCount,
                                    'no_of_opted_classes':(selectedNoOfClass-firstselectedNoOfClass),'batch_amount':response.classAmount,'estimate_master_no':estimate_master_no},
                            dataType: 'json',
                            success: function(response){
                                $('#SeasonsCbx2').attr('disabled',true);
                                $('#eligibleClassesCbx2').attr('disabled',true);
                                $('#batchCbx2').attr('disabled',true);
                                estimate_master_no=response.data['estimate_master_no'];
                                $('#estimate_master_no').val(response.data['estimate_master_no']);
                                estimate_id2=response.data['id'];
                                prepareGetClasses();
                            }
                        }); 
                    //alert('completed');
                    
                }else{
                    $('#batch2Msg').html('<p class="uk-alert uk-alert-success">ClassNo:'+response.classCount+'<i class="fa fa-trash" aria-hidden="true"></i></p>');
                    //firstselectedNoOfClass=firstselectedNoOfClass+response.classCount
                    enddate2=response.lastdate;
                    secondselectedNoOfClass=response.classCount;
                    batch2ClassCost=response.classAmount;
                    //selectedNoOfClass=selectedNoOfClass-response.classCount;
                    if(selectedNoOfClass==0){
                        alert('completed');
                    }
                    $('#enrollmentbtnsdisplay2').css('display','block');
                    
                    $('#GoParallelAge2').click(function(){
                        
                        $.ajax({
                            type: "POST",
                            url: "{{URL::to('/quick/insertEstimateDetails')}}",
                            data: {'customer_id':{{$student->customer_id}},'student_id':studentId,'season_id':$('#SeasonsCbx2').val(),
                                   'batch_id':$('#batchCbx2').val(),'class_id':$('#eligibleClassesCbx2').val(),
                                   'enroll_start_date':response.enrollment_start_date,'enroll_end_date':response.enrollment_end_date,
                                    'total_selected_classes':selectedNoOfClass,'no_of_available_classes':response.classCount,
                                    'no_of_opted_classes':response.classCount,'batch_amount':response.classAmount,'estimate_master_no':estimate_master_no},
                            dataType: 'json',
                            success: function(response){
                        
                                getEligibleClassesForBatch3WithoutAgeChange();
                                $('#SeasonsCbx2').attr('disabled',true);
                                $('#eligibleClassesCbx2').attr('disabled',true);
                                $('#batchCbx2').attr('disabled',true);
                                estimate_master_no=response.data['estimate_master_no'];
                                $('#estimate_master_no').val(response.data['estimate_master_no']);
                                estimate_id2=response.data['id'];
                            }
                        });
                    });
                    $('#GoChangeAge2').click(function(){
                        $.ajax({
                            type: "POST",
                            url: "{{URL::to('/quick/insertEstimateDetails')}}",
                            data: {'customer_id':{{$student->customer_id}},'student_id':studentId,'season_id':$('#SeasonsCbx2').val(),
                                   'batch_id':$('#batchCbx2').val(),'class_id':$('#eligibleClassesCbx2').val(),
                                   'enroll_start_date':response.enrollment_start_date,'enroll_end_date':response.enrollment_end_date,
                                    'total_selected_classes':selectedNoOfClass,'no_of_available_classes':response.classCount,
                                    'no_of_opted_classes':response.classCount,'batch_amount':response.classAmount,'estimate_master_no':estimate_master_no},
                            dataType: 'json',
                            success: function(response){
                        
                                getEligibleClassesForBatch3WithAgeChange();
                                $('#SeasonsCbx2').attr('disabled',true);
                                $('#eligibleClassesCbx2').attr('disabled',true);
                                $('#batchCbx2').attr('disabled',true);
                                estimate_master_no=response.data['estimate_master_no'];
                                $('#estimate_master_no').val(response.data['estimate_master_no']);
                                estimate_id2=response.data['id'];
                            }
                        });
                        
                    });
                   // $('#enrollmentcontinue3').show();
                }
                $('#eligibleClassesCbx3').val('');
                $('#batchCbx3').val('');
                $('#batch3Msg').html('');
                //$('#batch2Msg').html('');
                 //$('#enrollmentcontinue3').hide();
                //$('#MsgDiv').html('<p class="uk-alert uk-alert-success">selected class day:'+response.day+'.</p>');   
            }else{
                $('#batch2Msg').html('<p class="uk-alert uk-alert-danger">ClassNo:0</p>');
            }
        }
    });
});

$('#batchCbx3').change(function(){
console.log(enddate2);
$.ajax({
        type: "POST",
        url: "{{URL::to('/quick/getBatchRemainingClassesByBatchId')}}",
        data: {'batchId':$('#batchCbx3').val(),'preferredStartDate':enddate2},
        dataType:"json",
        success: function (response)
        {
            console.log(response);
            if(response.status=='success'){
                
                if((selectedNoOfClass-(firstselectedNoOfClass+secondselectedNoOfClass)) <= response.classCount){
                    $('#batch3Msg').html('<p class="uk-alert uk-alert-success">ClassNo:'+(selectedNoOfClass-(firstselectedNoOfClass+secondselectedNoOfClass))+'</p>');
                    batch3ClassCost=response.classAmount;
                    thirdselectedNoOfClass=(selectedNoOfClass-(firstselectedNoOfClass+secondselectedNoOfClass));
                    $('#enrollmentbtnsdisplay3').css('display','block');
                    $('#GoEnrollmentConfirm').click(function(){
                       $.ajax({
                            type: "POST",
                            url: "{{URL::to('/quick/insertEstimateDetails')}}",
                            data: {'customer_id':{{$student->customer_id}},'student_id':studentId,'season_id':$('#SeasonsCbx3').val(),
                                   'batch_id':$('#batchCbx3').val(),'class_id':$('#eligibleClassesCbx3').val(),
                                   'enroll_start_date':response.enrollment_start_date,'enroll_end_date':response.enrollment_end_date,
                                    'total_selected_classes':selectedNoOfClass,'no_of_available_classes':selectedNoOfClass,
                                    'no_of_opted_classes':thirdselectedNoOfClass,'batch_amount':response.classAmount,'estimate_master_no':estimate_master_no},
                            dataType: 'json',
                            success: function(response){
                        
                                $('#SeasonsCbx3').attr('disabled',true);
                                $('#eligibleClassesCbx3').attr('disabled',true);
                                $('#batchCbx3').attr('disabled',true);
                                $('#enrollmentbtnsdisplay3').css('display','none');
                                
                                estimate_master_no=response.data['estimate_master_no'];
                                $('#estimate_master_no').val(response.data['estimate_master_no']);
                                estimate_id3=response.data['id'];
                                prepareGetClasses();
                            }
                        }); 
                        
                    });
        
                        
                    
                    //prepareGetClasses();
                    //alert('completed');
                    
                }else{
                    $('#batch3Msg').html('<p class="uk-alert uk-alert-success">ClassNo:'+response.classCount+'<i class="fa fa-trash" aria-hidden="true"></i></p>');
                    enddate3=response.lastdate;
                    thirdselectedNoOfClass=response.classCount;
                    batch3ClassCost=response.classAmount;
                    //selectedNoOfClass=selectedNoOfClass-response.classCount;
                   // $('#enrollmentcontinue3').show();
                }
               // $('#batch3Msg').html('');
                //$('#MsgDiv').html('<p class="uk-alert uk-alert-success">selected class day:'+response.day+'.</p>');   
            }else{
                $('#batch3Msg').html('<p class="uk-alert uk-alert-danger">ClassNo:0</p>');
            }
        }
    });
});
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
                                    "<th class='uk-text-nowrap'>History Details</th>"+
                                    "<th class='uk-text-nowrap'>Created Date</th>"+
                                    "</tr>"+
                                    "</thead>";
                           for(var i=0;i<response.status.length;i++){
                                data+="<tr role='row'>"+"<td>"+response.status[i]['log_text']+"</td><td>"+response.status[i]['created_at']+"</td></tr>";
                            }
                            data+="</table>";
                            //console.log(data);
                            $('#ivhistorybody').html(data);
                            $('#ivhistory').modal('show');
                        }
             });  

    
}
$('#reschedule-date').kendoDatePicker();

$('#iveditSelect').change(function(){
if ($('#iveditSelect').val()=='RE_SCHEDULE'){    
    $('#reschedule').css('display','block');
}else{
    $('#reschedule').css('display','none');
}
});


$.urlParam = function(name){
	var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
	if(results){
        return results[1];
        }else{
        return 0;
        }
}

 //for selecting proper div receive payment tab 
$(document).ready(function(){
   $('#paymentOptionsReceive_1').change(function(){
      if($('#paymentOptionsReceive_1').is(":checked")){
          //console.log('card');
          $('#receivepayment').removeClass('disabled');
          $('#receiveCardDetailsDiv').css('display','block');
          $('#receiveemailEnrollPrintDiv').css('display','block');
          $('#receiveChequeDetailsDiv').css('display','none');
          $('#receivecard4digits').attr('required','true');
          
      } 
   });
   $('#paymentOptionsReceive_2').change(function(){
      if($('#paymentOptionsReceive_2').is(":checked")){
         // console.log('cash');
          $('#receivepayment').removeClass('disabled');
          $('#receiveemailEnrollPrintDiv').css('display','block');
          $('#receiveCardDetailsDiv').css('display','none');
          $('#receiveChequeDetailsDiv').css('display','none');
      }
   });
   $('#paymentOptionsReceive_3').change(function(){
      if($('#paymentOptionsReceive_3').is(":checked")){
         // console.log('cheque');
          $('#receivepayment').removeClass('disabled');
          $('#receiveCardDetailsDiv').css('display','none');
          $('#receiveChequeDetailsDiv').css('display','block');
          $('#receiveemailEnrollPrintDiv').css('display','block');
      } 
   });
});


$('#receivedueclose').click(function(){
   $('#receivedue').modal('hide');
});
/*
$('#receivecard4digits').change(function(){
    console.log('working');
   if(($('#receivecardBankName').val()!='') && ($('#receivecardRecieptNumber').val()!='') ){
      $('#receivepayment').removeClass('disabled'); 
   } 
});

$('#receivecardBankName').change(function(){
   if(($('#receivecard4digits').val()!='') && ($('#receivecardRecieptNumber').val()!='') ){
      $('#receivepayment').removeClass('disabled'); 
   } 
});
$('#receivecardRecieptNumber').change(function(){
   if(($('#receivecardBankName').val()!='') && ($('#receivecard4digits').val()!='') ){
      $('#receivepayment').removeClass('disabled'); 
   } 
});
*/
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
					<li id="aboutTabheading"class="uk-active"><a href="#about">About</a></li>
					
					<li id="enrollmentsTabheading"class=""><a href="#enrollments">Enrollments</a></li>
					<li id="paymentsTabheading"class=""><a href="#payments">Payments</a></li>
                                        <!--<li id="introvisitTabheading"class=""><a href="#introvisit">Intro Visit</a></li>-->
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
                                       
					<li id="enrollments">
                                                <h4 class="heading_c uk-margin-small-bottom">Enroll Class</h4>
                                                <br clear="all" />
                                                <div class='enrollmentMsg' id='enrollmentMsg'></div>
                                                    <div class="uk-grid" data-uk-grid-margin>
                                                        <div class="uk-width-medium-1-4 ">
                                                            <label>No of Classes</label>
                                                        </div>
                                                        <div class="uk-width-medium-1-4 text-center">
                                                            <label>Discount Percentage</label>
                                                        </div>
                                                        <div class="uk-width-medium-1-4 text-center">
                                                          <!--  <label>Discount Rs</label>-->
                                                        </div>
                                                        <div class="uk-width-medium-1-4 text-center">
                                                        </div>
                                                        <br clear="all"/>
                                                        
                                                        <!-- for discount enrollment -->
                                                        <?php if(isset($discountEnrollmentData)){?>
                                                        @for($i = 0; $i < count($discountEnrollmentData); $i++)
                                                        <div class="uk-width-medium-1-4 ">
                                                            <input type="radio" name="enrollmentClassesSelect" value="{{$discountEnrollmentData[$i]['number_of_classes']}}" discountPercentage="{{$discountEnrollmentData[$i]['discount_percentage']}}" position="{{$i}}" class="radio-custom" id="radio_demo_inline{{$i}}"/><label for="radio_demo_inline{{$i}}" class="radio-custom-label" >{{$discountEnrollmentData[$i]['number_of_classes']}} </label>
                                                        </div>
                                                        <div class="uk-width-medium-1-4 text-center">
                                                           <label id='discount{{$i}}' value='{{$discountEnrollmentData[$i]['discount_percentage']}}'>{{$discountEnrollmentData[$i]['discount_percentage']}}</label>%
                                                        </div>
                                                        <div class="uk-width-medium-1-4 ">
                                                           <!-- <input type="number" id="discountAmount{{$i}}" class='form-control input-sm md-input text-center' style='padding:0px' value='0'/> 
                                                           !-->
                                                        </div>
                                                        <div class="uk-width-medium-1-4">
                                                            
                                                        </div>
                                                        @endfor
                                                        <?php } ?>
                                                        <div class="uk-width-medium-1-4 ">
                                                            <input type="radio" name="enrollmentClassesSelect" value="custom" class="radio-custom" id="radio_demo_inline_custom"/><label for="radio_demo_inline_custom" class="radio-custom-label" >Custom Class No</label>
                                                            <br>
                                                            <input type='number' name='customEnrollmemtNoofClass' id='customEnrollmemtNoofClass' class='form-control input-sm md-input text-center' style='padding:0px;' value='0' />
                                                        </div>
                                                        <div class="uk-width-medium-1-4 text-center">
                                                            <br>
                                                                    <input type='number' name='customEnrollmemtDiscountPercentage' id='customEnrollmemtDiscountPercentage' class='form-control input-sm md-input text-center' style='padding:0px;margin-top:10px;' value='0' />
                                                        </div>
                                                        <div class="uk-width-medium-1-4 text-center">
                                                            <br>
                                                            <!--
                                                                    <input type='number' name='customEnrollmemt' id='customEnrollmemtDiscount' class='form-control input-sm md-input text-center' style='padding:0px;margin-top:10px;' value='0' />
                                                            -->
                                                        </div>
                                                        
                                                        <div class="uk-width-medium-1-4 text-center">
                                                        
                                                <?php 
                                                    $studentAgeCheck = date_diff(date_create(date('Y-m-d',strtotime($student->student_date_of_birth))), date_create('today'))->y;
                                                        if($studentAgeCheck <= 12){
                                                ?>
                                		
                                                <a class="md-fab md-fab-accent" id="addEnrollment"
                                                   style="float: right;" > <i class="material-icons"><!--&#xE03B;--> trending_flat</i>
                                                </a>
						<?php }?>
                                                </div>
                                                        
                                            </div>
                                                
                                                <hr>
                                               
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
                                                                                                                                         <tr><td>------Not Dues found------- </td></tr>
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
                                        <div id="seasonMsgDiv"></div>
                                        <div id='MsgDiv'></div>
					<div id="messageStudentEnrollmentDiv"></div>
					<div id="KidsformBody">
						<input type="hidden" name="discountOnLastInstallment" id="discountOnLastInstallment" value="no"/>
						<br clear="all" /> <input name="studentId" type="hidden"
							value="{{$student->id}}" /> <input name="customerId"
							type="hidden" value="{{$student->customers->id}}" /> <input
							id="selectedSessions" name="selectedSessions" type="hidden"
							value="" />
                                                        <input type="hidden" name="noOfBatchesUsed" id="noOfBatchesUsed" value=""/>
                                                        <input type="hidden" name="bipaybatch1availablesession" value=""/>
                                                        <input type="hidden" name="bipaybatch2availablesession" value=""/>
                                                        <input type="hidden" name="bipaybatch3availablesession" value=""/>
                                                        <input type="hidden" id="estimate_master_no" name="estimate_master_no" value=""/>
                                                <div class="uk-grid" data-uk-grid-margin>
                                                    <!--<div class="uk-width-medium-1-3">
								<div class="parsley-row">
									<label for="SeasonsCbx">Seasons<span
										class="req">*</span></label>
                                                                        <select id="SeasonsCbx"
										name="SeasonsCbx" required
										class='SeasonsCbx form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										
									</select>
                                                                        
								</div>
							</div>
                                                    -->
                                                    <div class="uk-width-medium-1-3">
                                                            <div class="parsley-row" style="margin-top: -23px;">
									<label for="enrollmentStartDate">Preferred Start date<span
                                                                                class="req">*</span></label>
									{{Form::text('enrollmentStartDate',
									null,array('id'=>'enrollmentStartDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}

                                                        
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-3">
                                                            <div class="parsley-row">
                                                            </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-3">
                                                            <div class="parsley-row">
								
                                                            </div>
                                                    </div>
                                                    
                                                    
                                                </div>
						<div class="uk-grid" data-uk-grid-margin>
							
                                                        
                                                        <div class="uk-width-medium-1-4">
								<div class="parsley-row">
									<label for="SeasonsCbx">Seasons<span
										class="req">*</span></label>
                                                                        <select id="SeasonsCbx"
										name="SeasonsCbx" required
										class='SeasonsCbx form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										
									</select>
                                                                        
								</div>
							</div>
                                                        <div class="uk-width-medium-1-4">
								<div class="parsley-row">
									<label for="eligibleClassesCbx">Eligible Classes<span
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
                                                    <div class="uk-width-medium-1-4">
                                                        <div class="parsley-row" id='batch1Msg'>
                                                            
                                                        </div>
                                                    </div>
                                                    
                                                    
                                                    

						</div>
                                                <div class="uk-grid" data-uk-grid-margin id='enrollmentbtnsdisplay1' style="display:none">
                                                    <div class="uk-width-medium-1-2">
                                                        <div class="parsley-row">
                                                            <button type="button" class="btn btn-sm uk-alert uk-alert-info pull-right" id="GoChangeAge" style="display: inline-block;">Change Age</button>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-2">
                                                        <div class="parsley-row">
                                                            <button type="button" class="btn btn-sm uk-alert uk-alert-info" id="GoParallelAge" style="display: inline-block;"> Parallel Age</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                        
                                                
                                                <div class="uk-grid" data-uk-grid-margin id='enrollmentcontinue2'>
                                                    <div class="uk-width-medium-1-4">
								<div class="parsley-row">
									<label for="SeasonsCbx2">Seasons<span
										class="req">*</span></label>
                                                                        <select id="SeasonsCbx2"
										name="SeasonsCbx2" required
										class='SeasonsCbx2 form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										
									</select>
                                                                        
								</div>
                                                    </div>
                                                    <div class="uk-width-medium-1-4">
								<div class="parsley-row">
									<label for="eligibleClassesCbx2">Eligible Classes<span
										class="req">*</span></label> <select id="eligibleClassesCbx2"
										name="eligibleClassesCbx2" required
										class='eligibleClassesCbx2 form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										<option value=""></option>
									</select>
								</div>
                                                    </div>
                                                    <div class="uk-width-medium-1-4">
                                                        <div class="parsley-row">
                                                            <label for="hobbies">Batch<span class="req">*</span></label> <select
										id="batchCbx2" name="batchCbx2" required
										class='form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										<option value=""></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-4">
                                                        <div class="parsley-row" id='batch2Msg'>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="uk-grid" data-uk-grid-margin id='enrollmentbtnsdisplay2' style="display:none;">
                                                    <div class="uk-width-medium-1-2">
                                                        <div class="parsley-row">
                                                            <button type="button" class="btn btn-sm uk-alert uk-alert-info pull-right" id="GoChangeAge2" style="display: inline-block;">Change Age</button>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-2">
                                                        <div class="parsley-row">
                                                            <button type="button" class="btn btn-sm uk-alert uk-alert-info" id="GoParallelAge2" style="display: inline-block;">Parallel Age</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                        
                                                <div class="uk-grid" data-uk-grid-margin id='enrollmentcontinue3'>
                                                    <div class="uk-width-medium-1-4">
								<div class="parsley-row">
									<label for="SeasonsCbx3">Seasons<span
										class="req">*</span></label>
                                                                        <select id="SeasonsCbx3"
										name="SeasonsCbx3" required
										class='SeasonsCbx3 form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										
									</select>
                                                                        
								</div>
                                                    </div>
                                                    <div class="uk-width-medium-1-4">
								<div class="parsley-row">
									<label for="eligibleClassesCbx3">Eligible Classes<span
										class="req">*</span></label> <select id="eligibleClassesCbx3"
										name="eligibleClassesCbx3" required
										class='eligibleClassesCbx3 form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										<option value=""></option>
									</select>
								</div>
                                                    </div>
                                                    <div class="uk-width-medium-1-4">
                                                        <div class="parsley-row">
                                                            <label for="hobbies">Batch<span class="req">*</span></label> <select
										id="batchCbx3" name="batchCbx3" required
										class='form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272;">
										<option value=""></option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="uk-width-medium-1-4">
                                                        <div class="parsley-row" id='batch3Msg'>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="uk-grid" data-uk-grid-margin id='enrollmentbtnsdisplay3' style="display:none">
                                                    <div class="uk-width-medium-1-1">
                                                        <div class="parsley-row" style="text-align: center;">
                                                            <button type="button"  class="btn btn-sm uk-alert uk-alert-info" id="GoEnrollmentConfirm" style="display: inline-block;">ConfirmThirdBatch</button>
                                                        </div>
                                                    </div>
                                                </div>
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
                                                    <br clear="all">
                                                        <div class="uk-grid" data-uk-grid-margin>
                                                            <div class="uk-width-medium-1-3">
                                                                
                                                            </div>
                                                             <div class="uk-width-medium-1-3">
                                                                
                                                            </div>
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row">
									<label for="CustomerType">Customer type<span
										class="req">*</span></label>
                                                                        <select id="CustomerType"
										name="CustomerType" required
										class='CustomeType form-control input-sm md-input'
										style="padding: 0px; font-weight: bold; color: #727272; float:right;">
										
                                                                    	
                                                                        <option value="NewCustomer">New Customer</option>
                                                                        <option value="OldCustomer">Old Customer</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                        </div>
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
                                                                                <!--
										<select type="hidden" name="discountPercentage" id="discountPercentage" class="input-sm md-input"
												style='padding: 0px; font-weight: bold; color: #727272; width:50%; float:right'>
											<option value="0">Select discount percentage</option>
											<option value="10">10%  discount</option>
											<option value="20">20%  discount</option>
											<option value="30">30%  discount</option>
											<option value="40">40%  discount</option>
											<option value="50">50%  discount</option>
										</select>
										<span id="discountText">
										
										</span>
                                                                                -->
                                                                                <div id="discount"></div>
                                                                                
                                                                                </td>
										<td><input style="font-weight: bold" type="text"
											name="discountTextBox" id="discountTextBox" readonly value=""
											class="form-control input-sm md-input" />
											<input type="hidden" name="discountPercentage" id="discountPercentage" value=""/> 
                                                                                        
											
										</td>
									</tr>
                                                                        <tr>
                                                                            <td colspan="2" style="text-align: right; font-weight: bold"><div id="second_child_discount"><p>Sibling Consideration:0%</p></div></td>
                                                                            <td><input style="font-weight: bold" type="text"
											name="second_child_amount" id="second_child_amount" readonly value="0"
											class="form-control input-sm md-input" />
											
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td colspan="2" style="text-align: right; font-weight: bold"><div id="second_class_discount"><p>Multi Classes:0%</p></div></td>
                                                                            <td><input style="font-weight: bold" type="text"
											name="second_class_amount" id="second_class_amount" readonly value="0"
											class="form-control input-sm md-input" />
											
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
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row" id="Order-date" style="display: none;">
                                                                        <label for="OrderDate">Order date<span
										class="req">*</span></label><br>
									{{Form::text('OrderDate',
									null,array('id'=>'OrderDate', 'required'=>'','class' =>
									'uk-form-width-medium'))}}
                                                                    </div>
                                                                </div>
                                                            
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row Order-Date"  style="display: none;">
                                                                       
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
												<option value="Rupay">Rupay</option>
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
												 type="text"
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
                                                            </div>
							
                                                           <!-- id="paymentType2" for old customer 2nd order box -->
                                                            
                                                            <div  id="Order-date2" class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-medium-1-3" >
									<div class="parsley-row Order-Date2" style="display:none">
										<input type="radio" name="paymentTypeRadioOldCustomer2" required
											id="paymentOptionsOldCustomer_1" value="card" /> <label
											for="paymentOptionsOldCustomer_1" class="inline-label">Card</label> <input
											type="radio" name="paymentTypeRadioOldCustomer2" id="paymentOptionsOldCustomer_2"
											value="cash" /> <label for="paymentOptionsOldCustomer_2"
											class="inline-label">Cash</label> <input type="radio"
											name="paymentTypeRadioOldCustomer2" id="paymentOptionsOldCustomer_3" value="cheque" />
										<label for="paymentOptionsOldCustomer_3" class="inline-label">Cheque</label>

									</div>
								</div>
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row Order-Date2"  style="display: none;">
                                                                       <label for="OrderDate2">Order date<span
										class="req">*</span></label><br>
									{{Form::text('OrderDate2',
									null,array('id'=>'OrderDate2', 'required'=>'','class' =>
									'uk-form-width-medium'))}}
                                                                    </div>
                                                                  </div>
                                                            
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row Order-Date2"  style="display: none;">
                                                                       
                                                                    </div>
                                                                </div>
							</div>
                                                        
                                                    
							<div  id="paymentType2" style="width: 100%; display:none">
                                                                <div  class="uk-grid" data-uk-grid-margin id="cardDetailsDiv2">
                                                                    <div class="uk-width-medium-1-1" >
										<h4>Card details</h4>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<select name="cardType2" id="cardType2"
												class="input-sm md-input"
												class="form-control input-sm md-input"
												style='padding: 0px; font-weight: bold; color: #727272;'>
												<option value="master">Master card</option>
												<option value="maestro">Maestro</option>
												<option value="visa">Visa</option>
												<option value="Rupay">Rupay</option>
											</select>
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="card4digits2" class="inline-label">Last 4 digits
												of your card<span class="req">*</span>
											</label> <input id="card4digits2" number name="card4digits2"
												maxlength="4" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="cardBankName2" class="inline-label">Bank Name of your card<span class="req">*</span>
											</label> <input id="cardBankName2" number name="cardBankName2"
												 type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="cardRecieptNumber2" class="inline-label">Reciept number<span class="req">*</span>
											</label> <input id="cardRecieptNumber2" number name="cardRecieptNumber2"
												maxlength="4" type="text" 
												class="form-control input-sm md-input" />
										</div>
									</div>

								</div>
								<div id="chequeDetailsDiv2" class="uk-grid" data-uk-grid-margin>

									<div class="uk-width-medium-1-1">
										<h4>Cheque details</h4>
										<br clear="all"/>
									</div>
									<br clear="all"/><br clear="all"/>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeBankName2" class="inline-label">Bank name<span
												class="req">*</span></label> <input id="chequeBankName2"
												name="bankName2" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeNumber2" class="inline-label">Cheque number<span
												class="req">*</span></label> <input id="chequeNumber2"
												name="chequeNumber2" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
								</div>
                                                            
                                                            </div>
                                                            
                                                            <!-- id="paymentType3" for old customer 3rd order box -->
                                                            
                                                            <div  id="Order-date3" class="uk-grid" data-uk-grid-margin>
                                                                
								<div class="uk-width-medium-1-3" >
									<div class="parsley-row Order-Date3" style="display:none">
										<input type="radio" name="paymentTypeRadioOldCustomer3" required
											id="paymentOptionsOldCustomer3_1" value="card" /> <label
											for="paymentOptionsOldCustomer3_1" class="inline-label">Card</label> <input
											type="radio" name="paymentTypeRadioOldCustomer3" id="paymentOptionsOldCustomer3_2"
											value="cash" /> <label for="paymentOptionsOldCustomer3_2"
											class="inline-label">Cash</label> <input type="radio"
											name="paymentTypeRadioOldCustomer3" id="paymentOptionsOldCustomer3_3" value="cheque" />
										<label for="paymentOptionsOldCustomer3_3" class="inline-label">Cheque</label>

									</div>
								</div> 
                                                                
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row Order-Date3"  style="display: none;">
                                                                       <label for="OrderDate3">Order date<span
										class="req">*</span></label><br>
									{{Form::text('OrderDate3',
									null,array('id'=>'OrderDate3', 'required'=>'','class' =>
									'uk-form-width-medium'))}}
                                                                    </div>
                                                                  </div>
                                                                
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row Order-Date3"  style="display: none;">
                                                                       
                                                                    </div>
                                                                </div>
                                                                
							</div>
                                                        
                                                       
							<div  id="paymentType3" style="width: 100%; display:none">
                                                            
                                                                <div  class="uk-grid" data-uk-grid-margin id="cardDetailsDiv3">
                                                                    <div class="uk-width-medium-1-1" >
										<h4>Card details</h4>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<select name="cardType3" id="cardType3"
												class="input-sm md-input"
												class="form-control input-sm md-input"
												style='padding: 0px; font-weight: bold; color: #727272;'>
												<option value="master">Master card</option>
												<option value="maestro">Maestro</option>
												<option value="visa">Visa</option>
												<option value="Rupay">Rupay</option>
											</select>
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="card4digits3" class="inline-label">Last 4 digits
												of your card<span class="req">*</span>
											</label> <input id="card4digits3" number name="card4digits3"
												maxlength="4" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="cardBankName3" class="inline-label">Bank Name of your card<span class="req">*</span>
											</label> <input id="cardBankName3" number name="cardBankName3"
												 type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="cardRecieptNumber3" class="inline-label">Reciept number<span class="req">*</span>
											</label> <input id="cardRecieptNumber3" number name="cardRecieptNumber3"
												maxlength="4" type="text" 
												class="form-control input-sm md-input" />
										</div>
									</div>

								</div>
                                                                
                                                                
								<div id="chequeDetailsDiv3" class="uk-grid" data-uk-grid-margin>
                                                                        
									<div class="uk-width-medium-1-1">
										<h4>Cheque details</h4>
										<br clear="all"/>
									</div>
                                                                       
									<br clear="all"/><br clear="all"/>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeBankName3" class="inline-label">Bank name<span
												class="req">*</span></label> <input id="chequeBankName3"
												name="bankName3" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeNumber3" class="inline-label">Cheque number<span
												class="req">*</span></label> <input id="chequeNumber3"
												name="chequeNumber3" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
                                                                       
								</div>
                                                                
                                                            </div>
                                                            
                                                            
                                                             
                                                        <!--  for old customer 4th order box -->
                                                            
                                                            <div  id="Order-date4" class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-medium-1-3" >
									<div class="parsley-row Order-Date4" style="display:none">
										<input type="radio" name="paymentTypeRadioOldCustomer4" required
											id="paymentOptionsOldCustomer4_1" value="card" /> <label
											for="paymentOptionsOldCustomer4_1" class="inline-label">Card</label> <input
											type="radio" name="paymentTypeRadioOldCustomer4" id="paymentOptionsOldCustomer4_2"
											value="cash" /> <label for="paymentOptionsOldCustomer4_2"
											class="inline-label">Cash</label> <input type="radio"
											name="paymentTypeRadioOldCustomer4" id="paymentOptionsOldCustomer4_3" value="cheque" />
										<label for="paymentOptionsOldCustomer4_3" class="inline-label">Cheque</label>

									</div>
								</div>
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row Order-Date4"  style="display: none;">
                                                                       <label for="OrderDate4">Order date<span
										class="req">*</span></label><br>
									{{Form::text('OrderDate4',
									null,array('id'=>'OrderDate4', 'required'=>'','class' =>
									'uk-form-width-medium'))}}
                                                                    </div>
                                                                  </div>
                                                            
                                                                <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row Order-Date4"  style="display: none;">
                                                                       
                                                                    </div>
                                                                </div>
							</div>
                                                        
                                                    
							<div  id="paymentType4" style="width: 100%; display:none">
                                                                <div  class="uk-grid" data-uk-grid-margin id="cardDetailsDiv4">
                                                                    <div class="uk-width-medium-1-1" >
										<h4>Card details</h4>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<select name="cardType4" id="cardType4"
												class="input-sm md-input"
												class="form-control input-sm md-input"
												style='padding: 0px; font-weight: bold; color: #727272;'>
												<option value="master">Master card</option>
												<option value="maestro">Maestro</option>
												<option value="visa">Visa</option>
												<option value="Rupay">Rupay</option>
											</select>
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="card4digits4" class="inline-label">Last 4 digits
												of your card<span class="req">*</span>
											</label> <input id="card4digits4" number name="card4digits4"
												maxlength="4" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="cardBankName4" class="inline-label">Bank Name of your card<span class="req">*</span>
											</label> <input id="cardBankName4" number name="cardBankName4"
												 type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="cardRecieptNumber4" class="inline-label">Reciept number<span class="req">*</span>
											</label> <input id="cardRecieptNumber4" number name="cardRecieptNumber4"
												maxlength="4" type="text" 
												class="form-control input-sm md-input" />
										</div>
									</div>

								</div>
								<div id="chequeDetailsDiv4" class="uk-grid" data-uk-grid-margin>

									<div class="uk-width-medium-1-1">
										<h4>Cheque details</h4>
										<br clear="all"/>
									</div>
									<br clear="all"/><br clear="all"/>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeBankName4" class="inline-label">Bank name<span
												class="req">*</span></label> <input id="chequeBankName4"
												name="bankName4" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
									<div class="uk-width-medium-1-2">
										<div class="parsley-row">
											<label for="chequeNumber4" class="inline-label">Cheque number<span
												class="req">*</span></label> <input id="chequeNumber4"
												name="chequeNumber4" type="text"
												class="form-control input-sm md-input" />
										</div>
									</div>
								</div>
                                                            </div>
                                                            
                                                            
                                                            
                                                             
                                                            
                                                        
                                                            
                                                            
                                                                    
                                                            
                                                                <!-- for invoice -->
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
                                                                <option value="RE_SCHEDULE">Re Schedule</option>
							</select>
						</div>
					</div>
					 <br clear="all" /> <br clear="all" />
                                         <div class="uk-grid" data-uk-grid-margin>
                                         <div class="uk-width-medium-1-3">
                                                                    <div class="parsley-row" id="reschedule" style="margin-top: -20px;display: none;">
										<label for="reschedule-date">Reschedule-date<span
											class="req">*</span></label> <br>
										{{Form::text('reschedule-date',
										null,array('id'=>'reschedule-date', 'required'=>'', 'class' =>
										''))}}
							            </div>
                                         </div>
                                             <div class="uk-width-medium-1-3"></div>
                                             <div class="uk-width-medium-1-3"></div>
                                         </div>
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
					Edit Kids<span id="kidNameInPopup"></span>
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
								<label for="nickname">Nickname</label>
								{{Form::text('nickname', null,array('id'=>'nickname',
								'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="studentDob">Date of birth</label>
								{{Form::text('studentDob',
								null,array('id'=>'studentDob', 'class' => '','required'))}}
							</div>
						</div>
					</div>
					<br clear="all" />
					<br clear="all" />
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="studentGender">Gender</label>
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
								<label for="school">School</label>
								{{Form::text('school', null,array('id'=>'school',
								'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="location">Location</label>
								{{Form::text('location', null,array('id'=>'location',
								 'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
					</div>
					<br clear="all" />
					<br clear="all" />
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="hobbies">Hobbies</label>
								{{Form::text('hobbies', null,array('id'=>'hobbies', 
								'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="emergencyContact">Emergency contact</label>
								{{Form::text('emergencyContact',
								null,array('id'=>'emergencyContact',  'class' =>
								'form-control input-sm md-input'))}}
							</div>
						</div>
						<div class="uk-width-medium-1-3">
							<div class="parsley-row">
								<label for="remarks">Remarks</label>
								{{Form::text('remarks', null,array('id'=>'remarks', 
								'class' => 'form-control input-sm md-input'))}}
							</div>
						</div>
					</div>
					<div class="uk-grid" data-uk-grid-margin>
						<div class="uk-width-medium-1-1">
							<div class="parsley-row">
								<label for="healthIssue">Health Issues</label>
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

<!-- pendingamount -->
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

 
 
 <!-- Receive due Modal -->
  <div id='receivedue' class="modal fade" role="dialog" style="margin-top: 50px; z-index: 99999;"> 
    <div class="modal-dialog modal-lg">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" id="receivedueheader">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Receive Due</h4>
        </div>
        <div class="modal-body" id="receiveduebody">
            <div  class="uk-grid" data-uk-grid-margin>
                      <div class="uk-width-medium-1-1" id="receiveDueMsg"></div>
            </div>
          <div  class="uk-grid" data-uk-grid-margin>
              <div class="uk-width-medium-1-1" >
               <h4>Select Payment Type</h4>
              </div>
            </div>
          <div id="receivepaymentType" class="uk-grid" data-uk-grid-margin>
								<div class="uk-width-medium-1-3">
									<div class="parsley-row">
										<input type="radio" name="paymentReceiveTypeRadio" required
											id="paymentOptionsReceive_1" value="card" /> <label
											for="paymentOptionsReceive_1" class="inline-label">Card</label> 
                                                                                <input type="radio" name="paymentReceiveTypeRadio" id="paymentOptionsReceive_2"
											value="cash" /> <label for="paymentOptionsReceive_2"
											class="inline-label">Cash</label>
                                                                                <input type="radio" name="paymentReceiveTypeRadio" id="paymentOptionsReceive_3" value="cheque" />
										       <label for="paymentOptionsReceive_3" class="inline-label">Cheque</label>

							
                                                                        </div>
                                                                </div>
                                                                <div class="uk-width-medium-1-3">
                                                                    <input type="hidden" id="pending_id" value=""/>
                                                                    <input type="hidden" id="pending_amt" value=""/>
                                                                    <input type="hidden" id="pending_discount" value=""/>
                                                                </div>
           </div>
           <div id="paymentReceiveType" style="width: 100%">
		<div id="receiveCardDetailsDiv" class="uk-grid" data-uk-grid-margin>
		    <div class="uk-width-medium-1-1">
                        <h4>Card details</h4>
                    </div>
                    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <select name="receivecardType" id="receivecardType"
				class="input-sm md-input"
				class="form-control input-sm md-input"
			        style='padding: 0px; font-weight: bold; color: #727272;'>
                                <option value="master">Master card</option>
				<option value="maestro">Maestro</option>
				<option value="visa">Visa</option>
                                <option value="Rupay">Rupay</option>
                            </select>
			</div>
                    </div>
                    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <label for="card4digits" class="inline-label">Last 4 digits
                               of your card<span class="req">*</span>
			    </label> 
                            <input id="receivecard4digits" number name="receivecard4digits"
			    maxlength="4" type="text" class="form-control input-sm md-input" />
			</div>
		    </div>
	            <br clear="all"/><br clear="all"/>						
                    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <label for="receivecardBankName" class="inline-label">Bank Name of your card<span class="req">*</span>
			    </label> 
                            <input id="receivecardBankName" number name="receivecardBankName"	 type="text"
			     class="form-control input-sm md-input" />
			</div>
		    </div>
									
		    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
			    <label for="receivecardRecieptNumber" class="inline-label">Reciept number<span class="req">*</span>
			    </label> 
                            <input id="receivecardRecieptNumber" number name="receivecardRecieptNumber"
				 type="text" class="form-control input-sm md-input" />
                        </div>
                    </div>

		</div>
                <div id="receiveChequeDetailsDiv" class="uk-grid" data-uk-grid-margin>

                    <div class="uk-width-medium-1-1">
			<h4>Cheque details</h4>
                            <br clear="all"/>
                    </div>
                    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <label for="receivechequeBankName" class="inline-label">Bank name<span
				class="req">*</span></label> <input id="receivechequeBankName"
				name="receivebankName" type="text"
                                accept=""class="form-control input-sm md-input" />
			</div>
                    </div>
		    <div class="uk-width-medium-1-2">
                        <div class="parsley-row">
                            <label for="receivechequeNumber" class="inline-label">Cheque number<span
				class="req">*</span></label> <input id="receivechequeNumber"
				name="receivechequeNumber" type="text"
				class="form-control input-sm md-input" />
                        </div>
		    </div>
		</div>
								
								
                <div id="receiveemailEnrollPrintDiv" class="uk-grid" data-uk-grid-margin>
                    <div class="uk-width-medium-1-1">
			<h4>Invoice option</h4>
		    </div>
		    <div class="uk-width-medium-1-2">
			<div class="parsley-row">
                            <input id="receiveinvoicePrintOption" name="receiveinvoicePrintOption"  value="yes"  type="checkbox"  class="checkbox-custom" />
                                <label for="receiveinvoicePrintOption"  class="checkbox-custom-label">Print Invoice<span
                                    class="req">*</span></label> 
			</div>
                    </div>
                    <div class="uk-width-medium-1-2" id='receiveemail' style="display:none">
			<div class="parsley-row">
                            <input id="receiveemailOption" name="receiveemailOption" type="checkbox"  value="yes" class="checkbox-custom"  />
				<label for="receiveemailOption" class="checkbox-custom-label">Email Invoice<span
                                    class="req">*</span></label> 
                        </div>
                    </div>
		</div>

            </div>

       </div>     
          
        <div class="modal-footer" id="receiveduefooter">
          <button type="submit" class="btn btn-primary" id="receivepayment">Receive Payment</button>
          <button type="button" class="btn btn-default" id='receivedueclose'>Close</button>
        </div>
      
      
    </div>
  </div>

@stop
