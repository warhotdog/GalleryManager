<?php
class GalleryManagerPageDecorator extends DataObjectDecorator {
	
	function extraStatics() {
		return array (
		'db' => Array (
			'GMEyeCatcher' => 'Boolean' 
		), 
		'has_one' => array (
			'GalleryManagerSet' => "GalleryManagerSets", 
			'GMECS' => "GMEyeCatherSettings" 
		));
	}
	
	function updateCMSFields(FieldSet &$fields) {
		$Curr = Controller::curr ()->currentPage ();
		Requirements::javascript ( GALLERYMANAGERPATH . '/javascript/GalleryManagerDecorator.js' );
		$fields->addFieldToTab ( "Root.Behaviour", new CheckboxField ( "GMEyeCatcher", '¿Usar GM EyeCatcher?' ), 'ShowInMenus' );
		if ($Curr->GMEyeCatcher) {
			$ob_GMS = DataObject::get ( 'GalleryManagerSets' )->toDropdownMap ( 'ID', 'Title', 'Selecione Uno' );
			$fields->addFieldToTab ( 'Root.Content.EyeCatcher', new DropdownField ( 'GalleryManagerSetID', 'Gallery', $ob_GMS ) );
			$fields->addFieldsToTab ( 'Root.Content.EyeCatcher', $this->owner->GMECS ()->getCMSFields () );
		}
		return $fields;
	}
	
	/*
     *  Por que no se puede usar SS_HTTPRequest::requestVar()?
     *  @TODO Change $_REQUEST[] to SS_HTTPRequest::requestVar() ?
     *  why cant i use this?
     * 
     */
	
	function onBeforeWrite() {
		parent::onBeforeWrite ();
		if ($this->owner->GMECSID) {
			$GMECS = DataObject::get_by_id ( 'GMEyeCatherSettings', $this->owner->GMECSID );
			if ($GMECS) {
				$GMECS->Height = Convert::raw2sql ( $_REQUEST ['Height'] );
				$GMECS->Width = Convert::raw2sql ( $_REQUEST ['Width'] );
				$GMECS->write ();
			} else {
				$GMECS = new GMEyeCatherSettings ();
				$GMECS->Height = Convert::raw2sql ( $_REQUEST ['Height'] );
				$GMECS->Width = Convert::raw2sql ( $_REQUEST ['Width'] );
				$GMECS->write ();
				$this->owner->GMECSID = $GMECS->ID;
				$this->owner->write ();
			}
		}
	}
	
	function onBeforeDelete() {
		$this->owner->GMECS ()->delete ();
		parent::onBeforeDelete ();
	}

}

class GalleryManagerPageExtension extends Extension {
	
	public function EyeCatcherGM() {
		
		$ob = DataObject::get('GalleryManagerImages',"GalleryManagerImages.IDSet ='{$this->owner->GalleryManagerSetID}'");
		
		Requirements::javascript ( GALLERYMANAGERPATH_THIRDPARTY . '/mootools/mootools-1.2.4-core.js' );
		Requirements::javascript ( GALLERYMANAGERPATH_THIRDPARTY . '/Slideshow2/slideshow.js' );
		Requirements::customScript("
		{$this->GMSToJson()}
		if ($('GMEyeCatcher')) {
		 	var myShow = new Slideshow('GMEyeCatcher', GMdata, {
		 		controller: false,
		 		height: ,
		 		thumbnails: false,
		 		width: 710
		 	});
		 }
		");
	
	}
	
	public function GMSToJson() {
		$ob = DataObject::get('GalleryManagerImages',"GalleryManagerImages.IDSet ='{$this->owner->GalleryManagerSetID}'");
		$js = null;
		if ($ob) {
			foreach ( $ob as $ob_item ) {
				$Imagen = $ob_item->Img ();
				if ($Imagen) {
					$js [$Imagen->URL] = array ('caption' => htmlspecialchars ( $ob_item->Description, ENT_NOQUOTES ) );
				}
			}
		} else {
			return false;
		}
		if (is_array ( $js )) {
			return "var GMdata = " . str_ireplace ( "\\", '', Convert::array2json ( $js ) );
		} else {
			return false;
		}
	}

}


