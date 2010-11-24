<?php
class GMEyeCatherSettings extends DataObject {
	static $db = array(
		'Height' =>		'Varchar',
		'Width'	=>		'Varchar'
	);

	public function getCMSFields() {
		//Debug::show($this);
		$fields = new FieldSet();
		
		$fields->push(new TextField('Height','Height',$this->Height));
		$fields->push(new TextField('Width','Width',$this->Width));
		
		return $fields;	
	}
	


} 