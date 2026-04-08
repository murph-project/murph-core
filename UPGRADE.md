## General process

Upgrade dependencies:

* `composer update`
* `yarn upgrade`

Build:

* `make build`

## Upgrade to v2.0.0

This is a major release upgrading Murph to **Symfony 7.4 LTS**, **Doctrine ORM 3.6**, **Vue 3.5**, and **PHP 8.2+**.

### Requirements

- PHP >= 8.2
- Node.js >= 20
- Yarn

### Removed packages

The following packages have been removed from `composer.json`. If your project requires them directly, remove them:

```bash
composer remove sensio/framework-extra-bundle
composer remove symfony/proxy-manager-bridge
composer remove composer/package-versions-deprecated
composer remove scheb/2fa-qr-code
composer remove spe/filesize-extension-bundle
composer remove doctrine/annotations
```

The `spe/filesize-extension-bundle` Twig filter `readable_filesize` is now provided by `App\Core\Twig\Extension\FilesizeExtension` (auto-registered).

The `scheb/2fa-qr-code` package was unused — QR codes are rendered client-side via `qrcodejs`.

### Upgraded packages

| Package | From | To |
|---------|------|----|
| All `symfony/*` | `5.4.*` | `7.4.*` |
| `doctrine/orm` | `^2.11` | `^3.6` |
| `doctrine/doctrine-bundle` | `^2.5` | `^2.13` |
| `doctrine/doctrine-migrations-bundle` | `^3.2` | `^3.4` |
| `knplabs/doctrine-behaviors` | `^2.6` | `^3.1` |
| `knplabs/knp-paginator-bundle` | `^5.8` | `^6.0` |
| `scheb/2fa-google-authenticator` | `^5.13` | `^7.9` |
| `friendsofsymfony/jsrouting-bundle` | `^2.8` | `^3.5` |
| `fusonic/opengraph` | `^2.2` | `^3.0` |
| `symfony/webpack-encore-bundle` | `^1.11` | `^2.0` |

### Security system

The Guard authenticator (`LoginFormAuthenticator`) has been removed. Symfony 7.4 uses the authenticator-based system exclusively. The `form_login` firewall handles authentication.

**If you extended `LoginFormAuthenticator`**, remove your extension and configure authentication via `security.yaml` `form_login` options.

`UserPasswordEncoderInterface` is replaced by `UserPasswordHasherInterface`:

```php
// Before
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
$encoder->encodePassword($user, $password);

// After
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
$hasher->hashPassword($user, $password);
```

### Routing

Route loading changed from `annotation` to `attribute`. The `Routing\Annotation\Route` import is replaced by `Routing\Attribute\Route`:

```php
// Before
use Symfony\Component\Routing\Annotation\Route;

// After
use Symfony\Component\Routing\Attribute\Route;
```

The `IsGranted` attribute now comes from Symfony Security instead of SensioFrameworkExtraBundle:

```php
// Before
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

// After
use Symfony\Component\Security\Http\Attribute\IsGranted;
```

### Session injection

Controller methods no longer accept `Session $session` as a parameter. Use `$request->getSession()` instead:

```php
// Before
public function index(Request $request, Session $session): Response
{
    return $this->doIndex($page, $query, $request, $session);
}

// After
public function index(Request $request): Response
{
    return $this->doIndex($page, $query, $request);
}
```

The `CrudController` methods `doIndex()`, `doSort()`, `doBatch()`, `doFilter()`, `updateFilters()` no longer accept a `Session` parameter. **Update all your CRUD controllers accordingly.**

If you generated controllers with `make:crud-controller`, update them to match the new signatures.

### Doctrine ORM 3

Entity `#[ORM\Column]` attributes no longer use string `type:` parameters. PHP types are inferred:

```php
// Before
#[ORM\Column(type: 'string', length: 255)]
protected $title;

#[ORM\Column(type: 'integer')]
protected $count;

#[ORM\Column(type: 'boolean')]
protected $active;

#[ORM\Column(type: 'text', nullable: true)]
protected $content;

#[ORM\Column(type: 'datetime')]
protected $createdAt;

#[ORM\Column(type: 'array', nullable: true)]
protected $data;

// After
#[ORM\Column(length: 255)]
protected ?string $title = null;

#[ORM\Column]
protected ?int $count = null;

#[ORM\Column]
protected bool $active = false;

#[ORM\Column(type: Types::TEXT, nullable: true)]
protected ?string $content = null;

#[ORM\Column(type: Types::DATETIME_MUTABLE)]
protected ?\DateTimeInterface $createdAt = null;

#[ORM\Column(type: Types::JSON, nullable: true)]
protected ?array $data = [];
```

Add `use Doctrine\DBAL\Types\Types;` where needed. Note: `Types::ARRAY` no longer exists in DBAL 4 — use `Types::JSON` instead.

**All entity properties must be typed.** Untyped properties will cause errors with Doctrine ORM 3.

### Gedmo annotations

Gedmo tree annotations must use PHP 8 attributes:

```php
// Before
/**
 * @Gedmo\Tree(type="nested")
 */
class Node { ... }

/**
 * @Gedmo\TreeLeft
 */
protected $treeLeft;

// After
#[Gedmo\Tree(type: 'nested')]
class Node { ... }

#[Gedmo\TreeLeft]
protected ?int $treeLeft = null;
```

### Form method return types

All Form type methods require explicit return types in Symfony 7.4:

