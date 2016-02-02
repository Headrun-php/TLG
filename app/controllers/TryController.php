<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
class TryController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
			
		echo "<pre>";
		

		
		echo "harsha";
		
		
		
		$enrolledCustomers = StudentClasses::getEnrolledCustomers();
		print_r($enrolledCustomers);
		
		
		
		
		exit();
		
		$data = array("customerName" => "Prasath Aru");
		
		Mail::send('emails.account.customer', $data, function($msg) {
			$email = 'prasath@sincerity.in';
			$msg->from('prasath@sincerity.in', 'The Little Gym');
			$msg->to($email, "Prasath Arumugam")->subject('Test mail');
		});
		
		
		
		
		
		exit();
		
		/* $batchEndDate = Batches::select('end_date')->where("id","=", 2)->get();
		print_r($batchEndDate['0']->end_date); */
		
		$batches = BatchSchedule::where('batch_id', '=', '2')
								->whereBetween('schedule_date', array('2016-1-1', '2016-3-31'))
								->count();
								//->get();
		echo $batches;
	}
	
	


	


}
