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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['is_open', 'open_at', 'close_at'];

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
        list($statusDesc, $class) = $this->_initStatusDescVariable();

        return true === $isWrap ? "<span class=\"label {$class}\">{$statusDesc}</span>" : $statusDesc;
    }

    private function _initStatusDescVariable()
    {
        return array(
            (true === $this->is_open ? '開放' : '關閉'), 
            (true === $this->is_open ? 'label-success' : 'label-danger')
        );
    }

    public function isValid()
    {
        return true === $this->is_open 
            && Carbon::now() <= $this->close_at
            && Carbon::now() >= $this->open_at
        ;
    }

    public function isPublicKeyValid($publicKey)
    {
        return $this->public_key === $publicKey;
    }

    public function cxts()
    {
        return $this->hasMany('App\Model\AlsRptIbCxt', 'channel_id', 'id');
    }

    public function scopeFindByKey($query, $privateKey)
    {   
        $query->where('private_key', '=', $privateKey);
    }

    public function scopeFindByCreater($query, User $user)
    {
        $query->where('creater_id', '=', $user->id);
    }
}
