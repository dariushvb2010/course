<?php
class CorrespondenceDemandController extends JControl

{
	function Start()
	{

		j::Enforce("Correspondence");
		if (count($_REQUEST))
		{
			$Cotag=$_REQUEST['Cotag']*1;
			$File=ORM::Query(new ReviewFile)->GetRecentFile($Cotag);
			if($File)
			{ 
				$types=$this->get_input_class('string');
				
				if(isset($_POST['submit']))
				{			
					$Res=$this->all_addfile($File);
					if(isset($Res['Error'])){
						$Error[]=$Res['Error'];
					}else{
						$this->Redirect("./?Cotag={$File->Cotag()}&success={$types}");
					}				
				}
			}else{
				$Error[]='کوتاژی با شماره ی'.$Cotag.'موجود نیست.';
			}
			$this->Cotag=$Cotag;
			$this->input_class=$this->get_input_class();
		}
		
		$this->Error=$Error;
		if (count($Error)) $this->Result=false;
		$this->makeForm();
		return $this->Present();
	}
	
	function all_addfile(ReviewFile $File){
		$imglist=(isset($_POST['imglist'])?$_POST['imglist']:null);
		$Indicator=(isset($_POST['Indicator'])?$_POST['Indicator']:null);
		$Comment=(isset($_POST['Comment'])?$_POST['Comment']:null);
		$types=$this->get_input_class('array');

		if(!FsmGraph::IsPossible($File->State(),$this->get_input_class('string'))){
			$Res['Error']='این عملیات روی پرونده قابل قبول نیست.';
			return $Res;
		}
		if($imglist=='' OR $imglist==null)
		{
			$Res['Error']="هیچ فایل عکسی  بارگذاری نشده است.";
			return $Res;
		}
		
		$params=array();
		foreach ($_POST as $key=>$value){
			if (substr($key, 0,3)=="%%%"){
				$params[substr($key, 3,strlen($key)-3 )]=$value;
			}
		}
		switch ($types[0]){
			case 'Senddemand':
				$Res=ORM::Query("ReviewProcessSenddemand")->AddToFile($File,$types[1],$Indicator,$Comment);
				break;
			case 'Protest':
				$req_num=$_POST['request'];
				if($req_num>=0 AND $req_num<4){
					$ar=array('karshenas','setad','commission','appeals');
					$Res=ORM::Query("ReviewProcessProtest")->AddToFile($File,$ar[$req_num],$Indicator,$Comment);
				}else{
					$Res['Error'][]='خطا در ورودی اطلاعات.';
				}
				break;
			case 'Prophecy':
				$Res=ORM::Query("ReviewProcessProphecy")->AddToFile($File,$types[1],$Indicator,$Comment);
				break;
		}
		if(!isset($Res['Error'])){
			$Imgs=explode(',', substr($imglist,1));
			foreach ($Imgs as $k=>$img)
			{
				ORM::Dump($Res);
				var_dump($Res['Class']->ID());
				
				rename(b::upload_folder_relative_from_japp().$img,b::upload_folder_relative_from_japp().$Res['Class']->ID().'_'.$k.".jpg" );
				ReviewImages::Add($Res['Class'], $Res['Class']->ID().'_'.$k);
			}
		}
		return $Res;
	}
	
	function makeForm()
	{
		$types=$this->get_input_class('array');
		$f=new AutoformPlugin("post");
		$f=$this->get_all_params($f);
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"Cotagshow",
				"Disabled"=>true,
				"Label"=>"کوتاژ",
				"Value"=>$this->Cotag,
		));
		$f->AddElement(array(
				"Type"=>"hidden",
				"Name"=>"Cotag",
				"Value"=>$this->Cotag,
		));
		$f->AddElement(array(
				"Type"=>"hidden",
				"Name"=>"input_class",
				"Value"=>$this->get_input_class(),
		));
		$f->AddElement(array(
				"Type"=>"text",
				"Name"=>"Indicator",
				"Label"=>"اندیکاتور",
				"Validation"=>"numeric"
		));
		$f->AddElement(array(
				"Type"=>"textarea",
				"Name"=>"Comment",
				"Label"=>"توضیحات",
		));
		$f->AddElement(array(
				"Type"=>"custom",
				"Label"=>"تاریخ یادآوری بعدی",
				"HTML"=>'<span id="cal">
						<img id="date_btn" src="/script/calendar/cal.png" style="vertical-align: top;" />
						<input type="text" id="data" name="data" />
						</span>',
		));
		$f->AddElement(array(
				"Type"=>"hidden",
				"Name"=>"imglist",
				"ID"=>"imglist",
				"Value"=>'',
				"Validation"=>"*"
		));
		$f->AddElement(array(
				"Type"=>"custom",
				"Label"=>"تصویر اتوماسیون",
				"HTML"=>'<div id="file-uploader">       
						    <noscript>          
						        <p>Please enable JavaScript to use file uploader.</p>
						    </noscript>
						</div>',
		));
		$f->AddElement(array(
				"Type"=>"submit",
				"Name"=>"submit",
				"Value"=>"ثبت",
		));
		$this->Form=$f;
	}
	
	function get_input_class($mode='string'){
		$inputclass=$_REQUEST['input_class'];
		if($mode=='string')
			return $inputclass;
		elseif($mode=='array')
			return explode('_',$inputclass);
		
	}
	
	function get_all_params($f){
		$types=$this->get_input_class('array');
		switch ($types[0]){
			case 'Refund':
				$f->AddElement(array(
					"Type"=>"text",
					"Name"=>"value",
					'Label'=>'مبلغ',
				));
				break;
			case 'Judgement':
				$f->AddElement(array(
					"Type"=>"select",
					"Name"=>"result",
					'Label'=>"نتیجه",
					"Options"=>array("0"=>"قبول اعتراض",
									"1"=>"رد اعتراض"),
					"Default"=>"1",
				));
				break;
			case 'Forward':
				$f->AddElement(array(
					"Type"=>"select",
					"Name"=>'office',
					'Label'=>'مقصد',
					"Options"=>array("0"=>"دفاتر ستادی",
									"1"=>"کمیسیون",
									"2"=>"کمیسیون تجدید نظر"),
				));
				break;
			case 'Feedback':
				$f->AddElement(array(
					"Type"=>"select",
					"Name"=>'office',
					'Label'=>'بخش مورد نظر',
					"Options"=>array("0"=>"دفاتر ستادی",
									"1"=>"کمیسیون",
									"2"=>"کمیسیون تجدید نظر"),
				));
				$f->AddElement(array(
					"Type"=>"select",
					"Name"=>'result',
					'Label'=>'رای',
					"Options"=>array("0"=>"به نفع گمرک",
									"1"=>"به نفع صاحب کالا"),
				));
				break;
			case 'Processconfirm':
				$f->AddElement(array(
					"Type"=>"select",
					"Name"=>'result',
					'Label'=>'نظر مدیر',
					"Options"=>array("0"=>"تایید مدیر",
									"1"=>"عدم تایید مدیر"),
				));
				break;
			case 'Payment':
				$f->AddElement(array(
					"Type"=>"text",
					"Name"=>'value',
					'Label'=>'مبلغ',
				));
				break;
			case 'Protest':
				$f->AddElement(array(
					"Type"=>"select",
					"Name"=>'request',
					'Label'=>'مرجع درخواستی',
					"Options"=>array("0"=>"کارشناس مربوطه",
									"1"=>"دفاتر ستادی",
									"2"=>"کمیسیون",
									"3"=>"کمیسیون تجدید نظر"),
				));
				break;
		}
		return $f;
	}
	
}