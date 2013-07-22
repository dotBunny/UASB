<?php

require('libs/Smarty.class.php');

class SmartyFlashbang extends Smarty
{
	public function __construct()
	{
		$this->Smarty();
		
		$this->template_dir = ROOT . '_tpl';
		$this->compile_dir = ROOT . '_tpl_c';
		$this->config_dir = ROOT . '_configs';
		$this->cache_dir = ROOT . '_cache';
		//$this->caching = true;
	}
	
}