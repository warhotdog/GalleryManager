<?php
class GalleryManagerAdmin extends LeftAndMain {

    static $url_segment = 'GalleryManager';
    static $menu_title = "Gallery Manager";
    static $menu_priority = '30';
    static $url_priority = '41';
    static $url_rule = '/$Action/$ID/$OtherID';
    static $tree_class = 'GalleryManagerSets';

    function init() {
        parent::init();
         Requirements::javascript ("GalleryManager/javascript/GalleryManagerAdmin_left.js");
    }
    function Link($action = null) {
        return "admin/GalleryManager/$action";
    }


    function getEditForm($id = null) {
        //Debug::show($id);
        if (is_null($id)) return false;
        if ($id == '0' ) return false;
        
        $validator = new RequiredFields('Title','Description');

        
        if ($id != 'new') {
        $GalleryTab = new Tab('Gallery',
                           new ComplexTableField(
                                   $this,
                                   'GalleryManagerItems',
                                   'GalleryManagerImages',
                                   Array(
                                       'Thumbnail' => 'Imagen',
                                       'Title' => 'Titulo',
                                       'Description' => 'Descripcion'),
                                   'getCMSFields_forpopup',
                                   "IDSet = '$id'"
                                   )
                            );
        } else { $GalleryTab = new Tab ('Gallery',new LiteralField('DoSave', '<label>Primero Ingrese el Nombre y Descripcion de la galeria</label>')); }

        $fields = new FieldSet(
                new TabSet('Root',
                    new Tab(
                            'Main',
                            new HiddenField('ID','ID',$id),
                            new TextField('Title','Titulo'),
                            new TextField('Description','Descripcion')
                            ),
                        $GalleryTab
                        )
                );

        if ($id == 'new') {
            $actions = new FieldSet(
                        new FormAction('DoSaveNew','Guardar')
                    );
        } else {
            $actions = new FieldSet(
                    new FormAction('DoSave','Actualizar'),
                    new FormAction('DoDelete', 'Eliminar')
                    );
        }

        $form = new Form($this,'EditForm',$fields,$actions,$validator);

        if ($id != 'new') {
            $Curr = DataObject::get_by_id('GalleryManagerSets', $id);
            $form->loadDataFrom(array(
                'ID' => $Curr->ID,
                'Title' => $Curr->Title,
                'Description' => $Curr->Description
            ));
        }

        return $form;
    }

    //No usar esta funcion todavia
    /*
    function LeftMenuForm() {
        $fields = new FieldSet(
                    new HiddenField('ID','id#','new')
                );
        $actions = new FieldSet (
                new FormAction('DoNewSet','Nueva Coleccion')
                );

        $form = new Form($this,'LeftMenuForm',$fields,$actions);
        return $form;
    }
    */
    
    	public function SiteTreeAsUL() {
		$siteTree = "";
		$GallerySets = DataObject::get("GalleryManagerSets");
                if ($GallerySets) {
        		foreach($GallerySets as $ID => $data) {
	        		$siteTree .= "<li id=\"record-" . $data->ID . "\" class=\"" . $data->class . " " .
		        	($data->Locked ? " nodelete" : "") . "\" >" .
        			"<a href=\"" . $this->Link("show/".$data->ID) . "\" >" . $data->Title . "</a>";
	        	}
                }

		$siteTree = "<ul id=\"sitetree\" class=\"tree unformatted\">" .
						"<li id=\"record-0\" class=\"Root nodelete\">" .
							"<a href=\"admin/GalleryManager/show/0\" ><strong>".'Gallery Manager'."</strong></a>"
							. $siteTree .
						"</li>" .
					"</ul>";
		return $siteTree;
	}

        public function doNewSet() {
            	$id = $_REQUEST['ID'];
		FormResponse::add("$('Form_EditForm').getPageFromServer('$id');");
		return FormResponse::respond();
        }

        public function DoSaveNew($data,$form) {
            $Submit = new GalleryManagerSets();
            $Submit->Status = 'Saved (new)';
            $form->saveInto($Submit);
            if ($Submit->write()) {
                FormResponse::status_message('Salvado', 'good');
                FormResponse::update_status($Submit->Status);
                $title = Convert::raw2js($Submit->Title);
		$response = <<<JS
var newNode = $('sitetree').createTreeNode($Submit->ID, "$title", "$Submit->class");
$('sitetree').appendTreeNode(newNode);
JS;
                FormResponse::add($response);
                Session::set('GalleryManagerPage', $Submit->ID);
                FormResponse::get_page($Submit->ID);
            } else {
                FormResponse::status_message('Error al salvar','bad');

            }
            return FormResponse::respond();
        }

        public function DoDelete($data,$form) {
            $id = $data['ID'];
            $rs = DataObject::get_by_id('GalleryManagerSets', $id);
            $rs->Status = 'Eliminando';
            $rs->delete();
            FormResponse::status_message('Eliminando');
            FormResponse::update_status($rs->Status);
            $response = $this->deleteTreeNodeJS($rs);
            FormResponse::add($response);
            return FormResponse::respond();
            
        }


}

?>
