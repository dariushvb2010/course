<?php
class HandleTransferSearch extends HandleTransfer
{
	protected $SearchMails;
	function Perform()
	{
		if(($_POST['ID']))
		{
			$M=ORM::Find("Mail", $_POST['ID']);
			if($M instanceof MailGive)
			{
				if($M->State()<Mail::STATE_INWAY)
					$this->Action="Give";
				else 
					$this->Action="Get";
			}
			elseif($M instanceof MailSend)
				$this->Action="Send";
			elseif($M instanceof MailReceive)
				$this->Action="Receive";
			$this->SearchMails[]=$M;
		}
		else
		{
			$Num=$_POST['Num'];
			$Subject=$_POST['Subject'];
			$State=$_POST['State'];
			$TopicID=$_POST['TopicID'];
			$Topic=ORM::Find("ReviewTopic", $TopicID);
			
			if($Num=="" OR $Num==" " OR $Num=="  ")
				$Num=null;
			if($Subject=="" OR $Subject==" " OR $Subject=="  ")
				$Subject=null;
			if($this->Action=="Give" OR $this->Action=="Get")
				$this->SearchMails=ORM::Query("MailGive")->Search($this->SourceGroup, $this->DestGroup, $State, $Num, $Subject);
			elseif($this->Action=="Send")
				$this->SearchMails=ORM::Query("MailSend")->Search($this->SourceGroup, $Topic, $State, $Num, $Subject);
			elseif($this->Action=="Receive")
				$this->SearchMails=ORM::Query("MailReceive")->Search($this->DestGroup, $Topic, $State, $Num, $Subject);
		}
		$this->MakeSearchForm();
		if(!$this->SearchMails and isset($_POST['Search']))
			$this->Result="هیچ موردی پیدا نشد.";
	}
	function ShowMails()
	{
		ViewMailPlugin::GroupShow($this->SearchMails, $this->Action);
	}
	
	function __construct($Action, $Source, $Dest)
	{
		parent::__construct($Action, $Source, $Dest);
	}
	function MailDescription()
	{
		
	}
}