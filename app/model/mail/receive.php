<?php
/**
 * 
 * when Archive group or Raked group receive some mail with 1000 file in, this type of mail is used 
 * ReceiverGroup='Archive' or 'Raked'
 * @author dariush jafari
 * @Entity
 * 
 */
class MailReceive extends Mail
{
	/**
	* @ManyToOne(targetEntity="MyGroup", inversedBy="MailReceive")
	* @JoinColumn(name="ReceiverGroupID",referencedColumnName="ID")
	* @var MyGroup
	*/
	protected $ReceiverGroup;
	function ReceiverGroup(){return $this->ReceiverGroup;}
	function SetReceiverGroup($s){$this->ReceiverGroup=$s;}
	function AssignReceiverGroup(MyGroup $ReceiverGroup)
	{
		$this->ReceiverGroup=$ReceiverGroup;
		$ReceiverGroup->MailReceive()->add($this);
	}
	
	/**
	* @ManyToOne(targetEntity="ReviewTopic", inversedBy="MailReceive")
	* @JoinColumn(name="SenderTopicID",referencedColumnName="ID")
	* @var ReviewTopic
	*/
	protected $SenderTopic;
	function SenderTopic(){
		return $this->SenderTopic;
	}
	function SetSenderTopic($r){
		$this->SenderTopic=$r;
	}
	function AssignSenderTopic(ReviewTopic $SenderTopic)
	{
		$this->SenderTopic=$SenderTopic;
		$SenderTopic->MailReceive()->add($this);
	}
	
	/**
	* @OneToMany(targetEntity="ReviewProgressReceive", mappedBy="MailReceive")
	* @var arrayCollectionOfReviewProgressReceive
	*/
	protected $ProgressReceive;
	function __construct($Num=null, $SenderTopic=null, $ReceiverGroup=null, $Comment=null)
	{
		parent::__construct($Num, $Comment);
		if($ReceiverGroup) $this->AssignSenderTopic($SenderTopic);
		if( $SenderTopic) $this->AssignReceiverGroup($ReceiverGroup);
		$this->ProgressReceive=new ArrayCollection();
	}
}