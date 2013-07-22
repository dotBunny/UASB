<?php

class Widgets
{	
	/**
	* @desc Get and return contents for smarty template
	*/
	public static function SmartyStatic($template)
	{
		$smarty = new SmartyFlashbang();
		return $smarty->fetch($template . ".tpl");
	}
}

?>