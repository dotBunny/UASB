<?php

class BreadCrumbs
{
	private $crumbs;
	
	private $textOverride;
	
	/**
	* @desc Always start off with home
	*/
	public function __construct()
	{
		
	}
	
	/**
	* @desc Add the bread crumbs for game (genre->game)
	*/
	public function addGame($game, $link = false)
	{
		$this->addGenre($game->getParentGenre());
		
		// add the specific genre if different from parent genre
		if($game->getParentGenre() != $genreid = $game->getSpecificGenre())
			$this->crumbs[] = array(Genre::getName($genreid), Genre::getURL($genreid));
		
		if($link)
			$this->crumbs[] = array($game->getName(true, true), $game->getGameURL());
		
		//$this->crumbs[] = array($game->getName() . "", "");
		
		// for now trying to reduce instances of the name
		//$this->crumbs[] = array("This Game", "");
	}
	
	/**
	* @desc Add a genre to the breadcrumbs
	*/
	public function addGenre($genreid)
	{
		//$this->crumbs[] = array("Home", "/");
		
		$this->crumbs[] = array("Download Games", "/");
		
		// get the genre name and url
		$this->crumbs[] = array(Genre::getName($genreid), Genre::getURL($genreid));
	}
	
	/**
	* @desc Add a breadcrumb by hand
	*/
	public function add($name, $url)
	{
		$this->crumbs[] = array($name, $url);
	}
	
	/**
	* @desc Return the text for our breadcrumbs
	*/
	public function fetch()
	{
		if(!empty($this->textOverride))
			return htmlspecialchars($this->textOverride);
		
		$display = "";
		
		for($i = 0; $i < count($this->crumbs); $i++)
		{
			$url = $this->crumbs[$i][1];
			$name = $this->crumbs[$i][0];
			
			if(empty($url))
				$display .= "$name";
			else
				$display .= "<a class=\"breadcrumbs\" href=\"$url\">$name</a>";
			
			if($i < count($this->crumbs) - 1)
				$display .= ' &raquo; ';
		}
		
		return $display;
	}
	
	/**
	* @desc Manually specify some text to override anything
	*/
	public function setText($text)
	{
		$this->textOverride = $text;
	}
}

?>