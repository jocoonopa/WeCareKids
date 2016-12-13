<!-- #################### 感覺處理剖析圖 Begin #################### -->
<div class="row">
@foreach ([
        \App\Model\AlsRptIbCxt::SYMBOL_LAND => '#FFCDD2',
        \App\Model\AlsRptIbCxt::SYMBOL_SEARCH => '#C8E6C9',
        \App\Model\AlsRptIbCxt::SYMBOL_SENSITIVE => '#D1C4E9',
        \App\Model\AlsRptIbCxt::SYMBOL_DODGE => '#BBDEFB'
    ] as $symbol => $color
)
<?php $quarLevel = array_get($quarLevels, $symbol); ?>
<?php $loopCursor = 0; ?>

<div class="col-md-6 col-sm-0 col-xs-6" style="padding: 0;">
    <table class="table analyze-border" style="margin: 0; padding: 0; background-color: {{$color}}">
    <tbody>
    @if ($symbol === \App\Model\AlsRptIbCxt::SYMBOL_LAND)
        @for ($i = 4; $i >= 0; $i --)
        <tr>
            @for ($j = 0; $j < 5; $j ++)
                @if ($j === $loopCursor)
                    <td>
                        @if ($i === $quarLevel)
                        <i class="fa fa-circle" fa-2x></i>
                        @else
                        <i class="fa fa-circle-o" fa-2x></i>
                        @endif
                    </td>
                @else
                    <td></td>
                @endif
            @endfor
        </tr>
        <?php $loopCursor ++ ?>
        @endfor
    @endif

    @if ($symbol === \App\Model\AlsRptIbCxt::SYMBOL_SEARCH)
        @for ($i = 4; $i >= 0; $i --)
        <tr>
            @for ($j = 4; $j >= 0; $j --)
                @if ($j === $loopCursor)
                    <td>
                        @if ($i === $quarLevel)
                        <i class="fa fa-circle" fa-2x></i>
                        @else
                        <i class="fa fa-circle-o" fa-2x></i>
                        @endif
                    </td>
                @else
                    <td></td>
                @endif
            @endfor
        </tr>
        <?php $loopCursor ++ ?>
        @endfor
    @endif

    @if ($symbol === \App\Model\AlsRptIbCxt::SYMBOL_SENSITIVE)
        @for ($i = 0; $i < 5; $i ++)
        <tr>
            @for ($j = 4; $j >= 0; $j --)
                @if ($j === $loopCursor)
                    <td>
                        @if ($i === $quarLevel)
                        <i class="fa fa-circle" fa-2x></i>
                        @else
                        <i class="fa fa-circle-o" fa-2x></i>
                        @endif
                    </td>
                @else
                    <td></td>
                @endif
            @endfor
        </tr>
        <?php $loopCursor ++ ?>
        @endfor
    @endif

    @if ($symbol === \App\Model\AlsRptIbCxt::SYMBOL_DODGE)
        @for ($i = 0; $i < 5; $i ++)
        <tr>
            @for ($j = 0; $j < 5; $j ++)
                @if ($j === $loopCursor)
                    <td>
                        @if ($i === $quarLevel)
                        <i class="fa fa-circle" fa-2x></i>
                        @else
                        <i class="fa fa-circle-o" fa-2x></i>
                        @endif
                    </td>
                @else
                    <td></td>
                @endif
            @endfor
        </tr>
        <?php $loopCursor ++ ?>
        @endfor
    @endif
    </tbody>  
    </table>
</div>
@endforeach
</div>
<br/>