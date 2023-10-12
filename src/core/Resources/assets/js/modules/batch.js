const $ = require('jquery')

module.exports = () => {
  $('th.crud-batch-column input').change((e) => {
    $('td.crud-batch-column input').prop('checked', $(e.target).is(':checked'))
  })

  const form = $('#form-batch')

  form.submit((e) => {
    const select = document.querySelector('#form-batch-action')
    const options = select.querySelectorAll('#form-batch-action option')
    let doPrevent = true

    options.forEach((option) => {
      if (option.value === select.value && option.getAttribute('data-isglobal') === 'true') {
        doPrevent = false
      }
    })

    if (doPrevent) {
      e.preventDefault()

      const route = form.attr('action')
      const datas = form.serialize()

      form.addClass('is-loading')

      $.post(route, datas)
        .always(() => {
          document.location.reload()
        })
    }
  })
}
