<?php

namespace App\Livewire;

use App\Models\ArsipPeg;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class UploadArsipPegawai extends Component implements HasForms
{
    use InteractsWithForms;

    public ?array $data = [];
    public ?string $nip = null;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema($this->getFormSchema())
            ->statePath('data');
    }

    protected function getFormSchema(): array
    {
        return [
            TextInput::make('nip')
                ->label('NIP Pegawai')
                ->required()
                ->numeric()
                ->length(18)
                ->placeholder('Masukkan 18 digit NIP')
                ->live(onBlur: true)
                ->afterStateUpdated(function ($state) {
                    $this->nip = $state;
                })
                ->helperText('NIP harus 18 digit angka dan akan digunakan untuk validasi nama file.'),

            Grid::make(1)
                ->schema([
                    $this->getUploadField('drh_file', 'DRH'),
                    $this->getUploadField('skcpns_file', 'SKCPNS'),
                    $this->getUploadField('skpns_file', 'SKPNS'),
                    $this->getUploadField('spmt_file', 'SPMT'),
                ])
                ->visible(fn (): bool => !empty($this->nip) && strlen($this->nip) === 18),
        ];
    }

    protected function getUploadField(string $name, string $type): FileUpload
    {
        return FileUpload::make($name)
            ->label("Unggah {$type}")
            ->acceptedFileTypes(['application/pdf'])
            ->maxSize(1024)
            ->disk('public')
            ->directory('uploads/' . strtolower($type))
            ->downloadable()
            ->deletable()
            ->rules([
                'nullable',
                'file',
                'mimes:pdf',
                'max:1024',
                function () use ($type) {
                    return function (string $attribute, $value, \Closure $fail) use ($type) {
                        if ($value && $this->nip && strlen($this->nip) === 18) {
                            $expectedFileName = "{$type}_{$this->nip}.pdf";
                            $fileName = is_array($value) ? $value[0]->getClientOriginalName() : $value->getClientOriginalName();

                            if ($fileName !== $expectedFileName) {
                                $fail("Nama file {$type} harus: {$expectedFileName}");
                            }
                        }
                    };
                },
            ]);
    }

    public function upload()
    {
        try {
            if (!$this->nip || strlen($this->nip) !== 18) {
                throw ValidationException::withMessages([
                    'nip' => 'NIP harus 18 digit angka.',
                ]);
            }

            $formData = $this->form->getState();
            $arsipPeg = ArsipPeg::firstOrCreate(['nip' => $this->nip]);
            $uploadedCount = 0;

            $fileMappings = [
                'drh_file' => 'drh_path',
                'skcpns_file' => 'skcpns_path',
                'skpns_file' => 'skpns_path',
                'spmt_file' => 'spmt_path',
            ];

            foreach ($fileMappings as $formField => $dbColumn) {
                if (!empty($formData[$formField])) {
                    $file = $formData[$formField][0];
                    $fileType = strtoupper(str_replace('_file', '', $formField));
                    $expectedFileName = "{$fileType}_{$this->nip}.pdf";

                    if ($file->getClientOriginalName() !== $expectedFileName) {
                        throw ValidationException::withMessages([
                            $formField => "Nama file harus: {$expectedFileName}",
                        ]);
                    }

                    if ($arsipPeg->{$dbColumn} && Storage::disk('public')->exists($arsipPeg->{$dbColumn})) {
                        Storage::disk('public')->delete($arsipPeg->{$dbColumn});
                    }

                    $filePath = $file->storeAs(
                        'uploads/' . strtolower($fileType),
                        $expectedFileName,
                        'public'
                    );

                    $arsipPeg->{$dbColumn} = $filePath;
                    $uploadedCount++;
                }
            }

            if ($uploadedCount > 0) {
                $arsipPeg->save();

                Notification::make()
                    ->title('Berhasil!')
                    ->body("{$uploadedCount} dokumen berhasil diunggah untuk NIP {$this->nip}.")
                    ->success()
                    ->send();

                $this->form->fill();
                $this->nip = null;

            } else {
                Notification::make()
                    ->title('Peringatan')
                    ->body('Tidak ada file yang diunggah.')
                    ->warning()
                    ->send();
            }

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Notification::make()
                ->title('Error')
                ->body('Terjadi kesalahan: ' . $e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function render()
    {
        return view('livewire.upload-arsip-pegawai')
            ->layout('layouts.app'); // Gunakan layout default Laravel
    }
}
