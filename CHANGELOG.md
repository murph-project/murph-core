## [Unreleased]

### Added
* add allowed chars in RouteParameterSlugify and CodeSlugify
* improve sidebar in mobile view
### Fixed
* fix creation of new element when a menu is edited
* fix editorjs error when the textarea is empty
### Changed

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
