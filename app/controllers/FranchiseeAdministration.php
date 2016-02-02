<?php

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
class FranchiseeAdministration extends \BaseController {

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
	public function users()
	{
		
		//echo "Users";
		$currentPage  =  "USERS";
		$mainMenu     =  "USERS_MAIN";
		
		$Users = User::getUsersByFranchisee();
		
		//$data = array('Users','currentPage', 'mainMenu');
		
		return View::make('pages.users.userslist', compact('Users','currentPage', 'mainMenu') );
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function adduser()
	{
		$currentPage  =  "ADD_USERS";
		$mainMenu     =  "USERS_MAIN";
		
		$Users = User::getUsersByFranchisee();
		
		$inputs = Input::all();
		
		if(isset($inputs['userType'])){
			
			$inputs['franchiseeId'] = Session::get('franchiseId');
			
			$randomPassword = str_random(6);
			$encryptedPassword = Hash::make($randomPassword);
			$inputs['password'] = $encryptedPassword;
			
			$addUserResult = User::addUser($inputs);
			
			
			$userFullName   = $inputs['firstName'].' '.$inputs['lastName'];
				
				
			$UserDetails = array('password'=>$randomPassword, 'userName'=>$userFullName, 'email'=>$inputs['email']);
			
			if($UserDetails){
				
				Mail::send('emails.account.usercreation', $UserDetails, function($msg) use ($UserDetails){
						
					$msg->from(Config::get('constants.EMAIL_ID'), Config::get('constants.EMAIL_NAME'));
					$msg->to($UserDetails['email'], $UserDetails['userName'])->subject('The Little Gym - User account created');
						
				});
			}
			
			if($addUserResult){
				Session::flash ( 'msg', "User account created successfully." );
			}else{
				Session::flash ( 'error', "User account could not be created at the moment." );
			}
			
			return Redirect::to ( 'admin/users/view/' . $addUserResult->id );
		}
		
		//$data = array('Users','currentPage', 'mainMenu');
		
		return View::make('pages.users.useradd', compact('Users','currentPage', 'mainMenu') );
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function viewUser($id)
	{
		
		
		
		$currentPage  =  "USERS";
		$mainMenu     =  "USERS_MAIN";
		
		$inputs = Input::all();
		
		if(isset($inputs['firstName'])){
			
			$userEditResult = User::editUser($id, $inputs);
			
			if($userEditResult){
				Session::flash ( 'msg', "User details has been edited Successfully." );
				
				return Redirect::to ( 'admin/users/view/' . $userEditResult->id );
			}
			
		}
		
		
		
		$User = User::getUsersByUserId($id);
		$User = $User['0'];
		
		//$data = array('Users','currentPage', 'mainMenu');
		
		return View::make('pages.users.useredit', compact('User','currentPage', 'mainMenu') );
		
	}
	
	
	public function checkUser(){
		
		$inputs = Input::all();
		$email = $inputs['email'];
		$count = User::where('email', '=', $email)->count();
		if($count>0){
			$existence = "exists";
		}else{
			$existence = "clear";
		}
		return Response::json(array('existence'=>$existence));
		
		
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
