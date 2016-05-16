@extends('layout.master')


@section('libraryCSS')
	<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->
	<link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.common-material.min.css"/>
    <link rel="stylesheet" href="{{url()}}/bower_components/kendo-ui/styles/kendo.material.min.css"/>
    <link href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css' rel='stylesheet' />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
 $("#basepriceTable").DataTable({
        "fnRowCallback": function (nRow, aData, iDisplayIndex) {

            // Bind click event
            $(nRow).click(function() {
                  //window.open($(this).find('a').attr('href'));
		//		window.location = $(this).find('a').attr('href');
                  //OR

                // window.open(aData.url);

            });

            return nRow;
        },
       "iDisplayLength": 5,
       "lengthMenu": [ 5, 10, 50]
	 });
         
function deletebaseprice(baseprice_id){
    $('#deletebaseprice').modal('show');
    $('#baseprice_delete').click(function(){
       $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/baseprice/deletebaseprice')}}",
                        data: {'baseprice_id':baseprice_id},
			dataType: 'json',
			success: function(response){
                            if(response.status=='success'){
                                //$('#addbaseprice').val('');
                                //$("form")[0].reset();
                                //window.location = window.location.href;
                                window.location.reload(1);
                            }
                        }
             }); 
    });
}  
function editbaseprice(id,price){
    $('#base_price2').val(price);
    $('#editbaseprice').modal('show');
    $('#baseprice_update').click(function(){
        $.ajax({
			type: "POST",
			url: "{{URL::to('/quick/baseprice/updatebaseprice')}}",
                        data: {'baseprice_id':id,'base_price':$('#base_price2').val()},
			dataType: 'json',
			success: function(response){
                            console.log(response.status);
                            if(response.status=='success'){
                                //$('#addbaseprice').val('');
                                //$("form")[0].reset();
                                //window.location = window.location.href;
                                window.location.reload(1);
                            }
                        }
             });  

    });
}

         
</script>

@stop

@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="#" style="z-index:8;">Prices&Discounts</a></li>
		<li><a href="#" style="z-index:7;">Add/view Prices</a></li>
	</ul>
</div>
<br clear="all"/>
<br clear="all"/>
<div class="">
    <div class="row">
        <h4>Add/View Base Prices</h4>
        <div class="md-card">
	    <div class="md-card-content large-padding">
		<h3 class="heading_b uk-margin-bottom">Add Base price</h3>
                
                <div class="md-card uk-margin-medium-bottom">
		    <div class="md-card-content">
                        <br>
                        {{ Form::open(array('url' => '/prices/add_or_view_prices', 'id'=>"addbaseprice", "class"=>"uk-form-stacked", 'method' => 'post')) }}    
                        <div class="uk-grid" data-uk-grid-margin>
                            <div class="uk-width-medium-1-3">
                                <div class="parsley-row">
                                <label for="base_price">BasePrice<span class="req">*</span></label>
                                {{Form::text('base_price', null,array('id'=>'base_price', 'required', 'class' => 'form-control input-sm md-input'))}}
                                </div>
                            </div>
                            <div class="uk-width-1-3">
                                <div class="parsley-row">
                                <button type="submit" class="md-btn md-btn-primary" style="float:right" >Submit</button>
                                </div>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
                <h3 class="heading_b uk-margin-bottom">View Base prices</h3>
                <div class="md-card uk-margin-medium-bottom">
                    <div class="md-card-content">
                        <div class="uk-overflow-container">
                            <table  class="uk-table" id="basepriceTable">
                            <thead>
		                <tr>
		                    <th>Name</th>
		                    <th>Base Price</th>
		                    <th>Created By</th>
                                    <th>Updated By</th>
		                    <th>Action</th>
		                </tr>
		            </thead>
                            <tbody>
                            <?php for($i=0;$i<count($base_price_data);$i++){ ?>
                                <tr>
                                    <td> BasePrice{{$base_price_data[$i]['base_price_no']}}</td>
                                    <td> {{$base_price_data[$i]['base_price']}}</td>
                                    <td> {{$base_price_data[$i]['created_by']}}</td>
                                    <td><?php if($base_price_data[$i]['updated_by']===0){ ?>
                                            none
                                        
                                    <?php }else{?>{{$base_price_data[$i]['updated_by']}} <?php } ?></td>
                                    <td><button class='btn btn-warning btn-xs' ><i class="Small material-icons" onclick='editbaseprice({{$base_price_data[$i]['id']}},{{$base_price_data[$i]['base_price']}})'>mode_edit</i></button>
                                        <button class='btn btn-danger btn-xs' ><i class="Small material-icons" onclick="deletebaseprice({{$base_price_data[$i]['id']}})">delete</i></button>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                
           </div>
        </div>
        
    
		            
    </div>
</div>


 <!-- Modal -->
  <div class="modal fade" id="deletebaseprice" role="dialog" style="margin-top: 50px; z-index: 99999;">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Confirm Delete</h4>
        </div>
        <div class="modal-body">
            <div><input type='hidden' id='deleteBatch_id' value=''/></div>
          <p>Do you really want to delete this Base price ?</p>
        </div>
        <div class="modal-footer ">
          <center>
          <button type="button" class="btn btn-primary" id='baseprice_delete' >Yes</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
          </center>
        </div>
      </div>
    </div>
  </div>
 
 
 <!-- Modal -->
  <div class="modal fade" id="editbaseprice" role="dialog" style="margin-top: 50px; z-index: 99999;">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Update</h4>
        </div>
        <div class="modal-body">
             <div class="uk-grid" data-uk-grid-margin>
                 <br>
                <div class="uk-width-medium-1-2">
                    <div class="parsley-row">
                        <label for="base_price2">BasePrice<span class="req">*</span></label><br>
                        {{Form::text('base_price2', null,array('id'=>'base_price2', 'required', 'class' => 'form-control input-sm md-input'))}}
                    </div>
                </div>
             </div>
        </div>
        <div class="modal-footer ">
          <center>
          <button type="button" class="btn btn-primary" id='baseprice_update' >Update</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </center>
        </div>
      </div>
    </div>
  </div>
 
 
@stop