<?php
/**
 * 
 * a profitional dynamiclist plugin with remove management, initial data and ...
 * @author dariush jafari
 */
class ProlistPlugin extends DynamiclistPlugin
{
	protected function jRemove()
	{ ?>
		tds=tr.children("td[header]");
		$.each(tds, function(i, n){
			td=$(this);
			if(!DList_<?php echo $this->ID;?>.MDU[i])
    		{
    			input=td.children('input');
    			name=input.attr("name");
    			name="<?php echo $this->RemoveNamePrefix;?>"+name;
    			input.attr("name",name);
    			DList_<?php echo $this->ID;?>.HiddenRemoveDiv.append(input);
    		}
		});
	<?php 	
	}
	protected function jCustom()
	{
		
	} 
}