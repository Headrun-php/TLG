@extends('layout.master')

@section('libraryCSS')
	<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->
	<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css"/>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' />
@stop

@section('libraryJS')
<script src="{{url()}}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="{{url()}}/bower_components/datatables-colvis/js/dataTables.colVis.js"></script>
<script src="{{url()}}/bower_components/datatables-tabletools/js/dataTables.tableTools.js"></script>
<script src="{{url()}}/assets/js/custom/datatables_uikit.min.js"></script>
<script src="{{url()}}/assets/js/pages/plugins_datatables.min.js"></script>
<script src="{{url()}}/assets/js/kendoui_custom.min.js"></script>
<script src="{{url()}}/assets/js/pages/kendoui.min.js"></script>
<script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js'></script>
<script type="text/javascript">

	$("#membersTable").DataTable({
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
        "iDisplayLength": 50,
        "lengthMenu": [ 10, 50, 100, 150, 200 ]
    });

	$("#introVisitDateDiv").hide();
	$("#state").change(function (){
		 getCities($("#state").val(), 'city');
	});

	$("#customerEmail").blur(function (){
		isCustomerExists();
	});


	function isCustomerExists(){
		
		var ajaxUrl = "{{url()}}/quick/"+"customerexistence";
		console.log(ajaxUrl);
		var isExists = "no";
		$.ajax({
			  type: "POST",
			  url: ajaxUrl,
			  dataType: 'json',
			  async: true,
			  data:{'email':$("#customerEmail").val()},
			  success: function(response, textStatus, jqXHR)
			  {
				    if (response.status == "exists"){	
					    isExists = "yes";			    	
				    	$("#callbackMessage").html('<div class="uk-alert uk-alert-danger" data-uk-alert><a href="#" class="uk-alert-close uk-close"></a>Sorry, This Email address already exists.</div>');
				    }else{
				    	$("#callbackMessage").html("");
				    }			  
			  },
			  error: function (jqXHR, textStatus, errorThrown)
			  { }
		});

		console.log(isExists);
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


$("#customerSubmit").click(function (event){
	
	event.preventDefault();
	var ajaxUrl = "{{url()}}/quick/"+"customerexistence";
	console.log(ajaxUrl);
	var isExists = "no";$.ajax({
		  type: "POST",
		  url: ajaxUrl,
		  dataType: 'json',
		  async: true,
		  data:{'email':$("#customerEmail").val()},
		  success: function(response, textStatus, jqXHR)
		  {
			    if (response.status == "exists"){	
				    		    	
			    	$("#callbackMessage").html('<div class="uk-alert uk-alert-danger" data-uk-alert><a href="#" class="uk-alert-close uk-close"></a>Sorry, This Email address already exists.</div>');
			    }else if(response.status == "clear"){
			    	$("#callbackMessage").html("");
			    	$("#addCustomerForm").submit();
			    }			  
		  },
		  error: function (jqXHR, textStatus, errorThrown)
		  { }
	});
	
});

/* $("#customersTable tr").click(function (){

	window.location = $(this).find('a').attr('href');
})
 */
	
	
</script>

@stop

@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="{{url()}}/customers/memberslist" style="z-index:8;">Customers</a></li>
		<li><a href="#" style="z-index:7;">Members List</a></li>
	</ul>
</div>
<br clear="all"/>
<div class="">
	<div class="row">
	
		
		
		
			<h4>List of Members</h4>
		
            
            <div class="md-card">
	            <div class="md-card-content large-padding">
		            <h3 class="heading_b uk-margin-bottom">Customers</h3>
		            
		            <?php 
		            	/*  echo "<pre>";
		            	print_r($customers);
		            	echo "</pre>";  */
		            
		            ?>
		
		           
		            <div class="md-card uk-margin-medium-bottom">
		                <div class="md-card-content">
		                    <div class="uk-overflow-container">
		                        <table class="uk-table table-striped" id="membersTable">
		                            <!-- <caption>Table caption</caption> -->
		                            <thead>
		                            <tr>
		                                <th>Customer Name</th>
		                                <th>Customer Email</th>
		                                <th>Customer mobil no</th>
		                                <th>Customer Address</th>
		                                <!-- <th>Action</th> -->
		                            </tr>
		                            </thead>
		                            <tbody>
                                               <?php if(isset($customers)){ ?>
		                            @foreach($customers as $customer)
		                            <tr>
		                                <td>{{$customer->customer_name}}{{$customer->customer_lastname}}</td>
		                                <td>{{$customer->customer_email}}</td>
		                                <td>{{$customer->mobile_no}}</td>
		                                <td>{{$customer->building}} {{$customer->apartment_name}} {{$customer->lane}}
		                                <a style="display: none;" href="{{url()}}/customers/view/{{$customer->id}}">View</a>
		                                </td>
		                                <!-- <td><a class="md-btn md-btn-flat md-btn-flat-primary" href="{{url()}}/customers/view/{{$customer->id}}">View</a></td> -->
		                                
		                            </tr>
		                            @endforeach
                                               <?php } ?>
		                          </tbody>
		                        </table>
		                    </div>
		                </div>
		            </div>
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