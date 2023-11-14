const $ = require('jquery')

const Sidebar = () => {
  const menu = document.querySelector('.sidebar')

  if (!menu) {
    return
  }

  const stickyMenu = menu.querySelector('.sidebar-sticky')
  const currentItem = menu.querySelector('.nav-link.active')
  const toggler = menu.querySelector('.sidebar-toggler .btn')

  toggler.addEventListener('click', () => {
    menu.classList.toggle('is-open')
  })

  stickyMenu.scroll({top: currentItem.offsetTop - menu.scrollTop})
}

module.exports = Sidebar
