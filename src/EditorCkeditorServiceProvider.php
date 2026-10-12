<?php

declare(strict_types=1);

namespace Inupress\EditorCkeditor;

use Filament\Forms\Components\Field;
use Inupress\Contracts\HookRegistry;
use Inupress\Contracts\Hooks;
use Kahusoftware\FilamentCkeditorField\CKEditor;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

/**
 * CKEditor 5 in every admin form that asks for a content field.
 *
 * The plugin owns no table, no route and no page: it answers the `content.editor` hook with a
 * CKEditor field, and the callers (`inupress/pages`, `inupress/widgets`) render it as they would
 * the built-in `RichEditor`. The stored value is HTML either way, so the switch needs no
 * migration: pages written with TipTap open in CKEditor and the other way round.
 *
 * Installing the package is the switch ([decision 0006](../../../docs/decisions/0006-composer-only-plugins.md));
 * there is no setting and no env key. Install one editor plugin at a time — two of them both
 * answer the same filter and the last callback to run decides the field.
 */
final class EditorCkeditorServiceProvider extends PackageServiceProvider
{
    public static string $name = 'inupress-editor-ckeditor';

    public function configurePackage(Package $package): void
    {
        $package->name(self::$name);
    }

    public function packageBooted(): void
    {
        $this->app->make(HookRegistry::class)->addFilter(
            Hooks::CONTENT_EDITOR,
            static fn (Field $field, string $name, string $source): Field => CKEditor::make($name),
        );
    }
}
