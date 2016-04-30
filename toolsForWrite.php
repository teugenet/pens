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


class Autopen extends Pen {
	protected $fullWrite=256;
	protected $leftWrite;
	protected $usage = 0;

	public function __construct($fullWrite=0, $color=null){
		parent::__construct($color);

		if(isset($fullWrite)==true && is_numeric($fullWrite)==true && $fullWrite!=0) {$this->fullWrite=round($fullWrite,0);}

		$this->leftWrite=$this->fullWrite;
	}

	public function getUsage(){
		return round($this->usage, 2)." %";
	}

	public function write($text=null){
		$stringSymbols=$this->canBeWrite($text);

		parent::write($stringSymbols["toWrite"]);
		$this->setLeftWrite($this->getLeftWrite() - $stringSymbols["count"]);
		$this->addUsage();
	}

	protected function canBeWrite($text){
		$stringSymbols = array("toWrite"=>"","count"=>0);
		$count=0;

		if($this->getLeftWrite()>0){
			for($a=0;$a<strlen($text);$a++){
				$character=$text[$a];
				if(preg_match("/[[:space:]]+/", $character)==false) {
					if(preg_match("/[[:alpha:]]+/", $character)==true) {
						$count++;
					}
						else {
							$character.=$text[++$a];
							$count++;
						}
				}

				$stringSymbols["toWrite"].=$character;
				$stringSymbols["count"]=$count;
				if($this->getLeftWrite()==$count) {break;}
			}
		}

		return $stringSymbols;
	}

	public function addInk($value=null) {
		$this->setUsage($value);
		$this->setLeftWrite();
	}

	protected function setLeftWrite($characters=null){
		if(isset($characters)==true && is_numeric($characters)==true) {$this->leftWrite=$characters;}
			else {$this->leftWrite=round($this->fullWrite*(1-$this->usage/100),0);}
	}

	protected function getLeftWrite(){
		return $this->leftWrite;
	}

	protected function setUsage($value=null) {
		(isset($value) == true && $value>0 && $value<$this->usage) ? $this->usage-=$value : $this->usage=0;
	}

	protected function addUsage() {
		$this->usage=100-$this->getLeftWrite()*100/$this->fullWrite;

		return $this;
	}
}

/*$ap = new Autopen(20);
$ap->write("as far as i know, рвловіла івдадіва");
echo $ap->getUsage();
$ap->addInk(25);
$ap->write("as far as i know, рвловіла івдадіва");
echo $ap->getUsage();*/


?>
