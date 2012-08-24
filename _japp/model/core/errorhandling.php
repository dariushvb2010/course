<?php
/**
 * jFramework Error Handler
 * @version 2
 * @author abiusx@jframework.info
 */
class jfErrorHandler 
{
	function __construct()
	{
		$this->SetErrorHandler();
	}
	
	function SetErrorHandler()
	{ 
		if (reg("jf/setting/ErrorHandler") )
		{
			reg("jf/ErrorHandler/DefaultReporting",error_reporting());
			error_reporting ( 0 );
			set_error_handler ( array($this,'GeneralError'), E_ALL);// & ~E_NOTICE );
		// 	set_exception_handler ( 'GeneralError');
			register_shutdown_function ( array($this,'ErrorShutdown') );
		}
		else
		{
			if (reg("jf/ErrorHandler/DefaultReporting"))
			{
				error_reporting(reg("jf/ErrorHandler/DefaultReporting"));
				restore_error_handler();
				reg("jf/ErrorHandler/DefaultReporting",null);
			}
		}
	}
	public $FileLines = 10;
	function FileLines($File, $Line)
	{
		$Limit = $this->FileLines/2;
		$f = fopen ( $File, "r" );
		$LineNumber = 0;
		$out = array ();
		while ( ! feof ( $f ) )
		{
			$curline = fgets ( $f );
			$LineNumber ++;
			if ($LineNumber > $Line + $Limit) break;
			if ($LineNumber > $Line - $Limit && $LineNumber < $Line + $Limit) $out [$LineNumber] = $curline;
		}
		fclose ( $f );
		return $out;
	}

	function GeneralError($errno, $errstr, $errfile, $errline, $errcontext = null)
	{
		switch ($errno)
		{
			case E_USER_ERROR :
				$Type = "User Error";
				break;
			case E_USER_WARNING :
				$Type = "User Warning";
				break;
			case E_USER_NOTICE :
				$Type = "User Notice";
				break;
			case E_ERROR :
				$Type = "Error";
				break;
			case E_NOTICE:
				if (strpos($errstr,"Use of undefined constant")!==false)
				{
					$Type="Notice";
					break;
				}
				else
					return;
			default :
				$Type = "Unknown Error";
				break;
		}
		if (j::$Log) j::Log("GeneralError",$Type." : ".$errstr." (".$errfile." at ".$errline.")",4);
		throw new ErrorException($errstr, $code, $errrno, $errfile, $errline);
		
		return PresentError($errno, $errstr, $errfile, $errline, $errcontext);
	}
function PresentError($errno, $errstr, $errfile, $errline, $exception = null)
{
	
	if (reg("jf/setting/PresentErrors")===false ) return false;
	
	?>
	<!-- <?php echo WHOAMI;?> encountered an error -->
	<style>
	.ErrorHolder {
		background-color: #FFFF99;
		color: black;
		width: 100%;
		min-height: 50px;
		-moz-border-radius: 3px;
	}
	
	.ErrorContent {
		-moz-border-radius: 5px;
		border: 5px outset;
		padding: 5px;
		border: 5px outset;
	}
	
	.jFrameworkDetect {
		font-size: 10px;
	}
	
	.CodeTable {
		margin-top: 5px;
		font-size: 14px;
		background-color: white;
		padding: 5px;
		border: 3px inset;
	}
	
	.StackTrace {
		font-size: 12px;
		font-stretch: wider;
		font-family: verdana;
	}
	
	.StackCall {
		color: gray;
	}
	
	.StackClass {
		color: green;
	}
	
	.StackFunction {
		color: blue;
	}
	
	.StackArgs {
		color: red;
	}
	
	.StackFilepath {
		font-size: smaller;
		color: gray;
	}
	
	.StackFilename {
		color: darkblue;
		font-weight: bolder;
	}
	
	.StackLine {
		color: brown;
		font-weight: bold;
		text-decoration: underline;
	}
	
	#tooltip {
		width: 80%;
		min-height: 100px;
		display: none;
		border: 5px double;
		position: fixed;
		background-color: white;
		color: black;
		padding: 5px;
	}
	
	.ErrorString {
		color: white;
		font-family: verdana;
		font-size: 16px;
		background-color: black;
		padding: 4px 10px 5px 10px;
		margin-bottom: 2px;
		margin-top: 2px;
		border: 3px double;
	}
	</style>
	<div class="ErrorHolder" dir='ltr'>
	<div class="ErrorContent"><span class="jFrameworkDetect"><?php echo WHOAMI;?> has
	detected an error in your application:</span> <br />
	<strong>Error <?php
		echo $Type?> <?php
		echo $errno?> </strong> in <span class="StackFilepath"
		style='font-size: medium;'><?php
		echo dirname ( $errfile ) . "/"?><span class='StackFilename'><?php
		echo basename ( $errfile )?></span></span> line <span class='StackLine'><?php
		echo $errline;?></span> :<br />
	<div class='ErrorString'><?php
		echo nl2br($errstr);?></div>
	
