if(typeof SiteTreeHandlers == 'undefined') SiteTreeHandlers = {};
SiteTreeHandlers.loadPage_url = 'admin/GalleryManager/getitem';
SiteTreeHandlers.controller_url = 'admin/GalleryManager';

_HANDLER_FORMS['addset'] = 'addset_options';

/**
 * New link action
 */
addset = {
    button_onclick : function() {
        addset.showNewForm();
        return false;
    },

    showNewForm : function() {
        Ajax.SubmitForm('addset_options', null, {
            onSuccess : function(response) {
                Ajax.Evaluator(response);
            },
            onFailure : function(response) {
                errorMessage('Error adding link', response);
            }
        });
    }
}
Behaviour.addLoader(function () {
    Observable.applyTo($('addset_options'));
    $('addset').onclick = addset.button_onclick;
    $('addset').getElementsByTagName('button')[0].onclick = function() {
        return false;
    };

});