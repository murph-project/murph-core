## [Unreleased]

### Added
* add new options in BooleanField: `toggle|checkbox_class_when_true` and `toggle|checkbox_class_when_false`
* add `count` method in repository query
* add `addForcedFilterHandler` method in repository query
* add `inline_form_validation` option to validate inline forms with custom algo
* add crud sorting in the session
* add flush option in the entity manager on create, update, remove, and persist methods

## [1.21.1] - 2023-08-17
### Added
* add form error handle in inline edit action and refill the form using the previous request content
* add form error handle in ssettings actions and refill the form using the previous request content
### Fixed
* fix tinymce reload when modal is closed and reopened
* fix modal hiding when a file is successfuly uploaded in the file manager

## [1.21.0] - 2023-08-11
### Added
* allow to use array syntax in string builder filter
* add color property in Navigation
* add badge with navigation color in admin views
* add `default_value` option in crud fields
* add `display` option in BooleanField
* add associated nodes in page form
### Fixed
* fix routes in the global settings controller

## [1.20.0] - 2023-07-27
### Added
* enable double click on cruds
* add block class name for the choice type in the page maker
* update file details view on the file manager
* add form options in the crud filter action
* add trans filter in inline form modal title
* add setter to define all fields in a defined context
* add filename generator setter in FileUploadHandler
* add variable for the sidebar size
* add twig block to override defaults actions in crud index template
* add option to remove iterable values and/or specifics keys in the twig toArray function
* add boolean field for CRUD
* add context variable in each controllers to simplify overrides
* core.site.name and core.site.logo are not longer required
* add default templates when a crud is generated
* add boolean 'is_disabled' in the menu item template options
### Fixed
* fix filemanager date ordering
* fix maker CrudController template: remove bad pasted code
* fix redirect listener: use boolean instead of integer
* fix responsive of account edit template
* fix collection widget: allow_add/allow_delete and prototype
### Changed
* user admin routes are defined in core, custom controller is not required

## [1.19.0] - 2023-04-15
### Added
* feat(page): forms for metas, opengraph and extra informations can be removed
* feat(navigation): user interface is improved
* feat(file): webp is allowed and shown in form widgets and in file manager details
* feat(file): the file manager now show the size and the modification date of a file
* feat(crud): add option `action` in field to add a link to the view page or to the edition page
* feat(crud): add option `inline_form` in field to configure to edit the data
* feat(crud): add `setDoubleClick` in the crud configuration

## [1.18.0] - 2023-01-13
### Added
* feat(dep): add symfony/runtime
* feat(dep): add symfony/flex
### Fixed
* fix(crud): allow POST in delete actions
* fix(crud): remove default page value in abstract crud controller
* fix(admin): test site_logo before using it
* fix(ui): update z-index of choices__list--dropdown

## [1.17.1] - 2022-12-03
### Fixed
* add mising attribute on timestampable (doctrine)

## [1.17.0] - 2022-11-19
### Fixed
* fix tinymce modal z-index in tox
### Changed
* replace annotation with attributes

## [1.16.0] - 2022-09-06
### Added
* add A/B testing feature
* add cleanup of html string extracted from grapesjs content
### Fixed
* fix file block type
### Changed
* remove dashboard action from the core

## [1.15.0] - 2022-05-09
### Added
* CrudConfiguration::setAction can receive a callable instead of a boolean in 'enabled' param
* add grapesjs-component-code-editor and grapesjs-parser-postcss
* hide the backoffice site name when small resolution
* add entity_to_array twig function
* add default field to show in crud configuration
### Fixed
* fix the mail notifier
* fix sitemap: navigation with several domains
* fix regression with editorjs: content not loaded
### Changed
* change default template to show an entity using `entity_to_array`

## [1.14.1] - 2022-04-30
### Added
* add allowed chars in RouteParameterSlugify and CodeSlugify
* improve sidebar in mobile view
### Fixed
* fix creation of new element when a menu is edited
* fix editorjs error when the textarea is empty

