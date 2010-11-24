<div id="treepanes" style="overflow-y: auto;">
    <h2>Gallery Manager </h2>
	<ul id="TreeActions">
		<li class="action" id="addset"><button>Nueva Coleccion</button></li>
	</ul>
	<div style="clear:both;"></div>
	<form class="actionparams" id="addset_options" style="display: none" action="admin/GalleryManager/doNewSet">
		<input type="hidden" name="ID" value="new" />
		<input type="submit" value="Crear Coleccion" />
	</form>
	$SiteTreeAsUL
</div>