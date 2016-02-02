
<?php 
/* echo "<pre>";
print_r($orders); */

/* $orders = $orderDetailsTomail['orders']; 
$class  = $orderDetailsTomail['class']; */
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
	
	</style>
	<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
	
</head>
<body>
<div class="col-md-12" style="background-color: #EEEEEE; padding:5px;" >
	<div class="container">
		
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
				<p style="text-align: center;">Thank You and welcome to The Little Gym family</p>
				<div class="col-md-11" style="margin:0px auto !important; float:none; border-bottom:2px dashed #EEEEEE;">
				 <h4>Payment Reciept and Enrollment  Details</h4>
				</div>
				<br clear="all"/>
				<div class="col-md-11" style="margin:0px auto !important; float:none;">
					<div class="row datarow">
					  <div class="col-md-3 title"><strong>Customer Name</strong></div>
					  <div class="col-md-4" style="float:left !important">{{$orders->Customers->customer_name}}</div>
					</div><br clear="all"/>
					<div class="row datarow">
					  <div class="col-md-3 title"><strong>Kid Name</strong></div>
					  <div class="col-md-4">{{$orders->Students->student_name}}</div>
					</div><br clear="all"/>
					<div class="row datarow">
					  <div class="col-md-3 title"><strong>Enrolled Class Name</strong></div>
					  <div class="col-md-4">{{$class->class_name}}</div>
					</div>
					
					
					
					
					
					
					
					<br clear="all"/>
					<br clear="all"/>
					<h4>Payment details:</h4>
					<div class="row datarow">
					  <div class="col-md-3 title"><strong>Payment date</strong></div>
					  <div class="col-md-4">{{date('d M Y  H:i A',strtotime($orders->created_at))}}</div>
					</div>
					
					
					
					
					
					
					<div class="col-md-6">
					  <div class="col-md-3 title"><strong>Enrollment Start date</strong></div>
					  <div class="col-md-4">{{date('d M Y',strtotime($studentbatch['start_date']))}}</div>
					</div>					
					<div class="col-md-6">
					  <div class="col-md-3 title"><strong>Enrollment End date</strong></div>
					  <div class="col-md-4">{{date('d M Y',strtotime($studentbatch['end_date']))}}</div>
					</div>
					<br clear="all"/>
					
					<div class="row datarow">
					  <div class="col-md-3 title"><strong>Membership Type</strong></div>
					  <div class="col-md-4"><?php if(isset($customers['customerMembership'])){?>{{$customers['customerMembership']->MembershipTypes->name}}<?php }?></div>
					</div>
					<br clear="all"/>
					<div class="row datarow">
					  <div class="col-md-3 title"><strong>Paid Amount</strong></div>
					  <?php 
					  	$percentageAmount =  ((14.5 / 100) * $orders->amount);
					  	$total            =  ($percentageAmount+$orders->amount);
					  
					  ?>
					  <div class="col-md-4">{{$total}}</div>
					</div>
					<br clear="all"/>
					
					
				
					<div class="row datarow">
					  <div class="col-md-3 title"><strong>Payment Mode</strong></div>
					  <div class="col-md-4">{{$orders->payment_mode}}</div>
					</div>
					<br clear="all"/>
					<div class="row datarow">
					  <div class="col-md-3 title"><strong>Payment Date</strong></div>
					  <div class="col-md-4">{{$orders->created_at}}</div>
					</div>
					<br clear="all"/>
					
					<p>Welcome. Thanks for Joining The Little Gym.  Regards, Team TLG</p>	
				</div>
			
			
			</div>
		
		</div>
	
	
	</div>


</body>
</html>
