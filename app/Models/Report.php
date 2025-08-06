<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'alerts';
    protected $fillable = [
        'panelid', 'seqno', 'zone', 'alarm', 'createtime', 'receivedtime', 
        'comment', 'status', 'sendtoclient', 'closedBy', 'closedtime', 
        'sendip', 'alerttype', 'location', 'priority', 'AlertUserStatus', 
        'level', 'sip2', 'c_status', 'auto_alert', 'critical_alerts', 'Readstatus'
    ];
    public $timestamps = false;

    /**
     * Get the site associated with the report.
     * This relationship checks both OldPanelID and NewPanelID fields
     */
    public function site()
    {
        return $this->belongsTo(Site::class, 'panelid', 'OldPanelID')
                    ->orWhere('sites.NewPanelID', '=', 'alerts.panelid');
    }

    /**
     * Get the customer associated with the report.
     */
    public function customer()
    {
        return $this->belongsTo(Client::class, 'customer_id');
    }

    /**
     * Calculate the aging of the report
     */
    public function getAgingAttribute()
    {
        if (!$this->createtime || !$this->closedtime) {
            return null;
        }
        
        $created = new \DateTime($this->createtime);
        $closed = new \DateTime($this->closedtime);
        
        return $created->diff($closed)->format('%d days, %h hours, %i minutes');
    }
}
