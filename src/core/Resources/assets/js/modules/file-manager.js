const { createApp } = require('vue')

const FileManager = require('../components/file-manager/FileManager').default

module.exports = () => {
  if (!document.getElementById('file-manager')) {
    return
  }

  return createApp({
    template: '<FileManager context="crud" />',
    components: {
      FileManager
    }
  }).mount('#file-manager')
}
