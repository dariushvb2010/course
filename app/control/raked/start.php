<?php
/**
 * starts the perilous journey of the cotag in the bazbini
 * @author dariush
 *
 */
class RakedStartController extends CotagStartController
{

	function Start()
	{
		j::Enforce("Raked");
		return $this->SemiStart(MyGroup::Raked());
	}
}
