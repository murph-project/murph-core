const $ = require('jquery')
const Vue = require('vue').default
const FileManager = require('../file-manager/FileManager').default

const createModal = function () {
  let container = $('#fm-modal')
  const body = $('body')

  if (!container.length) {
    container = $('<div id="fm-modal" class="modal">')

    body.append(container)
  }

  container.html(`
<div class="modal-dialog modal-dialog-large">
    <div class="modal-content">
        <div class="modal-body">
            <div id="fm-modal-content">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>
`)

  $(container).modal('show')

  return $(container)
}

const fileManagerBrowser = function (callback) {
  const container = createModal()

  const clickCallback = (e) => {
    callback($(e.target).attr('data-value'), {})
    $('div[id^="modal-container-"]').modal('hide')
    container.modal('hide')

    $('body').off('click', '#file-manager-insert', clickCallback)
  }

  $('body').on('click', '#file-manager-insert', clickCallback)

  return new Vue({
    el: '#fm-modal-content',
    template: '<FileManager context="tinymce" />',
    components: {
      FileManager
    }
  })
}

class ImageTool {
  static get isReadOnlySupported () {
    return true
  }

  static get enableLineBreaks () {
    return false
  }

  constructor ({ data, config, api, readOnly }) {
    this.api = api
    this.readOnly = readOnly

    this.nodes = {
      holder: null,
      image: null
    }

    this.data = {
      source: data.source || '',
      caption: data.caption || ''
    }

    this.nodes.holder = this.drawView()
  }

  render () {
    return this.nodes.holder
  }

  save (wrapper) {
    const inputs = wrapper.querySelectorAll('input')

    return {
      source: inputs[0].value,
      caption: inputs[1].value
    }
  }

  drawView () {
    const wrapper = document.createElement('div')
    const image = document.createElement('img')

    const opener = document.createElement('span')

    const inputSource = document.createElement('input')
    const inputCaption = document.createElement('input')

    const that = this

    opener.classList.add('btn', 'btn-sm', 'btn-primary', 'ml-3')
    opener.innerHTML = ImageTool.toolbox.icon

    inputSource.classList.add('cdx-input')
    inputCaption.classList.add('cdx-input', 'mt-3')
    inputCaption.setAttribute('placeholder', 'Caption')

    inputSource.style.width = 'calc(100% - 70px)'

    if (this.data.source) {
        image.src = this.data.source
    } else {
        image.src = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVQIW2NgD9b9DwACcwGHMIH3GgAAAABJRU5ErkJggg=='
    }

    inputSource.value = this.data.source
    inputCaption.value = this.data.caption

    wrapper.classList.add('editorjs-block-image')
    wrapper.appendChild(image)
    wrapper.appendChild(inputSource)
    wrapper.appendChild(opener)
    wrapper.appendChild(inputCaption)

    opener.addEventListener('click', () => {
        fileManagerBrowser((src) => {
            inputSource.value = src
            image.src = src
        })
    })

    inputSource.addEventListener('change', () => {
        that.data.source = inputSource.value

        if (that.data.source) {
            image.src = that.data.source
        }
    })

    inputCaption.addEventListener('change', () => {
        that.data.caption = inputCaption.value
    })

    this.nodes.image = image

    return wrapper
  }

  onPaste (event) {
  }

  get data () {
    return this._data
  }

  set data (data) {
    this._data = data

    if (this.nodes.image) {
      this.nodes.image.src = data.source
    }
  }

  static get toolbox () {
    return {
      icon: '<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M3.15 13.628A7.749 7.749 0 0 0 10 17.75a7.74 7.74 0 0 0 6.305-3.242l-2.387-2.127-2.765 2.244-4.389-4.496-3.614 3.5zm-.787-2.303l4.446-4.371 4.52 4.63 2.534-2.057 3.533 2.797c.23-.734.354-1.514.354-2.324a7.75 7.75 0 1 0-15.387 1.325zM10 20C4.477 20 0 15.523 0 10S4.477 0 10 0s10 4.477 10 10-4.477 10-10 10z"/></svg>',
      title: 'Image'
    }
  }

  static get pasteConfig () {
    return {
      tags: ['img']
    }
  }

  static get sanitize () {
    return {
      source: false,
      caption: false
    }
  }

  tabHandler (event) {
  }
}

module.exports = ImageTool
