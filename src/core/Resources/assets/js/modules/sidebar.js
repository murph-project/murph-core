const $ = require('jquery')

const SidebarOpener = () => {
  $('.sidebar-toggler .btn').click(() => {
    $('.sidebar').toggleClass('is-open')
  })
}

module.exports = SidebarOpener
