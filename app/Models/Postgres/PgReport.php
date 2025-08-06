<?php

namespace App\Models\Postgres;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PgReport extends Model
{
    protected $connection = "pgsql";
    protected $table = "alerts";
    public $timestamps = false;

    protected $fillable = [
        "id",
        "panelid",
        "zone",
        "alarm",
        "alerttype",
        "createtime",
        "receivedtime",
        "closedtime",
        "closedBy",
        "comment",
        "sendip",
        "sendtoclient"
    ];
}
