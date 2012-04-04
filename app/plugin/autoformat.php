<?php
/**
 * Autoform Generator Class
 * Enter description here ...
 * @author abiusx,dariush
 * @version 20.03
 */
class AutoformPlugin extends JPlugin
{
	public $formElements=array();
	public $labelNames=array();
	public $formAttribs;
	/**
	 * custom attributes of the form  : <form name="formattribs['name']"> 
	 * @var array that its keys are the names of attribs and its values are the values of attribs
	 */
	public $CustomAttribs;
	/**
	 * 
	 * invoke that you want to have form tag for you form(<form method='post'></form>
	 * @var boolean
	 */
	public $HasFormTag=true;
	/**
	 * 
	 * @var AutolistPlugin
	 */
	public $List;
	/**
	 * 
	 * determine which function of the will be called: Present or PresentForPrint
	 * @var string
	 */
	public $List_Present_Func="Present";
	/**
	 * String for styling the form
	 * @var string
	 **/
	public $Style;
	function __construct($method='get',$action='')
	{
		$this->formAttribs['Method']=$method;
		$this->formAttribs['Action']=$action;
		$this->formAttribs['Id']=$this->GenerateRandomID();
	}
	function SetCheckboxClass($ClassName)
	{
		$this->CheckboxClass=$ClassName;
	}
	function GenerateRandomID()
	{
		$id="";
		$l=rand(2,10);
		for ($i=0;$i<$l;++$i)
		{
			$id.=chr(ord('a')+rand(0,25));
		}
		return $id;
	}
	function PresentCSS()
	{
	if($this->HasFormTag):
	?>
			form.autoform {
				width:60%;
				margin:auto;
				padding:10px;
				border:3px double;
				text-align:center;
			}
			form.autoform input[type='submit'] {
				width:200px;
				margin:5px;
			}
			form.autoform input[type='text'] {
				width:150px;
				text-align:center;
			}
			form.autoform label {
				width:200px;
				float:right;
				height:1em;
			}
			form.autoform input {
				text-align:center;
			}
			form.autoform .autoFormContainer {
				margin:2px;
				margin-bottom:5px;
			}	
			form.autoform .autoFormContainer select{
				margin-top:8px;
			}
			form.autoform .autoFormContainer textarea{
				margin-top:8px;
			}
	<?php 
	else:
	?>
			div.autoform {
				width:60%;
				margin:auto;
				padding:10px;
				border:3px double;
				text-align:center;
			}
			div.autoform input[type='submit'] {
				width:200px;
				margin:5px;
			}
			div.autoform input[type='text'] {
				width:150px;
				text-align:center;
			}
			div.autoform label {
				width:200px;
				float:right;
				height:1em;
			}
			div.autoform input {
				text-align:center;
			}
			div.autoform .autoFormContainer {
				margin:2px;
				margin-bottom:5px;
			}
			div.autoform .autoFormContainer select{
				margin-top:8px;
			}
			div.autoform .autoFormContainer textarea{
				margin-top:8px;
			}
	<?php 
	endif;
	}
	function PresentScript()
	{
	?>
function setCheckboxNames(ClassName)
{
	
	s=$("input:checkbox."+ClassName);
	$.each(s,function(){
			Name=$(this).attr("Name");
			$(this).attr("Name",Name+"[]");	
	});
	
}
function showValidation_<?php echo $this->formAttribs['Id'];?>(name,validation)
{
	var icon=String();
	if (validation==true)
		icon='tick';
	else if (validation==false)
		icon='wrong';
		
	var bgimg="url('<?php echo SiteRoot;?>/img/plugin/autoform/icon/"+icon+"16.png')";
	$(".autoform#<?php echo $this->formAttribs['Id'];?> *[name='"+name+"']").css("background-image",bgimg);
	return validation;
}
function validateForm_<?php echo $this->formAttribs['Id'];?>(e)
{
	var name=e.target.tagName;
	name=(name.toLowerCase());
	var all=false;
	if (name=='form')
		all=true;
	else
		name=e.target.name;
	<?php 
		foreach ($this->formElements as $E)
		{
			if ($E['Validation'])
			{
			?>
	idx="<?php echo $E['Name'];?>";
	if (all || name==idx)
	{
		var item=$(".autoform#<?php echo $this->formAttribs['Id'];?> *[name='"+idx+"']");
		if (item.is(":visible") && !item.val().match(<?php echo $E['Validation'];?>))
			return showValidation_<?php echo $this->formAttribs['Id'];?>(idx,false);
		else
			showValidation_<?php echo $this->formAttribs['Id'];?>(idx,true);
	}
			<?php
			} 	
		}
		?>
	return true;
}

function checkDependency_<?php echo $this->formAttribs['Id'];?>(e)
{
<?php 
	foreach ($this->formElements as $E)
	{
		if ($E['Dependency'])
		{
			
			$v=$E['DependencyValue'];
			?>
			container=$("form.autoform#<?php echo $this->formAttribs['Id'];?> #container_<?php echo $this->formAttribs['Id'];?>_<?php echo $E['Name'];?>");
			item=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='<?php echo $E['Name'];?>']");
			if (item.attr("name")==undefined)
				item=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[nameBackup='<?php echo $E['Name'];?>']");
			
			<?php
			switch ($this->formElements[$E['Dependency']]['Type']){ 
			case 'radio':
				?>
				dependency=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='<?php echo $E['Dependency'];?>']:checked");
				<?php 
			break;
			case 'checkbox':
				?>
				dependency=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='<?php echo $E['Dependency'];?>']:checked");
				<?php 
			break;
			case 'select':
				?>
				dependency=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='<?php echo $E['Dependency'];?>'] option:selected");
				<?php 
			break;
			default:
				?>
				dependency=$("form.autoform#<?php echo $this->formAttribs['Id'];?> *[name='<?php echo $E['Dependency'];?>']");
				<?php 
			}
			?>
				data=dependency.val();
				if (data<?php echo $v;?>)
				{
					if (!container.is(":visible"))
					{
						item.attr("name",item.attr("nameBackup"));
						container.show();
					}
				}
				else
				{
					if (container.is(":visible"))
					{
						item.attr("nameBackup",item.attr("name"));
						item.attr("name","");
						container.hide();
					}
				}	
		<?php 
		}
	}