## [1.14.0] - 2022-04-20
### Added
* add grapesjs modes
* add tinymce block type
* add editor types in page maker
* add the page template when the page is generated with the maker
### Changed
* replace flag-icon-css with flag-icons

## [1.13.0] - 2022-04-17
### Added
* add editorjs hyperlink block
* add button to show and hide metas (admin)
* add grapesjs editor
* add editorjs type
### Fixed
* fix editorjs inline tools (bold and italic)
### Changed
* update editorjs quote block template

## [1.12.0] - 2022-03-26
### Added
* add page maker command (`make:page`)
* add CrudConfiguration::getViewData in complement of CrudConfiguration::getViewDatas
* add editorjs link block endpoint
### Fixed
* fix issue with empty user-agent in AnalyticListener
### Changed
* update editorjs image block view

## [1.11.0] - 2022-03-22
### Added
* add data-modal-create attribute to force modal to be open in a new container
* add blur when several modals are opened
* add specific form types for Tinymce and EditorJS
### Changed
* update file-manager with data-modal-create attribute

## [1.10.0] - 2022-03-17
### Added
* add url and path generators using code (twig)
### Changed
* update node entity constraints

## [1.9.2] - 2022-03-14
### Fixed
* fix issue with murph version constant and autoloader

## [1.9.1] - 2022-03-14
### Added
* add murph version in autoload file
### Changed
* remove AdminController constructor

## [1.9.0] - 2022-03-13
### Added
* add murph version in admin ui
### Changed
* the core is now installed with composer

## [1.8.0] - 2022-03-10
### Added
* add security roles in app configuration
* add option to restrict node access to specific roles
### Changed
* rename `core/EventSuscriber` with `core/EventSubscriber`

## [1.7.3] - 2022-03-06
### Added
* add ability to rename file in the file manager
### Fixed
* fix user factory
* fix user creation from ui

## [1.7.2] - 2022-03-03
### Added
* add templates to render sections and items in the admin menu
### Fixed
* fix the analytic table when a path is a long

## [1.7.1] - 2022-03-01
### Added
* add translations
### Fixed
* fix missing directories

## [1.7.0] - 2022-03-01
### Fixed
* fix the analytic referers table when a referer has a long domain
### Changed
* upgrade dependencies
* move assets to the core directory

## [1.6.0] - 2022-02-28
### Added
* add block in field templates to allow override
* merge route params in crud admin redirects
* improve murph:user:create command

### Fixed
* fix form namespace prefix in the crud controller maker
* fix date field when the value is empty
* fix crud batch column width
* fix sidebar icon width
* fix cache clear task

### Changed
* remove password generation from the user factory

## [1.5.0] - 2022-02-25
### Added
* add desktop views and mobile views

### Changed
* upgrade dependencies
* replace jaybizzle/crawler-detect with matomo/device-detector

## [1.4.1] - 2022-02-23
### Added
* handle app urls in twig routing filters

### Fixed
* fix views in analytics modal
* replace empty path with "/" in analytics
### Changed
* update default templates

## [1.4.0] - 2022-02-21
### Added
* add basic analytics

## [1.3.0] - 2022-02-19
### Added
* add support of regexp with substitution in redirect
* url tags can be used as redirect location
* add builders to replace file information tags and url tags

### Fixed
* fix filemanager sorting
* fix batch action setter

## [1.2.0] - 2022-02-14
### Added
* add sort in file manager
* add redirect manager

### Changed
* replace node-sass with sass

## [1.1.0] - 2022-02-29
### Added
* add directory upload in file manager

### Fixed
* fix admin node routing

### Changed
* symfony/swiftmailer-bundle is replaced by symfony/mailer

## [1.0.1] - 2022-02-25
### Fixed
* fix Makefile environment vars (renaming)
* fix composer minimum stability

## [1.0.0] - 2022-01-23
