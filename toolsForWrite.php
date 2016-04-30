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

class MechanicalPencil extends Autopen {
	protected $color = "grey";
	protected $slates = 5;
	protected $isWriteable = false;
	protected $clicksInSlate=10;
	protected $currClicksInSlate;
	protected $charactersToClick=0;
	protected $unfinished;

	public function __construct($slates = 0, $clicksInSlate = 0, $fullWrite=0, $color=null){
		parent::__construct($fullWrite, $color);

		if(is_numeric($slates)==true && $slates>0) {$this->slates=round($slates,0);}
		if(is_numeric($clicksInSlate)==true && $clicksInSlate>5) {$this->clicksInSlate=round($clicksInSlate,0);}
		$this->currClicksInSlate=$this->clicksInSlate;
		$this->charactersToClick=0;
	}

	public function click(){
		if(!$this->isWriteable) {
			if($this->currClicksInSlate==0){
				if($this->slates>1){
					$this->fullWrite-=round($this->fullWrite/$this->clicksInSlate,0);
					$this->slates--;
					$this->currClicksInSlate=10;	
				}
					else {return 0;}	
			}
	
			$this->isWriteable = true;
			$this->charactersToClick=round($this->fullWrite/$this->slates/$this->clicksInSlate,0);
		}
	}

	public function hideSlate(){
		if($this->isWriteable!=false) {$this->isWriteable = false;}
	}

	public function removeSlate() {
		$this->__construct(--$this->slates, $this->clicksInSlate, $this->fullWrite-round($this->fullWrite/($this->slates+1),0));
	}
	
	public function addSlate($number = 1){
		if(is_numeric($number)==true && $number>1){
			$number=round($number,0);
			$this->__construct($this->slates+=$number, $this->clicksInSlate, $this->fullWrite+round($this->fullWrite/($this->slates-$number),0));
		}
	}

	public function write($text=null){
		parent::write($text);

		if($this->charactersToClick==0) {$this->isWriteable = false;}
	}

	public function finishWrite(){
		parent::write($this->unfinished);

		if($this->charactersToClick==0) {$this->isWriteable = false;}
	}

	protected function canBeWrite($text){
		$stringSymbols = array("toWrite"=>"","count"=>0);
		$count=0;

		if($this->charactersToClick>0){
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
				if($this->charactersToClick==$count) {break;}
			}
		}

		$this->charactersToClick-=$stringSymbols["count"];

		if($stringSymbols["toWrite"]!=$text){ $this->unfinished=str_replace($stringSymbols["toWrite"], "", $text);}
			else {$this->unfinished="";}
		
		return $stringSymbols;
	}
}

/*$mp = new MechanicalPencil();
$mp->click();
$mp->write("ss");
$mp->write("asjdflknsadflkdsnflsdglsdmg,lsdk;fljaskndjasbhdjhbfdsmg;gjmflknkjbhgd fkjasgdhksagdku hafjlafj l alsjhaslidjas asasdasdasddas1d");
$mp->click();
$mp->finishWrite();*/


?>
