<?php 
/**
* developed for using in
* MetaDataArray possiblities for each header:
* ---array("Unique"=>true, "InputName"=>"string", "Clear"=>false{wether clear the input box after AddRow or nots}
* 			,"CustomValidation"=>"javascript code written here will be called in DList.Validation(), code can have ? mark for replacing seleced value of the input"
* 			, )
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
	 * string for form selector
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
	 * 
	 * whether has a column for removing the row
	 * @var boolean
	 */
	public $HasRemove=true;
	public $RemoveLabel="Remove";
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
	 * 
	 * prefix for naming the hidden inputs of the list
	 * @var string
	 */
	public $NamePrefix="dyn_";
	/**
	 * 
	 * for adding at the end of DList(javascript name of the list)
	 * @example
	 * 		DList_yikjkdslj (yikjkdslj is $this->ID)
	 * @var unknown_type
	 */
	public $ID;
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
	 */
	public function GetRequest()
	{
		$res=array();
		if(isset($_POST))
		foreach($this->HeaderArray as $name=>$label)
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
		return $res;
	}
	/**
	 * 
	 * returns the name for each Data for designing form
	 * @param string $name the name of the one of .. of HeaderArray
	 */
	private function FullInputName($name)
	{
		$InputName=$this->MetaData[$name]['InputName'];
		if(isset($InputName))
			return $this->NamePrefix.$InputName;
		else 
			return $this->NamePrefix.$name;
	}
	protected function EchoTHs()
	{
		parent::EchoTHs();
		if($this->HasRemove)
			echo "<th>".$this->RemoveLabel."</th>";
	}
	public function __construct()
	{
		parent::__construct();
		$this->ID=FPlugin::RandomString();
	}
	private function EchoCustomValidation($name, $i)
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
	public function PresentScript()
	{
		parent::PresentScript();
		?>
		
	var DList_<?php echo $this->ID;?>;
	DList_<?php echo $this->ID;?> = {
    	AddRow: function() {
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
		    DList_<?php echo $this->ID;?>.GetData();
		    if(!DList_<?php echo $this->ID;?>.Validation())
		    	return;
	        DList_<?php echo $this->ID;?>.List.append("<tr></tr>");
	        var row = $(DList_<?php echo $this->ID;?>._List + " tr:last");
	        DList_<?php echo $this->ID;?>.Tier++;
	        if(DList_<?php echo $this->ID;?>.HasTier)
	        	row.append("<td class='tier'>"+DList_<?php echo $this->ID;?>.Tier+"</td>");
	        if(DList_<?php echo $this->ID;?>.HasSelect)
	        {
		        val=$(DList_<?php echo $this->ID;?>._Select).attr("value");
		        row.append("<td ><input type='checkbox' name='<?php echo $this->SelectName;?>[]' value='"+ val +"' class='<?php echo $this->SelectClass; ?>' /></td>");
			}
	        DList_<?php echo $this->ID;?>.WriteRecord(row);
	        if(DList_<?php echo $this->ID;?>.HasRemove)
	        	row.append("<td class='remove' onclick='DList_<?php echo $this->ID;?>.Remove($(this));'><a><img style='height:100%; vertical-align:middle;' src='/img/delete-blue-20.png'/> </a> </td>");
	    },
	    _List:"<?php echo $this->_List?>",
	    List: $("<?php echo $this->_List; ?>"),
	    Tier: null,
	    HeaderCount:<?php echo count($this->HeaderArray);?>,
	    /* example: for a textbox, IPT and IPV are equal. but for a select tag are not equal(<option value='IPV'>IPT</option>) */
	    HAN:[], //HeaderArrayName
	    IPT:[], //InPut Text: got from the form
	    IPV:[], //InPut Values for input hidden for making the dynamic form: got from the form
	    MDS:[], //MetaData Selectors: got from the MetaData
	    MDT:[], //MetaDataType : got from the MetaData
	    _Select:null,
	    HasTier:false,
	    HasSelect:false,
	    HasRemove:true,
	    WriteRecord:function(row){
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
	        	str+="<input type='hidden' name='<?php echo $this->FullInputName($name);?>[]' value='";
	        	str+=DList_<?php echo $this->ID;?>.IPV[i];
	        	str+="' />";
	        	str+="</td>";
	            row.append(str);
	            i++;
	            <?php 
	        endforeach;
	        ?>
		},
	    GetData:function(){
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
	        		DList_<?php echo $this->ID;?>.IPT.push(x);
	        		DList_<?php echo $this->ID;?>.IPV.push(x);
	        	}
	        	 	
	        	else if(DList_<?php echo $this->ID;?>.MDT[i]=="Select")
	        	{
	        		x=$(selector).text();
	        		DList_<?php echo $this->ID;?>.IPT.push(x);
	        	   	y=$(selector).attr("value");
	        	   	DList_<?php echo $this->ID;?>.IPV.push(y);
	        	}
	        	else if(DList_<?php echo $this->ID;?>.MDT[i]=="Textarea")
	        	{
	        		x=$(selector).val();
	        		DList_<?php echo $this->ID;?>.IPT.push(x);
	        		DList_<?php echo $this->ID;?>.IPV.push(x);
				}
	        }
	    },
	    Validation:function()
	    {
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
	    		?>
	    	<?php
	    		$i++;
	    	endforeach; 
	    	?>
	    	return forReturn;
	    },
	    Init:function()
	    {
    		DList_<?php echo $this->ID;?>._Select="<?php echo $this->_Select;?>";
    		DList_<?php echo $this->ID;?>._List="<?php echo $this->_List; ?>";
    		DList_<?php echo $this->ID;?>.HasTier=<?php echo $this->HasTier ? 1:0;?>;
    		DList_<?php echo $this->ID;?>.HasSelect=<?php echo $this->HasSelect ? 1:0;?>;
    		DList_<?php echo $this->ID;?>.HasRemove=<?php echo $this->HasRemove ? 1:0;?>;
    		
	    	DList_<?php echo $this->ID;?>.MDS=[];
	    	DList_<?php echo $this->ID;?>.MDT=[]
	    	DList_<?php echo $this->ID;?>.HAN=[];
	    	var i=0;
	    <?php 
	    	foreach ($this->HeaderArray as $name=>$label):?>
	    		DList_<?php echo $this->ID;?>.HAN[i]="<?php echo $name;?>";
	    		DList_<?php echo $this->ID;?>.MDS[i]="<?php echo $this->MetaData[$name]['Selector'];?>";
	    		DList_<?php echo $this->ID;?>.MDT[i]="<?php echo $this->MetaData[$name]['Type']; ?>";
	    		i++;
	    	<?php
	    	endforeach;?>
	    },
	    Remove:function(rem){
	    	tr=rem.parent("tr");
	    	$.each(tr.nextAll(DList_<?php echo $this->ID;?>._List + " tr"), function(i,n){
	    		t=$(this).children("td.tier").text();
	    		t=t*1-1;
	    		$(this).children("td.tier").text(t);
	    	});
	    	tr.remove();
	    	DList_<?php echo $this->ID;?>.Tier--;
	    }
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
}