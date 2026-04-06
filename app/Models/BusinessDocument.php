<?php
// This file was missing — that's why Laravel threw "unknown class"
// Laravel needs a Model file for every table you want to work with

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDocument extends Model
{
    // Tell Laravel which fields can be mass-assigned (filled via create() or update())
    protected $fillable = [
        'business_application_id',
        'document_type',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
    ];

    // RELATIONSHIP: This document belongs to ONE business application
    // If you call $document->businessApplication, it gives you the parent application
    public function businessApplication()
    {
        return $this->belongsTo(BusinessApplication::class);
    }

    // Helper: return a human-readable label for the document type
    // Instead of 'dti_sec_certificate' → 'DTI / SEC Certificate'
    public function getDocumentLabelAttribute(): string
    {
        $labels = [
            'dti_sec_certificate' => 'DTI / SEC Certificate',
            'valid_id'            => 'Valid ID (with 3 Signatures)',
            'business_photo'      => 'Photo of Business',
            'business_sketch'     => 'Business Sketch / Location Map',
        ];

        // Return the label if found, otherwise clean up the raw type
        return $labels[$this->document_type]
            ?? ucwords(str_replace('_', ' ', $this->document_type));
    }

    // Helper: return file size in kilobytes
    // Usage: $document->size_in_kb → "45.2 KB"
    public function getSizeInKbAttribute(): string
    {
        if (!$this->file_size) return 'Unknown size';
        return number_format($this->file_size / 1024, 1) . ' KB';
    }

    // Helper: check if this document is an image (for preview purposes)
    public function isImage(): bool
    {
        $imageTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        return in_array($this->mime_type, $imageTypes);
    }
}