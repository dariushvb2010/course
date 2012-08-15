<?php
/**
 * starts the perilous journey of the cotag in the bazbini
 * @author dariush
 *
 */
class ArchiveStartController extends CotagStartController
{

	function Start()
	{
		j::Enforce("Archive");
		//////////////////////////////////////

		return $this->SemiStart(MyGroup::Archive());
	}
}
