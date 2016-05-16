<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Response;
class VaultController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function login()
	{
		if(Session::get('email')){
			
			
			return Redirect::to('/dashboard');
		}
		else{
			$inputs = Input::all();
			if($inputs){
				if (Auth::attempt(array('email' => $inputs['email'], 'password' => $inputs['password'])))
				{
					$authenticatedUser = Auth::user();
					$userObject = User::with('Franchisee')->find(Auth::id());
					
					
					Session::put('userId', $userObject->id);
					Session::put('email', $userObject->email);
					Session::put('franchiseId', $userObject->franchisee_id);
					Session::put('firstName', $userObject->first_name);
					Session::put('lastName', $userObject->last_name);
					Session::put('userType', $userObject->user_type);
					//Session::put('classAmount',$userObject->franchisee->franchisee_class_amount);
					//return Session::get('classAmount');
					
					return Redirect::to('/dashboard');
				}
			}
		}
		return View::make('pages.auth.login');
	}
	
	
	public function logout() {
		Session::flush();
		Session::flash('message', 'You have successfully logged out of the system.');
		Session::flash('alert-class', 'alert-success');
		return Redirect::to('/');
	}
	
	public function navigateToProfile(){
		
		$inputs = Input::all();
		//echo '<pre>';
		//print_r($inputs);
		$inputsArray = explode("####", $inputs['idCommonSearchTxt']);
		
		if($inputsArray['1'] == "CST"){
				
			return Redirect::to('/customers/view/'.$inputsArray['0']);
				
		}elseif($inputsArray['1'] == "STD"){
			return Redirect::to('/students/view/'.$inputsArray['0']);
		}
		
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		
		
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
