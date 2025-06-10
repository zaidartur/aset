<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterSubdata extends Model
{
    /**
     * Get the parameter that owns the MasterSubdata
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function isparam(): BelongsTo
    {
        return $this->belongsTo(MasterData::class, 'parent', 'uuid_aset');
    }

    /**
     * Get all of the aset for the MasterSubdata
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aset(): HasMany
    {
        return $this->hasMany(AsetData::class, 'uuid_subdata', 'kode_utama');
    }
}
