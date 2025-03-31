<?php

namespace App\Core;

use Carbon\Carbon;
use Spatie\QueryBuilder\AllowedFilter;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class BaseModel extends Model implements Auditable
{
    use AuditableTrait;
    protected $connection = 'mysql';

    /**
     * Return the default allowed filters for all models.
     */
    public function allowedFilters()
    {
        return [
            AllowedFilter::exact('id'),
            AllowedFilter::exact('created_at'),
            AllowedFilter::exact('updated_at'),
            AllowedFilter::exact('deleted_at'),
            AllowedFilter::scope('created_before'),
            AllowedFilter::scope('created_after'),
            AllowedFilter::scope('created_between'),
            AllowedFilter::trashed(),
        ];
    }

    /**
     * Return the default allowed sorts for all models.
     */
    public function allowedSorts()
    {
        return [
            'id',
            'created_at',
            'updated_at'
        ];
    }

    public function allowedIncludes()
    {
        return [
            'audits',
            'audits.user'
        ];
    }

    public function scopeCreatedBefore($query, $date)
    {
        return $query->where('created_at', '<=', Carbon::parse($date));
    }

    public function scopeCreatedAfter($query, $date)
    {
        return $query->where('created_at', '>=', Carbon::parse($date));
    }

    public function scopeCreatedBetween($query, $date1, $date2)
    {
        return $query->whereBetween('created_at', [$date1, $date2]);
    }
}
