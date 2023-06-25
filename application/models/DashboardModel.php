<?php
class DashboardModel extends MasterModel{
    
    
    
    public function sendSMS($mobiles,$message){
        
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://sms.scubeerp.in/sendSMS?");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, "username=9427235336&message=".$message."&sendername=NTVBIT&smstype=TRANS&numbers=".$mobiles."&apikey=7d37fc6d-a141-4f81-9d79-159cf37c3342");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec($ch);
		curl_close ($ch);
	}
	
}
?>