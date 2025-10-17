<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ArsipPeg extends Model
{
    use HasFactory;

    protected $fillable = [
        'nip',
        'email',
        'drh_path',
        'skcpns_path',
        'skpns_path',
        'spmt_path',
    ];

    /**
     * Helper to get public URL for a given document type.
     */
    public function getDocumentUrl(string $type): ?string
    {
        $pathColumn = strtolower($type) . '_path'; // e.g., 'drh_path'

        if ($this->{$pathColumn}) {
            return Storage::disk('public')->url($this->{$pathColumn});
        }
        return null;
    }
}
