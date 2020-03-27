let app = {
    init: function() {
        console.log('init');
        $('.delete-btn').click(app.deleteHandleClick);
    },

    deleteHandleClick : function (event) {
        if(!confirm('merci de confirmer la suppression définitive.')) {
            event.preventDefault();
        }
    }
}
$(app.init);
