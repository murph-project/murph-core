const $ = require('jquery')

const openModal = function (url, createModal) {
  if (createModal) {
    var id = 'modal-container-' + parseInt(Math.floor(Math.random() * 1000))
  } else {
    var id = 'modal-container'
  }

  let container = $(`#${id}`)
  const body = $('body')
  let doTrigger = true

  if (!container.length) {
    let doTrigger = false
    container = $(`<div id="${id}" class="modal">`)

    body.append(container)
  }

  const loader = $('<div style="position: absolute; top: 25vh; left: 50vw; z-index: 2000">')
  loader.html('<div class="spinner-border text-primary" role="status"><span class="sr-only">Loading...</span></div>')
  body.append(loader)

  container.html('')

  $(container).modal('show')

  container.load(url, function () {
    loader.remove()

    if (doTrigger) {
       container.trigger('shown.bs.modal')
    }
  })
}

module.exports = function () {
  let click = 0
  const body = $('body')

  body.on('hidden.bs.modal', '.modal', (e) => {
    if ($('.modal.show').length) {
      $('body').addClass('modal-open')
    }
  })

  body.on('click', '*[data-modal]', (e) => {
    e.preventDefault()
    e.stopPropagation()

    ++click

    window.setTimeout(() => {
      if (click !== 1) {
        click = 0

        return
      }

      click = 0

      let element = $(e.target).is('[data-modal]') ? $(e.target) : $(e.target).parents('*[data-modal]').first()
      let url = element.attr('data-modal')
      let createModal = element.is('[data-modal-create]')

      openModal(url, createModal)
    }, 250)
  })

  const urlParams = new URLSearchParams(window.location.search)
  const dataModal = urlParams.get('data-modal')

  if (dataModal) {
    openModal(dataModal)
  }
}
