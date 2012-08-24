<?php
use Doctrine\Common\Collections\ArrayCollection;
//if you want to edit the present subroutine, please edit your presentforprint subroutine too
/**
 * 
 * Autolist Plugin, Provides sortable list views of Objects and Arrays
 * Simplest scenario to use it to create a new instance and provide a SQL Tablename and an array of header/label maps.
 * legal metadata for headers: Style,CData
 * @version 1.8
 * @author abiusx
 * @author dariush jafari (dariushvb2010@gmail.com)
 */
class AutolistPlugin extends JPlugin
{
	/**
	 *
	 * Array or object access
	 * @var unknown_type
	 */
	public $ObjectAccess=false;
	/**
	 * 
	 * @var AutoformPlugin
	 */
	public $Autoform;
	/**
	 * 
	 * Specifies whether the form will be presented {after the list}=true or {befor the list}=false
	 * @var Boolean
	 */
	public $AutoformAfter;
	public $HasFormTag;
	public $FormMethod="get";
	/**
	 *Values of inputs of the form
	 */ 
	public $InputValues=array(
				"Limit"=>100,
				"Offset"=>0,
				"Sort"=>"",
				"Order"=>"",
				"ColsCount"=>1, // this is for presentforprint function
				"RowsCount"=>20, // this is for presentforprint function
				);
	/**
	 * 
	 * Name of inputs of the SortBox form
	 * @var unknown_type
	 */
	public $InputNames=array(
				"Limit"=>"lim",
				"Offset"=>"off",
				"Sort"=>"sort",
				"Order"=>"ord",
				"RowsCount"=>"rows",
				"ColsCount"=>"cols",
				);
	public $TableAttr=array();
	/**
	 * set Attributes of the textInput 'limit'
	 */
	public $_LimitAttr=array();
	public $_TierAttr=array();
	/**
	 * 
	 * Contains the data SQL table
	 * @var unknown_type
	 */
	public $Table;
	/**
	 * 
	 * an array for setting at the left column of the table
	 * ---------------------
	 * |              |--0--|
	 * |              |--1--|
	 * |              |--2--|
	 * |              |--3--|
	 * ---------------------
	 * @var maybe array
	 */
	public $LeftData;
	public $HasLeftData=false;
	public $LeftDataLabel="property";
	/**
	 *boolean for determining whether you want to have Tiers(1,2,3,...) in your table
	 */
	public $HasTier=false;
	public $TierLabel="Tier";
	public $Tier=1; 
	
	/**
	 * for editing rows of the autolist table
	 * @var AutoeditPlugin
	 */
	private $EditManager;
	/**
	 * 
	 * Enter description here ...
	 * @param string $Label
	 * @param string $EID Entity identifier for persisting the Entity to update database. default is "ID"
	 */
	public function EnableEdit($Label="Edit", $EID="ID")
	{
		$this->EditManager->Enable($Label, $EID);
	}
	public function Update($ClassName, &$Error, $Constraint=array())
	{
		$this->EditManager->Update($ClassName, $Error, $Constraint);
	}
//--------------------------------select------------------------
	/**
	*
	* if this property is true, your list will have a column for selecting records
	* <input type='checkbox' name='$SelectName[]' value='".$D->ID."' class='item' />
	* * ---------------------
	* | +++++++++++++|--checkbox name='SelectName[]'--|
	* | +++++++++++++|--checkbox--|
	* | +++++++++++++|--checkbox--|
	* | +++++++++++++|--checkbox--|
	* ---------------------
	* @var boolean
	*/
	public $HasSelect=false;
	/**
	 * The name of checkboxes in the html code
	 */
	public $SelectName="SelectName";
	/**
	 * Select One of HeaderArray items for this field, example: ID, Name, Comment
	 * @var string
	 */
	public $SelectValue="ID";
	public $SelectClass="SelectClass";
	public $SelectLabel="Select";
//=================================select========================
	/**
	*
	* whether has a column for removing the row
	* @var boolean
	*/
	public $HasRemove=false;
	public $RemoveLabel="Remove";
	/**
	 * 
	 * page_break in css style for print
	 * @var boolean
	 */
	public $HasPageBreak=true;
	/**
	 * Count of all elements. Is automatically evaluated when data provided via SetTable,
	 * otherwise should be set manually.
	 * @var integer
	 */
	public $Count;
	public $HasThead=true;
	public $HeaderArray;
	public $Data;
	/**
	 * 
	 * data for configuration of list
	 * @example $this->MetaData[$HeaderName]['CanEdit']=true; //means that this field can be edited in the list
	 * @var 2D Array
	 */
	public $MetaData;
	/**
	 * 
	 * 1-for adding at the end of DList(javascript name of the list)
	 * @example
	 * 		DList_yikjkdslj (yikjkdslj is $this->ID)
	 * 2- id attribute of the html table
	 * @example <table id="$this->ID"></table>
	 * @var unknown_type
	 */
	public $ID;
	
