<?php

namespace App\Models\Postgres;

use Illuminate\Database\Eloquent\Model;

class PgSite extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'sites';
    protected $primaryKey = 'SN';
    public $timestamps = false;

    protected $fillable = [
        'Customer',
        'Bank',
        'ATMID',
        'ATMShortName',
        'SiteAddress',
        'DVRIP',
        'Panel_Make',
        'Zone',
        'City',
        'State',
        'OldPanelID',
        'NewPanelID'
    ];

    public static function findByPanelId($panelIds)
    {
        return static::select([
                'sites.OldPanelID',
                'sites.NewPanelID',
                'sites.Customer',
                'sites.Zone',
                'sites.ATMID',
                'sites.SiteAddress',
                'sites.City',
                'sites.State',
                'sites.DVRIP',
                'sites.Panel_Make',
                'sites.Bank'
            ])
            ->whereIn('sites.OldPanelID', $panelIds)
            ->orWhereIn('sites.NewPanelID', $panelIds)
            ->get();
    }
}
