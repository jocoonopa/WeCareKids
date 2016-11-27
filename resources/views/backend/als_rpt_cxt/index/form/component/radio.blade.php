<fieldset>
    @foreach (['几乎从不5%', '很少25%', '有时50%', '经常75%', '几乎总是95%'] as $key => $desc)
        <label class="radio-inline">
            <input type="radio" name="{{ $name }}" id="{{ $name }}" value="{{$key}}" 
            @if($cxt->getContentValue($name) == $key) 
                checked 
            @endif />{{$desc}} 
        </label>
    @endforeach
</fieldset>