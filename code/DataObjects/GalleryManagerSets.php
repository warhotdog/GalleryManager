<?php

class GalleryManagerSets extends DataObject {
    
    static $db = array(
        'Title' => 'HTMLVarchar',
        'Description' => 'HTMLVarchar'
    );

    public function onBeforeDelete() {
     
     $cnn = new SQLQuery();
     $cnn->from('GalleryManagerImages');
     $cnn->where("IDSet = '$this->ID'");
     $cnn->delete = true;
     $cnn->execute();
     parent::onBeforeDelete();

    }
    
}

?>
