let app = {
    init: function() {
        // console.log('init');
        
        $('.delete-form').submit(app.deleteHandleSubmit);
        
        $('button.close').click(app.toggleCloseClass);
    },

    deleteHandleSubmit : function (event) {
        if(!confirm('merci de confirmer la suppression définitive.')) {
            event.preventDefault();
        }
    },

    toggleCloseClass : function (event) {
        const $alert = $(event.currentTarget).closest('.alert');
        if ($alert) {
            $alert.toggleClass('d-none');
        }
    }
}
$(app.init);