	public $FilterCallback=null;
	function GetRequest(){}// it is for compatibality with dynamiclist
	function RemoveCalled(){ return true;}//it is for comaptibality with dynamiclist
	public function SetSortParams()
	{
		if (isset($_GET[$this->InputNames['Sort']]))
			$this->SetSort($_GET[$this->InputNames['Sort']],$_GET[$this->InputNames['Order']]);
		if (isset($_GET[$this->InputNames['Limit']]))
			$this->SetLimit($_GET[$this->InputNames['Limit']],$_GET[$this->InputNames['Offset']]);
	}
	protected function GenerateRandomName()
	{
		$n="";
		for ($i=0;$i<10;++$i)
			$n.=chr(ord("a")+mt_rand(0,25));
		return $n;
	}
	public function __construct($DataOrTablename=null, $HeaderArray=null,$Name=null,$HasTier=false,$TierLabel="Tier")
	{
		$this->HasTier=$HasTier;
		$this->TierLabel=$TierLabel;
		if ($Name==null)
			$this->ID=$this->GenerateRandomName();
		else 
			$this->ID=$Name;
		$this->SetHeaders($HeaderArray);
		if ($DataOrTablename)
			if (is_string($DataOrTablename))
			{
				$this->SetSortParams();
				$this->SetTable($DataOrTablename);
			}
			else
				$this->SetData($DataOrTablename);
			//temporarily EditManager is not Enable
		$this->EditManager=new  AutoeditPlugin($this);
		$this->TableAttr["Id"]=FPlugin::RandomString();
	}
	function SetHeaders($HeaderArray)
	{
		$this->HeaderArray=$HeaderArray;
	}
	function SetData($Data)
	{
		$this->Data=$Data;
	}
	/**
	 * 
	 * Automatically retrieves the data from the table and sets data
	 * @param string $Table
	 */
	function SetTable($Table)
	{
		$Query="SELECT ";
		$fields=array();
		foreach ($this->HeaderArray as $k=>$h)
			$fields[]=$k;
		$Query.=implode(",",$fields);
		$Query.=" FROM {$Table} ";
		if ($this->Sort)
			$Query.=" ORDER BY {$this->Sort} {$this->Order} ";
		if ($this->InputValues['Offset'])
			$Query.=" LIMIT {$this->Offset},{$this->Limit}";
		elseif ($this->InputValues['Limit'])
			$Query.=" LIMIT {$this->Limit}";
		$r=j::SQL($Query);
		$this->SetData($r);
		$this->Table=$Table;
	}
	function SetLimit($Limit,$Offset=null)
	{
		$this->InputValues['Limit']=$Limit*1;
		$this->InputValues['Offset']=$Offset;
	}
	function SetSort($Sort,$Order="ASC")
	{
		$Order=strtoupper($Order);
		if ($Order!="ASC" AND $Order!="DESC")
			$Order="ASC";
		$flag=false;
		foreach ($this->HeaderArray as $k=>$h)
		{
			if (!$this->MetaData[$k]['Unsortable'])
				if ($Sort==$k) 
					$flag=true;
		}
		if (!$flag) 
		{
			reset($this->HeaderArray);
			do
			{
				$t=each($this->HeaderArray);
			} while ($this->MetaData[$t['key']]['Unsortable']);
			$Sort=$t['key'];
			
		}
		$this->Order=$Order;
		return $this->Sort=$Sort;
	}
	function SetMetadata($MetadataArray)
	{
		$this->MetaData=$MetadataArray;
	}
	function SetHeader($HeaderName,$HeaderLabel,$CData=false,$Unsortable=false,$CanEdit=true)
	{
		$this->HeaderArray[$HeaderName]=$HeaderLabel;
		$this->MetaData[$HeaderName]['CData']=$CData;
		$this->EditManager->MetaData[$HeaderName]['CanEdit']=$CanEdit;
		$this->MetaData[$HeaderName]['Unsortable']=$Unsortable;
		
	}
	function SetFilter($Callback)
	{
		$this->FilterCallback=$Callback;
	}
	protected function Filter($Key,$Value,$Object=null)
	{
		if ($this->FilterCallback)
			return call_user_func($this->FilterCallback,$Key,$Value,$Object);
		else
			return $Value;
	}
	
