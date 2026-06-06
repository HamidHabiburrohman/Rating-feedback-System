<?php

namespace App\Models\System;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'key', 'value', 'type', 'group', 'subgroup', 'label',
        'description', 'hint', 'options', 'validation_rules',
        'sort_order', 'is_editable', 'is_visible', 'is_public',
        'required_permission'
    ];

    protected $casts = [
        'value' => 'string',
        'options' => 'array',
        'sort_order' => 'integer',
        'is_editable' => 'boolean',
        'is_visible' => 'boolean',
        'is_public' => 'boolean',
    ];

    public function getValueAttribute($value)
    {
        switch ($this->type) {
            case 'boolean':
                return filter_var($value, FILTER_VALIDATE_BOOLEAN);
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'json':
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    public function setValueAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['value'] = json_encode($value);
        } elseif (is_bool($value)) {
            $this->attributes['value'] = $value ? '1' : '0';
        } else {
            $this->attributes['value'] = (string) $value;
        }
    }

    protected static function newFactory()
    {
        return \Database\Factories\SettingFactory::new();
    }
}