?>
}
$(function(){
	$(".autoform#<?php echo $this->formAttribs['Id'];?> *").live("change",validateForm_<?php echo $this->formAttribs['Id'];?>);
	$(".autoform#<?php echo $this->formAttribs['Id'];?> *").live("change",checkDependency_<?php echo $this->formAttribs['Id'];?>);
	$(".autoform#<?php echo $this->formAttribs['Id'];?>").live("submit",function(e) {
		if (!validateForm_<?php echo $this->formAttribs['Id'];?>(e))
		{
			alert("لطفا اطلاعات را اصلاح کنيد");
			return false;
		}
		});
	checkDependency_<?php echo $this->formAttribs['Id'];?>();
	$(".autoform input:first").focus();
});
		<?php 
	if($this->List)
		$this->List->PresentScript();
	}
	function PresentHTML()
	{
		//-------------------form tag----------------------
		if($this->HasFormTag):
		?>
		<form class='autoform' id='<?php echo $this->formAttribs['Id'];?>' 
				method='<?php echo $this->formAttribs['Method'];?>' 
				action='<?php echo $this->formAttribs['Action'];?>'
				style='<?php echo $this->Style;?>'
				onsubmit="setCheckboxNames('<?php echo $this->CheckboxClass;?>')"
				<?php if($this->CustomAttribs) foreach ($this->CustomAttribs as $name=>$value)
						echo "{$name}='{$value}' ";?>
				>
		<?php 
		//-------------------div tag--------------no form tag------
		else:
			?><div class='autoform' id="<?php echo $this->formAttribs['Id']; ?>"
				   style='<?php echo $this->Style; ?>'
				   <?php if($this->CustomAttribs) foreach ($this->CustomAttribs as $name=>$value)
						echo "{$name}='{$value}' ";?>
				   > <?php 
		endif;
		$OddEvenBack=0;  //odd containers apeare with different background of even ones
		foreach ($this->formElements as $E)
		{
			$num++;
			if($E['Type']=="check") $E['Type']="checkbox";

				?>
			<div id='container_<?php echo $this->formAttribs['Id'];?>_<?php echo $E['Name'];?>' class='autoFormContainer
				<?php 	
				if(strtolower($E['Type'])!="submit" AND strtolower($E['Type'])!="button")
				{
					echo ($num%2==0 ? "even" : "odd");
				}?>'
				style="<?php echo $E['ContainerStyle'];?>"
			>
			<?php 
			switch($E['Type'])
			{
			case 'text':
			case 'password':
			?>
				<label><?php echo $E['Label'];?></label>
				<input type='<?php echo $E['Type'];?>' name='<?php echo $E['Name'];?>' 
				<?php if ($E['Direction']) echo " dir='{$E['Direction']}' ";?> 
				<?php if ($E['Disabled']) echo " disabled='disabled' ";?> 
				<?php if ($E['Style']) echo " style='{$E['Style']}' ";?> 
				<?php if($E['Placeholder']) echo "placeholder='{$E['Placeholder']}'"; else echo "placeholder='{$E['Label']}'";?>
				<?php if($E['Required']) echo "required='required'"; ?>
				value='<?php 
				if (array_key_exists("Value",$E))
					echo $E['Value'];
				else 
					echo $E['Default'];
				?>'
				<?php if(isset($E['Class'])){?>
					class='<?php echo $E['Class'];?>'
				<?php }?>
				/><?php echo $E['Unit'];?>
			<?php 	
			break;
			case 'submit' :
			?>
				<input type='submit' value='<?php echo $E['Value']?$E['Value']:"ارسال";?>' Name='<?php echo $E['Name'];?>'/>
			<?php 
			break;
			case 'button' :
				?>
					<button type="button" value='<?php echo $E['Value']?$E['Value']:"اضافه";?>' Name='<?php echo $E['Name'];?>'><?php echo $E['Value']?$E['Value']:"اضافه";?></button>
				<?php 
			break;
			case 'textarea' :
			?>
				<label for="<?php echo $E['Name'];?>"><?php echo $E['Label'];?></label>
				<textarea name='<?php echo $E['Name'];?>' id='<?php echo $E['ID'];?>' 
				 <?php if ($E['Style']) echo " style='{$E['Style']}' ";?>
				 ><?php 
				if (array_key_exists("Value",$E))
					echo $E['Value'];
				else 
					echo $E['Default'];
				?></textarea>
			<?php 
			break;
			case 'radio':
			case "checkbox":
			?>
				<label><?php echo $E['Label'];?></label><?php 
				if (is_array($E['Options']))
				{
					$c=$E['Class'];
					foreach ($E['Options'] as $k=>$o)
					{
						if(isset($E["Vertical"]))echo "<br/>";
				?><input type='<?php echo $E['Type'];?>'name='<?php echo $E['Name'];?>' value='<?php echo $k;?>' class='<?php echo $c;?>' <?php 
					if ($E['Value']==$k or (!$E['Value'] AND $E['Default']==$k) )
					 	echo " checked='checked' ";
					if(is_array($E["Checks"]) AND in_array($k, $E['Checks']))
						echo " checked='checked' ";?>
					/><?php echo $o; 
							if($E['Type']=="checkbox")echo "&nbsp;&nbsp;";
							
					} 
				}
				?>
			<?php 
			break;
			case 'select':
			?>
				<label><?php echo $E['Label'];?></label>
				<select name='<?php echo $E['Name'];?>'  id='<?php echo $E['ID'];?>' 
					<?php if($E['Multiple']) echo "Multiple='Multiple' ";
						if($E['Size']) echo "Size='".$E['Size']."'";?>
					style="<?php
					if(!$E['Width'])
						echo 'Width:auto;'; 
					else 
						echo 'width:'.$E['Width'].';';?>">
				<?php 
					if (is_array($E['Options']))
					foreach ($E['Options'] as $k=>$o)
					{
				?>
					<option value='<?php echo $k;?>' <?php 
					if ($E['Value']==$k 
						or (!$E['Value'] AND $E['Default']==$k)) echo " selected ";?>
					><?php echo $o;?></option>
				<?php
					} ?></select>
			<?php 
			break;
			case "hidden": ?>
				<input type='hidden' name='<?php echo $E['Name'];?>'  id='<?php echo $E['ID'];?>' value='<?php echo $E['Value'];?>'/>
				<?php 
			break;
			case 'custom':
			?>
				<?php if(isset($E['Label'])){?><label><?php echo $E['Label'];?></label><?php }?>
				<?php echo $E['HTML'];?>
			<?php 
			break;
			default:
			?>
				<?php if(isset($E['Label'])){?><label><?php echo $E['Label'];?></label><?php }?>
				<input <?php foreach ($E as $key =>$value){ 
					echo $key."='".$E[$key]."' ";
				} ?>/>
			<?php
			}//switch close
			?> </div> <?php 	
		}
		if($this->List)
		if($this->List_Present_Func=="Present")
			$this->List->Present();
		elseif($this->List_Present_Func=="PresentForPrint")
			$this->List->PresentForPrint();
		if($this->HasFormTag):
			?></form><?php 
		else:
			?></div><?php 
		endif;
	}

	function AddElement($Element,$Name=null,$Label=null,$Value=null,$Default=null,$Unit=null,$Validation=null,$Options=null,$Dependency=null,$Style=null)
	{
		if (is_array($Element))
		{
			$data=$Element;
		}
		else 
		{
			$data['Type']=$Element;
		}
		if ($Name!==null)
			$data['Name']=$Name;
		if ($Label!==null)
			$data['Label']=$Label;
		if ($Default!==null)
			$data['Default']=$Default;
		if ($Unit!==null)
			$data['Unit']=$Unit;
		if ($Validation!==null)
			$data['Validation']=$this->getValidation($Validation);
		if ($Options!==null)
			$data['Options']=$Options;
		if ($Dependency!==null)
			$data['Dependency']=$Dependency;
		if ($Style!==null)
			$data['Style']=$Style;
		if ($Value!==null)
			$data['Value']=$Value;

		
		if ($Element['Validation'] && !$Validation)
			$data['Validation']=$this->getValidation($Element['Validation']);	
			
		//updaing label names
		if ($Name===false)
			$Name=$Element['Name'];
		if ($Label===false)
			$Label=$Element['Label'];
		if ($Name)
			$this->labelNames[$Name]=($Label)?$Label:$Name;
		
		$this->formElements[$data['Name']]=$data;
		return $data;
	}
	function getValidation($Validation)
	{
		if ($Validation=="number" or $Validation=='numeric')
		{
			return "/^\d{1,}$/";
		}
		elseif ($Valudation=='alphanumeric')
		{
			return "/^[a-zA-Z0-9 ]{1,}$/";
		}
		elseif ($Validation=='alpha')
		{
			return "/^[a-zA-Z ]{1,}$/";
		}
		elseif ($Validation=='alpha_farsi')
		{
			return "/[a-z ا-ي آ-يA-Z]{1,}/";
		}
		elseif ($Validation=='alphanumeric_farsi')
		{
			return "/[a-zA-Z0-9ا-ي?-?آ-ي ]{1,}/";
		}
		elseif ($Validation=='numeric_farsi' or $Validation=='number_farsi')
		{
			return "/^[?-?0-9]{1,}$/";
		}
		elseif ($Validation=='*')
			return "/.*/";
		elseif ($Validation=='?')
			return "/^.{1,}$/";
		else 
			return $Validation;
	}
	
	function Validate($Data,&$Result,$ForceExistance=false)
	{
		$flag=true;
		foreach ($this->formElements as $E)
		{
			if ($E['Validation'])
			{
				if (!$ForceExistance and !array_key_exists($E['Name'], $Data)) continue;
				if (!preg_match($E['Validation'],$Data[$E['Name']]))
				{
					$Result[$E['Name']]=$flag=false;
//					echo "Validation failed on ".$E['Name'].BR;
				}
//				else 
//					$Result[$E['Name']]=true;
			}
			elseif ($E['Type']=='select' or $E['Type']=='radio')
			#TODO: only allow listed options
			;
		}
		return $flag;
	}
	
}