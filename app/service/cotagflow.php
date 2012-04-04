<?php
class CotagflowService extends BaseService
{
	//lists the categories available
    function Execute($Params)
    {
		$this->App->LoadModule("model.review.file");
        
        $Cotag=$Params[Cotag]*1;
        $File=ORM::query(new ReviewFile)->GetRecentFile($Cotag);
        if(!$File)
        {
        	$res[Err]='1';
        	return $res;
        }
        $Data=$File->AllProgress();
        if(count($Data)==0)
        {
        	$res[Err]='2';
        	return $res;
        }
        $i=0;
      
        foreach ($Data as $D)
        {
        	$res[$i]['Title']=$D->Title();
        	$res[$i]['User']=$D->User()->getFullName();
        	$res[$i]["Summary"]=$D->Summary();
        	$res[$i]["Time"]=$D->CreateTime();
        	$i++;
        }
    	return $res;
    }
}
?>
