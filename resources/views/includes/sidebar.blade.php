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
                @if (Auth::user()->isOwner())
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
                    <li>
                        <a href="/backend/analysis/r/i/cxt"><i class="fa fa-file-text-o"></i>问卷</a>
                    </li>
                    <li>
                        <a href="/backend/child"><i class="fa fa-file-text-o"></i>开始测评</a>
                    </li>
                    <li>
                        <a href="/backend/amt_replica"><i class="fa fa-file-text-o"></i>测评状态</a>
                    </li>
                    <li>
                        <a href="/backend/amt_als_rpt"><i class="fa fa-file-text-o"></i>报告</a>
                    </li> 
                    @if (Auth::user()->isSuper())      
                    <li>
                        <a href="/backend/amt">
                            <i class="fa fa-file-text-o"></i>赶工爆肝区
                        </a>
                    </li>
                    <li>
                        <a href="/backend/organization">
                            <i class="fa fa-line-chart"></i>
                            金流顯示
                        </a>
                    </li>
                    <li><a href="/backend/recommend_course"><i class="fa fa-file-text-o"></i>推薦課程</a></li>
                    <li><a href="/backend/user"><i class="fa fa-file-text-o"></i>使用者管理</a></li>
                    <li><a href="/backend/organization/1"><i class="fa fa-file-text-o"></i>组织</a></li>
                    <li><a href="/backend/organization"><i class="fa fa-file-text-o"></i>组织管理</a></li>
                    @endif
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