```php
// Before
public function buildForm(FormBuilderInterface $builder, array $options)
public function configureOptions(OptionsResolver $resolver)
public function buildView(FormView $view, FormInterface $form, array $options)
public function getBlockPrefix()

// After
public function buildForm(FormBuilderInterface $builder, array $options): void
public function configureOptions(OptionsResolver $resolver): void
public function buildView(FormView $view, FormInterface $form, array $options): void
public function getBlockPrefix(): string
```

The same applies to:
- `EventSubscriberInterface::getSubscribedEvents(): array`
- `AbstractExtension::getFilters(): array` / `getFunctions(): array`
- `Voter::voteOnAttribute()` — add `?Vote $vote = null` parameter
- `Extension::load(): void` and `getConfiguration(): ?ConfigurationInterface`
- `Command::configure(): void`
- `UserInterface::eraseCredentials(): void` — add `#[\Deprecated]` attribute

### Vue 3

Vue 2 instantiation pattern is replaced:

```javascript
// Before
const Vue = require('vue').default
new Vue({ el: '#app', template: '...', components: { ... } })

// After
const { createApp } = require('vue')
createApp({ template: '...', components: { ... } }).mount('#app')
```

vuedraggable v4 requires slot-based API:

```html
<!-- Before -->
<Draggable v-model="items">
  <Item v-for="(item, key) in items" :key="item.id" />
</Draggable>

<!-- After -->
<Draggable v-model="items" :item-key="(el) => el.id">
  <template #item="{ element, index }">
    <Item :key="element.id" />
  </template>
  <template #footer>
    <!-- non-draggable content goes here -->
  </template>
</Draggable>
```

### NPM dependencies

The `murph-project` meta-package does not support Vue 3. Replace it with explicit dependencies in `package.json`. See the skeleton's `package.json` for the full list.

Required new devDependencies:

```bash
yarn add -D @babel/core @babel/preset-env webpack webpack-cli vue-loader@^17.0
```

Remove Vue 2 packages:

```bash
yarn remove vue-template-compiler @vue/babel-preset-jsx @vue/babel-helper-vue-jsx-merge-props
```

### Configuration changes

**`config/packages/security.yaml`:**
- Remove `encoders:` block (use `password_hashers:` only)
- Remove `enable_authenticator_manager: true` (default in Sf 7.4)
- Remove `guard:` block
- Replace `IS_AUTHENTICATED_ANONYMOUSLY` with `PUBLIC_ACCESS`
- Add `default_target_path: /admin/` under `form_login`

**`config/packages/scheb_2fa.yaml`:**
- Replace `window:` with `leeway:`
- Remove `Symfony\Component\Security\Guard\Token\PostAuthenticationGuardToken`
- Add `Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken`

**`config/packages/doctrine.yaml`:**
- Change Gedmo mapping `type: annotation` to `type: attribute`
- Add `controller_resolver: { auto_mapping: false }` under `doctrine.orm`

**`config/packages/framework.yaml`:**
- Add `property_info: { with_constructor_extractor: true }`
- Add `profiler: { collect_serializer_data: true }`

**`config/packages/liip_imagine.yaml`:**
- Add `twig: { mode: lazy }`

**`config/packages/stof_doctrine_extensions.yaml`:**
- Add `orm: { default: { tree: true } }`

**`config/services.yaml`:**
- Remove the manual `gedmo.listener.tree` service definition with `setAnnotationReader`

**`config/routes/annotations.yaml`:**
- Rename to `config/routes/attributes.yaml`
- Change all `type: annotation` to `type: attribute`

**`config/routes/web_profiler.yaml`:**
- Change `wdt.xml` to `wdt.php`, `profiler.xml` to `profiler.php`

**`config/routes/framework.yaml`:**
- Change `errors.xml` to `errors.php`

**`config/bundles.php`:**
- Remove `Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle`
- Remove `SPE\FilesizeExtensionBundle\SPEFilesizeExtensionBundle`

**`config/packages/sensio_framework_extra.yaml`:**
- Delete this file

**`.woodpecker.yml`:**
- Update PHP matrix to 8.2 / 8.3
- Update Node image to `node:20-slim`

### Commands after upgrade

```bash
composer update
yarn install
make build
make doctrine-migration
```

## Upgrade to v1.10.0
### Commands

```
make doctrine-migration
```

## Upgrade to v1.8.0
### Commands

```
make doctrine-migration
```

### Files

Event subscribers in `src/EventSubscriber` must update namespaces.

## Upgrade to v1.7.0
### Commands

```
yarn add sortablejs@^1.14.0

```

### Files

* `assets/css/_admin_extend.scss` is removed
* `assets/css/_admin_vars.scss` is removed
* `assets/css/_admin_vars.scss` is changed
* `assets/js/admin` is removed
* `assets/js/admin.js` is changed


## Upgrade to v1.5.0
### Commands

```
composer remove jaybizzle/crawler-detect
composer require matomo/device-detector
make doctrine-migration
```

## Upgrade to v1.4.0
### Commands

```
yarn remove node-sass
yarn add sass --dev --save
yarn add chart.js --save
composer require jaybizzle/crawler-detect
make doctrine-migration
make asset
```

### Configuration

```
// config/services.yaml
services:
    App\Core\EventListener\RedirectListener:
        tags:
            - { name: kernel.event_listener, event: kernel.exception }

    App\Core\EventListener\AnalyticListener:
        tags:
            - { name: kernel.event_listener, event: kernel.request }
```
