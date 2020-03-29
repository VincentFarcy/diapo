let app = {
    init: function() {
        // console.log('init');
        
        $('.delete-form').submit(app.deleteHandleSubmit);
        
        $('button.close').click(app.toggleCloseClass);

        $('.filter').change(app.submitFilterForm);
    },

    deleteHandleSubmit : function (event)
    {
        if(!confirm('merci de confirmer la suppression définitive.')) {
            event.preventDefault();
        }
    },

    toggleCloseClass : function (event)
    {
        const $alert = $(event.currentTarget).closest('.alert');
        if ($alert) {
            $alert.toggleClass('d-none');
        }
    },

    submitFilterForm : function (event)
    {
        const $select = $(event.currentTarget);
        const val = $select.val();
        const $form = $select.closest('form');
        var action = $form.attr('action')
        action = action.substr(0, action.length - 1) + val
        $form.attr('action', action);
        $form.submit();
    }
}
$(app.init);