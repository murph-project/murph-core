const $ = require('jquery')
const EditorJS = require('@editorjs/editorjs')
const InlineTools = require('editorjs-inline-tool')
const Routing  = require('../../../../../../../../friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js')
const routes = require('../../../../../../../../../public/js/fos_js_routes.json')

const UnderlineInlineTool = InlineTools.UnderlineInlineTool
const StrongInlineTool = InlineTools.StrongInlineTool
const ItalicInlineTool = InlineTools.ItalicInlineTool

Routing.setRoutingData(routes)

const tools = {
  header: {
    class: require('@editorjs/header'),
    inlineToolbar: true
  },
  paragraph: {
    class: require('@editorjs/paragraph'),
    inlineToolbar: true
  },
  quote: {
    class: require('@editorjs/quote'),
    inlineToolbar: true
  },
  delimiter: {
    class: require('@editorjs/delimiter'),
    inlineToolbar: true
  },
  warning: {
    class: require('@editorjs/warning'),
    inlineToolbar: true
  },
  list: {
    class: require('@editorjs/list'),
    inlineToolbar: true
  },
  nestedList: {
    class: require('@editorjs/nested-list'),
    inlineToolbar: true
  },
  checkList: {
    class: require('@editorjs/checklist'),
    inlineToolbar: true
  },
  hyperLink: {
    class: require('editorjs-hyperlink'),
    inlineToolbar: true
  },
  link: {
    class: require('@editorjs/link'),
    config: {
      endpoint: Routing.generate('admin_editor_editorjs_fetch_url')
    }
  },
  table: {
    class: require('@editorjs/table'),
    inlineToolbar: true
  },
  code: {
    class: require('@editorjs/code'),
    inlineToolbar: true
  },
  raw: {
    class: require('@editorjs/raw'),
    inlineToolbar: true
  },
  marker: {
    class: require('@editorjs/marker'),
    inlineToolbar: true
  },
  inlineCode: {
    class: require('@editorjs/inline-code'),
    inlineToolbar: true
  },
  underline: {
    class: require('@editorjs/underline'),
    inlineToolbar: true
  },
  image: {
    class: require('../components/editorjs/image-tool.js')
  }
}

const makeId = () => {
  let result = ''
  const characters = 'abcdefghijklmnopqrstuvwxyz0123456789'
  const charactersLength = characters.length

  for (let i = 0; i < 20; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength))
  }

  return 'editorjs-' + result
}

const configurationBase = {
  tools,
  bold: StrongInlineTool,
  italic: ItalicInlineTool,
  underline: UnderlineInlineTool
}

const buildConfiguration = (conf) => {
  return Object.assign({}, configurationBase, conf)
}

const doInitEditor = () => {
  $('textarea[data-editorjs]').each((i, v) => {
    const element = $(v)

    let editorWrapper = element.next()
    let ready = false
    let saveTimer = null

    if (!editorWrapper || !editorWrapper.is('.editorjs')) {
      editorWrapper = $('<div><div></div></div>')
      editorWrapper
        .addClass('editorjs')
        .insertAfter(element)
    }

    const editorContainer = editorWrapper.children()
    const id = editorContainer.attr('id') ? editorContainer.attr('id') : makeId()

    editorContainer.attr('id', id)
    element.hide()

    const editor = new EditorJS(buildConfiguration({
      holder: id,
      data: JSON.parse(element.val()),
      onReady: () => {
        ready = true
      }
    }))

    const save = () => {
      if (!ready) {
        return
      }

      if (saveTimer) {
        clearTimeout(saveTimer)
      }

      saveTimer = setTimeout(() => {
        editor.save().then((data) => {
          try {
            const value = JSON.stringify(data)
            element.val(value)
          } catch (e) {
          }
        })
      }, 500)
    }

    const observer = new MutationObserver(save)

    observer.observe(editorWrapper.get(0), {
      attributes: true,
      childList: true,
      subtree: true
    })
  })
}

module.exports = () => {
  $(() => {
    doInitEditor()
  })
}
