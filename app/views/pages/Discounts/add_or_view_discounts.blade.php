@extends('layout.master')

@section('libraryCSS')
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

<script type="text/javascript">

	$("#seasonTable").DataTable({
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {
            $(nRow).click(function() {
            });
            return nRow;
        },
       "iDisplayLength": 20,
       "lengthMenu": [ 10, 50, 100, 150, 200 ]
	 });

	$(document).on("click", "a.remove" , function() {
            $(this).parent().parent(".uk-grid").remove();
    });

	function addDiscount(){
		var markups ='<div class="uk-grid" data-uk-grid-margin>'+
					  	'<div class="uk-width-medium-1-3">'+
        					'<div class="parsley-row form-group">'+
        						'<label for="title[]">Number of Classes<span class="req">*</span></label>'+
        						'<input id="NoClasses[]" required class="form-control input-sm md-input" name="NoClasses[]" type="text"/>'+
        					'</div>'+
    					'</div>'+
    					'<div class="uk-width-medium-1-3">'+
        					'<div class="parsley-row form-group">'+
        						'<label for="title[]">Number of Classes<span class="req">*</span></label>'+
        						'<input id="DiscountPrcnt[]" required class="form-control input-sm md-input" name="DiscountPrcnt[]" type="text"/>'+
        					'</div>'+
    					'</div>'+
    					'<div class="uk-width-medium-1-3">'+	
    						'<a href="javascript:void(0);" class="remove badge" style = "background: red; color: #fff"> &times; </a>'+
    					'</div>'+
					'</div>';
		$('#addDiscount').append(markups);
	}

	function saveDiscount(){
		if($('#DiscountForSecondChaild').val() != '' && $('#DiscountForSecondClass').val() != ''){

			var no_of_class = $('input[name="NoClasses[]"]').map(function() {
                        return this.value
                   }).get();
			var discount_prcnt = $('input[name="DiscountPrcnt[]"]').map(function() {
                        return this.value
                   }).get();

			$.ajax({
        		type: "POST",
        		url: "{{URL::to('/quick/addMultipleDiscounts')}}",
        		data: {'no_of_class': no_of_class, 'discount_prcnt': discount_prcnt, 'DiscountForSecondChaild': $('#DiscountForSecondChaild').val(), 'DiscountForSecondClass': $('#DiscountForSecondClass').val()},
        		dataType:"json",
        		success: function (response)
        		{
        			if (response.status == "success") {
        				$('#msgDiv').html('<h5 class = "uk-alert-success" style = "color: #fff; width: 85%; padding: 8px; text-align: center">Discounts is added Successfully. Please wait untill this page reload</h5>');			
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
		else{
			$('#msgDiv').html('<h5 alert class = "uk-alert-danger" style = "color: #fff; width: 80%; padding: 8px; text-align: center">Please fill all required fields and Save.</h5>');
			setTimeout(function(){
 				$('#msgDiv').html('');            
           }, 3500);    
		}
	}
</script>
@stop

@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="#" style="z-index:8;">Discounts</a></li>
		<li><a href="#" style="z-index:7;">Add/View Discounts</a></li>
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
<div class="uk-width-medium-1-1">
	<div class="parsley-row form-group">
    	<strong><h4>Add Discounts<i class=" btn fa fa-plus fa-1x" onclick="addDiscount()"></i></h4></strong>
    </div>
</div>

<div id = "addDiscount">
	<div class="uk-grid" data-uk-grid-margin>
		<div class="uk-width-medium-1-3">
        	<div class="parsley-row form-group">
        		<label for="title[]">Number of Classes<span class="req">*</span></label>
            	{{Form::text('NoClasses[]', null,array('id'=>'NoClasses[]',
				'required', 'class' => 'form-control input-sm md-input'))}}
        	</div>
    	</div>
    	<div class="uk-width-medium-1-3">
        	<div class="parsley-row form-group">
        		<label for="title[]">Discount Percentage<span class="req">*</span></label>
            	{{Form::text('DiscountPrcnt[]', null,array('id'=>'DiscountPrcnt[]',
				'required', 'class' => 'form-control input-sm md-input'))}}
        	</div>
    	</div>
	</div>
</div>
<!-- For discount_second_child and discount_second_class -->
	<div class="uk-grid" data-uk-grid-margin style = "margin-top: 4em">
		<div class="uk-width-medium-1-3"></div>
		<div class="uk-width-medium-1-3">
        	<div class="parsley-row form-group">
        		<label for="title[]">Discount For Second Child<span class="req">*</span></label>
            	{{Form::text('DiscountForSecondChaild', null,array('id'=>'DiscountForSecondChaild',
				'required', 'class' => 'form-control input-sm md-input'))}}
        	</div>
    	</div>
    	<div class="uk-width-medium-1-3">
        	<div class="parsley-row form-group">
        		<label for="title[]">Discount For Second Class<span class="req">*</span></label>
            	{{Form::text('DiscountForSecondClass', null,array('id'=>'DiscountForSecondClass',
				'required', 'class' => 'form-control input-sm md-input'))}}
        	</div>
    	</div>
	</div>
	<div class="row" style = "margin-top: 3em;">
        <div class="col-md-12">
			<button type="button" id="saveDiscount" onclick = "saveDiscount()" class="md-btn md-btn-primary">Save Discounts</button>
        </div>
    </div>
    </div>
</div>

<div class="md-card">
	<div class="md-card-content large-padding">
		<h3 class="heading_b uk-margin-bottom">Discounts</h3>

		<div class="md-card uk-margin-medium-bottom" >
		            <div class="md-card-content">
                                <div class="uk-overflow-container">
                                    <table  class="uk-table" id="seasonTable">
                                        <thead>
		                            <tr>
		                                <th>Number Of Classes</th>
		                                <th>Discount Percentage</th>
		                                <th>Discount Second Child</th>
		                                <th>Discount Second Class</th>
                                        <th>Created BY</th>
		                            </tr>
		                            </thead>
                                        <tbody>
                                        <?php if($discount_data){for($i=0;$i<sizeof($discount_data);$i++){ ?>
                                         <tr>
                                             <td>{{$discount_data[$i]['number_of_classes']}}</td>
                                             <td>{{$discount_data[$i]['discount_percentage']}}</td>
                                             <td>{{$discount_data[$i]['discount_second_child']}}</td>
                                             <td>{{$discount_data[$i]['discount_second_class']}}</td>
                                             <td>{{$discount_data[$i]['created_by_name']}}</td>
                                         </tr>
                                         <?php }}else{ ?>
                                         <tr>
                                             No seasons Added
                                         </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


	</div>
</div>
@stop