	public $Border=1;
	public $Padding=0;
	public $Spacing=0;
	public $Width="100%";
	public $FormWidth="100%";
	
	
	/**
	 * 
	 * you can make a list width seperate pages and any count of rows and columns for print in a paper like A4
	 * if you have a form for your autolist dont set colscount and rowscount parameters, it will be set from the form inputs 
	 * @param integer $RowsCount: Number of rows you want to present in a print page
	 * @param integer $ColsCount: Number of cols you want to present in a print page
	 * @throws Exception
	 */
	function PresentForPrint()
	{
		if($this->Data)
			$AllDataCount=count($this->Data);
		else
			$AllDataCount=0;
		//ColsCount
		if($this->InputValues['ColsCount']!=0 && $this->InputValues['ColsCount']!="auto")
			$ColsCount=$this->InputValues['ColsCount'];
		else
			$ColsCount=1;
		//RowsCount
		if($RowsCount==0 && $this->InputValues['RowsCount']!=0)
			$RowsCount=$this->InputValues['RowsCount'];
		else if($this->InputValues['RowsCount']=="auto")
		{
				$RowsCount=(int)($AllDataCount/$ColsCount);
				if($AllDataCount%$ColsCount!=0)
					$RowsCount++;
		}
		else
			$RowsCount=1;

		$PageDataCount=$ColsCount*$RowsCount;//number of data in each page
		$PageCount=$AllDataCount/$PageDataCount;//number of pages
		if((int)($PageCount)<$PageCount)
		{
			$PageCount=(int)($PageCount)+1;//the last page will contain less data than the others
		}
		if($this->Autoform)
			$this->Autoform->HasFormTag=false;
		$this->MakeTopOfForm();
		if($this->Autoform AND !$this->AutoformAfter)
			$this->Autoform->PresentHTML();
		//one seperate table for each page
		for($p=0; $p<$PageCount; $p++){
		?>
		
			<table class='autolistprint' id='<?php echo $this->ID;?>' width='<?php echo $this->Width;?>' border='<?php echo $this->Border;?>' 
				cellpadding='<?php echo $this->Padding;?>' cellspacing='<?php echo $this->Spacing;?>' 
				style="<?php if(!$this->HasPageBreak) echo 'page-break-after: auto;'?>">
			<?php if($this->HasThead): ?>
					<thead>
					<tr>
						<?php 
						/*
						* $PastDataCount
						*number of data that have been wirten on last pages.
						*and now in this page we start with $D[$PastDataCount]
						*/
						$PastDataCount=$p*$PageDataCount;
						if (!is_array($this->HeaderArray))
						 	throw new Exception("No header array provided!");
						$this->EchoLeftTH();
						for($c=0; $c<$ColsCount; $c++)
						{
							$this->EchoTHs();
						}
						?>
					</tr>
					</thead>
			<?php endif;
			if($this->HasLeftData):?>
			<tbody>
			<colgroup>
			<col class="autolist-leftdata" />
			</colgroup>
			<?php endif; 
			
				if ($this->Data)
				for($r=0; $r<$RowsCount; $r++)
				if($PastDataCount+$r<$AllDataCount || $this->HasTier)//this condition prevents from empty rows
				{
					?>
			<tr align='center'>
					<?php 
					
					//----for left column
					$this->EchoLeftTD($r);
					for($c=0; $c<$ColsCount; $c++)
					{
						$index=$PastDataCount+$r+($c*$RowsCount);
						$D=$this->Data[$index];
						$Tier=$index+$this->Tier;
						$this->EchoTierTD($Tier);
						$this->EditManager->EchoTD();
						$this->EchoSelectTD($D);
						$this->EchoRecord($D, $index < $AllDataCount);
						
					}
						?>
			</tr>
					<?php  
				}
				?>
			</tbody>
			</table>
			<?php
		}//end of for: for each page
			if( $this->Autoform AND $this->AutoformAfter)
				$this->Autoform->PresentHTML();
			$this->MakeBottomOfForm();
		
	}
	/**
	 * 
	 * ----for left column
	 */
	protected function EchoLeftTD($index)
	{
		if($this->HasLeftData)
		{
			echo "<th style='color:white;' class=> ".$this->LeftData[$index]."</th>";
		}
	}
	protected function EchoLeftTH()
	{
		if($this->HasLeftData)
			echo "<th>".$this->LeftDataLabel."</th>";
	}
	protected function EchoTierTD($Tier)
	{
		if($this->HasTier)
		{
			$td="<td class='tier' ";
			foreach ($this->_TierAttr as $k=>$v)
			$td.=$k."='".$v."' ";
			$td.=">";
			$td.=$Tier."</td>";
			echo $td;
		}
	}
	
