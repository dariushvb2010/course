<?php

if(isset($_GET['st'])&&isset($_GET['ft']))
{
	$res=ORM::Query(new ReviewProgressStart())->Prints($_GET['st'],$_GET['ft']);
}
	if($res)
	foreach ($res as $v){?>
		 <div align =center style ="margin-bottom:300px; margin-top: 200px;" >
		 <img src='<?php echo jURL::Root();?>/barcode?number=<?php echo $v['Cotag'];?>&width=3&height=100&font=18' >
		 </div>	
<?php }?>
	
