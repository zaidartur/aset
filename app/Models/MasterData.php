<?php

namespace App\Models;

use App\Models\MasterSubdata;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MasterData extends Model
{
    /**
     * Get all of the comments for the MasterData
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subdata(): HasMany
    {
        return $this->hasMany(MasterSubdata::class, 'uuid_aset', 'parent');
    }

    /**
     * Get all of the barang for the MasterData
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function barang(): HasMany
    {
        return $this->hasMany(AsetData::class, 'uuid_aset', 'kode_parent');
    }
}
