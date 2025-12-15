<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalSource extends Model
{
    protected $fillable = [
        "source_url",
        "source_type",
        "name_or_title",
        "phone_number",
        "email",
        "property_type",
        "city",
        "district",
        "is_qualified"
    ];
}