	<table class="CodeTable" width="100%">
		<tr>
			<td style="border-right: 1px solid"><code>
		<?php
		$out = $this->FileLines ( $errfile, $errline );
		$txt = "";
		foreach ( $out as $k => $v )
		{
			if ($k == $errline)
				echo "<b>$k</b>" . BR;
			else
				echo $k . BR;
			if ($v == "") $v = " ";
			$txt .= $v;
		}
		?>
		</code></td>
			<td>
	    <?php
		$x = highlight_string ( "<" . "?php" . $txt . "?>", true );
		$l1 = strlen ( '<code><span style="color: #000000">
	<span style="color: #0000BB">&lt;?php' );
		$l2 = strlen ( '<span style="color: #0000BB">?&gt;</span></code>
	' );
		$l = strlen ( $x );
		echo "<code>" . substr ( $x, $l1, $l - $l1 - $l2 ) . "</code>\n";
		
		?>
		</td>
		</tr>
		<tr>
			<td colspan="2">
			<hr />
			<h4>Call Stack (most recent last):</h4>
			<div class="StackTrace"><?php
			
			if ($exception)
				$stack=$exception->getTrace();
			else
				$stack=debug_backtrace ();
			
			$this->PresentStack ($stack);
		?>
		</div>
			</td>
		</tr>
	</table>
	</div>
	<!-- ErrorContent -->
	<div id="tooltip"></div>
	<script>
	$("a[content]").bind("mouseover",displayContent);
	$("a[content]").bind("click",function(){$("#tooltip").fadeOut();return false;});
	function displayContent(e)
	{
		//console.dir(e.target);
		$("#tooltip").html($(e.target).attr("content"))
			//.css({"left":(e.target.offsetLeft-$("#tooltip").css("width"))/2,"top":e.target.offsetTop})
			.css({"right":"0","bottom":"0"})
			.fadeIn();
	}
	$("#tooltip").bind("mouseout",hideContent);
	function hideContent(e)
	{
		$("#tooltip").fadeOut();
	}
	</script></div>
	<!-- ErrorHolder -->
	<?php
	/* Don't execute PHP internal error handler */
	return false;
	
	}
	
	function PresentStack($StackBacktrace)
	{
		$a = $StackBacktrace; 
		$count = count ( $a );
		$a = array_reverse ( $a );
		$depth = 0;
		foreach ( $a as $StackKey => $Stack )
		{
			$depth ++;
			if ($depth > $count - 2) continue; //bypass GeneralError and stack
			$Pre = $depth . str_repeat ( "-", $depth * 2 );
			$v = $Stack;
			{
				$Call = "<span class='StackCall'><span class='StackClass'><b>" . $v ["class"] . "</b></span> " . htmlspecialchars ( $v ["type"] ) . " <span class='StackFunction'><b>" . $v ["function"] . "</b></span></span>";
				$Call .= "(<span class='StackArgs'>";
				$flag = false;
				foreach ( $v ['args'] as $arg )
				{
					
					if ($flag) $Call .= ", ";
					$flag = true;
					if (is_object ( $arg ))
					{
						$x = get_class ( $arg );
						if (method_exists ( $arg, "__toString" ))
							$x = '"' . htmlspecialchars ( $arg->__toString () ) . '"';
						else
						{
							$x = " <a href='#' content='" . htmlspecialchars ( nl2br ( str_replace ( " ", "&nbsp;", print_r ( $arg, true ) ) ) ) . "'>" . htmlspecialchars (get_class ( $arg ) ) . "</a> ";
						}
					}
					elseif (is_resource ( $arg ))
					{
						$x = " <a href='#' content='" . htmlspecialchars ( nl2br ( str_replace ( " ", "&nbsp;", print_r ( $arg, true ) ) ) ) . "'>[Resource]</a> ";
					}
					elseif (is_array ( $arg ))
					{
						$x = " <a href='#' content='" . htmlspecialchars ( nl2br ( str_replace ( " ", "&nbsp;", print_r ( $arg, true ) ) ) ) . "'>[Array]</a> ";
					}
					else
					{
						$x = '"' . $arg . '"';
					}
					$Call .= $x;
				}
				$Call .= "</span>)";
			
			}
			$Line = $v ['line'];
			$File = $v ['file'];
			$o = $v ['object'];
			$Path = dirname ( $File );
			$File = basename ( $File );
			
			$ErrorPosition = "in <strong>{$File}</strong> (<span class='StackFilepath'><b>" . $Path . "/" . "</b><span class='StackFilename'>{$File}</span></span>) at line <span class='StackLine'>$Line</span> ";
			echo $Pre . $Call . BR;
			echo $Pre . $ErrorPosition . BR . BR;
		}
	}
	function ErrorShutdown()
	{
		$isError = false;
		$error = error_get_last ();
		if ($error)
		{
			switch ($error ['type'])
			{
				case E_ERROR :
				case E_CORE_ERROR :
				case E_PARSE :
				case E_COMPILE_ERROR :
				case E_USER_ERROR :
// 				case E_NOTICE:
// 				default:
					$isError = true;
				break;
			}
		}
		if ($isError)
		{
			$this->GeneralError ( $error ['type'], $error ['message'], $error ['file'], $error ['line'] );
			echo "<h1>Fatal Error</h1>
			<p>Some fatal error caused the application to quit unexpectedly. The error details have been successfully
			logged for the system administrator to review them later.
			We're sorry for the inconvenience.</p>";
			if (j::$Log) j::Log("ShutdownError","Error type ".$error['type']." : ".$error['message']." (".$error['file']." at ".$error['line'].")",5);
			exit(1);
		}
	}
	
	
	function HandleException(Exception $e)
	{
//		print_r($e);
		$this->PresentError($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(),$e);
	}
}
?>