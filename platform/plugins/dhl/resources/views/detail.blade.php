<div class="shipment-detail mb-3 card">
    <div class="card-body">
        <h5>Shipment Information</h5>
        <div class="row">
            <div class="col-12">
                <div class="mt-3 mb-3">
                    <button class="btn btn-info btn-get-dhl-rate">Get rates for this order</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dhl-rates mt-3"></div>

<script>
    'use strict';
    $(document).ready(function () {
        $(document).on('click', '.btn-get-dhl-rate', function (event) {
            event.preventDefault();
            $('.dhl-rates').html('');
            
            $.ajax({
                type: 'GET',
                url: '{{ route(app(\Botble\DHL\DHL::class)->getRoutePrefixByFactor() . 'dhl.rates', $shipment->id) }}',
                beforeSend: () => {
                    $('.btn-get-dhl-rate').addClass('button-loading');
                },
                success: (response) => {
                    if (response.error) {
                        Botble.showError(response.message);
                    } else {
                        $('.dhl-rates').html(response.data.html);
                    }
                },
                error: (error) => {
                    Botble.handleError(error);
                },
                complete: () => {
                    $('.btn-get-dhl-rate').removeClass('button-loading');
                }
            });
        });

        $(document).on('change', 'input[name=dhl-radio-option]', function () {
            const _self = $(this);
            const rate = _self.closest('form').find('input[name=rate_id]');
            const serviceName = _self.closest('form').find('input[name=service_name]');
            const amount = _self.closest('form').find('input[name=amount]');

            rate.val(_self.val());
            serviceName.val(_self.data('service-name'));
            amount.val(_self.data('amount'));
        });

        $(document).on('click', '.get-dhl-rates', function (event) {
            event.preventDefault();
            $('.btn-get-dhl-rate').trigger('click');
        });

        $(document).on('click', '.create-transaction', function (event) {
            event.preventDefault();
            const _self = $(this);
            
            $.ajax({
                type: 'POST',
                url: '{{ route(app(\Botble\DHL\DHL::class)->getRoutePrefixByFactor() . 'dhl.transactions.create', $shipment->id) }}',
                beforeSend: () => {
                    _self.addClass('button-loading');
                },
                success: (response) => {
                    if (response.error) {
                        Botble.showError(response.message);
                    } else {
                        Botble.showSuccess(response.message);
                        $('#dhl-view-n-create-transaction').modal('hide');
                        $('#dhl-view-n-create-transaction').find('.modal-body').html('');
                    }
                },
                error: (error) => {
                    Botble.handleError(error);
                },
                complete: () => {
                    _self.removeClass('button-loading');
                },
            });
        });
    });
</script> 