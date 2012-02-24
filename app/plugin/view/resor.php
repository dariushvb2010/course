<?php
/**
 * 
 * view RESult and errOR
 * @author dariush jafari
 *
 */
class ViewResortPlugin extends JPlugin
{
	static function Show($Success=null,$Errors=null)
	{
		if (is_array($Errors))
		{
			foreach ($Errors as $E)
			{
				?>
<div style="margin: 2px; padding: 0 .7em; min-height: 35px;overflow:auto;padding-bottom:0px;"
	class="ui-state-error ui-corner-all noprint"
>
<p style='margin-top: 5px;'><span
	style="float: right; margin-left: .3em; margin-top: 4px;"
	class="ui-icon ui-icon-alert noprint"
></span> <strong>خطا: </strong> <?php echo $E;?></p>
</div>
				<?php
			}

		}
		if (($Success))
		{
			?>
<div style="margin: 2px; margin: 5px; padding: 0 .7em; height: 35px;"
	class="ui-state-highlight ui-corner-all"
>
<p style='margin-top: 5px;'><span
	style="float: right; margin-left: .3em; margin-top: 4px;"
	class="ui-icon ui-icon-info"
></span> <?php echo $Success;?></p>
</div>
			<?php

		}
	}
}