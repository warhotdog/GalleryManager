<?php
DEFINE('GALLERYMANAGERPATH', basename(dirname(__FILE__)));
DEFINE('GALLERYMANAGERPATH_THIRDPARTY',GALLERYMANAGERPATH . "javascript/thirdparty");

Object::add_extension('SiteTree', 'GalleryManagerPageDecorator');
//Object::add_extension('ContentController' , 'Socializer');
?>
