<?php
class GalleryManagerPage extends Page {

	static $db = array(
		'GalleryManagerTypeJS' => "Int"
	);
	
	static $has_one = array(
		'GalleryManagerSet' => 'GalleryManagerSet'
	);
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$ob_GMS = DataObject::get('GalleryManagerSets')->toDropdownMap('ID','Title','Selecione Uno');
		$fields->addFieldToTab('Root.Content.Galeria',new DropdownField('GalleryManagerSetID','Gallery',$ob_GMS));
		$fields->addFieldToTab('Root.Content.GaleriaSettings',
			new OptionsetField('GalleryManagerTypeJS','Libreria',
			array(
					"1" => 'noobSlider',
					"2" => 'Slideshow 2'
			),"1")
		);
		return $fields;		
	}
}

class GalleryManagerPage_Controller extends Page_Controller {

	public function init() {
		parent::init();
		$type = parent::__get('GalleryManagerTypeJS');
		Requirements::javascript(GALLERYMANAGERPATH_THIRDPARTY. "/mootools/mootools-1.2.4-core.js");
		if ($type == "1") {
			Requirements::javascript(GALLERYMANAGERPATH_THIRDPARTY."/noobSlide/_class.noobSlide.packed.js");
			Requirements::css(GALLERYMANAGERPATH_THIRDPARTY."/noobSlide/css/style.css");
		}
		if ($type == "2") {
			Requirements::javascript(GALLERYMANAGERPATH_THIRDPARTY."/Slideshow2/slideshow.js");
			Requirements::css(GALLERYMANAGERPATH_THIRDPARTY."/Slideshow2/css/slideshow.css");
		}
	}
}