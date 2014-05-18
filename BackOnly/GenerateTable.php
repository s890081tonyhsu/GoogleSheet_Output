<?php

class htmlElement{
	private $elementTag = ["start" => "", "end" => ""];
	private $innerHTML = "";
	public function printData(){
		return $this->elementTag["start"].$this->innerHTML.$this->elementTag["end"];
	}
	protected function setTag($start,$end){
		$this->elementTag["start"] = $start;
		$this->elementTag["end"] = $end;
	}
	protected function getTag(){
		return $this->elementTag;
	}
	protected function setContent($content){
		$this->innerHTML = $content;
	}
	protected function getContent(){
		return $this->innerHTML;
	}
}

class plainHTML extends htmlElement{
	public function __construct($content,$tag){
		parent::setTag('<'.$tag.'>', '</'.$tag.'>');
		parent::setContent($content);
	}
}

class tableHTML extends htmlElement{
	public function __construct(){
		parent::setTag('<table>','</table>');
	}
	public function createSubTitle($subTitle){
		$content = "<tr>";	
		foreach($subTitle as $th){
			$content = $content."<th>".$th."</th>";
		}
		$content = $content."</tr>";
		parent::setContent($content);
	}
	public function createDetail($detail){
		$content = parent::getContent()."<tr>";	
		foreach($detail as $td){
			$content = $content."<td>".$td."</td>";
		}
		$content = $content."</tr>";
		parent::setContent($content);
	}
}

function createPage($data){
	$dataOutput = array();
	$dataEnd = end($data);
	foreach($data as $item){
		switch($item->{'gsx$datatype'}->{'$t'}){
			case 'title':
				if (isset($tableLast)){
					array_push($dataOutput, $tableLast);
					unset($tableLast);
				}
				$datadetail = new plainHTML($item->{'gsx$datadetail'}->{'$t'}, 'h1');
				array_push($dataOutput, $datadetail);
				break;
			case 'description':
				$datadetail = new plainHTML($item->{'gsx$datadetail'}->{'$t'},'p');
				array_push($dataOutput, $datadetail);
				break;
			case 'subtitle':
				$tableLast = new tableHTML();
				$subTitle = array();
				foreach($item as $subKey => $subObj){
					if(strpos($subKey, 'gsx') !== false && (strpos($subKey, 'gsx$datatype') === false && strpos($subKey, 'gsx$datadetail') === false))
						array_push($subTitle, $subObj->{'$t'});
				}
				$tableLast->createSubTitle($subTitle);
				break;
			case 'detail':
				$subDetail = array();
				foreach($item as $subKey => $subObj){
					if(strpos($subKey, 'gsx') !== false && (strpos($subKey, 'gsx$datatype') === false && strpos($subKey, 'gsx$datadetail') === false))
						array_push($subDetail, $subObj->{'$t'});
				}
				$tableLast->createDetail($subDetail);
				if($item == $dataEnd)
					array_push($dataOutput, $tableLast);
				break;
		}
	}
	foreach($dataOutput as $item){
		echo $item->printData();
	}
}

//$test = new tableHTML();

//$test->createSubTitle(['Title', 'Content', 'Author']);

//$test->createDetail(['Hello', 'Hello, world!', 'OtomeSound']);

//echo $test->printData();

?>
