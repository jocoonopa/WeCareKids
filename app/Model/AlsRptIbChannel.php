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

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_open' => 'boolean',
       
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'open_at',
        'close_at',
        'created_at',
        'updated_at'
    ];

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

    public function getStatusDesc($isWrap = NULL)
    {
        $statusDesc = $this->is_open ? '開放' : '關閉';

        return true === $isWrap ? '<span class="label label-success">' . $statusDesc . '</span>'
            : '<span class="label label-default">' . $statusDesc . '</span>';
    }

    public function cxts()
    {
        return $this->hasMany('App\Model\AlsRptIbCxt', 'channel_id', 'id');
    }

    public function scopeFindByKey($query, $privateKey)
    {   
        $query->where('private_key', '=', $privateKey);
    }
}
