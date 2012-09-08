<?php
class ReviewCorrespondenceMainController extends JControl
{
	function Start()
	{
		j::Enforce("Reviewer");
		$Error=array();
		$Cotag=$_REQUEST['Cotag'];
		if(isset($Cotag)){
			$Cotag = b::CotagFilter($_REQUEST['Cotag']);
			$File=b::GetFile($Cotag);
			if($File instanceof ReviewFile){
				$SubManner = $_POST['SubManner'];
				$MailNum = $_POST['MailNum'];
				$Comment = $_REQUEST['Comment'];
				$SetadID = $_POST['SetadID'];
				if(!empty($SetadID))
					$Setad = ORM::Find('ReviewTopic', $SetadID);
				if(!($Setad instanceof ReviewTopic))
					$Setad = null;
				$R = ORM::Query('ReviewProcessReview')->AddToFile($File,$SubManner, $MailNum, $Setad);
				if(is_string($R))
					$Error[] = $R;
				else 
					$Result=$R->Summary();
			}else{
				$Error[] = v::Ecnf($Cotag);
			}
		}
		
		foreach (ReviewProcessReview::$SubMannerArray as $v){
			$fsmp = FsmGraph::GetProgressByName('ProcessReview_'.$v);
			$sma[$v]=$fsmp->Label;
		}
		$this->SubMannerArray = $sma;
		
		foreach (ReviewTopic::Topics('mokatebat') as $v){
			$ta[$v['ID']] = $v['Topic'];
		}
		$this->TopicArray= $ta;
		$this->Error=$Error;
		$this->Result=$Result;
		if (count($this->Error)) $this->Result=false;
		$this->Cotag=$Cotag;
		$this->makeForm();
		return $this->Present();
	}
	function makeForm(){
		$f = new AutoformPlugin('post');
		$f->AddElement(array(
				'Name'=>'Cotag',
				'Label'=>p::Cotag
				));
		$f->AddElement(array(
				'Name'=>'SubManner',
				'Label'=>'تعیین مسیر',
				'Vertical'=>true,
				'ContainerStyle'=>'text-align:right; margin-right:50px; padding-right:50px; ',
				'Type'=>'radio',
				'Options'=>$this->SubMannerArray
				));
		$f->AddElement(array(
				'Name'=>'SetadID',
				'Type'=>'select',
				'Label'=>'دفتر',
				'Dependency'=>'SubManner',
				'DependencyValue'=>'=="setad"',
				'Options'=>$this->TopicArray
				));
		$f->AddElement(array(
				'Name'=>'Comment',
				'Type'=>'textarea',
				'Label'=>p::desc
				));
		$f->AddElement(array(
				'Name'=>'submit',
				'Type'=>'submit',
				'Value'=>'ثبت رای'
		));
		$this->Form = $f;
	}
}