const $ = require('jquery')

const Sidebar = () => {
  const menu = document.querySelector('.sidebar')

  if (!menu) {
    return
  }

  const stickyMenu = menu.querySelector('.sidebar-sticky')
  const items = stickyMenu.querySelectorAll('a.nav-link')
  const currentItem = menu.querySelector('.nav-link.active')

  items.forEach((item) => {
    item.addEventListener('click', () => {
      localStorage.setItem('sidebar-item-top', stickyMenu.scrollTop)
    })
  })

  const toggler = menu.querySelector('.sidebar-toggler .btn')

  toggler.addEventListener('click', () => {
    menu.classList.toggle('is-open')
  })

  if (currentItem) {
    stickyMenu.scrollTo({
      top: localStorage.getItem('sidebar-item-top') ?? 0,
      behavior: 'smooth'
    })
  }
}

module.exports = Sidebar
