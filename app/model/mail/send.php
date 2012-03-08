<?php
/**
 * 
 * @author dariush jafari
 * @Entity
 */
class MailSend extends Mail
{
	/**
	* @ManyToOne(targetEntity="MyGroup", inversedBy="MailSend")
	* @JoinColumn(name="SenderGroupID",referencedColumnName="ID")
	* @var MyGroup
	*/
	protected $SenderGroup;
	function SenderGroup(){ return $this->SenderGroup; }
	function SetSenderGroup($s){$this->SenderGroup=$s; }
	function AssignSenderGroup(MyGroup $SenderGroup)
	{
		$this->SenderGroup=$SenderGroup;
		$SenderGroup->MailSend()->add($this);
	}
	/**
	 *
	 * @ManyToOne(targetEntity="ReviewTopic", inversedBy="MailSend")
	 * @JoinColumn(name="ReceiverTopicID",referencedColumnName="ID")
	 * @var ReviewTopic
	 */
	protected $ReceiverTopic;
	function ReceiverTopic(){ return $this->ReceiverTopic;}
	function SetReceiverTopic($r){ $this->ReceiverTopic=$r;}
	function AssignReceiverTopic(ReviewTopic $ReceiverTopic)
	{
		$this->ReceiverTopic=$ReceiverTopic;
		$ReceiverTopic->MailSend()->add($this);
	}
	/**
	 * @OneToMany(targetEntity="ReviewProgressSend", mappedBy="MailSend")
	 * @var arrayCollectionOfReviewProgressSend
	 */
	protected $ProgressSend;
	function ProgressSend(){ return $this->ProgressSend; }
	
	function Save($Files){}
	function __construct($Num=null, $Subject=null, $SenderGroup=null, $ReceiverTopic=null, $Description=null)
	{
		parent::__construct($Num, $Subject, $Description);
		if($SenderGroup) $this->AssignSenderGroup($SenderGroup);
		if($ReceiverTopic) $this->AssignReceiverTopic($ReceiverTopic);
		$this->ProgressSend= new ArrayCollection();
	}
	
	
	
}