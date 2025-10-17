<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\ArsipPeg;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Actions;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\FormulirResource\Pages\EditFormulir;
use App\Filament\Resources\ArsipPegResource\Pages\ListArsipPegs;
use App\Filament\Resources\FormulirResource\Pages\CreateFormulir;

class ArsipPegResource extends Resource
{
    protected static ?string $model = ArsipPeg::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationGroup = 'Dokumen Pegawai';
    protected static ?string $pluralLabel = 'Dokumen Pegawai';
    protected static ?string $modelLabel = 'Dokumen Pegawai';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nip')
                    ->required()
                    ->maxLength(18)
                    ->numeric()
                    ->readOnly() // NIP tidak bisa diedit
                    ->columnSpanFull(),
                Forms\Components\Fieldset::make('Dokumen DRH')
                    ->schema([
                        Forms\Components\TextInput::make('drh_path')
                            ->label('Path File DRH')
                            ->readOnly(),
                        Forms\Components\Placeholder::make('drh_link')
                            ->label('Link DRH')
                            ->content(fn (ArsipPeg $record): HtmlString => new HtmlString(
                                $record->drh_path ? "<a href='{$record->getDocumentUrl('DRH')}' target='_blank' class='filament-link'>Lihat DRH</a>" : "Belum diunggah."
                            )),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('delete_drh')
                                ->label('Hapus DRH')
                                ->icon('heroicon-o-trash')
                                ->color('danger')
                                ->hidden(fn (ArsipPeg $record): bool => !$record->drh_path)
                                ->requiresConfirmation()
                                ->action(function (ArsipPeg $record) {
                                    if ($record->drh_path && Storage::disk('public')->exists($record->drh_path)) {
                                        Storage::disk('public')->delete($record->drh_path);
                                        $record->update(['drh_path' => null]);
                                        \Filament\Notifications\Notification::make()->title('DRH berhasil dihapus.')->success()->send();
                                    }
                                    return redirect()->back();
                                }),
                        ]),
                    ])
                    ->columns(1),
                // Ulangi Fieldset untuk SKCPNS, SKPNS, dan SPMT dengan logika yang sama
                Forms\Components\Fieldset::make('Dokumen SKCPNS')
                    ->schema([
                        Forms\Components\TextInput::make('skcpns_path')
                            ->label('Path File SKCPNS')
                            ->readOnly(),
                        Forms\Components\Placeholder::make('skcpns_link')
                            ->label('Link SKCPNS')
                            ->content(fn (ArsipPeg $record): HtmlString => new HtmlString(
                                $record->skcpns_path ? "<a href='{$record->getDocumentUrl('SKCPNS')}' target='_blank' class='filament-link'>Lihat SKCPNS</a>" : "Belum diunggah."
                            )),
                        Forms\Components\Actions::make([
                            Forms\Components\Actions\Action::make('delete_skcpns')
                                ->label('Hapus SKCPNS')
                                ->icon('heroicon-o-trash')
                                ->color('danger')
                                ->hidden(fn (ArsipPeg $record): bool => !$record->skcpns_path)
                                ->requiresConfirmation()
                                ->action(function (ArsipPeg $record) {
                                    if ($record->skcpns_path && Storage::disk('public')->exists($record->skcpns_path)) {
                                        Storage::disk('public')->delete($record->skcpns_path);
                                        $record->update(['skcpns_path' => null]);
                                        \Filament\Notifications\Notification::make()->title('SKCPNS berhasil dihapus.')->success()->send();
                                    }
                                    return redirect()->back();
                                }),
                        ]),
                    ])
                    ->columns(1),
                Forms\Components\Fieldset::make('Dokumen SKPNS')
                    ->schema([
                        Forms\Components\TextInput::make('skpns_path')
                            ->label('Path File SKPNS')
                            ->readOnly(),
                        Forms\Components\Placeholder::make('skpns_link')
                            ->label('Link SKPNS')
                            ->content(fn (ArsipPeg$record): HtmlString => new HtmlString(
                                $record->skpns_path ? "<a href='{$record->getDocumentUrl('SKPNS')}' target='_blank' class='filament-link'>Lihat SKPNS</a>" : "Belum diunggah."
                            )),
                        Forms\Components\Actions::make([
                            Action::make('delete_skpns')
                                ->label('Hapus SKPNS')
                                ->icon('heroicon-o-trash')
                                ->color('danger')
                                ->hidden(fn (ArsipPeg $record): bool => !$record->skpns_path)
                                ->requiresConfirmation()
                                ->action(function (ArsipPeg $record) {
                                    if ($record->skpns_path && Storage::disk('public')->exists($record->skpns_path)) {
                                        Storage::disk('public')->delete($record->skpns_path);
                                        $record->update(['skpns_path' => null]);
                                        \Filament\Notifications\Notification::make()->title('SKPNS berhasil dihapus.')->success()->send();
                                    }
                                    return redirect()->back();
                                }),
                        ]),
                    ])
                    ->columns(1),
                Forms\Components\Fieldset::make('Dokumen SPMT')
                    ->schema([
                        Forms\Components\TextInput::make('spmt_path')
                            ->label('Path File SPMT')
                            ->readOnly(),
                        Forms\Components\Placeholder::make('spmt_link')
                            ->label('Link SPMT')
                            ->content(fn (ArsipPeg $record): HtmlString => new HtmlString(
                                $record->spmt_path ? "<a href='{$record->getDocumentUrl('SPMT')}' target='_blank' class='filament-link'>Lihat SPMT</a>" : "Belum diunggah."
                            )),
                        Actions::make([
                            Action::make('delete_spmt')
                                ->label('Hapus SPMT')
                                ->icon('heroicon-o-trash')
                                ->color('danger')
                                ->hidden(fn (ArsipPeg $record): bool => !$record->spmt_path)
                                ->requiresConfirmation()
                                ->action(function (ArsipPeg $record) {
                                    if ($record->spmt_path && Storage::disk('public')->exists($record->spmt_path)) {
                                        Storage::disk('public')->delete($record->spmt_path);
                                        $record->update(['spmt_path' => null]);
                                        \Filament\Notifications\Notification::make()->title('SPMT berhasil dihapus.')->success()->send();
                                    }
                                    return redirect()->back();
                                }),
                        ]),
                    ])
                    ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('DRH')
                    ->formatStateUsing(fn (ArsipPeg $record) => new HtmlString(
                        $record->drh_path ? "<a href='{$record->getDocumentUrl('DRH')}' target='_blank' class='text-primary-600 hover:text-primary-500'>Lihat DRH</a>" : "N/A"
                    ))
                    ->url(fn (ArsipPeg $record): string => $record->getDocumentUrl('DRH') ?: '#')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document')
                    ->badge()
                    ->color(fn (ArsipPeg $record): string => $record->drh_path ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('SKCPNS')
                    ->formatStateUsing(fn (ArsipPeg $record) => new HtmlString(
                        $record->skcpns_path ? "<a href='{$record->getDocumentUrl('SKCPNS')}' target='_blank' class='text-primary-600 hover:text-primary-500'>Lihat SKCPNS</a>" : "N/A"
                    ))
                    ->url(fn (ArsipPeg $record): string => $record->getDocumentUrl('SKCPNS') ?: '#')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document')
                    ->badge()
                    ->color(fn (ArsipPeg $record): string => $record->skcpns_path ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('SKPNS')
                    ->formatStateUsing(fn (ArsipPeg $record) => new HtmlString(
                        $record->skpns_path ? "<a href='{$record->getDocumentUrl('SKPNS')}' target='_blank' class='text-primary-600 hover:text-primary-500'>Lihat SKPNS</a>" : "N/A"
                    ))
                    ->url(fn (ArsipPeg $record): string => $record->getDocumentUrl('SKPNS') ?: '#')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document')
                    ->badge()
                    ->color(fn (ArsipPeg $record): string => $record->skpns_path ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('SPMT')
                    ->formatStateUsing(fn (ArsipPeg $record) => new HtmlString(
                        $record->spmt_path ? "<a href='{$record->getDocumentUrl('SPMT')}' target='_blank' class='text-primary-600 hover:text-primary-500'>Lihat SPMT</a>" : "N/A"
                    ))
                    ->url(fn (ArsipPeg $record): string => $record->getDocumentUrl('SPMT') ?: '#')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document')
                    ->badge()
                    ->color(fn (ArsipPeg $record): string => $record->spmt_path ? 'success' : 'gray'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                // Admin bisa menghapus file satu per satu dari view
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('delete_drh_action')
                        ->label('Hapus DRH')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->hidden(fn (ArsipPeg $record): bool => !$record->drh_path)
                        ->requiresConfirmation()
                        ->action(function (ArsipPeg$record) {
                            if ($record->drh_path && Storage::disk('public')->exists($record->drh_path)) {
                                Storage::disk('public')->delete($record->drh_path);
                                $record->update(['drh_path' => null]);
                                \Filament\Notifications\Notification::make()->title('DRH berhasil dihapus.')->success()->send();
                            }
                        }),
                    Tables\Actions\Action::make('delete_skcpns_action')
                        ->label('Hapus SKCPNS')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->hidden(fn (ArsipPeg $record): bool => !$record->skcpns_path)
                        ->requiresConfirmation()
                        ->action(function (ArsipPeg$record) {
                            if ($record->skcpns_path && Storage::disk('public')->exists($record->skcpns_path)) {
                                Storage::disk('public')->delete($record->skcpns_path);
                                $record->update(['skcpns_path' => null]);
                                \Filament\Notifications\Notification::make()->title('SKCPNS berhasil dihapus.')->success()->send();
                            }
                        }),
                    Tables\Actions\Action::make('delete_skpns_action')
                        ->label('Hapus SKPNS')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->hidden(fn (ArsipPeg $record): bool => !$record->skpns_path)
                        ->requiresConfirmation()
                        ->action(function (ArsipPeg $record) {
                            if ($record->skpns_path && Storage::disk('public')->exists($record->skpns_path)) {
                                Storage::disk('public')->delete($record->skpns_path);
                                $record->update(['skpns_path' => null]);
                                \Filament\Notifications\Notification::make()->title('SKPNS berhasil dihapus.')->success()->send();
                            }
                        }),
                    Tables\Actions\Action::make('delete_spmt_action')
                        ->label('Hapus SPMT')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->hidden(fn (ArsipPeg$record): bool => !$record->spmt_path)
                        ->requiresConfirmation()
                        ->action(function (ArsipPeg $record) {
                            if ($record->spmt_path && Storage::disk('public')->exists($record->spmt_path)) {
                                Storage::disk('public')->delete($record->spmt_path);
                                $record->update(['spmt_path' => null]);
                                \Filament\Notifications\Notification::make()->title('SPMT berhasil dihapus.')->success()->send();
                            }
                        }),
                ]),
                // Tables\Actions\DeleteAction::make(), // Tidak perlu menghapus seluruh baris
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->before(function (\Illuminate\Support\Collection $records) {
                            foreach ($records as $record) {
                                // Hapus semua file fisik dari storage yang terkait dengan baris ini
                                if ($record->drh_path && Storage::disk('public')->exists($record->drh_path)) {
                                    Storage::disk('public')->delete($record->drh_path);
                                }
                                if ($record->skcpns_path && Storage::disk('public')->exists($record->skcpns_path)) {
                                    Storage::disk('public')->delete($record->skcpns_path);
                                }
                                if ($record->skpns_path && Storage::disk('public')->exists($record->skpns_path)) {
                                    Storage::disk('public')->delete($record->skpns_path);
                                }
                                if ($record->spmt_path && Storage::disk('public')->exists($record->spmt_path)) {
                                    Storage::disk('public')->delete($record->spmt_path);
                                }
                            }
                        }),
                ]),
            ])
            ->emptyStateActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListArsipPegs::route('/'),
            'create' => CreateFormulir::route('/create'),
            'edit' => EditFormulir::route('/{record}/edit'),
        ];
    }


}
