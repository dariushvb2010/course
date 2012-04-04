<?php
/* jFramework
 * Database Access Layer Library
 */

$x=new jpCustom2Module ("lib.dbal.interface",".");
$this->LoadSystemModule ( $x->__toString() ); //DBAL interfaces
$x=new jpCustom2Module ("lib.dbal.base",".");
$this->LoadSystemModule ( $x->__toString() ); //DBAL base

$x=new jpCustom2Module ("lib.dbal.nestedset.base",".");
$this->LoadSystemModule ( $x->__toString() ); //Nested Sets
$x=new jpCustom2Module ("lib.dbal.nestedset.full",".");
$this->LoadSystemModule ( $x->__toString() );

if (! reg("app/db/default/adapter")) reg("app/db/default/adapter","mysqli");

if (reg("app/db/default/adapter") == "mysqli")
{
	reg("app/db/default/type","mysql");
	$x=new jpCustom2Module ("lib.dbal.adapter.mysqli","."); 
	$this->LoadSystemModule ( $x->__toString()); //MySQL DBAL adapter

eval("
	final class DBAL extends DBAL_MySQLi {}
	");
}
elseif (reg("app/db/default/adapter") == "pdo_sqlite")
{
	reg("app/db/default/type","sqlite");
	$x=new jpCustom2Module ( "lib.dbal.adapter.pdo_sqlite",".") ;
	$this->LoadSystemModule ($x->__toString()); //PDO_SQLite DBAL adapter

eval("
	final class DBAL extends DBAL_PDO_SQLite {}
	");
}
elseif (reg("app/db/default/adapter") == "pdo_mysql")
{
	reg("app/db/default/type","mysql");
	$x=new jpCustom2Module ( "lib.dbal.adapter.pdo_mysql",".") ;
	$this->LoadSystemModule ($x->__toString()); //PDO_MySQL DBAL adapter

eval("
	final class DBAL extends DBAL_PDO_MySQL {}
	");
}
else 
{
	throw new Exception("Unknown database type {".reg("app/db/default/adapter")."}, no driver for this kind of database.");
}
?>