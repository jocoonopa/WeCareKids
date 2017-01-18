<div class="form-group">
    <label for="name">姓名:</label>
    <input id="name" name="name" type="text" class="form-control" value="{{$user->name}}" required>
</div>
<div class="form-group">
    <label for="email">Email:</label>
    <input id="email" name="email" type="email" class="form-control" value="{{$user->email}}" required>
</div>
<div class="form-group">
    <button type="submit" class="btn btn-success">确认</button>
    <a href="/backend/user" class="btn btn-primary">取消</a>
</div>