const $ = require('jquery')
const GrapesJs = require('grapesjs')
const bootstrap4 = require('grapesjs-blocks-bootstrap4').default

const makeId = () => {
  let result = ''
  const characters = 'abcdefghijklmnopqrstuvwxyz0123456789'
  const charactersLength = characters.length

  for (let i = 0; i < 20; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength))
  }

  return 'grapesjs-' + result
}

const doInitEditor = () => {
  $('textarea[data-grapesjs]').each((i, v) => {
    const textarea = $(v)
    const element = textarea.parent().prev()
    const id = element.attr('id') ? element.attr('id') : makeId()

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
      plugins: ['grapesjs-blocks-bootstrap4'],
      colorPicker: {
        appendTo: 'parent',
        offset: {
          top: 26,
          left: -166
        }
      },
      pluginsOpts: {
        'grapesjs-blocks-bootstrap4': {
          blocks: {},
          blockCategories: {},
          labels: {},
          gridDevicesPanel: true,
          formPredefinedActions: [
          ]
        }
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

    console.log(textarea.val())

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
