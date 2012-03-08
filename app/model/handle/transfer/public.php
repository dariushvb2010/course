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
		parent::Perform();
		$this->MakeCreateForm();
		if(isset($_POST['Create']))
		{
			$Num=$_POST['MailNum'];
			$Title=$_POST['Title'];
			$Comment=$_POST['Comment'];
			$Mail=ORM::Query("Mail".$this->Action)->Add($Num, $Title, $this->Transferor, $this->Catcher, $Comment);
			if(is_string($Mail))
				$this->Error[]=$Mail;
			else 
			{
				$this->Result.="نامه با شماره ".$Mail->Num()." با موفقیت ایجاد شد.";
				ORM::Flush();//i want to use the ID of the created Mail thus i have to Flush()!
				$this->Mail=$Mail;
			}
		}
		else
		{
			if(!$this->TransferorGroup OR !$this->CatcherGroup)
			$this->Error[]="گروه یافت نشد.";
			$this->Mail=ORM::Query("Mail".$this->Action)->LastMail($this->TransferorGroup, $this->CatcherGroup);
		}
		
		$this->MakeMainForm();
	}
	/**
	 * a form for creating a new mail
	 */
	private function MakeCreateForm()
	{
		$f=new AutoformPlugin("post");
		$f->AddElement(array("Type"=>"text","Name"=>"MailNum","Label"=>"شماره نامه"));
		$f->AddElement(array( "Type"=>"text", "Name"=>"Title","Label"=>"عنوان نامه"));
		$f->AddElement(array("Type"=>"textarea","Name"=>"Comment","Label"=>"توضیحات"));
		$f->AddElement(array("Type"=>"submit","Name"=>"Create","Value"=>"ایجاد نامه"));
		$this->CreateForm=$f;
	}
	function __construct($Action, $Transferor, $Catcher)
	{
		parent::__construct($Action, $Transferor, $Catcher);
	}
}