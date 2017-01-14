    <div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="/backend/home" class="site_title">
                <i class="fa fa-paw"></i> 
                <span>优尼尔智能运动</span>
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
                <h2>{{ Auth::user()->name }}</h2>
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
                        <a href="/backend/analysis/r/i/channel"><i class="fa fa-file-text-o"></i>问卷</a>
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
                    @if (Auth::user()->is_super)      
                    <li>
                        <a href="/backend/amt">
                            <i class="fa fa-file-text-o"></i>赶工爆肝区
                        </a>
                    </li>
                    <li><a href="/backend/recommend_course"><i class="fa fa-file-text-o"></i>推薦課程</a></li>
                    <li><a href="/backend/organization/1"><i class="fa fa-file-text-o"></i>组织</a></li>
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
