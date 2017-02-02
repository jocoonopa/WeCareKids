<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav>
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                        <img src="{{ Gravatar::src(Auth::user()->email) }}" alt="Avatar of {{ Auth::user()->name }}">
                        {{ Auth::user()->name }}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu pull-right">
                        <li>
                            <a href="{{ url('/auth/logout') }}" onclick="return confirm('確定要登出嗎?')">
                                <i class="fa fa-sign-out pull-right"></i>登出
                            </a>
                        </li>

                        <li>
                            <a href="{{Wck::getUserChannel()->getUrl()}}"> 
                                <i class="fa fa-qrcode pull-right"></i>
                                QRCode
                            </a>
                        </li>

                        <li>
                            <form action="/backend/analysis/r/i/channel/{{Wck::getUserChannel()->id}}/is_open" method="post" style="padding-left: 10px;">   
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="put" />
                                
                                <div class="form-group">
                                     @if(Wck::getUserChannel()->isOpen())
                                        <button type="submit" class="btn btn-default btn-xs">
                                            關閉頻道 
                                        </button>
                                        <span class="label label-success pull-right">目前開啟</span>
                                    @else
                                        <button type="submit" class="btn btn-default btn-xs">
                                            打開頻道 
                                        </button>
                                        <span class="label label-warning pull-right">目前關閉</span>
                                    @endif
                                </div>                               
                            </form>
                        </li>    
                    </ul>
                </li>
            </ul>
        </nav>
    </div>
</div>
<!-- /top navigation -->