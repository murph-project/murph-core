const $ = require('jquery')
const Vue = require('vue').default
const FileManager = require('../components/file-manager/FileManager').default

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
    $('#modal-container').modal('hide')
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

if (typeof window.tinymce !== 'undefined') {
  window.tinymce.murph = window.tinymce.murph || {}
  window.tinymce.murph.selector = window.tinymce.murph.selector || '*[data-tinymce]'
  window.tinymce.murph.configurationBase = window.tinymce.murph.configurationBase || {
    base_url: '/vendor/tinymce/',
    cache_suffix: '?v=4.1.6',
    importcss_append: true,
    image_caption: true,
    noneditable_noneditable_class: 'mceNonEditable',
    toolbar_drawer: 'sliding',
    spellchecker_dialog: true,
    tinycomments_mode: 'embedded',
    convert_urls: false,
    file_picker_callback: fileManagerBrowser,
    file_picker_types: 'image',
    init_instance_callback: function (editor) {
      editor.on('SetContent', () => {
        window.tinymce.triggerSave(false, true)
      })

      editor.on('Change', () => {
        window.tinymce.triggerSave(false, true)
      })
    }
  }

  window.tinymce.murph.modes = window.tinymce.murph.modes || {}

  window.tinymce.murph.modes.default = window.tinymce.murph.modes.default || {
    plugins: 'print preview importcss searchreplace visualblocks visualchars fullscreen template table charmap hr pagebreak nonbreaking toc insertdatetime advlist lists wordcount textpattern noneditable help charmap quickbars link image code autoresize',
    menubar: 'file edit view insert format tools table tc help',
    toolbar: 'undo redo | bold italic underline strikethrough | link image | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist checklist | forecolor backcolor casechange permanentpen formatpainter removeformat | pagebreak | charmap | fullscreen preview',
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    contextmenu: 'link image imagetools table configurepermanentpen'
  }

  window.tinymce.murph.modes.light = window.tinymce.murph.modes.light || {
    contextmenu: 'link image imagetools table configurepermanentpen',
    quickbars_selection_toolbar: 'bold italic',
    toolbar: 'undo redo | bold italic underline'
  }

  tinymce.addI18n('fr_FR', {
    Redo: 'R\u00e9tablir',
    Undo: 'Annuler',
    Cut: 'Couper',
    Copy: 'Copier',
    Paste: 'Coller',
    'Select all': 'S\u00e9lectionner tout',
    'New document': 'Nouveau document',
    Ok: 'OK',
    Cancel: 'Annuler',
    'Visual aids': 'Aides visuelles',
    Bold: 'Gras',
    Italic: 'Italique',
    Underline: 'Soulign\u00e9',
    Strikethrough: 'Barr\u00e9',
    Superscript: 'Exposant',
    Subscript: 'Indice',
    'Clear formatting': 'Effacer la mise en forme',
    'Align left': 'Aligner \u00e0 gauche',
    'Align center': 'Centrer',
    'Align right': 'Aligner \u00e0 droite',
    Justify: 'Justifier',
    'Bullet list': 'Liste \u00e0 puces',
    'Numbered list': 'Liste num\u00e9rot\u00e9e',
    'Decrease indent': 'R\u00e9duire le retrait',
    'Increase indent': 'Augmenter le retrait',
    Close: 'Fermer',
    Formats: 'Formats',
    "Your browser doesn't support direct access to the clipboard. Please use the Ctrl+X\/C\/V keyboard shortcuts instead.": "Votre navigateur ne supporte pas l\u2019acc\u00e8s direct au presse-papiers. Merci d'utiliser les raccourcis clavier Ctrl+X\/C\/V.",
    Headers: 'En-t\u00eates',
    'Header 1': 'En-t\u00eate 1',
    'Header 2': 'En-t\u00eate 2',
    'Header 3': 'En-t\u00eate 3',
    'Header 4': 'En-t\u00eate 4',
    'Header 5': 'En-t\u00eate 5',
    'Header 6': 'En-t\u00eate 6',
    Headings: 'Titres',
    'Heading 1': 'Titre\u00a01',
    'Heading 2': 'Titre\u00a02',
    'Heading 3': 'Titre\u00a03',
    'Heading 4': 'Titre\u00a04',
    'Heading 5': 'Titre\u00a05',
    'Heading 6': 'Titre\u00a06',
    Preformatted: 'Pr\u00e9format\u00e9',
    Div: 'Div',
    Pre: 'Pre',
    Code: 'Code',
    Paragraph: 'Paragraphe',
    Blockquote: 'Blockquote',
    Inline: 'En ligne',
    Blocks: 'Blocs',
    'Paste is now in plain text mode. Contents will now be pasted as plain text until you toggle this option off.': "Le presse-papiers est maintenant en mode \"texte plein\". Les contenus seront coll\u00e9s sans retenir les formatages jusqu'\u00e0 ce que vous d\u00e9sactiviez cette option.",
    Fonts: 'Polices',
    'Font Sizes': 'Tailles de police',
    Class: 'Classe',
    'Browse for an image': 'Rechercher une image',
    OR: 'OU',
    'Drop an image here': 'D\u00e9poser une image ici',
    Upload: 'T\u00e9l\u00e9charger',
    Block: 'Bloc',
    Align: 'Aligner',
    Default: 'Par d\u00e9faut',
    Circle: 'Cercle',
    Disc: 'Disque',
    Square: 'Carr\u00e9',
    'Lower Alpha': 'Alpha minuscule',
    'Lower Greek': 'Grec minuscule',
    'Lower Roman': 'Romain minuscule',
    'Upper Alpha': 'Alpha majuscule',
    'Upper Roman': 'Romain majuscule',
    'Anchor...': 'Ancre...',
    Name: 'Nom',
    Id: 'Id',
    'Id should start with a letter, followed only by letters, numbers, dashes, dots, colons or underscores.': "L'Id doit commencer par une lettre suivi par des lettres, nombres, tirets, points, deux-points ou underscores",
    'You have unsaved changes are you sure you want to navigate away?': 'Vous avez des modifications non enregistr\u00e9es, \u00eates-vous s\u00fbr de quitter la page?',
    'Restore last draft': 'Restaurer le dernier brouillon',
    'Special character...': 'Caract\u00e8re sp\u00e9cial...',
    'Source code': 'Code source',
    'Insert\/Edit code sample': 'Ins\u00e9rer \/ modifier une exemple de code',
    Language: 'Langue',
    'Code sample...': 'Exemple de code...',
    'Color Picker': 'S\u00e9lecteur de couleurs',
    R: 'R',
    G: 'V',
    B: 'B',
    'Left to right': 'Gauche \u00e0 droite',
    'Right to left': 'Droite \u00e0 gauche',
    Emoticons: 'Emotic\u00f4nes',
    'Emoticons...': '\u00c9motic\u00f4nes...',
    'Metadata and Document Properties': 'M\u00e9tadonn\u00e9es et propri\u00e9t\u00e9s du document',
    Title: 'Titre',
    Keywords: 'Mots-cl\u00e9s',
    Description: 'Description',
    Robots: 'Robots',
    Author: 'Auteur',
    Encoding: 'Encodage',
    Fullscreen: 'Plein \u00e9cran',
    Action: 'Action',
    Shortcut: 'Raccourci',
    Help: 'Aide',
    Address: 'Adresse',
    'Focus to menubar': 'Cibler la barre de menu',
    'Focus to toolbar': "Cibler la barre d'outils",
    'Focus to element path': "Cibler le chemin vers l'\u00e9l\u00e9ment",
    'Focus to contextual toolbar': "Cibler la barre d'outils contextuelle",
    'Insert link (if link plugin activated)': 'Ins\u00e9rer un lien (si le module link est activ\u00e9)',
    'Save (if save plugin activated)': 'Enregistrer (si le module save est activ\u00e9)',
    'Find (if searchreplace plugin activated)': 'Rechercher (si le module searchreplace est activ\u00e9)',
    'Plugins installed ({0}):': 'Modules install\u00e9s ({0}) : ',
    'Premium plugins:': 'Modules premium :',
    'Learn more...': 'En savoir plus...',
    'You are using {0}': 'Vous utilisez {0}',
    Plugins: 'Plugins',
    'Handy Shortcuts': 'Raccourcis utiles',
    'Horizontal line': 'Ligne horizontale',
    'Insert\/edit image': 'Ins\u00e9rer\/modifier une image',
    'Alternative description': 'Description alternative',
    Accessibility: 'Accessibilit\u00e9',
    'Image is decorative': "L'image est d\u00e9corative",
    Source: 'Source',
    Dimensions: 'Dimensions',
    'Constrain proportions': 'Conserver les proportions',
    General: 'G\u00e9n\u00e9ral',
    Advanced: 'Avanc\u00e9',
    Style: 'Style',
    'Vertical space': 'Espacement vertical',
    'Horizontal space': 'Espacement horizontal',
    Border: 'Bordure',
    'Insert image': 'Ins\u00e9rer une image',
    'Image...': 'Image...',
    'Image list': "Liste d'images",
    'Rotate counterclockwise': 'Rotation anti-horaire',
    'Rotate clockwise': 'Rotation horaire',
    'Flip vertically': 'Retournement vertical',
    'Flip horizontally': 'Retournement horizontal',
    'Edit image': "Modifier l'image",
    'Image options': "Options de l'image",
    'Zoom in': 'Zoomer',
    'Zoom out': 'D\u00e9zoomer',
    Crop: 'Rogner',
    Resize: 'Redimensionner',
    Orientation: 'Orientation',
    Brightness: 'Luminosit\u00e9',
    Sharpen: 'Affiner',
    Contrast: 'Contraste',
    'Color levels': 'Niveaux de couleur',
    Gamma: 'Gamma',
    Invert: 'Inverser',
    Apply: 'Appliquer',
    Back: 'Retour',
    'Insert date\/time': 'Ins\u00e9rer date\/heure',
    'Date\/time': 'Date\/heure',
    'Insert\/edit link': 'Ins\u00e9rer\/modifier un lien',
    'Text to display': 'Texte \u00e0 afficher',
    Url: 'Url',
    'Open link in...': 'Ouvrir le lien dans...',
    'Current window': 'Fen\u00eatre active',
    None: 'n\/a',
    'New window': 'Nouvelle fen\u00eatre',
    'Open link': 'Ouvrir le lien',
    'Remove link': 'Enlever le lien',
    Anchors: 'Ancres',
    'Link...': 'Lien...',
    'Paste or type a link': 'Coller ou taper un lien',
    'The URL you entered seems to be an email address. Do you want to add the required mailto: prefix?': "L'URL que vous avez entr\u00e9e semble \u00eatre une adresse e-mail. Voulez-vous ajouter le pr\u00e9fixe mailto: n\u00e9cessaire?",
    'The URL you entered seems to be an external link. Do you want to add the required http:\/\/ prefix?': "L'URL que vous avez entr\u00e9e semble \u00eatre un lien externe. Voulez-vous ajouter le pr\u00e9fixe http:\/\/ n\u00e9cessaire?",
    'The URL you entered seems to be an external link. Do you want to add the required https:\/\/ prefix?': "L'URL que vous avez saisie semble \u00eatre un lien externe. Voulez-vous ajouter le pr\u00e9fixe https:\/\/ requis\u00a0?",
    'Link list': 'Liste de liens',
    'Insert video': 'Ins\u00e9rer une vid\u00e9o',
    'Insert\/edit video': 'Ins\u00e9rer\/modifier une vid\u00e9o',
    'Insert\/edit media': 'Ins\u00e9rer\/modifier un m\u00e9dia',
    'Alternative source': 'Source alternative',
    'Alternative source URL': 'URL de la source alternative',
    'Media poster (Image URL)': "Affiche de m\u00e9dia (URL de l'image)",
    'Paste your embed code below:': "Collez votre code d'int\u00e9gration ci-dessous :",
    Embed: 'Int\u00e9grer',
    'Media...': 'M\u00e9dia...',
    'Nonbreaking space': 'Espace ins\u00e9cable',
    'Page break': 'Saut de page',
    'Paste as text': 'Coller comme texte',
    Preview: 'Pr\u00e9visualiser',
    'Print...': 'Imprimer...',
    Save: 'Enregistrer',
    Find: 'Chercher',
    'Replace with': 'Remplacer par',
    Replace: 'Remplacer',
    'Replace all': 'Tout remplacer',
    Previous: 'Pr\u00e9c\u00e9dente',
    Next: 'Suiv',
    'Find and Replace': 'Trouver et remplacer',
    'Find and replace...': 'Trouver et remplacer...',
    'Could not find the specified string.': 'Impossible de trouver la cha\u00eene sp\u00e9cifi\u00e9e.',
    'Match case': 'Respecter la casse',
    'Find whole words only': 'Mot entier',
    'Find in selection': 'Trouver dans la s\u00e9lection',
    Spellcheck: 'V\u00e9rification orthographique',
    'Spellcheck Language': 'Langue du correcteur orthographique',
    'No misspellings found.': "Aucune faute d'orthographe trouv\u00e9e.",
    Ignore: 'Ignorer',
    'Ignore all': 'Tout ignorer',
    Finish: 'Finie',
    'Add to Dictionary': 'Ajouter au dictionnaire',
    'Insert table': 'Ins\u00e9rer un tableau',
    'Table properties': 'Propri\u00e9t\u00e9s du tableau',
    'Delete table': 'Supprimer le tableau',
    Cell: 'Cellule',
    Row: 'Ligne',
    Column: 'Colonne',
    'Cell properties': 'Propri\u00e9t\u00e9s de la cellule',
    'Merge cells': 'Fusionner les cellules',
    'Split cell': 'Diviser la cellule',
    'Insert row before': 'Ins\u00e9rer une ligne avant',
    'Insert row after': 'Ins\u00e9rer une ligne apr\u00e8s',
    'Delete row': 'Effacer la ligne',
    'Row properties': 'Propri\u00e9t\u00e9s de la ligne',
    'Cut row': 'Couper la ligne',
    'Copy row': 'Copier la ligne',
    'Paste row before': 'Coller la ligne avant',
    'Paste row after': 'Coller la ligne apr\u00e8s',
    'Insert column before': 'Ins\u00e9rer une colonne avant',
    'Insert column after': 'Ins\u00e9rer une colonne apr\u00e8s',
    'Delete column': 'Effacer la colonne',
    Cols: 'Colonnes',
    Rows: 'Lignes',
    Width: 'Largeur',
    Height: 'Hauteur',
    'Cell spacing': 'Espacement inter-cellulles',
    'Cell padding': 'Espacement interne cellule',
    Caption: 'Titre',
    'Show caption': 'Afficher le sous-titrage',
    Left: 'Gauche',
    Center: 'Centr\u00e9',
    Right: 'Droite',
    'Cell type': 'Type de cellule',
    Scope: 'Etendue',
    Alignment: 'Alignement',
    'H Align': 'Alignement H',
    'V Align': 'Alignement V',
    Top: 'Haut',
    Middle: 'Milieu',
    Bottom: 'Bas',
    'Header cell': "Cellule d'en-t\u00eate",
    'Row group': 'Groupe de lignes',
    'Column group': 'Groupe de colonnes',
    'Row type': 'Type de ligne',
    Header: 'En-t\u00eate',
    Body: 'Corps',
    Footer: 'Pied',
    'Border color': 'Couleur de la bordure',
    'Insert template...': 'Ins\u00e9rer un mod\u00e8le...',
    Templates: 'Th\u00e8mes',
    Template: 'Mod\u00e8le',
    'Text color': 'Couleur du texte',
    'Background color': "Couleur d'arri\u00e8re-plan",
    'Custom...': 'Personnalis\u00e9...',
    'Custom color': 'Couleur personnalis\u00e9e',
    'No color': 'Aucune couleur',
    'Remove color': 'Supprimer la couleur',
    'Table of Contents': 'Table des mati\u00e8res',
    'Show blocks': 'Afficher les blocs',
    'Show invisible characters': 'Afficher les caract\u00e8res invisibles',
    'Word count': 'Nombre de mots',
    Count: 'Total',
    Document: 'Document',
    Selection: 'S\u00e9lection',
    Words: 'Mots',
    'Words: {0}': 'Mots : {0}',
    '{0} words': '{0} mots',
    File: 'Fichier',
    Edit: 'Editer',
    Insert: 'Ins\u00e9rer',
    View: 'Voir',
    Format: 'Format',
    Table: 'Tableau',
    Tools: 'Outils',
    'Powered by {0}': 'Propuls\u00e9 par {0}',
    'Rich Text Area. Press ALT-F9 for menu. Press ALT-F10 for toolbar. Press ALT-0 for help': "Zone Texte Riche. Appuyer sur ALT-F9 pour le menu. Appuyer sur ALT-F10 pour la barre d'outils. Appuyer sur ALT-0 pour de l'aide.",
    'Image title': "Titre d'image",
    'Border width': '\u00c9paisseur de la bordure',
    'Border style': 'Style de la bordure',
    Error: 'Erreur',
    Warn: 'Avertir',
    Valid: 'Valide',
    'To open the popup, press Shift+Enter': 'Pour ouvrir la popup, appuyez sur Maj+Entr\u00e9e',
    'Rich Text Area. Press ALT-0 for help.': "Zone de texte riche. Appuyez sur ALT-0 pour l'aide.",
    'System Font': 'Police syst\u00e8me',
    'Failed to upload image: {0}': "\u00c9chec d'envoi de l'image\u00a0: {0}",
    'Failed to load plugin: {0} from url {1}': '\u00c9chec de chargement du plug-in\u00a0: {0} \u00e0 partir de l\u2019URL {1}',
    'Failed to load plugin url: {0}': "\u00c9chec de chargement de l'URL du plug-in\u00a0: {0}",
    'Failed to initialize plugin: {0}': "\u00c9chec d'initialisation du plug-in\u00a0: {0}",
    example: 'exemple',
    Search: 'Rechercher',
    All: 'Tout',
    Currency: 'Devise',
    Text: 'Texte',
    Quotations: 'Citations',
    Mathematical: 'Op\u00e9rateurs math\u00e9matiques',
    'Extended Latin': 'Latin \u00e9tendu',
    Symbols: 'Symboles',
    Arrows: 'Fl\u00e8ches',
    'User Defined': "D\u00e9fini par l'utilisateur",
    'dollar sign': 'Symbole dollar',
    'currency sign': 'Symbole devise',
    'euro-currency sign': 'Symbole euro',
    'colon sign': 'Symbole col\u00f3n',
    'cruzeiro sign': 'Symbole cruzeiro',
    'french franc sign': 'Symbole franc fran\u00e7ais',
    'lira sign': 'Symbole lire',
    'mill sign': 'Symbole milli\u00e8me',
    'naira sign': 'Symbole naira',
    'peseta sign': 'Symbole peseta',
    'rupee sign': 'Symbole roupie',
    'won sign': 'Symbole won',
    'new sheqel sign': 'Symbole nouveau ch\u00e9kel',
    'dong sign': 'Symbole dong',
    'kip sign': 'Symbole kip',
    'tugrik sign': 'Symbole tougrik',
    'drachma sign': 'Symbole drachme',
    'german penny symbol': 'Symbole pfennig',
    'peso sign': 'Symbole peso',
    'guarani sign': 'Symbole guarani',
    'austral sign': 'Symbole austral',
    'hryvnia sign': 'Symbole hryvnia',
    'cedi sign': 'Symbole cedi',
    'livre tournois sign': 'Symbole livre tournois',
    'spesmilo sign': 'Symbole spesmilo',
    'tenge sign': 'Symbole tenge',
    'indian rupee sign': 'Symbole roupie indienne',
    'turkish lira sign': 'Symbole lire turque',
    'nordic mark sign': 'Symbole du mark nordique',
    'manat sign': 'Symbole manat',
    'ruble sign': 'Symbole rouble',
    'yen character': 'Sinogramme Yen',
    'yuan character': 'Sinogramme Yuan',
    'yuan character, in hong kong and taiwan': 'Sinogramme Yuan, Hong Kong et Taiwan',
    'yen\/yuan character variant one': 'Sinogramme Yen\/Yuan, premi\u00e8re variante',
    'Loading emoticons...': 'Chargement des \u00e9motic\u00f4nes en cours...',
    'Could not load emoticons': '\u00c9chec de chargement des \u00e9motic\u00f4nes',
    People: 'Personnes',
    'Animals and Nature': 'Animaux & nature',
    'Food and Drink': 'Nourriture & boissons',
    Activity: 'Activit\u00e9',
    'Travel and Places': 'Voyages & lieux',
    Objects: 'Objets',
    Flags: 'Drapeaux',
    Characters: 'Caract\u00e8res',
    'Characters (no spaces)': 'Caract\u00e8res (espaces non compris)',
    '{0} characters': '{0}\u00a0caract\u00e8res',
    'Error: Form submit field collision.': 'Erreur\u00a0: conflit de champs lors de la soumission du formulaire.',
    'Error: No form element found.': 'Erreur : aucun \u00e9l\u00e9ment de formulaire trouv\u00e9.',
    Update: 'Mettre \u00e0 jour',
    'Color swatch': '\u00c9chantillon de couleurs',
    Turquoise: 'Turquoise',
    Green: 'Vert',
    Blue: 'Bleu',
    Purple: 'Violet',
    'Navy Blue': 'Bleu marine',
    'Dark Turquoise': 'Turquoise fonc\u00e9',
    'Dark Green': 'Vert fonc\u00e9',
    'Medium Blue': 'Bleu moyen',
    'Medium Purple': 'Violet moyen',
    'Midnight Blue': 'Bleu de minuit',
    Yellow: 'Jaune',
    Orange: 'Orange',
    Red: 'Rouge',
    'Light Gray': 'Gris clair',
    Gray: 'Gris',
    'Dark Yellow': 'Jaune fonc\u00e9',
    'Dark Orange': 'Orange fonc\u00e9',
    'Dark Red': 'Rouge fonc\u00e9',
    'Medium Gray': 'Gris moyen',
    'Dark Gray': 'Gris fonc\u00e9',
    'Light Green': 'Vert clair',
    'Light Yellow': 'Jaune clair',
    'Light Red': 'Rouge clair',
    'Light Purple': 'Violet clair',
    'Light Blue': 'Bleu clair',
    'Dark Purple': 'Violet fonc\u00e9',
    'Dark Blue': 'Bleu fonc\u00e9',
    Black: 'Noir',
    White: 'Blanc',
    'Switch to or from fullscreen mode': 'Passer en ou quitter le mode plein \u00e9cran',
    'Open help dialog': "Ouvrir la bo\u00eete de dialogue d'aide",
    history: 'historique',
    styles: 'styles',
    formatting: 'mise en forme',
    alignment: 'alignement',
    indentation: 'retrait',
    Font: 'Police',
    Size: 'Taille',
    'More...': 'Plus...',
    'Select...': 'S\u00e9lectionner...',
    Preferences: 'Pr\u00e9f\u00e9rences',
    Yes: 'Oui',
    No: 'Non',
    'Keyboard Navigation': 'Navigation au clavier',
    Version: 'Version',
    'Code view': 'Affichage du code',
    'Open popup menu for split buttons': 'Ouvrir le menu contextuel pour les boutons partag\u00e9s',
    'List Properties': 'Propri\u00e9t\u00e9s de la liste',
    'List properties...': 'Lister les propri\u00e9t\u00e9s...',
    'Start list at number': 'Liste de d\u00e9part au num\u00e9ro',
    'Line height': 'Hauteur de la ligne',
    comments: 'commentaires',
    'Format Painter': 'Reproduire la mise en forme',
    'Insert\/edit iframe': 'Ins\u00e9rer\/modifier iframe',
    Capitalization: 'Mise en majuscules',
    lowercase: 'minuscule',
    UPPERCASE: 'MAJUSCULE',
    'Title Case': 'Casse du titre',
    'permanent pen': 'feutre ind\u00e9l\u00e9bile',
    'Permanent Pen Properties': 'Propri\u00e9t\u00e9s du feutre ind\u00e9l\u00e9bile',
    'Permanent pen properties...': 'Propri\u00e9t\u00e9s du feutre ind\u00e9l\u00e9bile...',
    'case change': 'changement de cas',
    'page embed': 'int\u00e9gration de page',
    'Advanced sort...': 'Tri avanc\u00e9...',
    'Advanced Sort': 'Tri avanc\u00e9',
    'Sort table by column ascending': 'Trier le tableau par colonne ascendante',
    'Sort table by column descending': 'Trier le tableau par colonne en ordre d\u00e9croissant',
    Sort: 'Sorte',
    Order: 'Ordre',
    'Sort by': 'Trier par',
    Ascending: 'Ascendant',
    Descending: 'Descendant',
    'Column {0}': 'Colonne {0}',
    'Row {0}': 'Ligne {0}',
    'Spellcheck...': 'V\u00e9rification orthographique...',
    'Misspelled word': 'Mot mal orthographi\u00e9',
    Suggestions: 'Suggestions',
    Change: 'Changement',
    'Finding word suggestions': 'Trouver des suggestions de mots',
    Success: 'Succ\u00e8s',
    Repair: 'R\u00e9paration',
    'Issue {0} of {1}': ' {0} Erreur sur  {1}',
    'Images must be marked as decorative or have an alternative text description': 'Les images doivent \u00eatre marqu\u00e9es comme d\u00e9coratives ou avoir une description textuelle alternative',
    'Images must have an alternative text description. Decorative images are not allowed.': 'Les images doivent avoir une description textuelle alternative. Les images d\u00e9coratives ne sont pas autoris\u00e9es.',
    'Or provide alternative text:': 'Ou fournissez un texte alternatif\u00a0:',
    'Make image decorative:': "Rendre l'image d\u00e9corative\u00a0:",
    'ID attribute must be unique': "L'attribut ID doit \u00eatre unique",
    'Make ID unique': "Rendre l'identifiant unique",
    'Keep this ID and remove all others': 'Conservez cet identifiant et supprimez tous les autres',
    'Remove this ID': 'Supprimer cet identifiant',
    'Remove all IDs': 'Supprimer tous les identifiants',
    Checklist: 'Liste de contr\u00f4le',
    Anchor: 'Ancre',
    'Special character': 'Caract\u00e8res sp\u00e9ciaux',
    'Code sample': 'Extrait de code',
    Color: 'Couleur',
    'Document properties': 'Propri\u00e9t\u00e9 du document',
    'Image description': "Description de l'image",
    Image: 'Image',
    'Insert link': 'Ins\u00e9rer un lien',
    Target: 'Cible',
    Link: 'Lien',
    Poster: 'Publier',
    Media: 'M\u00e9dia',
    Print: 'Imprimer',
    Prev: 'Pr\u00e9c ',
    'Find and replace': 'Trouver et remplacer',
    'Whole words': 'Mots entiers',
    'Insert template': 'Ajouter un th\u00e8me'
  })
}

