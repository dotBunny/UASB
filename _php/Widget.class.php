<?php

class Widget
{
	protected $smarty;
	protected $template;
	
	function __construct()
	{
		$this->smarty = new SmartyFlashbang();
	}

	function __toString()
	{
		return $this->smarty->fetch($this->template);
	}	
	
}

?>