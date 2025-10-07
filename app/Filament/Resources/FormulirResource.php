<?php

namespace App\Filament\Resources;

use App\Models\Formulir;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\File;

class FormulirResource extends Resource
{
    protected static ?string $model = Formulir::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Form Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pribadi')
                    ->schema([
                        Forms\Components\TextInput::make('nama')
                            ->required()
                            ->maxLength(255)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('nama', Str::upper($state)))
                            ->live(onBlur: true),

                        Forms\Components\TextInput::make('nik')
                            ->label('NIK')
                            ->required()
                            ->numeric()
                            ->length(16)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('kelompok_jabatan')
                            ->options([
                                'Tenaga Teknis' => 'Tenaga Teknis',
                                'Tenaga Guru' => 'Tenaga Guru',
                                'Tenaga Kesehatan' => 'Tenaga Kesehatan',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('no_whatsapp')
                            ->label('No WhatsApp')
                            ->required()
                            ->tel()
                            ->numeric(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Upload Dokumen')
                    ->description('Maksimal ukuran file 1MB untuk setiap file')
                    ->schema([
                        Forms\Components\FileUpload::make('skck')
                            ->label('SKCK')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024)
                            ->directory('formulir/skck')
                            ->downloadable(),

                        Forms\Components\FileUpload::make('suket_sehat')
                            ->label('SUKET Sehat')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024)
                            ->directory('formulir/suket-sehat')
                            ->downloadable(),

                        Forms\Components\FileUpload::make('ijazah')
                            ->label('Ijazah')
                            ->multiple()
                            ->disk('public')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024)
                            ->directory('formulir/ijazah')
                            ->downloadable()
                            ->reorderable(),

                        Forms\Components\FileUpload::make('transkrip_nilai')
                            ->label('Transkrip Nilai')
                            ->multiple()
                            ->disk('public')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024)
                            ->directory('formulir/transkrip')
                            ->downloadable()
                            ->reorderable(),

                        Forms\Components\FileUpload::make('surat_pernyataan')
                            ->label('Surat Pernyataan 5 Point')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024)
                            ->directory('formulir/surat-pernyataan')
                            ->downloadable(),

                        Forms\Components\FileUpload::make('pas_foto')
                            ->label('Pas Foto Latar Merah')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024)
                            ->directory('formulir/pas-foto')
                            ->downloadable(),

                        Forms\Components\FileUpload::make('foto_ktp')
                            ->label('Foto KTP')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(1024)
                            ->directory('formulir/ktp')
                            ->downloadable(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nik')
                    ->label('NIK')
                    ->searchable(),

                Tables\Columns\TextColumn::make('kelompok_jabatan')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Tenaga Teknis' => 'success',
                        'Tenaga Guru' => 'warning',
                        'Tenaga Kesehatan' => 'danger',
                    }),

                Tables\Columns\TextColumn::make('email')
                    ->searchable(),

                Tables\Columns\TextColumn::make('no_whatsapp')
                    ->label('WhatsApp'),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('kelompok_jabatan')
                    ->options([
                        'Tenaga Teknis' => 'Tenaga Teknis',
                        'Tenaga Guru' => 'Tenaga Guru',
                        'Tenaga Kesehatan' => 'Tenaga Kesehatan',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => \App\Filament\Resources\FormulirResource\Pages\ListFormulirs::route('/'),
            'create' => \App\Filament\Resources\FormulirResource\Pages\CreateFormulir::route('/create'),
            'edit' => \App\Filament\Resources\FormulirResource\Pages\EditFormulir::route('/{record}/edit'),
        ];
    }
}
