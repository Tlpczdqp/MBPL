<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
        'notifiable_type',
        'notifiable_id',
        'title',
        'message',
        'link',
        'is_read',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
        ];
    }

    // Polymorphic relationship
    // This lets one Notification belong to either a User or Employee
    public function notifiable()
    {
        return $this->morphTo();
    }
}