<?php
class AlarmDeleteService extends BaseService
{
	//lists the categories available
    function Execute($Params)
    {
		$this->App->LoadModule("model.alarm.free");
        
        $AID=$Params['ID'];
        $A = ORM::Find("AlarmFree", $AID*1);
        $res[data]=$A->ID();
        if(!$A)
        {
        	$res[Err]='1';
        	return $res;
        }
      	else {
      		$res[Res]='1';
      		ORM::Delete($A);
      		return $res;
      	}
    }
}
?>