	protected function EchoSelectTD($D)
	{
		if($this->HasSelect)
		{ ?>
			<td canedit='no'><input type="checkbox" name="<?php echo $this->SelectName;?>[]" value="<?php
				if ($this->ObjectAccess)
				{
					$k=$this->SelectValue;
					if (isset($D->{$k}))
					$Value=($D->{$k});
					elseif (method_exists($D,$k))
					$Value=$D->{$k}();
					else
					$Value="";
					echo $Value;
				}
				else
					echo $D[$this->SelectValue]; ?>"
			class="<?php echo $this->SelectClass; ?>" /></td><?php 
		}
	}
	protected function EchoRemoveTD()
	{ 
		if($this->HasRemove):
		?><td class='remove' onclick='DList_<?php echo $this->ID;?>.Remove($(this));'><a><img style='height:100%; vertical-align:middle;' src='/img/delete-blue-20.png'/> </a> </td><?php 
		endif;
	}
	protected function EchoTHs()
	{
		if($this->HasTier)
		{
			echo "<th>".$this->TierLabel."</th>";
		}
		$this->EditManager->EchoTH();
		if($this->HasSelect)
		{
			echo "<th>".$this->SelectLabel."</th>";
		}
		foreach ($this->HeaderArray as $h)
		{
			?>
			<th><?php echo $h; ?></th>
			<?php 	
		}	
		if($this->HasRemove)
			echo "<th>".$this->RemoveLabel."</th>";
	}
	protected function EchoRecord($D, $IfEchoValue=true)
	{
		foreach ($this->HeaderArray as $k=>$h)
		{
			if($IfEchoValue)
			{
				if ($this->ObjectAccess)
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
				$Value=$this->Filter($k, $Value,$D);
				if (!$this->MetaData[$k]['CData'])
				$Value=htmlspecialchars($Value);
			}
			$this->EchoRecordTD($k, $Value, $IfEchoValue);
		}
		$this->EditManager->EchoHiddenTD($D);
	}
	protected function EchoRecordTD($k, $Value, $IfEchoValue)
	{
	?>
		<td header="<?php echo $k;?>" 
		<?php echo $this->EditManager->MetaData[$k]['CanEdit'] ? "canedit='yes'" : "canedit='no'"; ?>
		style='<?php echo $this->MetaData[$k]['Style'];?>'
		><?php if($IfEchoValue) echo ($Value===null || $Value==="") ? "-" : $Value;?></td>
	<?php
	}
	function Present()
	{
		if($this->Autoform)
			$this->Autoform->HasFormTag=false;
		$this->MakeTopOfForm();
		if($this->Autoform AND !$this->AutoformAfter)
			$this->Autoform->PresentHTML();
		?>
		<div class="clear"></div>
		<table class='autolist' id='<?php echo $this->ID;?>' width='<?php echo $this->Width;?>' border='<?php echo $this->Border;?>' cellpadding='<?php echo $this->Padding;?>' cellspacing='<?php echo $this->Spacing;?>' >
		<?php if($this->HasThead): ?> 
				<thead>
				<tr>
					<?php 
					if (!is_array($this->HeaderArray))
					 throw new Exception("No header array provided!");
					$this->EchoLeftTH();
					$this->EchoTHs();
					?>
				</tr>
				</thead>
		<?php endif;?>
		<tbody>
			<?php 
			$index=0;
			if ($this->Data)
			foreach ($this->Data as $D)
			{
				?>
		<tr align='center'>
				<?php 
				$this->EchoLeftTD($index);
				$this->EchoTierTD($this->Tier++);
				$this->EditManager->EchoTD();
				$this->EchoSelectTD($D);
				$this->EchoRecord($D);
				$this->EchoRemoveTD();
				?>
		</tr>
				<?php  
				$index++;
			}
			?>
		</tbody>
		</table>
		<div id="RemoveContainer_<?php echo $this->ID?>" class="HiddenContainer"></div><!-- for dynamiclist -->
		<?php
		if( $this->Autoform AND $this->AutoformAfter)
			$this->Autoform->PresentHTML();
		$this->MakeBottomOfForm();
	}
	protected function MakeTopOfForm()
	{
		if(( $this->EditManager->IsEnable()==true AND $this->HasFormTag!==false) OR $this->HasFormTag==true)
			echo "<form method='post' class='autolistform'>";
	}
	protected function MakeBottomOfForm()
	{
		if(($this->EditManager->IsEnable()==true  AND $this->HasFormTag!==false) OR $this->HasFormTag==true)
			echo "</form>";
	}
	protected function SetFormInputValues()
	{
		$m=strtoupper($this->FormMethod);
		foreach ($this->InputNames as $k=>$l)
		{
			if(isset($_GET[$l]))
			{
				$this->InputValues[$k]=$_GET[$l];
			}
		}
	}
	protected function PresentSortBoxTop()
	{
		$this->SetFormInputValues();
		?>
		<form class='sortform sortbox' id="sortform_<?php echo $this->ID;?>" method='<?php echo $this->FormMethod;?>' style='text-align:center;width:<?php echo $this->FormWidth;?>'>
		از
		<input type='text' name='<?php echo $this->InputNames['Offset'];?>' size='5' value='<?php echo $this->InputValues['Offset'];?>' />
		به تعداد
		<input type='text' name='<?php echo $this->InputNames['Limit'];?>' value='<?php echo $this->InputValues['Limit'];?>' size='5'
		<?php 
			foreach($this->_LimitAttr as $k=>$v)
			{
				echo $k."='$v'";	
			}
		?>
		/>
		مرتب سازی بر اساس
		<select name='<?php echo $this->InputNames['Sort'];?>'>
		<?php 
		foreach ($this->HeaderArray as $k=>$v)
		{
			if (!$this->MetaData[$k]['Unsortable'])
			{
				if ($this->InputValues['Sort']==$k)
					$sel=" selected='selected' ";
				else 
					$sel="";
				echo "<option {$sel} value='{$k}'>$v</option>\n";
			}
		}
		?>
		</select>
		<select name='<?php echo $this->InputNames['Order'];?>'>
		<option value='ASC'  >صعودی</option>
		<option value='DESC' <?php if ($this->InputValues['Order']=='DESC') echo " selected='selected' ";?>>نزولی</option>
		
		</select><?php 
	}
	function PresentSortboxForPrint()
	{
		$this->PresentSortBoxTop();
		?>
		تعداد سطر
		<input type='text' name='<?php echo $this->InputNames['RowsCount'];?>' size='5' value='<?php echo $this->InputValues['RowsCount'];?>' />
		تعداد ستون
		<input type='text' name='<?php echo $this->InputNames['ColsCount'];?>' value='<?php echo $this->InputValues['ColsCount'];?>' size='5'/>
		<input type='submit' value='برو' />		
			</form>
			<?php 
	}
	function PresentSortBox()
	{
		$this->PresentSortBoxTop();
		?>
		<input type='submit' value='برو' />		
		</form>
		<?php 
	}
	function PresentCSS()
	{ ?>
		table.autolist tr td{text-align:center;}
		input.autolist-edit-text{width:97%; background:#a1caea; color:#033; text-align:center; -moz-box-shadow:5px #316891;}
		form.autolistform {
			width:auto;
			margin:0;
			border:none;
			padding:0;
			text-align:center;
		}
		form.autolistform input[type='submit'] {
			margin:5px;
			width:auto;
		}
		form.autolistform input[type='text'] {
			width:auto;
			text-align:center;
		}
	<?php 	
	}
	function PresentScript()
	{
		$this->EditManager->PresentScript(); 
	}//PresentScript
}