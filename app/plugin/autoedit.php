<?php 
/**
* developed for using in the AutolistPlugin
* your list will have a column for editing records
* ---------------------------
* | -----|----|----|editLabel
* | +++++|++++|++++|--edit--|
* | +++++|++++|++++|--edit--|
* | +++++|++++|++++|--edit--|
* | +++++|++++|++++|--edit--|
* --------------------------
* @author dariush jafari (dariushvb2010@gmail.com)
*/
class AutoeditPlugin extends JPlugin
{
	/**
	 * whether or not show the edit column
	 * @var boolean
	 */
	private $Enable;
	public function IsEnable()
	{
		return $this->Enable;
	}
	public function Enable($Label="Edit", $EID="ID")
	{
		$this->Enable=true;
		$this->Label=$Label;
		$this->EID=$EID;
		if($this->List->HeaderArray)
		foreach($this->List->HeaderArray as $k=>$v)
		{
			if(!isset($this->MetaData[$k]['CanEdit']))
			$this->MetaData[$k]['CanEdit']=true;
		}
		$this->Flag_EchoHiddenTD=!array_key_exists($this->EID, $this->List->HeaderArray);
		$this->Flag_EchoHiddenTD |=($this->MetaData[$this->EID]['CanEdit']==false);
	}
	/**
	 * always is set
	 * @var AutolistPlugin
	 */
	private $List;
	
	public $MetaData;
	
	private $Label;
	
	/**
	 * prefix for name of text inputs
	 * @var string
	 */
	private $Prefix="__";
	/**
	 * Save Identifier: property of class or database table field name
	 * to identify the entity for updating the database
	 * @var string
	 */
	private $EID;
	/**
	 * for detecting whether HiddenTD will be Echoed or not
	 * @var boolean
	 */
	private $Flag_EchoHiddenTD;
	/**
	 *	
	 * 1-gets the post sent by 'AutoEditSave' submit and updates the class $ClassName
	 * 2-for changing the value of the field we need a set method. this method can be introdused
	 *  by $al->MetaData['headername']['SetMethod'].
	 *  the dfault value is 'Set'+headername
	 * @example $al->MetaData['Comment']['SetComment']
	 * @example (properties: Name, ID) then: $ClassName->SetID(); $ClassName->SetName();
	 * @param string $ClassName
	 */
	public function Update($ClassName,&$Error,$Constraint=array())
	{
		if(isset($_POST['AutoEditSave']))
		{
			$p=$this->Prefix;
			$EID=$_POST[$p.$this->EID];
			if(!isset($this->EID))
			{
				$Error[]="EID not set!";
				return ;
			}
			if(!isset($EID))
			{
				$Error[]="EditEID POST has not been sent!";
				return;
			}
				
			$D=ORM::Find($ClassName, $EID);
			if(!$D)
			{
				$Error[]= "داده مورد نظر برای ذخیره تغییرات یافت نشد.";
				return;
			}
				
			foreach($this->List->HeaderArray as $k=>$v)
			{
				if($this->MetaData[$k]['CanEdit'])
				{
					$Value=$_POST[$p.$k];
					if($Constraint['Unique'])
					if(array_search($k, $Constraint['Unique'])!==false)
					{
						$var=ORM::Find($ClassName, $k,$Value);
						if(count($var)>=1)
						{
							$Error[]="تکراری است.";
							return;
						}
					}
					$method=$this->MetaData[$k]['SetMethod'];
					if(method_exists($D,$method))
					{
						$D->{$method}($Value);
					}
					elseif (method_exists($D,"Set".$k))
					{
						$D->{"Set".$k}($Value);
					}
					else 
					{
						$Error[]="your class:'{$ClassName}' has no method Set{$k}";
					}
				}
			}
			try{
			ORM::Write($D);
			return $D;
			}catch (Exception $e){
				$Error[]="خطای نوشتن در پایگاه داده رخ داده است.";
			}
			
		}
	}
	public function EchoTH()
	{
		if($this->Enable)
			echo "<th>".$this->Label."</th>";
	}
	public function EchoTD()
	{
		if($this->Enable):
		?> <td isedittd="yes"><a class="autolistedit" ><img src="/img/plugin/autolist/edit-20.png" /></a></td> <?php 	
		endif;
	}
	function EchoHiddenTD($D)
	{
		if($this->Flag_EchoHiddenTD)
		{
			$k=$this->EID;
			if ($this->List->ObjectAccess)
			{
				if (isset($D->{$k}))
				$Value=($D->{$k});
				elseif (method_exists($D,$k))
				$Value=$D->{$k}();
				else
				$Value="";
					
			}
			else
			$Value=($D[$k]);
			echo "<td class='hiddenedit' style='display:none;' header='{$this->EID}'>".$Value."</td>";
		}
	}
	
	
	public function __construct(AutolistPlugin $List)
	{
		$this->List=$List;
		$this->Enable=false;
	}
	
	public function PresentScript()
	{
	?>
	$(".autolistedit").click(function()
	{
	
		var me=$(this);
		//alert("1");
		/*  return back the editing rows   */
		var rows=me.parents("tr").siblings("[editing=yes]");
		//alert(rows[0]);
		$.each(rows,function(i,n){
			var row=$(this);
			row.attr("editing","no");
			$.each(row.children("td[canedit=yes | isedittd=yes]"),function(i,n){
				td=$(this);
				$.each(td.children("input"),function(i,n){
					textvalue=$(this).attr("tempvalue");
					//alert(textvalue);
				});
				//alert("2");
				if(td.attr("isedittd")=="yes")
				{
					//alert("3");
					td.children("a").show("slow");
					td.children("input").remove();
					//alert("4");
				}
				else if(td.attr("canedit")=="yes")
				{
					//alert("before"+textvalue);
					td.html(textvalue);
					//alert("after");
				}
			});
			$.each(row.children("td.hiddenedit"), function(i, n){
				td=$(this);
				$.each(td.children("input"),function(i,n){
					textvalue=$(this).attr("tempvalue");
					//alert(textvalue);
				});
				td.html(textvalue);
			});
		});
		//alert("5");
		//-------------making input boxes-----------
		var tds=me.parents("td").siblings("[canedit=yes]");
		me.parents("tr").attr("editing","yes");
		me.hide();
		me.parents("td").append("<input type='submit' name='AutoEditSave' value='save' />" );
		$.each(tds,function (i,n){
			header=$(this).attr('header') ? $(this).attr('header') : "";
			header="<?php echo $this->Prefix;?>"+header;
			$(this).html("<input name='"+ header +"' value='"+$(this).text()+"' tempvalue='"+$(this).text()+"' class='autolist-edit-text'/>");
		});
		var idtd=me.parents("td").siblings("td.hiddenedit");
		header=idtd.attr('header') ? idtd.attr('header') : "";
		header="<?php echo $this->Prefix;?>"+header;
		if(idtd)
			idtd.html("<input type='hidden' name='"+ header +"' value='"+idtd.text()+"' tempvalue='"+idtd.text()+"' class='autolist-edit-text'/>");
			
	});
	<?php 
	}//PresentScript
}