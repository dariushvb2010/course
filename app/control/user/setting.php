<?php
class UserSettingController extends BaseControllerClass
{
    function Start ()
    {
        $this->Form=null;
        $this->Result=null;
        $User=MyUser::CurrentUser();
        $MySetting=$User->MainSetting();
        
        //------------------------manager--------------------------
        /**
         * setting is in type MySettingManager
         */
        if(j::Check("MasterHand"))
        {
        	if($MySetting)
        		$this->Result=$MySetting->Make($_GET['ShowRetireds']);
        	else 
        	{
        		$b=1;
        		$MySetting=new MySettingManager($User,1,$b);
        		ORM::Persist($MySetting);
        	}
   			$this->Form=$MySetting->MakeForm();
        }
		return $this->Present();
    }
}
?>