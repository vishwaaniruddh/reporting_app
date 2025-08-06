<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'sites';
    protected $fillable = [
        'Customer',
        'Bank',
        'ATMID',
        'ATMShortName',
        'SiteAddress',
        'DVRIP',
        'Panel_make',
        'zone',
        'City',
        'State',
        'OldPanelID',
        'NewPanelID'
    ];
    public $timestamps = false;

    public static function getPanelMakes()
    {
        return self::distinct()->pluck('Panel_Make');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'panelid', 'OldPanelID')
                    ->orWhere('panelid', 'NewPanelID');
    }
}
