<div class="form-group">
    <label class="col-md-2 control-label" for="name">加盟商名称</label>
    <input id="name" name="name" class="form-control" value="{{ $organization->name }}" placeholder="ex: 長沙國小" required/>
</div>

<div class="form-group">
    <label class="col-md-2 control-label" for="region">地區</label>
    <input id="region" name="region" class="form-control" value="{{ $organization->region }}" placeholder="ex: 長沙"/>
</div>

<div class="form-group">
    <label class="col-md-2 control-label" for="owner">拥有人</label>
    <select name="owner" id="owner"class="form-control">
        <option value="0">未指定</option>

        @foreach ($users as $user)
            <option value="{{ $user->id }}" @if($organization->isOwner($user)) selected  @endif>
                {{ $user->name }}
            </option>尚未指定
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="col-md-2 control-label" for="contacter">聯絡人</label>
    <select name="contacter" id="contacter"class="form-control">
        <option value="0">未指定</option>

        @foreach ($users as $user)
            <option value="{{ $user->id }}" @if($organization->isContacter($user)) selected  @endif>
                {{ $user->name }}
            </option>尚未指定
        @endforeach
    </select>
</div>

<div class="form-group">
    <button class="btn btn-success btn-sm">
        <i class="fa fa-check-circle-o"></i>
        确认
    </button>

    <a href="/backend/organization" class="btn btn-default btn-sm">
        <i class="fa fa-circle-o"></i>
        取消
    </a>
</div>