const buildConfiguration = (conf) => {
  const result = Object.assign({}, window.tinymce.murph.configurationBase, conf)

  if (window.tinymce.language && !result.language) {
    result.language = window.tinymce.language
  }

  return result
}

const makeId = () => {
  let result = ''
  const characters = 'abcdefghijklmnopqrstuvwxyz0123456789'
  const charactersLength = characters.length

  for (let i = 0; i < 20; i++) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength))
  }

  return 'tinymce-' + result
}

const doInitEditor = () => {
  $(window.tinymce.murph.selector).each((i, v) => {
    const element = $(v)
    let id = null

    if (element.attr('id')) {
      id = element.attr('id')
    } else {
      id = makeId()
      element.attr('id', makeId)
    }

    let mode = element.attr('data-tinymce')

    if (!mode) {
      mode = 'default'
    }

    if (!Object.prototype.hasOwnProperty.call(window.tinymce.murph.modes, mode)) {
      return
    }

    const conf = buildConfiguration(window.tinymce.murph.modes[mode])
    conf.mode = 'exact'
    conf.elements = id

    window.tinymce.init(conf)
  })
}

module.exports = function () {
  if (typeof tinymce === 'undefined') {
    return
  }

  const observer = new MutationObserver(doInitEditor)
  const config = { attributes: false, childList: true, subtree: true }
  observer.observe(document.querySelector('body'), config)

  $(() => {
    doInitEditor()
  })
}
