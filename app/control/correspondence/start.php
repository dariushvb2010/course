<?php
/**
 * starts the perilous journey of the cotag in the bazbini
 * @author dariush
 *
 */
class CorrespondenceStartController extends CotagStartController
{

	function Start()
	{
		j::Enforce("Correspondence");
		return $this->SemiStart(MyGroup::Correspondence());
	}
}
