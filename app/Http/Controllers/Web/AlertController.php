<?php
/*
Project Name: IonicEcommerce
Project URI: http://ionicecommerce.com
Author: VectorCoder Team
Author URI: http://vectorcoder.com/
*/
namespace App\Http\Controllers\Web;

//validator is builtin class in laravel
use Validator;

use Mail;
use DB;
//for password encryption or hash protected
use Hash;

//for authenitcate login data
use Auth;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Lang;
//for requesting a value 
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

//for Carbon a value 
use Carbon;
use URL;

class AlertController extends Controller
{
	
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   /* public function __construct()
    {
        $this->middleware('auth');
    }*/

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
	 
	// device
	public function userDevice($customers_id){		
		$device = DB::table('devices')->where('customers_id','=', $customers_id)->get();
		
		if(count($device)>0){
			return $device[0]->device_id;
		}else{
			return '';
		}
		
	}
	
	//alert Setting
	public function getAlertSetting(){
		$setting = DB::table('alert_settings')->get();
		return $setting;
	}
	
	//alert Setting
	public function setting(){
		$setting = DB::table('settings')->get();
		return $setting;
	}
	
	//listingDevices
	public function createUserAlert($existUser){
	 
		
		//alert setting 
		$alertSetting = $this->getAlertSetting();
		
		//setting 
		$setting = $this->setting();
		$existUser[0]->app_name = $setting[18]->value;	
		$existUser[0]->verification_link = 'accountverification/'.$existUser[0]->customers_id;
		$existUser[0]->token = URL::to('/').'/accountverification/'.$existUser[0]->token;
		
		//if($alertSetting[0]->create_customer_email==1 and !empty($existUser[0]->email)){					
			Mail::send('/mail/createAccount', ['userData' => $existUser], function($m) use ($existUser){
				$m->to($existUser[0]->email)->subject(Lang::get("labels.WelcometoEcommerce"))->getSwiftMessage()
				->getHeaders()
				->addTextHeader('x-mailgun-native-send', 'true');	
			});
		//}
		
		if($alertSetting[0]->create_customer_notification==1){	
				
			$title = Lang::get("labels.userThankYou");
			$message = Lang::get("labels.welcomeemailtext").$setting[18]->value;
			
			$sendData = array
				  (
					'body' 	=> $message,
					'title'	=> $title ,
							'icon'	=> 'myicon',/*Default Icon*/
							'sound' => 'mySound',/*Default sound*/
							'image' => '',
				  );
				  
			if($setting[54]->value=='fcm'){
				$functionName = 'fcmNotification';	
			}elseif($setting[54]->value=='onesignal'){
				$functionName = 'onesignalNotification';
			}
			
			//get device id
			$device_id = $this->userDevice($existUser[0]->customers_id);		
			if(!empty($device_id)){	
				$response = $this->$functionName($device_id, $sendData);	
			}
		}
		
		
	}	
	
