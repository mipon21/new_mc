'use strict';

$(document).ready(function () {
    $(document).on('click', '[data-bs-target="#dhl-view-n-create-transaction"]', function (e) {
        const $this = $(e.currentTarget);
        
        const $modal = $($this.data('bs-target'));
        
        $modal.find('.modal-body').html('');
        
        $.ajax({
            url: $this.data('url'),
            type: 'GET',
            beforeSend: () => {
                $this.addClass('button-loading');
            },
            success: (response) => {
                if (response.error) {
                    Botble.showError(response.message);
                } else {
                    $modal.find('.modal-body').html(response.data.html);
                }
            },
            error: (error) => {
                Botble.handleError(error);
            },
            complete: () => {
                $this.removeClass('button-loading');
            }
        });
    });

    $(document).on('click', '.btn-view-log-file', function (e) {
        e.preventDefault();
        const $this = $(e.currentTarget);
        
        $.ajax({
            url: $this.data('url'),
            type: 'GET',
            beforeSend: () => {
                $this.addClass('button-loading');
            },
            success: (response) => {
                if (response.error) {
                    Botble.showError(response.message);
                } else {
                    const modal = $('#dhl-view-n-create-transaction');
                    modal.find('.modal-body').html(response.data);
                    modal.modal('show');
                }
            },
            error: (error) => {
                Botble.handleError(error);
            },
            complete: () => {
                $this.removeClass('button-loading');
            }
        });
    });
}); 