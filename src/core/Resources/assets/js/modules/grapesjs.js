const $ = require('jquery')
const GrapesJs = require('grapesjs')

require('grapesjs-blocks-bootstrap4').default
require('grapesjs-preset-webpage').default
require('grapesjs-preset-newsletter').default

const makeId = () => {
  let result = ''
  const characters = 'abcdefghijklmnopqrstuvwxyz0123456789'
  const charactersLength = characters.length

  for (let i = 0; i < 20; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength))
  }

  return 'grapesjs-' + result
}

const modes = {
  bootstrap4: {
    id: 'grapesjs-blocks-bootstrap4',
    options: {
      blocks: {},
      blockCategories: {},
      labels: {},
      gridDevicesPanel: true,
      formPredefinedActions: [
      ]
    },
    canvas: {
      styles: [
        'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css'
      ],
      scripts: [
        'https://code.jquery.com/jquery-3.5.1.slim.min.js',
        'https://unpkg.com/@popperjs/core@2',
        'https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js'
      ]
    }
  },
  presetWebpage: {
    id: 'gjs-preset-webpage',
    options: {
    },
    canvas: {
    }
  },
  presetNewsletter: {
    id: 'gjs-preset-newsletter',
    options: {
    },
    canvas: {
    }
  }
}

const doInitEditor = () => {
  $('textarea[data-grapesjs]').each((i, v) => {
    const textarea = $(v)
    const element = textarea.parent().prev()
    const id = element.attr('id') ? element.attr('id') : makeId()

    let mode = textarea.attr('data-grapesjs')
    const pluginsOpts = {}

    if (!mode || typeof modes[mode] === 'undefined') {
      mode = 'bootstrap4'
    }

    pluginsOpts[modes[mode].id] = modes[mode].options
    element.attr('id', id)

    const editor = GrapesJs.init({
      container: '#' + id,
      fromElement: false,
      height: '900px',
      width: 'auto',
      storageManager: {
        autoload: false
      },
      noticeOnUnload: 0,
      showOffsets: 1,
      showDevices: false,
      plugins: [modes[mode].id],
      colorPicker: {
        appendTo: 'parent',
        offset: {
          top: 26,
          left: -166
        }
      },
      pluginsOpts: pluginsOpts,
      canvas: modes[mode].canvas
    })

    const deviceManager = editor.DeviceManager

    const devices = [
      'Extra Small',
      'Small',
      'Medium',
      'Large',
      'Extra Large',
      'Desktop',
      'Tablet',
      'mobileLandscape',
      'mobilePortrait'
    ]

    for (const device of devices) {
      deviceManager.remove(device)
    }

    deviceManager.add('All', '100%')

    editor.Panels.getPanels().remove('devices-buttons')

    editor.on('update', () => {
      textarea.val(JSON.stringify(editor.storeData()))
    })

    try {
      editor.loadData(JSON.parse(textarea.val()))
    } catch (e) {
      editor.loadData({ html: '' })
    }
  })
}

module.exports = () => {
  $(() => {
    doInitEditor()
  })
}
