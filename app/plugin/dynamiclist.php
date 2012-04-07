<?php 
/**
* developed for using in
* MetaDataArray possiblities for each header:
* ---array("Unique"=>true, "InputName"=>"string", "Clear"=>false{wether clear the input box after AddRow or nots}
* 			,"CustomValidation"=>"javascript code written here will be called in DList.Validation(), code can have ? mark for replacing seleced value of the input"
* 			,"Useless"=>{0 or 1}: whether send this header for the post or not, 0:send for post, 1: dont send for post)
* InputName is optional name for designing the post inputs
* @author dariush jafari (dariushvb2010@gmail.com)
*/
class DynamiclistPlugin extends AutolistPlugin
{
	/*
	 * $this->MetaData[]['Selector'] help:
	 * example: 
---- * textbox: "form :text[name=cotag][alt=hello]"
---- * selected select: "form select[name=ho] option:selected"
	 * selected radio: "form :radio:checked"
---- * textarea:  "textarea[name=ho]"
	 * 
	 */
	/**
	 * selector of the form
	 * @var string
	 */
	public $_Form="form.autoform";
	/**
	 * selector for main table
	 * @var string
	 */
	public $_List=".autolist tbody";
	/**
	 * string for selecting the Add button
	 * @var string
	 */
	public $_Button="form button";
	/**
	 * selector for checkboxes in the list
	 * @var string
	 */
	public $_Select="select[name=selectname] option:selected";
	
