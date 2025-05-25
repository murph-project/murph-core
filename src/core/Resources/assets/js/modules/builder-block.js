const Vue = require('vue').default

const BuilderBlock = require('../components/builder-block/BuilderBlock').default

module.exports = () => {
  const wrappers = document.querySelectorAll('.builder-widget')

  wrappers.forEach((wrapper) => {
    const component = wrapper.querySelector('.builder-widget-component')

    return new Vue({
      el: component,
      template: `<BuilderBlock
        :initialValue="value"
        :allowedWidgets="allowedWidgets"
        name="${component.getAttribute('data-name')}"
        id="${component.getAttribute('data-id')}"
      />`,
      data() {
        return {
          value: JSON.parse(component.getAttribute('data-value')),
          allowedWidgets: JSON.parse(component.getAttribute('data-allowedwidgets')),
        }
      },
      components: {
        BuilderBlock
      }
    })
  })
}
