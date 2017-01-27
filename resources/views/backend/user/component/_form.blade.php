<div class="form-group">
    <label for="name">姓名:</label>
    <input id="name" name="name" type="text" class="form-control" value="{{$user->name}}" required />
</div>
<div class="form-group">
    <label for="email">Email:</label>
    <input id="email" name="email" type="email" class="form-control" value="{{$user->email}}" required />
</div>
<div class="form-group">
    <label for="phone">電話:</label>
    <input type="number" name="phone" id="phone" class="form-control" value="{{$user->phone}}" required />
</div>
<div class="form-group">
    <label for="organization_id">加盟商:</label>

    <select name="organization_id" id="organization_id" class="form-control">
        <option value="0"></option>
        @foreach (\App\Model\Organization::all() as $organization)
            <option value="{{ $organization->id }}" @if($organization->id == $user->organization_id) selected @endif>{{ $organization->name }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-success btn-sm">
        <i class="fa fa-check-circle-o"></i>
        确认
    </button>
    <a href="/backend/user" class="btn btn-default btn-sm">
        <i class="fa fa-circle-o"></i>
        取消
    </a>
</div>