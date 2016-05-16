 <!-- main sidebar -->
    <aside id="sidebar_main">
        <div class="sidebar_main_header">
            <div class="sidebar_logo">
                <a href="{{url()}}/dashboard" class="sSidebar_hide">
                	<img src="{{url()}}/assets/img/logo.png" alt="" height="87" width="170"/> 
                </a>
                <a href="{{url()}}/dashboard" class="sSidebar_show">
                	<!-- <img src="assets/img/logo_main_small.png" alt="" height="32" width="32"/> -->
                </a>
            </div>
            <!-- <div class="sidebar_actions">
                <select id="lang_switcher" name="lang_switcher">
                    <option value="gb" selected>English</option>
                </select>
            </div> -->
        </div>
        <div class="menu_section">
            <ul>
                <li id="DASHBOARD" title="Dashboard">
                   <a href="{{url()}}/dashboard">
                        <span class="menu_icon"><i class="material-icons">&#xE871;</i></span>
                        <span class="menu_title">Dashboard</span>
                    </a>
                </li>
                
               
                 <li id="CUSTOMERS_MAIN">
                    <a href="#">
                        <span class="menu_icon"><i class="material-icons">&#xE8D2;</i></span>
                        <span class="menu_title">Customers</span>
                    </a>
                    <ul id="CUSTOMERS_MAIN_UL">
                        <li id="PROSPECTUS_LIST"><a href="{{url()}}/customers/prospectslist">Prospects List</a></li>
                        <li id="MEMBERS_LIST"><a href="{{url()}}/customers/memberslist">Members List</a></li>
                        <li id="CUSTOMERS_ADD"><a href="{{url()}}/customers/add">Add Customers</a></li>    
                    </ul>
                </li>
                 <li id="STUDENTS_MAIN">
                    <a href="#">
                        <span class="menu_icon"><i class="material-icons">&#xE8D2;</i></span>
                        <span class="menu_title">Kids</span>
                    </a>
                    <ul id="STUDENTS_MAIN_UL">
                        <li id="NONENROLLEDSTUDENTS"><a href="{{url()}}/students/nonenrolled">Non Enrolled Kids</a></li>
                        <li id="ENROLLEDSTUDENTS"><a href="{{url()}}/students/enrolled">Enrolled Kids</a></li>
                    </ul>
                </li>
                
                 <li id="COURSES_MAIN">
                    <a href="#">
                        <span class="menu_icon"><i class="material-icons">&#xE8D2;</i></span>
                        <span class="menu_title">Batches</span>
                    </a>
                    <ul id="COURSES_MAIN_UL">
                       <!--  <li id="COURSES"><a href="{{url()}}/courses">Courses</a></li>
                        <li id="CLASSES"><a href="{{url()}}/classes">Classes</a></li> -->
                        <li id="CLASSES"><a href="{{url()}}/batches">Batches</a></li>
                    </ul>
                </li>
               
                
                
                
                 <li id="EVENTS_MAIN">
                 	<hr/>
                 </li>
                
                <?php if(Session::get('userType') == 'ADMIN'){?>
                        <li id="EVENTS_MAIN">
                            <a href="#">
                                <span class="menu_icon"><i class="material-icons">&#xE8D2;</i></span>
                                <span class="menu_title">Events</span>
                            </a>
                            <ul id="EVENTS_MAIN_UL">
                                 <!--  <li id="COURSES"><a href="{{url()}}/courses">Courses</a></li>
                                 <li id="CLASSES"><a href="{{url()}}/classes">Classes</a></li> -->
                                 <li id="EVENTS"><a href="{{url()}}/events">Events</a></li>
                                 <li id="EVENT_TYPES"><a href="{{url()}}/events/types">Event Types</a></li>
                            </ul>
                        </li>
                 
                 
                	 <li id="USERS_MAIN">
	                    <a href="#">
	                        <span class="menu_icon"><i class="material-icons">&#xE8D2;</i></span>
	                        <span class="menu_title">Users</span>
	                    </a>
	                    <ul id="USERS_MAIN_UL">
	                       <!--  <li id="COURSES"><a href="{{url()}}/courses">Courses</a></li>
	                        <li id="CLASSES"><a href="{{url()}}/classes">Classes</a></li> -->
	                        <li id="USERS"><a href="{{url()}}/admin/users">All Users</a></li>
	                        <li id="ADD_USERS"><a href="{{url()}}/admin/users/add">Add User</a></li>
	                    </ul>
	                </li>
                        <?php if(Session::get('userType') == 'SUPER_ADMIN'){?>
	                 <li id="COURSES_MENU_MAIN">
	                    <a href="#">
	                        <span class="menu_icon"><i class="material-icons">&#xE8D2;</i></span>
	                        <span class="menu_title">Courses</span>
	                    </a>
	                    <ul id="COURSES_MENU_MAIN_UL">
	                       <!--  <li id="COURSES"><a href="{{url()}}/courses">Courses</a></li>
	                        <li id="CLASSES"><a href="{{url()}}/classes">Classes</a></li> -->
	                        <li id="COURSES_LI"><a href="{{url()}}/courses">Courses</a></li>
	                    </ul>
	                </li>
	                 <li id="CLASSES_MAIN">
	                    <a href="#">
	                        <span class="menu_icon"><i class="material-icons">&#xE8D2;</i></span>
	                        <span class="menu_title">Classes</span>
	                    </a>
	                    <ul id="CLASSES_MAIN_UL">
	                       <!--  <li id="COURSES"><a href="{{url()}}/courses">Courses</a></li>
	                        <li id="CLASSES"><a href="{{url()}}/classes">Classes</a></li> -->
	                        <li id="CLASSES_LI"><a href="{{url()}}/classes">Classes</a></li>
	                    </ul>
	                </li>
                        <?php }?>
                        <li id="SEASONS_MENU_MAIN">
	                    <a href="#">
	                        <span class="menu_icon"><i class="material-icons">&#xE8D2;</i></span>
	                        <span class="menu_title">Seasons</span>
	                    </a>
	                    <ul id="SEASONS_MENU_MAIN_UL">
	                        <li id="AddSeasons_LI"><a href="{{url()}}/season/add">Add Sesason</a></li>
                                <li id="ViewSeasons_LI"><a href="{{url()}}/season/viewseasons">View Seasons</a></li>
	                    </ul>
	                </li>
                <?php }?>
                
                
                
                
            </ul>
        </div>
    </aside><!-- main sidebar end -->
   