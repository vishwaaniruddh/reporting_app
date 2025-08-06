<?php

namespace App\Models\Postgres;

use Illuminate\Database\Eloquent\Model;

class PgAlert extends Model
{
    protected $connection = 'pgsql';
    protected $table = 'alerts';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id',
        'panelid',
        'seqno',
        'zone',
        'alarm',
        'createtime',
        'receivedtime',
        'comment',
        'status',
        'sendtoclient',
        'closedBy',
        'closedtime',
        'sendip',
        'alerttype',
        'location',
        'priority',
        'AlertUserStatus',
        'level',
        'sip2',
        'c_status',
        'auto_alert',
        'critical_alerts',
        'Readstatus'
    ];

    public function site()
    {
        return $this->belongsTo(PgSite::class, 'panelid', 'OldPanelID')
                    ->orWhere('panelid', 'NewPanelID');
    }
}
