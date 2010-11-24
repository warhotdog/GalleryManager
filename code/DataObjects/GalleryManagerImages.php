<?php

class GalleryManagerImages extends DataObject {

    static $db = Array(
            'Title' => 'Varchar(255)',
            'Description' => 'Varchar(255)',
            'IDSet' => 'Int'
    );

    static $has_one = array(
            'Img' => 'Image'
    );

    public function getCMSFields_forpopup() {
        $LeftAndMainController = Controller::curr();
        $Curr = $LeftAndMainController->currentPage();
        $fields = new FieldSet();
        $fields->push(new TextField('Title','Titulo'));
        $fields->push(new TextField('Description','Descripcion'));
        $fields->push(new ImageField('Img','Imagen',null, null, null, "GalleryManager/$Curr->Title"));
        $fields->push(new HiddenField('IDSet','IDSet',$Curr->ID));

        return $fields;

    }

    public function onBeforeWrite() {
        $LeftAndMainController = Controller::curr();
        $Curr = $LeftAndMainController->currentPage();
        if ($this->IDSet == "") $this->IDSet = $Curr->ID;
        if ($this->IDSet == "") user_error('IDSet No se encuentra', E_USER_ERROR);
        parent::onBeforeWrite();

    }

    function getThumbnail() {
        if ($Image = $this->Img()) {
            return $Image->CMSThumbnail();
        }
        else {
            return '(No Image)';
        }
    }



}

?>
