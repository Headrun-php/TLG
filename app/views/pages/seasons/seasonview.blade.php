@extends('layout.master')
@section('libraryCSS')
	<!-- <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.min.css" media="all">
	<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/fullcalendar/2.4.0/fullcalendar.print.css" media="all"> -->
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
<script>
    $("#seasonTable").DataTable({
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
       "iDisplayLength": 20,
       "lengthMenu": [ 10, 50, 100, 150, 200 ]
	 });

</script>
@stop

@section('content')
<div id="breadcrumb">
	<ul class="crumbs">
		<li class="first"><a href="{{url()}}" style="z-index:9;"><span></span>Home</a></li>
		<li><a href="#" style="z-index:8;">Seasons</a></li>
		<li><a href="#" style="z-index:7;">View Seasons</a></li>
	</ul>
</div>

<br clear="all"/>
<div class="">
	<div class="row">
             
              <div class="md-card">
                  <div class="md-card-content large-padding ">
                      <h3 class="heading_b uk-margin-bottom">Seasons</h3>
                      <div class="md-card uk-margin-medium-bottom" >
		            <div class="md-card-content">
                                <div class="uk-overflow-container">
                                    <table  class="uk-table" id="seasonTable">
                                        <thead>
		                            <tr>
		                                <th>Season Name</th>
		                                <th>Session No</th>
		                                <th>Start date</th>
		                                <th>End date</th>
                                                <th>Created BY</th>
		                            </tr>
		                            </thead>
                                        <tbody>
                                        <?php if($season_data){for($i=0;$i<sizeof($season_data);$i++){ ?>
                                         <tr>
                                             <td>{{$season_data[$i]['season_name']}}</td>
                                             <td>{{$season_data[$i]['session_no']}}</td>
                                             <td>{{$season_data[$i]['start_date']}}</td>
                                             <td>{{$season_data[$i]['end_date']}}</td>
                                             <td>{{$season_data[$i]['created_by_name']}}</td>
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
        </div>
</div>
@stop