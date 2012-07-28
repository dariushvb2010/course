<?php

class MainController extends BaseControllerClass
{

	function Start()
	{
		
// 		echo b::CotagValidation(1234567);
// 		var_dump(strval(123));
// 		echo b::GenerateClassNum(248);
		//file_put_contents("a.txt", "salam", FILE_APPEND | LOCK_EX);
		
		//		phpinfo();
//$subject = "Registration completed !!!! ";
//$from= "From: admin@xxxx.com\n";
//$message ="Dear ".$varfname.",\n";
//$message = $message."\r\nCongratulations! \n";
//$message = $message."You are now a member of B2B Portal.\n";
//$message = $message."Your profile is under screening and will be activated within next 24 hrs.\n ";
//
//$header .= "Reply-To: admin <$from>\r\n";
//$header .= "Return-Path: admin <$from>\r\n";
//$header .= "From: admin <$from>\r\n";
//$header .= "Organization: The organization\r\n";
//$header .= "Content-Type: text/plain\r\n";

//var_dump(mail("taram.mohammad@gmail.com", $subject, $message, $header));
//
//		$con=oci_connect("root", "root","192.168.0.1/ASY_DB");
//		if (!$conn) {
//	    $e = oci_error();
//	    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
//		}
		
		$this->User=MyUser::CurrentUser();
		if($this->User && $this->User->Group())
			$this->GroupTitle=$this->User->Group()->PersianTitle();
		
		$this->Alarm_Personal=ORM::Query("Alarm")->CurrentUserAlarms_Personal();
		$this->Alarm_Group=ORM::Query("Alarm")->CurrentUserAlarms_Group();
		
		if(count($this->Error))
			$this->Result=false;
		return $this->Present ();

	}
	function MakeAlarmList($Alarms)
	{
		$al=new AutolistPlugin($Alarms);
		$al->Width="auto";
		$al->HasSelect=true;
		$al->SetHeader("ID", "شناسه",false,false,false);
		$al->SetHeader("Moratorium", "مهلت");
		$al->SetHeader("Comment","توضیحات");
		$al->HasEdit=true;
		$al->HasTier=true;
		return $al;
	}
	function PresentAlarm(Alarm $A)
	{
		
	}
}
?>