	//orderAlert
	public function orderAlert($ordersData){
		//tax rate setting
		$tax_rate_setting_data = DB::table('settings')->where('id',93)->get();
		$tax_rate_setting= $tax_rate_setting_data[0]->value;
		
		//alert setting 
		$alertSetting = $this->getAlertSetting();
		
		//setting 
		$setting = $this->setting();
		$ordersData['app_name'] = $setting[18]->value;	
		$ordersData['tax_rate_setting'] = $tax_rate_setting;	
		$ordersData['orders_data'][0]->admin_email = $setting[70]->value;	
		
		if($alertSetting[0]->order_email==1){
			//admin email
			if(!empty($ordersData['orders_data'][0]->admin_email)){
				Mail::send('/mail/orderEmail', ['ordersData' => $ordersData], function($m) use ($ordersData){
					$m->to($ordersData['orders_data'][0]->admin_email)->subject('An order has been placed - NRG Indigenous online store')->getSwiftMessage()
					->getHeaders()
					->addTextHeader('x-mailgun-native-send', 'true');	
				});
			}			
			//admin dispatch email
			// if(!empty($ordersData['orders_data'][0]->admin_email)){			 
			// 	Mail::send('/mail/orderDispatch', ['ordersData' => $ordersData], function($m) use ($ordersData){			
			// 		$m->to($ordersData['orders_data'][0]->admin_email)->subject(Lang::get("labels.Ecommerce App: An order has been dispatched"))->getSwiftMessage()	
			// 		->getHeaders()					
			// 		->addTextHeader('x-mailgun-native-send', 'true');					
			// 	});			
			// }
			
			//customer email
			if(!empty($ordersData['orders_data'][0]->email)){
				Mail::send('/mail/orderEmail', ['ordersData' => $ordersData], function($m) use ($ordersData){
					$m->to($ordersData['orders_data'][0]->email)->subject(Lang::get("labels.Ecommerce App: An order has been placed"))->getSwiftMessage()
					->getHeaders()
					->addTextHeader('x-mailgun-native-send', 'true');	
				});
			}		
		}
		
		if($alertSetting[0]->order_notification==1){	
				
			$title = Lang::get("labels.OrderTitle");
			$message = Lang::get("labels.OrderDetail").$setting[18]->value;
			
			$sendData = array
				  (
					'body' 	=> $message,
					'title'	=> $title ,
							'icon'	=> 'myicon',/*Default Icon*/
							'sound' => 'mySound',/*Default sound*/
							'image' => '',
				  );
				  
			if($setting[54]->value=='fcm'){
				$functionName = 'fcmNotification';	
			}elseif($setting[54]->value=='onesignal'){
				$functionName = 'onesignalNotification';
			}
			
			//get device id
			$device_id = $this->userDevice($ordersData['orders_data'][0]->customers_id);	
			
			if(!empty($device_id)){	
				$response = $this->$functionName($device_id, $sendData);	
			}
		}
	}
		
	
	//listingDevices
	public function forgotPasswordAlert($existUser){
		
		//alert setting 
		$alertSetting = $this->getAlertSetting();
		
		//setting 
		$setting = $this->setting();
		$existUser[0]->app_name = $setting[18]->value;	

		//echo "<pre>"; print_r($alertSetting); echo "</pre>".__FILE__.":".__LINE__;exit;
		
		//if($alertSetting[0]->forgot_email==1 and !empty($existUser[0]->email)){	
			Mail::send('/mail/recoverPassword', ['existUser' => $existUser], function($m) use ($existUser){
				$m->to($existUser[0]->email)->subject(Lang::get("labels.fogotPasswordEmailTitle"))->getSwiftMessage()
				->getHeaders()
				->addTextHeader('x-mailgun-native-send', 'true');	
			});
		//}
		
		if($alertSetting[0]->forgot_notification==1){	
				
			$title = Lang::get("labels.forgotNotificationTitle");
			$message = Lang::get("labels.forgotNotificationMessage");
			
			$sendData = array
				  (
					'body' 	=> $message,
					'title'	=> $title ,
							'icon'	=> 'myicon',/*Default Icon*/
							'sound' => 'mySound',/*Default sound*/
							'image' => '',
				  );
				  
			if($setting[54]->value=='fcm'){
				$functionName = 'fcmNotification';	
			}elseif($setting[54]->value=='onesignal'){
				$functionName = 'onesignalNotification';
			}
			
			//get device id
			$device_id = $this->userDevice($existUser[0]->customers_id);			
			if(!empty($device_id)){	
				$response = $this->$functionName($device_id, $sendData);	
			}	
		}
		
		
	}	
	
	
	
	/**
     * Notifcation section 
     *
     * @return void
     */
	 
	 
	public function fcmNotification($device_id, $sendData){
		
		//get function from other controller
		$setting = $this->setting();
		
		#API access key from Google API's Console
		if (!defined('API_ACCESS_KEY')){
			define('API_ACCESS_KEY', $setting[12]->value);
		}
				
		$fields = array
				(
					'to'		=> $device_id,
					'data'	=> $sendData
				);


		$headers = array
				(
					'Authorization: key=' . API_ACCESS_KEY,
					'Content-Type: application/json'
				);
		#Send Reponse To FireBase Server	
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, true );
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		$result = curl_exec($ch);
		$data = json_decode($result);
		if($result === false)
		die('Curl failed ' . curl_error());

		curl_close($ch);

		if(!empty($data->success) and $data->success >= 1){
			$response = '1';
		}else{
			$response = '0';	
		}	
		
		//print $response;
		
	}
	
	public function onesignalNotification($device_id, $sendData){
		
		//get function from other controller
		$setting = $this->setting();
		
		
		$content = array(
		   "en" => $sendData['body']
		   );
		
		$headings = array(
		   "en" => $sendData['title']
		   );
		
		$big_picture = array(
		   "id1" => $sendData['image']
		   );
		
		$fields = array(
		   'app_id' => $setting[55]->value,
		   'include_player_ids' => array($device_id),
		   'contents' => $content,
		   'headings'=>$headings,
		   'big_picture'=>$sendData['image']
		);
	
		$fields = json_encode($fields);
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
				   'Authorization: Basic ZTJhZTcwNzItODQ4Ni00Y2FiLWFjZjEtMGY4ODZhZGZlMGZl'));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	
		$result = curl_exec($ch);
		$data = json_decode($result);
		curl_close($ch);
		
		if(!empty($data->recipients) and $data->recipients >= 1){
			$response = '1';
		}else{
			$response = '0';	
		}	
		
		//print $response;
		
	}
	
}