# inupress/editor-ckeditor

CKEditor 5 in the Inupress admin. It replaces the built-in editor in every content field —
pages, widgets, and anything a plugin adds later — and changes nothing else.

This package is developed in the `inupress/framework` monorepo (`packages/editor-ckeditor`).
Report issues and send changes there.

## Install

```bash
composer require inupress/editor-ckeditor
```

That is the whole switch: no setting, no `.env` key, no migration. The content is HTML in the
database either way, so pages written before the install open in CKEditor and the other way
round. Remove it with `composer remove inupress/editor-ckeditor` and the built-in editor is back.

## How it works

The core asks a filter for the field to render — `Inupress\Core\Support\ContentEditor::field()`
calls `content.editor` — and this plugin answers with CKEditor instead of the built-in
`RichEditor` (TipTap):

```php
$hooks->addFilter(Hooks::CONTENT_EDITOR, fn (Field $field, string $name, string $source) => CKEditor::make($name));
```

The caller configures what comes back, so `required()` and the rest survive the swap. A plugin
that wants to change the field after the editor is chosen (add toolbar buttons, say) registers on
the same filter at a higher priority and receives the CKEditor field.

## What it adds

- `kahusoftware/filament-ckeditor-field` — the Filament field, configured with the free CKEditor 5
  feature set;
- nothing else: no tables, no routes, no admin screens, no assets of our own. CKEditor itself
  ships inside that dependency.

## Before you install it

- **One editor plugin at a time.** Two plugins that answer `content.editor` both run; the last
  callback decides the field, so which editor wins is not something the admin should have to guess.
- **The licence is the dependency's, not ours.** CKEditor 5 is GPL-2.0-or-later or commercial.
  Inupress is MIT and the starter does not ship this plugin, so a site that installs it carries
  CKEditor's own terms (it is only in the admin, never on the public site).
- The built-in editor stays the default. A fresh `composer create-project inupress/inupress` has
  TipTap in the panel and no extra dependency.

## License

MIT. CKEditor 5 and the field package keep their own licences.
