const $ = require('jquery')

const doExpandCollapse = (stmt) => {
  stmt = (stmt == 1)

  const button = $('#page-form-expand')
  const mainForm = $('#page-main-form')
  const metasForm = $('#page-metas-form')

  mainForm
    .toggleClass('col-md-8', !stmt)
    .toggleClass('col-md-12', stmt)

  metasForm
    .toggleClass('d-none', stmt)

  button
    .children()
    .toggleClass('fa-expand-arrows-alt', !stmt)
    .toggleClass('fa-compress-arrows-alt', stmt)

  localStorage.setItem('pageFormExpandStmt', stmt ? 1 : null)
}

const initExpander = () => {
  const button = $('#page-form-expand')

  if (button.length) {
    doExpandCollapse(localStorage.getItem('pageFormExpandStmt'))

    button.click(() => {
      doExpandCollapse(button.children().hasClass('fa-expand-arrows-alt'))
    })
  }
}

module.exports = () => {
  $(() => {
    initExpander()
  })
}
