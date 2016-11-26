<?php

namespace App\Model;

use App\Model\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * AnalysisReportInboundChannel
 */
class AlsRptIbChannel extends Model
{
    protected $table = 'als_rpt_ib_channels';

    public static function createPrototype(User $user)
    {
        $channel = new AlsRptIbChannel;
        
        $channel->creater_id = $user->id;
        $channel->open_at = Carbon::now();
        $channel->close_at = Carbon::now()->modify('+7 days');
        $channel->is_open = true;
        $channel->public_key = md5(time() . uniqid());
        
        return $channel;
    }

    public function getStatusDesc()
    {
        return $this->is_open ? '開放' : '關閉';
    }

    public function cxts()
    {
        return $this->hasMany('App\Model\AlsRptIbCxt', 'id', 'channel_id');
    }

    public function scopeFindByKey($query, $privateKey)
    {   
        $query->where('private_key', '=', $privateKey);
    }
}
