<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class CheckTest extends TestCase {

	/*
		Test class definition
	*/	
	public function testClassDefinition() : void {
		$obj = new Check("id","name","desc","xpath","type");
		$this->assertTrue(
			method_exists($obj, 'fromArray'), 
			'Class does not have method fromArray'
		);
		$this->assertTrue(
			method_exists($obj, 'listFromArray'), 
			'Class does not have method listFromArray'
		);
	}

	/*
		Test constructor
	*/	
	public function testConstructor() : void {
		
		$obj = new Check("id","name","desc","xpath","type");
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('name', $obj->name);
		$this->assertEquals('desc', $obj->description);
		$this->assertEquals('xpath', $obj->xpath);
		$this->assertEquals('type', $obj->type);

	}

	public function testConstructorEmptyID() : void {
		$this->expectException(Exception::class);
		$obj = new Check("","name","desc","xpath","type");
	}

	public function testConstructorEmptyName() : void {
		$this->expectException(Exception::class);
		$obj = new Check("id","","desc","xpath","type");
	}

	public function testConstructorEmptyDescription() : void {
		$this->expectException(Exception::class);
		$obj = new Check("id","name","","xpath","type");
	}

	public function testConstructorEmptyXPath() : void {
		$this->expectException(Exception::class);
		$obj = new Check("id","name","desc","","type");
	}

	public function testConstructorEmptyType() : void {
		$this->expectException(Exception::class);
		$obj = new Check("id","name","desc","xpath","");
	}

	/*
		Test fromArray()
	*/	
	public function testFromArray() : void {
		
		$obj = Check::fromArray(["checkid"=>"id","name"=>"name","description"=>"desc","xpath"=>"xpath","checktype"=>"type"]);
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('name', $obj->name);
		$this->assertEquals('desc', $obj->description);
		$this->assertEquals('xpath', $obj->xpath);
		$this->assertEquals('type', $obj->type);
	}

	/*
		Test listFromArray()
	*/	
	public function testListFromArray() : void {
		
		$objs = Check::listFromArray([
			["checkid"=>"id1","name"=>"name1","description"=>"desc1","xpath"=>"xpath1","checktype"=>"type1"],
			["checkid"=>"id2","name"=>"name2","description"=>"desc2","xpath"=>"xpath2","checktype"=>"type2"]
		]);
		$this->assertEquals(2, sizeof($objs));

		$this->assertEquals('id1', $objs[0]->id);
		$this->assertEquals('name1', $objs[0]->name);
		$this->assertEquals('desc1', $objs[0]->description);
		$this->assertEquals('xpath1', $objs[0]->xpath);
		$this->assertEquals('type1', $objs[0]->type);

		$this->assertEquals('id2', $objs[1]->id);
		$this->assertEquals('name2', $objs[1]->name);
		$this->assertEquals('desc2', $objs[1]->description);
		$this->assertEquals('xpath2', $objs[1]->xpath);
		$this->assertEquals('type2', $objs[1]->type);

	}

	/*
		Test setter and getters
	*/
	public function testSettersAndGetters() : void {
		
		$obj = new Check("id","name","desc","xpath","type");
		$obj->id = "id";
		$obj->name = "name";
		$obj->description = "desc";
		$obj->xpath = "xpath";
		$obj->type = "type";
		$this->assertEquals('id', $obj->id);
		$this->assertEquals('name', $obj->name);
		$this->assertEquals('desc', $obj->description);
		$this->assertEquals('xpath', $obj->xpath);
		$this->assertEquals('type', $obj->type);

	}

	/*
		Test domain functions
	*/
	public function testSetDocumentByTagName() : void {
		
		$html = "<html><head><title>Title</title></head><body></body></html>";
		$check = new Check("id","name","desc","//html/head/title","exists");
		$check->setDocument($html);
		$nodes = $check->nodes;
		$this->assertCount(1, $nodes);
		$this->assertInstanceOf(DOMNodeList::class, $nodes);
		$this->assertInstanceOf(DOMNode::class, $nodes[0]);
		$this->assertEquals('title', $nodes[0]->nodeName);
		$this->assertEquals('title', $nodes[0]->tagName);
		$this->assertEquals('Title', $nodes[0]->nodeValue);
		$this->assertEquals('Title', $nodes[0]->textContent);

	}

	public function testSetDocumentByAttributeValue() : void {
		
		$html = "<html><head><title>Title</title></head><body id='foo'>Body</body></html>";
		$check = new Check("id","name","desc","//html/body[@id='foo']","exists");
		$check->setDocument($html);
		$nodes = $check->nodes;
		$this->assertCount(1, $nodes);
		$this->assertInstanceOf(DOMNodeList::class, $nodes);
		$this->assertInstanceOf(DOMNode::class, $nodes[0]);
		$this->assertEquals('body', $nodes[0]->nodeName);
		$this->assertEquals('body', $nodes[0]->tagName);
		$this->assertEquals('Body', $nodes[0]->nodeValue);
		$this->assertEquals('Body', $nodes[0]->textContent);

	}

	public function testCheckWithoutSetDocument() : void {
		
		$check = new Check("id","name","desc","xpath","exists");
		$this->expectException(Exception::class);
		$nodes = $check->check();

	}

	public function testCheckExistsPass() : void {
		
		$html = "<html><head><title>Title</title></head><body id='foo'>Body</body></html>";
		$check = new Check("id","name","desc","//html/body[@id='foo']","exists");
		$check->setDocument($html);
		$this->assertEquals(true, $check->check());

	}

	public function testCheckExistsByTagNameFail() : void {
		
		$html = "<html><head><title>Title</title></head><body>Body</body></html>";
		$check = new Check("id","name","desc","//html/body/div","exists");
		$check->setDocument($html);
		$this->assertEquals(false, $check->check());

	}

	public function testCheckExistsByAttributeFail() : void {
		
		$html = "<html><head><title>Title</title></head><body>Body</body></html>";
		$check = new Check("id","name","desc","//html/body[@id='foo']","exists");
		$check->setDocument($html);
		$this->assertEquals(false, $check->check());

	}

}

?>