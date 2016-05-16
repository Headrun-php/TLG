<?php 
/* echo "<pre>";
print_r($orders); */
?>
<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <link rel="icon" type="image/png" href="{{url()}}/assets/img/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="{{url()}}/assets/img/favicon-32x32.png" sizes="32x32">
    <title>TLG - Administration</title> 	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	<style>
		.datarow{
			margin-bottom:10px;
		}
		
		.title{
			font-weight:bold;
		}
		
		.paymentsTable{
		
			width:100%;
		
		
		}
		
		.paymentsTable tr td{
		
			border-bottom:1px #e5e5e5 dashed;
			padding:10px;
			height:30px;
		
		
		}
                @media print {
                    #Header, #Footer { display: none !important; }
                }
	
	</style>
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	<script>
		$(document).ready(function (){
			
			printme();
			$("#printBtn").click(function (){
				printme();
		
			});
		
		});
		function printme() {
		   // window.print();

			  //Get the HTML of div
            var divElements = document.getElementById('printable').innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;
			   

			 
		}
	</script>
</head>
<body>
<div class="col-md-12" style="background-color: #EEEEEE; padding:5px;" >
	<div class="container">
		<div class="row">		
			<div class="col-md-9"  style="margin:0px auto !important; float:none; border: 1px dashed #ededed;">
				<button id="printBtn" style="float:right;" class="btn btn-sm  btn-primary">Print</button>
			</div>
		</div>
	</div>
