<?php
class Pen{
	protected $color = "blue";

	public function __construct($color=null){
		if (is_string($color)==true&&strlen($color)>2) {$this->color=$color;}
	}

	public function setColor($color){
		$this->color=$color;
	}

	public function getColor(){
		return $this->color;
	}

	public function write($text=null){
		if(isset($text)==true){
			print( "<span style=\"color:".$this->getColor()." \">$text</span>");
		}
	}
}

$newPen = new Pen();
print($newPen->getColor());
$newPen->write("some text");

?>
