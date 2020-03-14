jQuery(($) => {
    $('.joinpoints-form__submit').removeAttr('disabled');
    $('.joinpoints-form').on('submit', async (e) => {
        e.preventDefault();
        const { ajax_url } = window.joinpoints;
        const data = {
            action: 'jointpoins-form__submit',
            data: $(e.currentTarget).find('[name=test]').val(),
        };
        try {
            const response = await $.post(ajax_url, data);
            // TODO : show success page to user
            console.log('success', response);
        } catch (e) {
            // TODO : show error message to user
            console.log('unable to send', e);
        }
    });
});
