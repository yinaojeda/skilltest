<?php

namespace App\Traits;

trait CommonQueryScopes
{
    public function scopeFilterByStatus($query, $status = null)
    {
        if ($status) return $query->where('status', $status);
        return $query;
    }

    public function scopeSearchByTitle($query, $q = null)
    {
        if ($q) return $query->where('title', 'like', "%{$q}%");
        return $query;
    }
}
