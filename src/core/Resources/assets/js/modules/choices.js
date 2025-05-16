const Choices = require('choices.js')

module.exports = () => {
  document.querySelectorAll('*[data-jschoice]').forEach((item) => {
    return new Choices(item, {
      searchFields: ['label'],
      removeItemButton: true,
    })
  })
}
