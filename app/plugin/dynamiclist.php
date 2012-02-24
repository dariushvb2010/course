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
	public $NamePrefix="dyn_";
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
				echo "DList.IPT[".$i."]";
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
		
	var DList;
	DList = {
    	AddRow: function() {
	        if(!DList.Tier)
	        {
	            if(DList.List)
	            if(DList.List.children("tr:last"))
	            if(DList.List.children("tr:last").children("td.tier"))
	                DList.Tier=DList.List.children("tr:last").children("td.tier").html()*1;
	            else DList.Tier=0;
	            else DList.Tier = 0;
	            else DList.Tier = 0;
	        }
		    DList.GetData();
		    if(!DList.Validation())
		    	return;
	        DList.List.append("<tr></tr>");
	        var row = $(DList._List + " tr:last");
	        DList.Tier++;
	        if(DList.HasTier)
	        	row.append("<td class='tier'>"+DList.Tier+"</td>");
	        if(DList.HasSelect)
	        {
		        val=$(DList._Select).attr("value");
		        row.append("<td ><input type='checkbox' name='<?php echo $this->SelectName;?>[]' value='"+ val +"' class='<?php echo $this->SelectClass; ?>' /></td>");
			}
	        DList.WriteRecord(row);
	        if(DList.HasRemove)
	        	row.append("<td class='remove' onclick='DList.Remove($(this));'><a><img style='height:100%; vertical-align:middle;' src='/img/delete-blue-20.png'/> </a> </td>");
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
		        ?> selector=DList.MDS[i];<?php
	        	$clear=$this->MetaData[$name]['Clear'];
        		if($clear):
        		?> $(selector).val(""); <?php
	        	endif;?>
	        	var str="<td header='<?php echo $name;?>' >";
	        	str+="<span class='data'>" + DList.IPT[i] + "</span>";
	        	str+="<input type='hidden' name='<?php echo $this->FullInputName($name);?>[]' value='";
	        	str+=DList.IPV[i];
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
	    	DList.IPT=[];
	    	DList.IPV=[];
	    	var i=0;
	        for(i; i< DList.HAN.length; i++)
	        {
	         	selector=DList.MDS[i];
	        	if(DList.MDT[i]=="Text")
	        	{
	        		x=$(selector).val();
	        		DList.IPT.push(x);
	        		DList.IPV.push(x);
	        	}
	        	 	
	        	else if(DList.MDT[i]=="Select")
	        	{
	        		x=$(selector).text();
	        		DList.IPT.push(x);
	        	   	y=$(selector).attr("value");
	        	   	DList.IPV.push(y);
	        	}
	        	else if(DList.MDT[i]=="Textarea")
	        	{
	        		x=$(selector).val();
	        		DList.IPT.push(x);
	        		DList.IPV.push(x);
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
	    		otherTDs=$(DList._List +" tr td[header=<?php echo $name;?>]");
	    		$.each(otherTDs, function(i,n){
	    			td=$(this);
	    			tdData=td.children("span.data").text();
	    			if(tdData==DList.IPT[<?php echo $i;?>])
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
    		DList._Select="<?php echo $this->_Select;?>";
    		DList._List="<?php echo $this->_List; ?>";
    		DList.HasTier=<?php echo $this->HasTier ? 1:0;?>;
    		DList.HasSelect=<?php echo $this->HasSelect ? 1:0;?>;
    		DList.HasRemove=<?php echo $this->HasRemove ? 1:0;?>;
    		
	    	DList.MDS=[];
	    	DList.MDT=[]
	    	DList.HAN=[];
	    	var i=0;
	    <?php 
	    	foreach ($this->HeaderArray as $name=>$label):?>
	    		DList.HAN[i]="<?php echo $name;?>";
	    		DList.MDS[i]="<?php echo $this->MetaData[$name]['Selector'];?>";
	    		DList.MDT[i]="<?php echo $this->MetaData[$name]['Type']; ?>";
	    		i++;
	    	<?php
	    	endforeach;?>
	    },
	    Remove:function(rem){
	    	tr=rem.parent("tr");
	    	$.each(tr.nextAll(DList._List + " tr"), function(i,n){
	    		t=$(this).children("td.tier").text();
	    		t=t*1-1;
	    		$(this).children("td.tier").text(t);
	    	});
	    	tr.remove();
	    	DList.Tier--;
	    }
	}; //end of DList
		
	DList.Init();
	$("<?php echo $this->_Button;?>").click(function(){
		DList.AddRow();
	});
	var rem=$(DList._List + " tr td.remove a");
	
		<?php 
	}//PresentScript
}