</div>
	<div class="container" id="printable">
		
		
		<br clear="all"/>
		<div class="row">
		
			<div class="col-md-9"  style="margin:0px auto !important; float:none; border: 1px dashed #ededed; padding:20px;">
				<div class="col-md-11" style="margin:0px auto !important; float:none; border-bottom:2px dashed #EEEEEE;">
					<div style="margin:0px auto; width:174px; display:block;  background-repeat:no-repeat;  background-image: url('{{url()}}//assets/img/logo.png'); height:90px;">
						<img src="{{url()}}//assets/img/logo.png"/>
					
					</div>
				
				</div>
				<br clear="all"/>
				<p style="text-align: center;">Thank You and welcome to The Little Gym family</p>
				<br clear="all"/>
				<div class="col-md-11" style="margin:0px auto !important; float:none; border-bottom:2px dashed #EEEEEE;">
				 <h4>Payment Reciept and Enrollment  Details</h4>
				</div>
				<br clear="all"/>
				<div class="col-md-11" style="margin:0px auto !important; float:none;">
					<div class="row datarow">
					  <div class="col-md-3 title">Customer Name</div>
					  <div class="col-md-4" style="float:left !important">{{$orders->Customers->customer_name}}</div>
					</div>
					
					<div>
						<div class="" style="width:50%; float:left">
						  <div class="title">Kid Name</div>
						  <div class="col-md-4">{{$orders->Students->student_name}}</div>
						</div>
						
						<div class="" style="width:50%; float:right">
						  <div class="title">Enrolled Class Name</div>
						  <div class="col-md-4">{{$class->class_name}}</div>
						</div>
					</div>
					
					<br clear="all"/>
					<h4>Payment details:</h4>
					<div class="row datarow">
					  <div class="col-md-3 title">Payment date</div>
					  <div class="col-md-4">{{date('d M Y  H:i A',strtotime($orders->created_at))}}</div>
					</div>
					<div class="row datarow">
                                            <div class="" style="width:50%; float:left">
                                                <div class="col-md-3 title">No of sessions</div>
                                                <div class="col-md-4">{{$paymentDues[0]['selected_order_sessions']}}</div>
                                            </div>
                                            <div class="" style="width:50%; float:left">
                                                <div class="col-md-3 title">Payment Type</div>
                                                <div class="col-md-4">{{$paymentDues[0]['payment_type']}}</div>
                                            </div>
					</div>
					
					
					<div>
						<div class="" style="width:50%; float:left">
						  <div class="title">Enrollment Start date</div>
						  <div class="col-md-4">{{date('d M Y',strtotime($paymentDues[0]['start_order_date']))}}</div>
						</div>
						
						<div class="" style="width:50%; float:right">
						  <div class="title">Enrollment End date</div>
						  <div class="col-md-4">{{date('d M Y',strtotime($paymentDues[0]['end_order_date']))}}</div>
						</div>
					</div>
					
					<div class="row datarow">
					  <div class="col-md-3 title">Membership Type</div>
					  <div class="col-md-4"><?php if($customerMembership){?><?php }?></div>
					</div>
					
					<div class="row datarow">
					  <div class="col-md-3 title">Payment amount</div>
					  <div class="col-md-4">{{$orders->amount}}</div>
					</div>
					
					<?php
					$membershipAmount = 0;
					 if($orders->membership_type){
					
						if($customerMembership->MembershipTypes->id == 1){
							$membershipAmount = 2000;
								
						}else if($customerMembership->MembershipTypes->id == 2){
						
							$membershipAmount = 5000;
						}
						
						
					}?>
					<table class="paymentsTable">
						<thead>
							<tr style="font-weight:bold">
								<td style="text-align:left; width:70%;">Particulars</td>
								<td style="text-align:right">Amount</td>
							</tr>
						
						</thead>						
						<tr>
							<td>Enrollment Amount</td>
							<td style="text-align:right">
								<?php 
								
								//$paymentDues['0']->discount_applied
									$percentageAmount =  ((14.5 / 100) * $orders->amount);
								  	$actualAmount =  ($orders->amount-$percentageAmount);
								  	
								  	if($orders->membership_type){
								  		//$enrollmentAmount = ($actualAmount-$membershipAmount);
								  	}
								  	
								  ?>
								  {{$orders->amount}}
							</td>
						</tr>		
						
						<?php 
												
						if($orders->membership_type){?>
						<tr>
							<td>Membership ({{$customerMembership->MembershipTypes->name}})</td>
							<td style="text-align:right">
								{{number_format($membershipAmount, 2, '.', '');}}
							</td>
						</tr>
						<?php }?>
						
										
						<tr>
							<td style="text-align:right"><strong>Discount amount</strong></td>
							<td  style="text-align:right">
								
								<strong>{{$paymentDues['0']->discount_amount}}</strong>
							</td>
						</tr>
						<tr>
							<td style="text-align:right"><strong>Subtotal</strong></td>
							<td  style="text-align:right">
								<?php 
								//$percentageAmount =  ((14.5 / 100) * $orders->amount);
								$discountedAmount =  ((int)$orders->amount - abs($paymentDues['0']->discount_amount)); 
								
								if(isset($customerMembership->id)){
									
									$discountedAmount = ($discountedAmount+$membershipAmount);
								}
								
								
								?>
								<strong>{{number_format($discountedAmount, 2, '.', '');}}</strong>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:right"><strong>Service Tax</strong></td>
							<td  style="text-align:right">
								<?php 
								//$percentageAmount =  ((14.5 / 100) * $orders->amount);
								$percentageAmount =  ((14.5 / 100) * $orders->amount);
								
								?>
								<strong>{{number_format($percentageAmount, 2, '.', '');}}</strong>
							</td>
						</tr>
						
						<tr>
							<td style="text-align:right"><strong>Total</strong></td>
							<td  style="text-align:right">
								<?php 
								
								$totalAmountToPay = ($discountedAmount+$percentageAmount);
								
								
								
								
								?>
								<strong>{{number_format($totalAmountToPay, 2, '.', '');}}</strong>
							</td>
						</tr>
						<tr>
							<td></td>
							<td></td>
						</tr>
					
					
					</table>
					
					
					
					<div class="row datarow">
					  <div class="col-md-3 title">Payment Mode</div>
					  <div class="col-md-4">{{$orders->payment_mode}}</div>
					</div>
					
					<div class="row datarow">
					  <div class="col-md-3 title">Payment type details</div>
					  <div class="col-md-4">
					  <?php if($orders->payment_mode == 'card'){ ?>
					  	Card type: {{$orders->card_type}}
					  
					  <?php }else if($orders->payment_mode == 'cheque'){ ?>
					  		Cheque number: {{$orders->cheque_number}}
					  <?php }else{?>
					  cash payment
					  <?php }?>
					  
					  
					  </div>
					</div>
					
					<br clear="all"/>
					
					<p>Welcome. Thanks for Joining The Little Gym.  Regards, Team TLG</p>
					<hr/>
					<p>Terms & Conditions:</p>
					<br/>					
				</div>
			
			
			</div>
		
		</div>
	
	
	</div>


</body>
</html>