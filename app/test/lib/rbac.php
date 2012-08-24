<?php
class LibRbacTest extends JTest
{
	function __construct()
	{
	}
	function setUp()
	{
		
	}
	function tearDown()
	{
	}
	function testStart()
	{
		$this->assertTrue(true);
		//var_dump(j::$RBAC->Role_All());
	}
	function testUsersOfRole()
	{
		$r=j::$RBAC->UsersOfRole(4);
		$r=j::$RBAC->Role_All();
		
		$r->InsertChildData(
		array("title"=>"ali", "persiantitle"=>"vali")
		,$this->RoleCondition(0),0
		);
		//$r=MyUser::CurrentUser()->PersianLegalRoles();
		var_dump($r);
		//$r=j::$RBAC->SubRoles(3);
		//var_dump($r);
	}
	private function RoleField($Role)
	{
		if (is_numeric($Role))
		return "ID";
		else
		return "Title";
	}
	private function RoleCondition($Role)
	{
		return $this->RoleField($Role)."=?";
	}
	function testA()
	{
		for($i=0; $i<100; $i++)
		{
			$r=j::$RBAC->RoleIDsOfUser($i);
			echo "i:";
			echo $i." ";
			var_dump( $r);
		}
		//$s=j::SQL("insert into app_alarm (ID, `FileID`,`MotherUserID`) values (124,123,45)");
		
		
	}
	
	

}