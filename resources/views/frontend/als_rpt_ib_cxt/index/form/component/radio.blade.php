<fieldset>
    @foreach (['几乎从不5%', '很少25%', '有时50%', '经常75%', '几乎总是95%'] as $key => $desc)
    <div class="radio" style="float: left; margin: 10px 4px 10px 0;">
        <label>
            <input type="radio" name="{{ $name }}" id="{{ $name }}" value="{{$key}}" 
            <?php $cxtValue = $cxt->getContentValue($name); ?>
            @if(false !== $cxtValue and !is_null($cxtValue) and (int) $cxtValue === $key) 
                checked 
            @endif />{{$desc}} 
        </label>
    </div>
    @endforeach
</fieldset>