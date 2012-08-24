<?php
/**
 * 
 * handles the mail controller on public mode(when no MailID is got)
 * ----------------------------------------------------------------------------------------------<p>
 * |               نامه های ذخیره شده: نامه 1    نامه 2
 * |  -public_mode-------------------------
 * |                      ایجاد نامه جدید
 * |                 text: name=Num,
 * |                 text: name=Title,
 * |                 textarea: name=Comment,
 * |                 submit: name=Create,
 * |  -public_mode(cont)-----------------------
 * |                  شماره نامه 234 
 * |                  عنوان نامه
 * |                  توضیحات
 * |           <input type='hidden' name='MailID' value='<?php echo $_GET['MailID']; ?>' />
 * |           Dynamic List         
 * |           <input name="Cotages[]" />
 * |          --------  
 * |   if(Mail->State()==Mail::STATE_EDITING)            
 * |       {  submit: name=Save,     
 * |          submit: name=Give,  
 * |       }    
 * |                                 
 * -----------------------------------</p>
 * @author dariush jafari
 *
 */
class HandleTransferPublic extends HandleTransfer
{
	/**
	 * A form for creating new mail 
	 * @var AutoformPlugin
	 */
	public $CreateForm;
	function Perform()
	{
		
		if(isset($_POST['Create']))
		{
			$Num=$_POST['MailNum'];
			$Title=$_POST['Title'];
			$Comment=$_POST['Comment'];
			if($this->Action=="Give")
				$Mail=ORM::Query("MailGive")->Add($Num, $Title, $this->Source, $this->Dest, $Comment);
			elseif($this->Action=="Send")
			{
				$TopicID=$_POST['TopicID'];//for send and receive
				$Topic=ORM::Find("ReviewTopic", $TopicID);
				if(!$Topic)
				{
					$this->Error[]="محل ارسال یافت نشد.";
				}
				else
					$Mail=ORM::Query("MailSend")->Add($Num, $Title, $this->SourceGroup, $Topic, $Comment);
			}
			elseif($this->Action=="Receive")
			{
				$TopicID=$_POST['TopicID'];//for send and receive
				$Topic=ORM::Find("ReviewTopic", $TopicID);
				if(!$Topic)
				{
					$this->Error[]="محل ارسال یافت نشد.";
				}
				else
					$Mail=ORM::Query("MailReceive")->Add($Num, $Title, $this->DestGroup, $Topic, $Comment);
			}
			if(is_string($Mail))
				$this->Error[]=$Mail;
			elseif(!count($this->Error))
			{
				$this->Result.="نامه با شماره ".$Mail->Num()." ایجاد شد.";
				ORM::Flush();//i want to use the ID of the created Mail thus i have to Flush()!
				$this->Mail=$Mail;
			}
			if(count($this->Error))
				$this->Result=false;
			$this->MakeMainForm();
		}
		else
		{
			if($this->Action!="Get")
				$this->MakeCreateForm();
			
			if($this->Action=="Give" OR $this->Action=="Get")
			if(!$this->SourceGroup OR !$this->DestGroup)
			{
				$this->Error[]="گروه یافت نشد.";
				return;
			}
			if($this->Action=="Send")
			if(!$this->SourceGroup)
			{
				$this->Error[]="گروه یافت نشد.";
				return;
			}
			if($this->Action=="Receive")
			if(!$this->DestGroup)
			{
				$this->Error[]="گروه یافت نشد.";
				return;
			}
			if($this->Action=="Give")
				$this->Mail=ORM::Query("MailGive")->LastMail($this->SourceGroup, $this->DestGroup, Mail::STATE_EDITING);
			elseif($this->Action=="Get")
				$this->Mail=ORM::Query("MailGive")->LastMail($this->SourceGroup, $this->DestGroup, Mail::STATE_INWAY);
			elseif($this->Action=="Send")
				$this->Mail=ORM::Query("MailSend")->LastMail($this->SourceGroup);
			elseif($this->Action=="Receive")
				$this->Mail=ORM::Query("MailReceive")->LastMail($this->DestGroup);
		}
		//$this->MakeMainForm();
		//$this->MakeSearchForm();
		//$this->ShowMails();
	}
	/**
	 * a form for creating a new mail
	 */
	private function MakeCreateForm()
	{
		$f=new AutoformPlugin("post");
		$f->AddElement(array("Type"=>"text","Name"=>"MailNum","Label"=>"شماره نامه"));
		$f->AddElement(array( "Type"=>"text", "Name"=>"Title","Label"=>"عنوان نامه"));
		if($this->Action=="Send" OR $this->Action=="Receive")	
		{	
			$Ts=ORM::Query("ReviewTopic")->GetTopics($this->TopicType);
			$Label=($this->Action=="Send" ? "ارسال به " : "دریافت از" );
			if($Ts)
			foreach ($Ts as $T)
				$Topics[$T['ID']]=$T['Topic'];
			$f->AddElement(array("Type"=>"select", "Name"=>"TopicID","Label"=>$Label, "Options"=>$Topics));
		}
		$f->AddElement(array("Type"=>"textarea","Name"=>"Comment","Label"=>"توضیحات"));
		$f->AddElement(array("Type"=>"submit","Name"=>"Create","Value"=>"ایجاد نامه"));
		$this->CreateForm=$f;
	}
	function ShowMails()
	{
		if($this->Action=="Give")
		{
			$Mails=ORM::Query("MailGive")->GetAll($this->SourceGroup,$this->DestGroup, MailGive::STATE_EDITING);
			ViewMailPlugin::GroupShow($Mails,"Give");
		}
		elseif($this->Action=="Get")
		{
			$Mails=ORM::Query("MailGive")->GetAll($this->SourceGroup,$this->DestGroup, MailGive::STATE_INWAY);
			ViewMailPlugin::GroupShow($Mails,"Get");
			$Mails2=ORM::Query("MailGive")->GetAll($this->SourceGroup,$this->DestGroup, MailGive::STATE_GETTING);
			ViewMailPlugin::GroupShow($Mails2, "Get");
		}
		elseif($this->Action=="Send")
		{
			$Mails=ORM::Query("MailSend")->GetAll($this->SourceGroup,'all',mail::STATE_EDITING);
			ViewMailPlugin::GroupShow($Mails, "Send");
		}
		elseif($this->Action=="Receive")
		{
			$Mails=ORM::Query("MailReceive")->GetAll($this->DestGroup, 'all', mail::STATE_EDITING);
			ViewMailPlugin::GroupShow($Mails, "Receive");
		}
	}
	
	function __construct($Action, $Source, $Dest)
	{
		parent::__construct($Action, $Source, $Dest);
	}
}