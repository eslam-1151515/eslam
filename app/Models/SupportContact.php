<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportContact extends Model
{
    protected $fillable = [
        'type',
        'title',
        'phone_number',
        'whatsapp_message',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get clean phone for wa.me link
     */
    public function getCleanPhoneAttribute(): string
    {
        $phone = preg_replace('/[^0-9]/', '', $this->phone_number);
        // If Egyptian number starting with 01
        if (str_starts_with($phone, '01') && strlen($phone) === 11) {
            return '2' . $phone;
        }
        return $phone;
    }

    /**
     * Get direct action link (wa.me or tel:)
     */
    public function getActionUrlAttribute(): string
    {
        if ($this->type === 'whatsapp') {
            $url = "https://wa.me/" . $this->clean_phone;
            if ($this->whatsapp_message) {
                $url .= "?text=" . urlencode($this->whatsapp_message);
            }
            return $url;
        }
        return "tel:" . $this->phone_number;
    }
}
