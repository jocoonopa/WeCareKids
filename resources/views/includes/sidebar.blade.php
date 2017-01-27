    <div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="/backend/home" class="site_title">
                <i class="fa fa-paw"></i> 
                <span>{{ trans('wck.company_name') }}</span>
            </a>
        </div>
        
        <div class="clearfix"></div>
        
        <!-- menu profile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="{{ Gravatar::src(Auth::user()->email) }}" alt="Avatar of {{ Auth::user()->name }}" class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span>Welcome,</span>
                @if (Auth::user()->isOwner() && !Auth::user()->isSuper())
                    <div>
                        <span class="label label-success white">剩餘: {{ array_get($counts, 'points') . '點' }}</span>
                    </div>
                @endif    
                <h2>
                    {{ Auth::user()->name }}

                    @if(Auth::user()->isSuper())
                        <span class="label label-default white">系統管理員</span>
                    @elseif(Auth::user()->isOwner())
                        <span class="label label-warning white">擁有者</span>
                    @else
                        <span class="label label-danger white">教師</span>
                    @endif
                </h2>        
            </div>
            <div>
                <span class="label label-default">問卷: {{ array_get($counts, 'rpt') . '筆' }}</span>
                <span class="label label-default">評測: {{ array_get($counts, 'cxt') . '筆' }}</span>   
                <span class="label label-default">孩童: {{ array_get($counts, 'child'). '位' }}</span> 
            </div>
        </div>
        <!-- /menu profile quick info -->
        
        <br />
        
        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>&nbsp;</h3>
                <ul class="nav side-menu">
                    @foreach ($menus as $menu)
                    <li>
                        <a href="{{ $menu['url'] }}">
                        <i class="fa fa-chevron-right"></i>
                        {{ $menu['name'] }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
        
        <!-- /menu footer buttons -->
        <div class="sidebar-footer hidden-small">
        </div>
        <!-- /menu footer buttons -->
    </div>
</div>
