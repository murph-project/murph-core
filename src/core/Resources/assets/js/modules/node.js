const $ = require('jquery')

const abTestChecker = () => {
  $('body').on('change', '#node_hasAbTest', () => {
    const checkbox = document.querySelector('#node_hasAbTest')
    const code = document.querySelector('#node_abTestCode')

    code.parentNode.parentNode.classList.toggle('d-none', !checkbox.checked)

    if (checkbox.checked) {
      code.setAttribute('required', 'required')
    } else {
      code.removeAttribute('required')
    }
  })
}

module.exports = () => {
  $(abTestChecker)
}
