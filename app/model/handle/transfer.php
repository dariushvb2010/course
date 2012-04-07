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
	protected  $Action;
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
				$str="دریافت از ";
				$str.=$this->PersianSource();
				return $str;
		}
	}
	/**
	 * Giver or Sender 
	 * @var string
	 */
	protected $Source;
	function Source(){ return $this->Source; }
	protected function PersianSource()
	{
		if($this->Action=="Send")
			return $this->Topic()->Topic();
		elseif($this->Action=="Receive")
			return $this->Topic()->Topic();
		else
			return ConfigData::$GROUPS[$this->Source];
	}
	/**
	 * Gitter or Receiver
	 * @var stirng
	 */
	protected $Dest;
	function Dest(){ return $this->Dest; }
	protected function PersianDest()
	{
		if($this->Action=="Send")
			return $this->Topic()->Topic();
		elseif($this->Action=="Receive")
			return $this->Topic()->Topic();
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
	protected function Topic()
	{
		if($this->Action=="Send")
		if($this->Mail instanceof MailSend)
			return $this->Mail->ReceiverTopic();
		if($this->Action=="Receive")
		if($this->Mail instanceof MailReceive)
			return $this->Mail->SenderTopic();
		return null;
	}
	public $TopicType;
	public $Error=array();
	
	function __construct( $Action="Give", $Source, $Dest)
	{
		$this->Source=$Source;
		$this->Dest=$Dest;
		$this->Action=$Action;
		if($Action=="Receive")
		{
			$this->SourceGroup=null;
			if(isset($Source))
				$this->TopicType=$Source;
			elseif($this->Mail)
				$this->TopicType=$this->Mail->SenderTopic()->Type();
		}
		else// Action =Get or Give or Send
			$this->SourceGroup=ORM::Find1("MyGroup", "Title",$Source);
		if($Action == "Send")
		{
			$this->DestGroup=null;
			if(isset($Dest))
				$this->TopicType=$Dest;
			elseif($this->Mail)
				$this->TopicType=$this->Mail->ReceiverTopic()->Type();
		}
		else 
		{//Action =Get or Give or Receive
			$this->DestGroup=ORM::Find1("MyGroup","Title", $Dest);
		}
	}
	
	protected function MakeList()
	{
		if($this->Mail instanceof MailGive)
		return $this->MakeListForMailGive();
		else
		return $this->MakeListForMailSendANDReceive();
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
				$f->AddElement(array("Type"=>"submit", "Name"=>"Complete", "Value"=>"کامل کردن"));
				$f->AddElement(array("Type"=>"hidden", "Name"=>"MailID", "Value"=>$this->Mail->ID()));
				
				$al->SetHeader("Error", "وضعیت", "","",array("Useless"=>true,"Style"=>"color:red;"));
				$al->RemoveLabel="حذف";
				$al->HasFormTag=true;
				$al->_List=".autoform .autolist tbody";
				$al->_Button="div.autoform button";
				$al->EnterTextName="Cotag";
				$al->Notifier_Add=array("? اضافه شد","Cotag");
				//--------al custom javascript code for validating the format of the Cotag-------------
				$al->AutoformAfter=true;
			}
			else 
			{
				$al->InputValues['ColsCount']=5;
				$al->InputValues['RowsCount']=25;
				$al->HasRemove=false;
			}
		}
		elseif($this->Action="Get")
		{
			$al->HasRemove=false;
			if($this->Mail->State()==Mail::STATE_GETTING OR $this->Mail->State()==Mail::STATE_INWAY)
			{
				$f->AddElement(array("Type"=>"submit", "Name"=>"Save", "Value"=>"ذخیره"));
				$f->AddElement(array("Type"=>"submit", "Name"=>$this->Action, "Value"=>$this->PersianAction()));
				$f->AddElement(array("Type"=>"submit", "Name"=>"Complete", "Value"=>"کامل کردن"));
				$f->AddElement(array("Type"=>"hidden", "Name"=>"MailID", "Value"=>$this->Mail->ID()));
				
				$al->AutoformAfter=true;
				$al->SetHeader("Select","انتخاب", true, false, false);
				$al->SetFilter(array($this, "Filter"));
				$al->SetHeader("Error", "وضعیت", "","",array("Useless"=>true,"Style"=>"color:red;"));
				$al->HasFormTag=true;
				
			}
			else
			{
				$al->InputValues['ColsCount']=5;
				$al->InputValues['RowsCount']=25;
				$al->HasRemove=false;
			}
		}
		$f->Style="border:none;";
		$al->Autoform=$f;
		return $al;
	}
	function Filter($k, $v, $D)
	{
		if($k=="Select")
		{
			if($D->File()->Stock())
			if($D->File()->Stock()->IfSaveGet())
			{
				$s="checked='checked'";
			}
			return "<input type='checkbox' name='Cotag[]' value='".$D->Cotag()."' class='item' {$s}/>";
		}
		else 
			return $v;
	}
	private function MakeListForMailSendANDReceive()
	{
		$al=$this->MakeListTemplate();
		$f=new AutoformPlugin();
		$f->HasFormTag=false;
		if($this->Mail->State()==Mail::STATE_EDITING )
		{
			$f->AddElement(array("Type"=>"submit", "Name"=>"Save", "Value"=>"ذخیره"));
			$f->AddElement(array("Type"=>"submit", "Value"=>$this->PersianAction(), "Name"=>$this->Action));
			$f->AddElement(array("Type"=>"submit", "Name"=>"Complete", "Value"=>"کامل کردن"));
			$f->AddElement(array("Type"=>"hidden", "Name"=>"MailID", "Value"=>$this->Mail->ID()));
			
			$al->SetHeader("Error", "وضعیت", "","",array("Useless"=>true,"Style"=>"color:red;"));
			
			$al->RemoveLabel="حذف";
			$al->HasFormTag=true;
			$al->_List=".autoform .autolist tbody";
			$al->_Button="div.autoform button";
			$al->EnterTextName="Cotag";
			$al->Notifier_Add=array("? اضافه شد","Cotag");
			//--------al custom javascript code for validating the format of the Cotag-------------
			$al->AutoformAfter=true;
		}
		else
		{
			$al->HasRemove=false;
			$al->InputValues['ColsCount']=5;
			$al->InputValues['RowsCount']='auto';
		}
		$al->Autoform=$f;
		return $al;
	}
	protected function MakeMainForm()
	{
		$f=$this->MakeMainFormTemplate();
		if($this->Mail instanceof MailGive)
		{
			if($this->Action=="Give")
			{
				if($this->Mail->State()==Mail::STATE_EDITING)
				{
					$f->AddElement(array("Type"=>"text", "Name"=>"Cotag", "Label"=>"کوتاژ"));
					$f->AddElement(array("Type"=>"button","Value"=>"اضافه"));					
				}
			}
			else if($this->Action=="Get")
			{
				if($this->Mail->State()==Mail::STATE_GETTING OR $this->Mail->State()==Mail::STATE_INWAY)
				{
					$f->AddElement(array("Type"=>"text", "Name"=>"SelectCotag", "Label"=>"کوتاژ"));
					$f->AddElement(array("Type"=>"button","Name"=>"Select", "Value"=>"انتخاب"));
					$f->AddElement(array("Type"=>"custom", "HTML"=>"<input id='selectall' type='checkbox'/> انتخاب همه"));
				}
			}
		}
		elseif($this->Mail instanceof MailSend OR $this->Mail instanceof MailReceive)
		{
			if($this->Mail->State()==mail::STATE_EDITING)
			{
				$f->AddElement(array("Type"=>"text", "Name"=>"Cotag", "Label"=>"کوتاژ"));
				$f->AddElement(array("Type"=>"button","Value"=>"اضافه"));
			}
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
		$f->List_Present_Func="Present";
		if($this->Mail->State()==Mail::STATE_CLOSED or ($this->Mail->State()==Mail::STATE_INWAY and $this->Action=="Give"))
			$f->List_Present_Func="PresentForPrint";
		$f->Style="border:none; padding:10px;";
		//------place the list into the form-----
		$f->HasFormTag=false;
		$f->List=$List;
		return $f;
	}
	protected function MakeListTemplate()
	{
		if($this->Mail->State()==Mail::STATE_INWAY)
			$Data=$this->Mail->GetProgress();
		else 	
			$Data=$this->Mail->Box();
		if($this->Action=="Get" or $this->Mail->State()==Mail::STATE_CLOSED)
			$al=new AutolistPlugin($Data);
		else
			$al=new DynamiclistPlugin($Data);
		$al->ObjectAccess=true;
		$al->HasTier=true;
		$al->TierLabel="ردیف";
		$CotagCode="var patt=/^0{0,2}\d{".b::$CotagLength."}$/;";  //javascript code
		$CotagCode.="if(patt.test(?)); else {alert('فرمت کوتاژ رعایت نشده است.'); return false;}";
		$CotagMetaData=array("Unique"=>true,"Clear"=>true, "CustomValidation"=>$CotagCode);
		$al->SetHeader("Cotag", "کوتاژ", "div.autoform :text[name=Cotag]","Text",$CotagMetaData);
		
		
		return $al;
	}
	protected function MakeSearchForm()
	{
		$State=Mail::$PersianState;
		$State['all']="همه";
		if($this->TopicType)
			$Topics=ORM::Query("ReviewTopic")->GetTopics($this->TopicType);
		else
			$Topics=ORM::Query("ReviewTopic")->GetTopics();
		$Ts['all']="همه";
		if($Topics)
		foreach ($Topics as $T)
			$Ts[$T['ID']]=$T['Topic'];
		$f=new AutoformPlugin("post");
		$f->AddElement(array("Type"=>"text", "Name"=>"Num", "Label"=>"شماره نامه", "Value"=>$_POST['Num']));
		$f->AddElement(array("Type"=>"text", "Name"=>"Subject", "Label"=>"عنوان نامه", "Value"=>$_POST['Subjet']));
		$f->AddElement(array("Type"=>"select", "Name"=>"State", "Label"=>"وضعیت نامه", "Options"=>$State, "Default"=>$_POST['State']));
		if($this->Action=="Send" or $this->Action=="Receive")
			$f->AddElement(array("Type"=>"select", "Name"=>"TopicID", "Label"=>"طرف مکاتبه", "Options"=>$Ts, "Default"=>$_POST['TopicID']));
				
		$f->AddElement(array("Type"=>"submit", "Name"=>"Search", "Value"=>"جستجوی نامه ها"));
		$this->SearchForm=$f;
	}
}