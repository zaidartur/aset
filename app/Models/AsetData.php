<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AsetData extends Model
{
    /**
     * Get the subdata that owns the AsetData
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subdata(): BelongsTo
    {
        return $this->belongsTo(MasterSubdata::class, 'kode_utama', 'uuid_subdata');
    }

    /**
     * Get the parameter that owns the AsetData
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parameter(): BelongsTo
    {
        return $this->belongsTo(MasterData::class, 'kode_parent', 'uuid_aset');
    }
}
