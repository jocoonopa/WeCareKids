    <div class="col-md-3 left_col">
    <div class="left_col scroll-view">
        <div class="navbar nav_title" style="border: 0;">
            <a href="/backend/home" class="site_title">
                <i class="fa fa-paw"></i> 
                <span>培奇智能运动</span>
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
                        <a href="/backend/analysis/r/i/channel"><i class="fa fa-file-text-o"></i>剖析</a>
                    </li>
                    <li>
                        <a href="/backend/amt_replica"><i class="fa fa-file-text-o"></i>評測</a>
                    </li>
                    <li>
                        <a href="/backend/amt_als_rpt"><i class="fa fa-file-text-o"></i>報告</a>
                    </li>
                    <li>
                        <a href="/backend/child"><i class="fa fa-file-text-o"></i>受測者</a>
                    </li>
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