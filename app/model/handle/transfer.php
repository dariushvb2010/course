<?php
/**
 * 
 * before using this class you have to get some notifications:
 * you should have two buttons Save and {$ProgressType:[Give][Get]...} in single mode
 * you should name the list of cotages of the mail with "Cotages[]" in hidden inputs
 * your mail repository should have an Add Function
 * 
 * this page has two modes single_mode: MailID is set, and public_mode: MailID is not set
 * when MailID is set you see only the list of cotages in that mail in details
 * when MailID is not set you see all of Mails, a form for creating new mail and a random mail details at the bottom
 * 
 * ---------------- [?Num=234 [& last=1 ]]--OR-- [MailID=id] ------------------
 * -----------------------------------Num=234 shows all mails with num 234-----------------------
 * -----------------------------------Num=234 $ last=1 shows the latest mail with num 234 in details--------
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
abstract class HandleTransfer
{
	/**
	 * My Open mail
	 * @var Mail
	 */
	public $Mail;
	public $MainForm;
	/**
	 * Type of the progress: [Give], [Get], [Send], [Receive] 
	 * @var string
	 */
	protected $Action;
// 	const Get="Get";
// 	const Give="Give";
// 	const Send="Send";
// 	const Receive="Receive";
	function Action(){ return $this->Action; }
	protected function PersianAction()
	{
		switch ($this->Action)
		{
			case "Give":
				$str="تحویل به ";
				$str.=$this->PersianDest();
				return $str;
			case "Get":
				$str="تحویل گرفتن از ";
				$str.=$this->PersianSource();
				return $str;
			case "Send":
				$str="ارسال به ";
				$str.=$this->PersianDest();
				return $str;				
			case "Receive":
				$str="دریافت از";
				$str.=$this->PersianSource();
				return $str;
		}
	}
	/**
	 * Giver or Sender 
	 * @var string
	 */
	protected $Source;
	protected function PersianSource()
	{
		if($this->Source=="Out")
			return "خارج";
		else
			return ConfigData::$GROUPS[$this->Source];
	}
	/**
	 * Gitter or Receiver
	 * @var stirng
	 */
	protected $Dest;
	protected function PersianDest()
	{
		if($this->Dest=="Out")
			return "خارج";
		else
			return ConfigData::$GROUPS[$this->Dest];
	}
	/**
	 * SenderGroup for MailSend and GiverGroup for MailGive
	 * @var MyGroup
	 */
	protected $SourceGroup;
	/**
	 * GetterGroup for MailGet and ReceiverGroup for MailReceive
	 * @var MyGroup
	 */
	protected $DestGroup;
	/**
	 * SenderTopic for MailReceive and ReceiverTopic for MailSend
	 * @var ReviewTopic
	 */
	protected $Topic;
	public $Error=array();
	
	function __construct( $Action="Give", $Source, $Dest)
	{
		$this->Source=$Source;
		$this->Dest=$Dest;
		$this->Action=$Action;
		if($Action=="Receive")
			$this->SourceGroup=null;
		else// Action =Get or Give or Send
			$this->SourceGroup=ORM::Find1("MyGroup", "Title",$Source);
		if($Action == "Send")
			$this->DestGroup=null;
		else //Action =Get or Give or Receive
			$this->DestGroup=ORM::Find1("MyGroup","Title", $Dest);
	}
	/**
	* Performing on a MailGive
	* @param integer $MailID
	*/
	function Perform()
	{
		
	}
	function ShowMails()
	{
		
	}
	protected function MakeListTemplate()
	{
		$al=new DynamiclistPlugin($this->Mail->Box());
		$al->ObjectAccess=true;
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$al->RemoveLabel="حذف";
		$al->HasFormTag=true;
		$al->_List=".autoform .autolist tbody";
		$al->_Button="div.autoform button";
		$al->EnterTextName="Cotag";
		$al->Notifier_Add=array("? اضافه شد","Cotag");
		//--------al custom javascript code for validating the format of the Cotag-------------
		$CotagCode="var patt=/^\d{".b::$CotagLength."}$/;";
		$CotagCode.="if(patt.test(?)); else {alert('فرمت کوتاژ رعایت نشده است.'); return false;}";
		//----------A C J C------------------------------
		$CotagMetaData=array("Unique"=>true,"Clear"=>true, "CustomValidation"=>$CotagCode);
		$al->SetHeader("Cotag", "کوتاژ", "div.autoform :text[name=Cotag]","Text",$CotagMetaData);
		$al->SetHeader("Error", "خطا", "","",array("Useless"=>true,"Style"=>"color:red;"));
		$al->AutoformAfter=true;
		return $al;
	}
	protected function MakeList()
	{
		if($this->Mail instanceof MailGive)
		return $this->MakeListForMailGive();
		else if($this->Mail instanceof MailSend)
		return $this->MakeListForMailSend();
		else if($this->Mail instanceof MailReceive)
		return $this->MakeListForMailReceive();
	}
	private function MakeListForMailGive()
	{
		$al=$this->MakeListTemplate();
		$f=new AutoformPlugin();
		$f->HasFormTag=false;
		if($this->Action=="Give")
		{
			if($this->Mail->State()==Mail::STATE_EDITING)
			{
				$f->AddElement(array("Type"=>"submit", "Name"=>"Save", "Value"=>"ذخیره"));
				$f->AddElement(array("Type"=>"submit", "Value"=>$this->PersianAction(), "Name"=>$this->Action));
				$f->AddElement(array("Type"=>"hidden", "Name"=>"MailID", "Value"=>$this->Mail->ID()));
			}
			else 
			{
				$al->HasRemove=false;
			}
		}
		elseif($this->Action="Get")
		{
			if($this->Mail->State()==Mail::STATE_GETTING)
			{
				$f->AddElement(array("Type"=>"submit", "Name"=>"Save", "Value"=>"ذخیره"));
				$f->AddElement(array("Type"=>"submit", "Value"=>$this->PersianAction(), "Name"=>$this->Action));
				$f->AddElement(array("Type"=>"hidden", "Name"=>"MailID", "Value"=>$this->Mail->ID()));
			}
			else
			{
				$al->HasRemove=false;
			}
		}
		$f->Style="border:none;";
		$al->Autoform=$f;
		return $al;
	}
	private function MakeListForMailSend()
	{
	
	}
	private function MakeListForMailReceive()
	{
	
	}
	protected function MakeMainForm()
	{
		$f=$this->MakeMainFormTemplate();
		if($this->Mail instanceof MailGive)
		{
			if($this->Action=="Give")
			{
				echo "hi";
				if($this->Mail->State()==Mail::STATE_EDITING)
				{
					$f->AddElement(array("Type"=>"text", "Name"=>"Cotag", "Label"=>"کوتاژ"));
					$f->AddElement(array("Type"=>"button","Value"=>"اضافه"));					
				}
			}
			else if($this->Action=="Get")
			{
				echo 1;
				if($this->Mail-State()==Mail::STATE_GETTING OR $this->Mail-State()==Mail::STATE_INWAY)
				{
					echo 2;
					$f->AddElement(array("Type"=>"text", "Name"=>"Cotag", "Label"=>"کوتاژ"));
					$f->AddElement(array("Type"=>"button","Value"=>"اضافه"));
				}
			}
		}
		elseif($this->Mail instanceof MailSend)
		{
			//TODO
		}
		elseif($this->Mail instanceof MailReceive)
		{
			//TODO
		}
		$this->MainForm=$f;
	}
	protected function MakeMainFormTemplate()
	{
		if(!$this->Mail)
		return;
		$List=$this->MakeList();
		$f=new AutoformPlugin("post");
		$f->Style="border:none; padding:10px;";
		//------place the list into the form-----
		$f->HasFormTag=false;
		$f->List=$List;
		return $f;
	}
}