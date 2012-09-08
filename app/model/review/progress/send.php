<?php
use Doctrine\Common\Collections\ArrayCollection;

/** 
 * @Entity
 * @entity(repositoryClass="ReviewProgressSendRepository")
 * */
class ReviewProgressSend extends ReviewProgress
{
	
	/**
	*
	* @ManyToOne(targetEntity="MailSend", inversedBy="ProgressSend")
	* @JoinColumn(name="MailSendID",referencedColumnName="ID")
	* @var MailSend
	*/
	protected $MailSend;
	function MailSend(){ return $this->MailSend; }
	function SetMailSend($Mail){ $this->MailSend=$Mail; }
	function AssignMailSend($Mail)
	{
		$this->MailSend=$Mail;
		$Mail->ProgressSend()->add($this);
	}
	function __construct(ReviewFile $File=null, MailSend $Mail=null)
	{
		parent::__construct($File, null, $IfPersist);
		if($Mail)
			$this->SetMailSend($Mail);
		
	}
	function  Summary()
	{
		if(!$this->MailSend)
			return "نامه یافت نشد.";
		$href=ViewMailPlugin::GetHref($this->MailSend, "Send");
		$r="اظهارنامه از ".$this->MailSend->SenderGroup()->PersianTitle()." با شماره نامه <a href='".$href."'>".$this->MailSend->Num()."</a> به <b>".$this->MailSend->ReceiverTopic()->Topic()."</b> ارسال شد.";
		return $r;
	}
	function Title()
	{
		return "ارسال ";
	}
	function Manner()
	{
		$r="Send_";
		$Sender=$this->MailSend->SenderGroup()->Title();
		$r="Send_".strtolower($Sender)."_to_out";
		return $r;
	}
	public function AddAssociations(){
		parent::AddAssociations();
		$this->MailSend->ProgressSend()->add($this);
	}
}


use \Doctrine\ORM\EntityRepository;
class ReviewProgressSendRepository extends EntityRepository
{
	public function AddToFile(ReviewFile $File,MailSend $Mail)
	{
		$P=new ReviewProgressSend($File, $Mail);
		$ch=$P->Check();
		if(is_string($ch))
			return $ch;
			//$P=new ReviewProgressSend($File, $Mail, true);
		$P->Apply();
		ORM::Persist($P);
		return $P;
	}
	
	public function SendCountPerMonth($monthCount, $startMonth=0)
	{
		//-----get the numberof days of last month---------------------------
		$c = new CalendarPlugin();
		$t = $c->TodayJalaliArray();
		$dayOfMonth= $t[2]; //our purpose--------------------------------
		$addDays = 30 - $dayOfMonth;
		
		//\Doctrine\ORM\Query\Parser::registerNumericFunction('FLOOR', 'Doctrine\ORM\Query\MysqlFloor');
		
		$r = j::DQL("SELECT COUNT(S.ID) as co, T.Type as tt,
						 (
									( ( CURRENT_TIMESTAMP() + ? * 24*3600 - ? * 30*24*3600 ) - S.CreateTimestamp )
									/30.3*24*3600 
						 	  ) as mon
				FROM ReviewProgressSend S JOIN S.MailSend M JOIN M.ReceiverTopic T
				WHERE 
					( ( CURRENT_TIMESTAMP() + ? * 24*3600 - ? * 30*24*3600 ) - S.CreateTimestamp ) 
					>=0
				GROUP BY T.Type",$addDays,$startMonth,$addDays,$startMonth);
	}
	
}