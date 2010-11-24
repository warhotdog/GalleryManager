<?php
class GalleryManagerPageDecorator extends DataObjectDecorator {
	
	//protected $GMECSID;
	
    function extraStatics() {
        return array(
                'db' => array(
                        'GMEyeCatcher' => 'Boolean'
                ),
                'has_one' => array(
                		'GalleryManagerSet' => "GalleryManagerSets",
                		'GMECS' => "GMEyeCatherSettings"
                )
        );
    }
    
    function updateCMSFields(FieldSet &$fields) {
    	$Curr = Controller::curr()->currentPage();
    	Requirements::javascript(GALLERYMANAGERPATH.'/javascript/GalleryManagerDecorator.js');
    	
        $fields->addFieldToTab("Root.Behaviour",
                new CheckboxField("GMEyeCatcher",'¿Usar GM EyeCatcher?'),'ShowInMenus');    
                  
        if ($Curr->GMEyeCatcher) {
        	$ob_GMS = DataObject::get('GalleryManagerSets')->toDropdownMap('ID','Title','Selecione Uno');
        	$fields->addFieldToTab('Root.Content.EyeCatcher',new DropdownField('GalleryManagerSetID','Gallery',$ob_GMS));
        	
        	
        	$fields->addFieldsToTab('Root.Content.EyeCatcher',$this->owner->GMECS()->getCMSFields());
        	//$fields->addFieldsToTab('Root.Content.EyeCatcher',singleton('GMEyeCatherSettings')->getCMSFields());
        	//Debug::show(singleton('GMEyeCatherSettings'));
        	//Debug::show($this->owner);
        }        
        return $fields;
    }
    
    function onBeforeWrite() {
    	parent::onBeforeWrite();
    	//Debug::show($this->owner);
    	if ($this->owner->GMECSID == '0') {
	    	$GMECS = DataObject::get_by_id('GMEyeCatherSettings',$this->owner->GMECSID);
	    	if ($GMECS) {
	    		Debug::show($GMECS);
	    		$GMECS->Height = $this->owner->Height;
	    		$GMECS->Width = $this->owner->Width;
	    		$GMECS->write();
	    	}
	    	else {
	    		$GMECS = new GMEyeCatherSettings();
	    		Debug::show($GMECS);
	    		$GMECS->Height = $this->owner->Height;
	    		$GMECS->Width = $this->owner->Width;
	    		$GMECS->write();
	    		$this->owner->GMECSID = $GMECS->ID;
	    		$this->owner->write();
	    	}
    	}
    }
    
    function onBeforeDelete() {
    	$this->owner->GMECS()->delete();
    	parent::onBeforeDelete();
    }
	
}