	/**
	 * <p>
	 * name of the header that is related to a textbox
	 * and you want to add a new row in the list by pressing enter key on that textbox.
	 * the value of this parameter must be in HeaderArray keys
	 * </p>
	 * @var string
	 */
	public $EnterTextName;
	/**
	 * if you want to show a text above the list whenever a row is added, you can use this
	 * any ? mark will be replaced with the headernames in this array
	 * @example
	 * $DynamicList->Notifier_Add=array("? added","myHeaderName");// result: myHeaderValue added
	 * @var array|string
	 */
	public $Notifier_Add;
	/**
	 * if you want to show a text above the list whenever a row is removed, you can use this
	 * @see $this->Notifier_Add
	 * @var array|string
	 */
	public $Notifier_Remove;
	/**
	 * when a row is removed a hidden input is created like this one:
	 * <input type='hidden' name='Dynamiclist_RemoveCalled_yolysw' vlaue='yes'/>
	 * this input is created only one time
	 */
	private function RemoveCalledInputName()
	{
		return 'Dynamiclist_RemoveCalled';
	}
	/**
	 * if user remove at least one row of the list, RemoveCalled will be true
	 * @return boolean
	 */
	function RemoveCalled(){
		if($_POST[$this->RemoveCalledInputName()]=='yes')
			$r=true;
		else
			$r=false;
		return $r; 
	}
	protected function Notifier_Add()
	{
		$NA=$this->Notifier_Add;
		if(is_string($NA))
		{
			echo "'<span>".$NA."</span>'";
			return;
		}
		if(count($NA))
		{
			$arr=explode("?", $NA[0]);
			$n=count($arr);
			$i=1;
			$j=1;
			echo "'<span>".$arr[0]." '";
		
			for($i=1; $i<$n; $i++,$j++)
			{
				$Type=$this->MetaData[$NA[$j]]['Type'];
				$Selector=$this->MetaData[$NA[$j]]['Selector'];
				if($Type=='Text')
					$str= "+$('".$Selector."').val()";
				else if($Type=='Select')
					$str= "+$('".$Selector."').attr('value')";
				else if($Type=='Textarea')
					$str= "+$('".$Selector."').val()";
				echo $str;
				echo "+ '".$arr[$i]."' ";
			}
			echo "+'".$arr[$i]."</span>' ";
		}
	}
	/**
	 * 
	 * prefix for naming the hidden inputs of the list
	 * @var string
	 */
	public $NamePrefix="dyn_";
	/**
	 * when you remove a row, a hidden input is created for sending the removed element
	 * @var string
	 */
	public $RemoveNamePrefix="remove_";
	/**
	 * $Selector help:
	 * example: 
---- * textbox: "form :text[name=cotag][alt=hello]"
---- * selected select: "form select[name=ho] option:selected"
	 * selected radio: "form :radio:checked"
---- * textarea:  "textarea[name=ho]"
	 * (non-PHPdoc)
	 * @see AutolistPlugin::SetHeader()
	 * @var Type: like text textarea select
	 */
	public function SetHeader($HeaderName, $HeaderLabel,$Selector, $Type="Text", $MetaDataArray=array())
	{
		parent::SetHeader($HeaderName, $HeaderLabel);
		$this->MetaData[$HeaderName]["Selector"]=$Selector;
		$this->MetaData[$HeaderName]["Type"]=$Type;
		foreach($MetaDataArray as $k=>$v)
		{
			$this->MetaData[$HeaderName][$k]=$v;
		}
	}
	/**
	 * 
	 * make a 2D array of Request(post,get) sent by its own
	 * <p>this is text
	 * and this is a test
	 * </p>
	 * if you have headers Cotag, ID, Comment; the output will be in format below:
	 * <p>
	 * array(2) {
		  [0]=>
		  array(3) {
		    ["Cotag"]=>
		    string(2) "60621"
		    ["ID"]=>
		    string(2) "45"
		    ["Comment"]=>
		    string(2) "a test comment"
		  }
		  [1]=>
		  array(3) {
		    ["Cotag"]=>
		    string(2) "60599"
		    ["ID"]=>
		    string(2) "45"
		    ["Comment"]=>
		    string(2) "this another comment"
		  }
		}
		</p>
	 *@return 2D_array|1D_array 2D_array whenever $HeaderName='all'(just like example above), 1D_array whenever $HeaderName!='all'
	 */
	public function GetRequest($HeaderName='all')
	{
		$res=array();
		if(isset($_POST))
		{
			if($HeaderName=='all')//all of headers 
			{
				foreach($this->HeaderArray as $name=>$label)
				if(!$this->MetaData[$name]['Useless'])
				{
					$fin=$this->FullInputName($name);
					$vars=$_POST[$fin];
					if(is_array($vars))
					{
						$i=0;
						foreach ($vars as $v)
						{
							$res[$i][$name]=$v;
							$i++;
						}
					}
				}
			}
			else//-----------only one header---
			{
				$fin=$this->FullInputName($HeaderName);
				$vars=$_POST[$fin];
				if(is_array($vars))
				foreach ($vars as $v)
					$res[]=$v;
			}
			
		}
		return $res;
	}
	/**
	 * 
	 * returns the name for each Data for designing form
	 * @param string $name the name of the one of .. of HeaderArray
	 */
	protected function FullInputName($name)
	{
		$InputName=$this->MetaData[$name]['InputName'];
		if(isset($InputName))
			return $this->NamePrefix.$InputName;
		else 
			return $this->NamePrefix.$name;
	}
	public function __construct($Data=null)
	{
		parent::__construct($Data);
		$this->HasRemove=true;
	}
	protected function EchoCustomValidation($name, $i)
	{
		$CV=$this->MetaData[$name]['CustomValidation'];
		if($CV)
		{
			$arr=explode("?", $CV);
			$n=count($arr);
			
			if($n>1)
			{
				echo $arr[0];
				echo "DList_".$this->ID.".IPT[".$i."]";
				echo $arr[1];
			}
			else 
				echo $CV;
		}
	}
	protected function EchoRecordTD($k, $Value, $IfEchoValue)
	{
	?>
		<td header="<?php echo $k;?>" 
		<?php echo $this->EditManager->MetaData[$k]['CanEdit'] ? "canedit='yes'" : "canedit='no'"; ?>
		style='<?php echo $this->MetaData[$k]['Style'];?>'
		>
		<span class="data"><?php if($IfEchoValue) echo ($Value===null || $Value==="") ? "-" : $Value;?></span>
		<?php if(!$this->MetaData[$k]['Useless']): if($IfEchoValue):?>
		<input type="hidden" value="<?php echo $Value;?>" name="<?php echo $this->NamePrefix.$k?>[]"/>
		<?php endif; endif;?>
		</td>
	<?php
	}
	public function Present()
	{
		echo "<div class='autolist_notifier_".$this->ID."' metadata='autolist_notifier' style='font-size:14px; height:25px; overflow:hidden;'></div>";
		parent::Present();
	}
	public function PresentScript()
	{
		parent::PresentScript();
		?>
		 
		var DList_<?php echo $this->ID;?>;
		DList_<?php echo $this->ID;?> = {
	    	<?php 
	    	$this->jDListDeclaration(); ?>
	    	AddRow: function(){<?php $this->jAddRow(); ?>},
	    	Init: function(){<?php $this->jInit(); ?>},
	    	InitTier: function(){<?php $this->jInitTier(); ?>},
	    	GetData: function(){<?php $this->jGetData(); ?>},
	    	Remove: function(remtd){<?php $this->jRemove(); ?>},
	    	UpdateNotifier: function(){<?php $this->jUpdataNotifier(); ?>},
	    	ClearNotifier: function(){<?php $this->jClearNotifier(); ?>},
	    	Validation: function(){<?php $this->jValidation(); ?>},
	    	WriteRecord: function(row){<?php $this->jWriteRecord(); ?>},
	    	<?php $this->jCustom();?>
		    end: null
		}; //end of DList_<?php echo $this->ID;?>
			
		DList_<?php echo $this->ID;?>.Init();
		$("<?php echo $this->_Button;?>").click(function(){
			DList_<?php echo $this->ID;?>.AddRow();
		});
		$("<?php echo $this->MetaData[$this->EnterTextName]['Selector'];?>").keydown(function(event){
		  if(event.keyCode==13)
			  DList_<?php echo $this->ID;?>.AddRow();
		});
		var rem=$(DList_<?php echo $this->ID;?>._List + " tr td.remove a");
		
		<?php 
	}//PresentScript
	protected function jDListDeclaration()
	{ ?>
		Timer:null,
		NotifierDiv: $("div.autolist_notifier_<?php echo $this->ID;?>[metadata=autolist_notifier]"),
		HiddenRemoveDiv: $("div#RemoveContainer_<?php echo $this->ID;?>.HiddenContainer"),
		_List:"<?php echo $this->_List?>",
		List: $("<?php echo $this->_List; ?>"),
		ID: "<?php echo $this->ID;?>",
		Tier: null,
		HeaderCount:<?php echo count($this->HeaderArray);?>,
	    /* example: for a textbox, IPT and IPV are equal. but for a select tag are not equal(<option value='IPV'>IPT</option>) */
	    HAN:[], //HeaderArrayName
	    IPT:[], //InPut Text: got from the form
	    IPV:[], //InPut Values for input hidden for making the dynamic form: got from the form
	    MDS:[], //MetaData Selectors: got from the MetaData
	    MDT:[], //MetaDataType : got from the MetaData
	    MDU:[], //MetaDataUseless(boolean): whether the header be sent with the post or not
	    _Select:null,
	    HasTier:false,
	    HasSelect:false,
	    HasRemove:true,
	    Trim: function(str)
		{
		        return str.replace(/^\s+|\s+$/g,"");
		},
		TrimZero: function(str)
		{
			return str.replace(/^0+/, '');
		},
	<?php 		
	}
	protected function jAddRow()
	{ ?>
		DList_<?php echo $this->ID;?>.GetData();
	    if(!DList_<?php echo $this->ID;?>.Validation())
	    	return;
        DList_<?php echo $this->ID;?>.List.append("<tr align='center'></tr>");
        var row = $(DList_<?php echo $this->ID;?>._List + " tr:last");
        DList_<?php echo $this->ID;?>.Tier++;
        if(DList_<?php echo $this->ID;?>.HasTier)
        	row.append("<td class='tier'>"+DList_<?php echo $this->ID;?>.Tier+"</td>");
        if(DList_<?php echo $this->ID;?>.HasSelect)
        {
	        val=$(DList_<?php echo $this->ID;?>._Select).attr("value");
	        row.append("<td ><input type='checkbox' name='<?php echo $this->SelectName;?>[]' value='"+ val +"' class='<?php echo $this->SelectClass; ?>' /></td>");
		}	
		DList_<?php echo $this->ID;?>.UpdateNotifier();
        DList_<?php echo $this->ID;?>.WriteRecord(row);
        if(DList_<?php echo $this->ID;?>.HasRemove)
        	row.append("<?php $this->EchoRemoveTD();?>");
	<?php 			    
	}
	protected function jInit()
	{ ?>
		DList_<?php echo $this->ID;?>._Select="<?php echo $this->_Select;?>";
    	DList_<?php echo $this->ID;?>._List="<?php echo $this->_List; ?>";
    	DList_<?php echo $this->ID;?>.HasTier=<?php echo $this->HasTier ? 1:0;?>;
    	DList_<?php echo $this->ID;?>.HasSelect=<?php echo $this->HasSelect ? 1:0;?>;
    	DList_<?php echo $this->ID;?>.HasRemove=<?php echo $this->HasRemove ? 1:0;?>;
    		
	    DList_<?php echo $this->ID;?>.MDS=[];
    	DList_<?php echo $this->ID;?>.MDT=[];
    	DList_<?php echo $this->ID;?>.MDU=[];
    	DList_<?php echo $this->ID;?>.HAN=[];
    	var i=0;
    <?php 
    	foreach ($this->HeaderArray as $name=>$label):?>
    		DList_<?php echo $this->ID;?>.HAN[i]="<?php echo $name;?>";
    		DList_<?php echo $this->ID;?>.MDS[i]="<?php echo $this->MetaData[$name]['Selector'];?>";
    		DList_<?php echo $this->ID;?>.MDT[i]="<?php echo $this->MetaData[$name]['Type']; ?>";
    		DList_<?php echo $this->ID;?>.MDU[i]=<?php echo $this->MetaData[$name]['Useless'] ? 1:0;?>;
    		i++;
    	<?php
    	endforeach;?>
    	DList_<?php echo $this->ID;?>.InitTier();
	<?php 
	}
	protected function jInitTier()
	{ ?>
		if(!DList_<?php echo $this->ID;?>.Tier)
	    {
            if(DList_<?php echo $this->ID;?>.List)
            if(DList_<?php echo $this->ID;?>.List.children("tr:last"))
            if(DList_<?php echo $this->ID;?>.List.children("tr:last").children("td.tier"))
                DList_<?php echo $this->ID;?>.Tier=DList_<?php echo $this->ID;?>.List.children("tr:last").children("td.tier").html()*1;
            else DList_<?php echo $this->ID;?>.Tier=0;
            else DList_<?php echo $this->ID;?>.Tier = 0;
            else DList_<?php echo $this->ID;?>.Tier = 0;
	    }
	<?php 		
	}
	protected function jGetData()
	{ ?>
		var x;
		var selector;
		DList_<?php echo $this->ID;?>.IPT=[];
    	DList_<?php echo $this->ID;?>.IPV=[];
    	var i=0;
        for(i; i< DList_<?php echo $this->ID;?>.HAN.length; i++)
        {
         	selector=DList_<?php echo $this->ID;?>.MDS[i];
        	if(DList_<?php echo $this->ID;?>.MDT[i]=="Text")
        	{
        		x=$(selector).val();
        		//---TODO ----these two line is depriated 
      			x=DList_<?php echo $this->ID;?>.TrimZero(x);
      			x=DList_<?php echo $this->ID;?>.Trim(x);
      			//---------------------------------------
        		if(x==undefined) x="-";
        		DList_<?php echo $this->ID;?>.IPT.push(x);
        		DList_<?php echo $this->ID;?>.IPV.push(x);
        	}
        	else if(DList_<?php echo $this->ID;?>.MDT[i]=="Select")
        	{
        		x=$(selector).text();
        		DList_<?php echo $this->ID;?>.IPT.push(x);
        	   	y=$(selector).attr("value");
        		if(y==undefined) y="-";
        	   	DList_<?php echo $this->ID;?>.IPV.push(y);
        	}
        	else if(DList_<?php echo $this->ID;?>.MDT[i]=="Textarea")
        	{
        		x=$(selector).val();
        		if(x==undefined) x="-";
        		DList_<?php echo $this->ID;?>.IPT.push(x);
        		DList_<?php echo $this->ID;?>.IPV.push(x);
			}
			else
			{
				x=$(selector).val();
        		if(x==undefined) x="-";
        		DList_<?php echo $this->ID;?>.IPT.push(x);
        		DList_<?php echo $this->ID;?>.IPV.push(x);
			}
        }
	<?php 
	}
	protected function jRemove()
	{ ?>
		if(DList_<?php echo $this->ID;?>.HiddenRemoveDiv.children(":hidden[name=<?php echo $this->RemoveCalledInputName();?>]")!=undefined)
			DList_<?php echo $this->ID;?>.HiddenRemoveDiv.append("<input type='hidden' name='<?php echo $this->RemoveCalledInputName();?>' value='yes' />");
		tr=remtd.parent("tr");
    	$.each(tr.nextAll(DList_<?php echo $this->ID;?>._List + " tr"), function(i,n){
    		t=$(this).children("td.tier").text();
    		t=t*1-1;
    		$(this).children("td.tier").text(t);
    	});
    	tr.remove();
    	DList_<?php echo $this->ID;?>.Tier--;
	<?php 
	}
	protected function jClearNotifier()
	{ ?>
		DList_<?php echo $this->ID;?>.NotifierDiv.html("");
	<?php 
	}
	protected function jUpdataNotifier()
	{ ?>
		DList_<?php echo $this->ID;?>.ClearNotifier();
		DList_<?php echo $this->ID;?>.NotifierDiv.css("color","green");
        DList_<?php echo $this->ID;?>.NotifierDiv.append(<?php $this->Notifier_Add();?>);
        //DList_<?php echo $this->ID;?>.Timer=setTimeout(DList_<?php echo $this->ID;?>.ClearNotifier,2500);
	<?php 
	}
	protected function jValidation()
	{ ?>
		var forReturn=1;
		<?php
		$i=0;
		foreach($this->HeaderArray as $name=>$label):
		//------------------------Custom Javascript Code----------------
		$this->EchoCustomValidation($name, $i);
		//-------------------------C J C--------------------------------
		if($this->MetaData[$name]['Unique']===true):
		?>
    		otherTDs=$(DList_<?php echo $this->ID;?>._List +" tr td[header=<?php echo $name;?>]");
    		$.each(otherTDs, function(i,n){
    			td=$(this);
    			tdData=td.children("span.data").text();
    			if(tdData==DList_<?php echo $this->ID;?>.IPT[<?php echo $i;?>])
    			{
    				str="<?php echo $label;?>";
    				str+="تکراری است";
    				alert(str);
    				forReturn=0;
    			}
    		});
    		<?php
    	endif; 
    	$i++;
    	endforeach; 
    	?>
    	return forReturn;
	<?php 
	}
	protected function jWriteRecord()
	{ ?>
		var i=0;
		<?php
		foreach($this->HeaderArray as $name=>$label):
		?> selector=DList_<?php echo $this->ID;?>.MDS[i];<?php
	        $clear=$this->MetaData[$name]['Clear'];
        	if($clear):
        		?> $(selector).val(""); <?php
        	endif;?>
        	var str="<td header='<?php echo $name;?>' >";
        	str+="<span class='data'>" + DList_<?php echo $this->ID;?>.IPT[i] + "</span>";
        	if(!DList_<?php echo $this->ID;?>.MDU[i])
        	{
	        	str+="<input type='hidden' name='<?php echo $this->FullInputName($name);?>[]' value='";
	        	str+=DList_<?php echo $this->ID;?>.IPV[i];
	        	str+="' />";
        	}
        	str+="</td>";
            row.append(str);
            i++;
            <?php 
        endforeach;
	}
	protected function jCustom()
	{
		
	}
}