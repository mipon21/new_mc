'use strict'

$(() => {
    // Desktop language switcher
    $('.tp-header-lang-toggle')
        .off('click')
        .on('click', (event) => {
            event.preventDefault()
            let _self = $(event.currentTarget)
            let dropdown = _self.siblings('ul')

            if (_self.hasClass('active')) {
                dropdown.hide()
                _self.removeClass('active')
            } else {
                dropdown.show()
                _self.addClass('active')
            }
        })

    // Mobile language switcher
    $('.tp-lang-toggle')
        .off('click')
        .on('click', (event) => {
            event.preventDefault()
            let _self = $(event.currentTarget)
            let dropdown = _self.siblings('.tp-lang-list')

            if (_self.hasClass('active')) {
                dropdown.hide()
                _self.removeClass('active')
            } else {
                dropdown.show()
                _self.addClass('active')
            }
        })

    // Close dropdowns when clicking outside
    $(document).on('click', (event) => {
        let target = $(event.target)
        
        // Handle desktop dropdown
        if (!target.closest('.tp-header-lang').length) {
            $('.tp-header-lang-toggle').removeClass('active')
            $('.tp-header-lang ul').hide()
        }
        
        // Handle mobile dropdown
        if (!target.closest('.offcanvas__select.language').length) {
            $('.tp-lang-toggle').removeClass('active')
            $('.tp-lang-list').hide()
        }
    })
})
