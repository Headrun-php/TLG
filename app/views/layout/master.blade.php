
<!doctype html>
<!--[if lte IE 9]> <html class="lte-ie9" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="en"> <!--<![endif]-->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no"/>

    <link rel="icon" type="image/png" href="{{url()}}/assets/img/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="{{url()}}/assets/img/favicon-32x32.png" sizes="32x32">

    <title>Inspire</title>


    <!-- uikit -->
    <link rel="stylesheet" href="{{url()}}/bower_components/uikit/css/uikit.almost-flat.min.css" media="all">

    <!-- flag icons -->
    <link rel="stylesheet" href="{{url()}}/assets/icons/flags/flags.min.css" media="all">

    <!-- altair admin -->
    <link rel="stylesheet" href="{{url()}}/assets/css/main.min.css" media="all">
    <link rel="stylesheet" href="{{url()}}/assets/css/style.css" media="all">
    
    <link rel="stylesheet" href="{{url()}}/assets/breadcrumbs/css/global.css" media="all">
    
    <!-- matchMedia polyfill for testing media queries in JS -->
    <!--[if lte IE 9]>
        <script type="text/javascript" src="bower_components/matchMedia/matchMedia.js"></script>
        <script type="text/javascript" src="bower_components/matchMedia/matchMedia.addListener.js"></script>
    <![endif]-->
    
    
    
    
    
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
	
	
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
	
	
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
	
	
	@yield('libraryCSS')
	<style>
		.has-error .form-control {
		    border-color: #a94442 !important;
		    /* background-color: #F1CCCC !important; */
		    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
		    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
		}
		.has-error .form-control {
		    border-color: #a94442;
		    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
		    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
		}
	
	</style>
	 
   
	<!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
	
</head>
<body class="sidebar_main_open sidebar_main_swipe">
    <!-- main header -->
    <header id="header_main">
        <div class="header_main_content">
            <nav class="uk-navbar">
                <!-- main sidebar switch -->
                <a href="#" id="sidebar_main_toggle" class="sSwitch sSwitch_left">
                    <span class="sSwitchIcon"></span>
                </a>
                <!-- secondary sidebar switch -->
                <a href="#" id="sidebar_secondary_toggle" class="sSwitch sSwitch_right sidebar_secondary_check">
                    <span class="sSwitchIcon"></span>
                </a>
                <div id="menu_top" class="uk-float-left uk-hidden-small">
                    <div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}">
                        <a href="#" class="top_menu_toggle"><i class="material-icons md-24">&#xE8F0;</i></a>
                        <div class="uk-dropdown uk-dropdown-width-3">
                            <div class="uk-grid uk-dropdown-grid" data-uk-grid-margin>
                                <div class="uk-width-2-3">
                                    <div class="uk-grid uk-grid-width-medium-1-3 uk-margin-top uk-margin-bottom uk-text-center" data-uk-grid-margin>
                                        <a href="page_mailbox.html">
                                            <i class="material-icons md-36">&#xE158;</i>
                                            <span class="uk-text-muted uk-display-block">Mailbox</span>
                                        </a>
                                        <a href="page_invoices.html">
                                            <i class="material-icons md-36">&#xE53E;</i>
                                            <span class="uk-text-muted uk-display-block">Invoices</span>
                                        </a>
                                        <a href="page_chat.html">
                                            <i class="material-icons md-36 md-color-red-600">&#xE0B9;</i>
                                            <span class="uk-text-muted uk-display-block">Chat</span>
                                        </a>
                                        <a href="page_scrum_board.html">
                                            <i class="material-icons md-36">&#xE85C;</i>
                                            <span class="uk-text-muted uk-display-block">Scrum Board</span>
                                        </a>
                                        <a href="page_snippets.html">
                                            <i class="material-icons md-36">&#xE86F;</i>
                                            <span class="uk-text-muted uk-display-block">Snippets</span>
                                        </a>
                                        <a href="page_user_profile.html">
                                            <i class="material-icons md-36">&#xE87C;</i>
                                            <span class="uk-text-muted uk-display-block">User profile</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="uk-width-1-3">
                                    <ul class="uk-nav uk-nav-dropdown uk-panel">
                                        <li class="uk-nav-header">Components</li>
                                        <li><a href="components_accordion.html">Accordions</a></li>
                                        <li><a href="components_buttons.html">Buttons</a></li>
                                        <li><a href="components_notifications.html">Notifications</a></li>
                                        <li><a href="components_sortable.html">Sortable</a></li>
                                        <li><a href="components_tabs.html">Tabs</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="" style="display: block; padding-top:10px;">
		            <!-- <i class="md-icon header_main_search_close material-icons">&#xE5CD;</i> -->
		            <form class="uk-form" id="profileSearchForm" action="{{url()}}/quick/navigateToProfile" method="post">
		                <input type="text" id="commonSearchTxt" name="term" class="headerSearchInput" />
		                <input type="hidden"  id="idCommonSearchTxt" name="idCommonSearchTxt" class="" />
		                <button id="profileSearchFormSubmitBtn" class="buttonSearch uk-button-link" type="submit">
		                	<i class="md-icon material-icons" style="color: #FFF;">&#xE8B6;</i>
		                </button>
		            </form>
		        </div>
                <div class="uk-navbar-flip">
                    <ul class="uk-navbar-nav user_actions">
                    	<!-- <li>
                    		<div class="header_main_search_form">
					            <i class="md-icon header_main_search_close material-icons">&#xE5CD;</i>
					            <form class="uk-form">
					                <input type="text" id="commonSearchTxt" name="term" class="header_main_search_input" />
					                <button class="header_main_search_btn uk-button-link">
					                	<i class="md-icon material-icons">&#xE8B6;</i>
					                </button>
					            </form>
					        </div>
                    	</li> -->
                        <!-- <li><a href="#" id="main_search_btn" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE8B6;</i></a></li> -->
                        <li data-uk-dropdown="{mode:'click'}">
                            <!-- <a href="#" class="user_action_icon"><i class="material-icons md-24 md-light">&#xE7F4;</i><span class="uk-badge">16</span></a> -->
                            <div class="uk-dropdown uk-dropdown-xlarge uk-dropdown-flip">
                                <div class="md-card-content">
                                    <ul class="uk-tab uk-tab-grid" data-uk-tab="{connect:'#header_alerts',animation:'slide-horizontal'}">
                                        <li class="uk-width-1-2 uk-active"><a href="#" class="js-uk-prevent uk-text-small">Messages (12)</a></li>
                                        <li class="uk-width-1-2"><a href="#" class="js-uk-prevent uk-text-small">Alerts (4)</a></li>
                                    </ul>
                                    <ul id="header_alerts" class="uk-switcher uk-margin">
                                        <li>
                                            <ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <span class="md-user-letters md-bg-cyan">fj</span>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Ad quam.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Molestias non aut ut voluptates ratione ea quaerat quia.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <!-- <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_07_tn.png" alt=""/> -->
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Occaecati maiores.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Sunt eum corrupti iste quia optio quasi placeat et quisquam vel.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <span class="md-user-letters md-bg-light-green">ju</span>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Sapiente voluptas mollitia.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Facere nesciunt nesciunt voluptas dignissimos corporis laborum ea possimus aperiam esse.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                       <!--  <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_02_tn.png" alt=""/> -->
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Qui qui saepe.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Architecto placeat velit corporis voluptatibus et et eaque laudantium nam deleniti.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                       <!--  <img class="md-user-image md-list-addon-avatar" src="assets/img/avatars/avatar_09_tn.png" alt=""/> -->
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading"><a href="pages_mailbox.html">Sed architecto.</a></span>
                                                        <span class="uk-text-small uk-text-muted">Quam et vero animi quo itaque eos tenetur at.</span>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="uk-text-center uk-margin-top uk-margin-small-bottom">
                                                <a href="page_mailbox.html" class="md-btn md-btn-flat md-btn-flat-primary js-uk-prevent">Show All</a>
                                            </div>
                                        </li>
                                        <li>
                                            <ul class="md-list md-list-addon">
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-warning">&#xE8B2;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Enim culpa et.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Quis voluptate quia voluptatibus sunt mollitia eius incidunt dolorum.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-success">&#xE88F;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Eveniet quod.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Harum et mollitia atque aut expedita exercitationem.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-danger">&#xE001;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Quo magni aspernatur.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Sint aspernatur et reprehenderit perspiciatis nam.</span>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="md-list-addon-element">
                                                        <i class="md-list-addon-icon material-icons uk-text-primary">&#xE8FD;</i>
                                                    </div>
                                                    <div class="md-list-content">
                                                        <span class="md-list-heading">Accusamus in.</span>
                                                        <span class="uk-text-small uk-text-muted uk-text-truncate">Eum excepturi necessitatibus aut quaerat minima.</span>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                        <li data-uk-dropdown="{mode:'click'}">
                            <a href="#" class="user_action_image" style="margin-top: -10px;">
                             <img class="md-user-image" src="{{url()}}/assets/img/avatars/avatar_11_tn.png" alt=""/> 
                            
                            </a>
                            <div class="uk-dropdown uk-dropdown-small uk-dropdown-flip">
                                <ul class="uk-nav js-uk-prevent">
                                    <!-- <li><a href="page_user_profile.html">My profile</a></li>
                                    <li><a href="page_settings.html">Settings</a></li> -->
                                    <li><a href="{{url()}}/vault/logout">Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        
        
        <!-- <div class="header_main_search_form">
            <i class="md-icon header_main_search_close material-icons">&#xE5CD;</i>
            <form class="uk-form">
                <input type="text" id="commonSearchTxt" name="term" class="header_main_search_input" />
                <button class="header_main_search_btn uk-button-link">
                	<i class="md-icon material-icons">&#xE8B6;</i>
                </button>
            </form>
        </div> -->
    </header><!-- main header end -->

   @include('layout.sidebar')
    
  <div id="page_content">
        <div id="page_content_inner">
        
        	@yield('content')
        
         </div>
    </div>

    <!-- google web fonts -->
    <script>
        WebFontConfig = {
            google: {
                families: [
                    'Source+Code+Pro:400,700:latin',
                    'Roboto:400,300,500,700,400italic:latin'
                ]
            }
        };
        (function() {
            var wf = document.createElement('script');
            wf.src = ('https:' == document.location.protocol ? 'https' : 'http') +
            '://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js';
            wf.type = 'text/javascript';
            wf.async = 'true';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(wf, s);
        })();
    </script>

   

<div id="style_switcher">
    <div id="style_switcher_toggle"><i class="material-icons">&#xE8B8;</i></div>
    <div class="uk-margin-medium-bottom">
        <h4 class="heading_c uk-margin-bottom">Colors</h4>
        <ul class="switcher_app_themes" id="theme_switcher">
            <li class="app_style_default active_theme" data-app-theme="">
                <span class="app_color_main"></span>
                <span class="app_color_accent"></span>
            </li>
            <li class="switcher_theme_a" data-app-theme="app_theme_a">
                <span class="app_color_main"></span>
                <span class="app_color_accent"></span>
            </li>
            <li class="switcher_theme_b" data-app-theme="app_theme_b">
                <span class="app_color_main"></span>
                <span class="app_color_accent"></span>
            </li>
            <li class="switcher_theme_c" data-app-theme="app_theme_c">
                <span class="app_color_main"></span>
                <span class="app_color_accent"></span>
            </li>
            <li class="switcher_theme_d" data-app-theme="app_theme_d">
                <span class="app_color_main"></span>
                <span class="app_color_accent"></span>
            </li>
            <li class="switcher_theme_e" data-app-theme="app_theme_e">
                <span class="app_color_main"></span>
                <span class="app_color_accent"></span>
            </li>
            <li class="switcher_theme_f" data-app-theme="app_theme_f">
                <span class="app_color_main"></span>
                <span class="app_color_accent"></span>
            </li>
            <li class="switcher_theme_g" data-app-theme="app_theme_g">
                <span class="app_color_main"></span>
                <span class="app_color_accent"></span>
            </li>
        </ul>
    </div>
    <div class="uk-visible-large">
        <h4 class="heading_c">Sidebar</h4>
        <p>
            <input type="checkbox" name="style_sidebar_mini" id="style_sidebar_mini" data-md-icheck />
            <label for="style_sidebar_mini" class="inline-label">Mini Sidebar</label>
        </p>
    </div>
</div>
	
	<!-- common functions -->
    <script src="{{url()}}/assets/js/common.min.js"></script>
	<!-- uikit functions -->
    <script src="{{url()}}/assets/js/uikit_custom.min.js"></script>
    <!-- altair common functions/helpers -->
    
    <script src="{{url()}}/assets/js/altair_admin_common.min.js"></script>
    <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
    <script>
	 	$( "#commonSearchTxt" ).autocomplete({
	        source: "{{url()}}/quick/customerStudentSearch",
	        minLength: 2,
	        select: function( event, ui ) {

	        	$( "#commonSearchTxt" ).val(ui.item.value);
	        	$( "#idCommonSearchTxt" ).val(ui.item.id);

		        
	          console.log( ui.item ?
	            "Selected: " + ui.item.value + " aka " + ui.item.id :
	            "Nothing selected, input was " + this.value );
	        }
	      });


	 	$("#profileSearchFormSubmitBtn").click(function (event){

		 	event.preventDefault();

		 	if($("#idCommonSearchTxt").val() != ""){

		 		$("#profileSearchForm").submit();

		 	}




		})


	</script>
	<script type="text/javascript">
		var ajaxUrl = "{{URL::to('/quick/')}}/";
	</script>
    @yield('libraryJS')
    
	

    <script>
        $(function() {
            // enable hires images
            altair_helpers.retina_images();
            // fastClick (touch devices)
            if(Modernizr.touch) {
                FastClick.attach(document.body);
            }
        });
    </script>
    
<script>
    
    $(function() {

        $("#{{$currentPage}}").addClass('act_item');
        $("#{{$mainMenu}}").addClass('current_section act_item');
        $("#{{$mainMenu}}").addClass('current_section act_section');
        $("#{{$mainMenu}}_UL").css('display','block');
        
        var $switcher = $('#style_switcher'),
            $switcher_toggle = $('#style_switcher_toggle'),
            $theme_switcher = $('#theme_switcher'),
            $mini_sidebar_toggle = $('#style_sidebar_mini');

        $switcher_toggle.click(function(e) {
            e.preventDefault();
            $switcher.toggleClass('switcher_active');
        });

        $theme_switcher.children('li').click(function(e) {
            e.preventDefault();
            var $this = $(this),
                this_theme = $this.attr('data-app-theme');

            $theme_switcher.children('li').removeClass('active_theme');
            $(this).addClass('active_theme');
            $('body')
                .removeClass('app_theme_a app_theme_b app_theme_c app_theme_d app_theme_e app_theme_f app_theme_g')
                .addClass(this_theme);

            if(this_theme == '') {
                localStorage.removeItem('altair_theme');
            } else {
                localStorage.setItem("altair_theme", this_theme);
            }

        });

        // change input's state to checked if mini sidebar is active
        if((localStorage.getItem("altair_sidebar_mini") !== null && localStorage.getItem("altair_sidebar_mini") == '1') || $('body').hasClass('sidebar_mini')) {
            $mini_sidebar_toggle.iCheck('check');
        }

        // toggle mini sidebar
        $mini_sidebar_toggle
            .on('ifChecked', function(event){
                $switcher.removeClass('switcher_active');
                localStorage.setItem("altair_sidebar_mini", '1');
                location.reload(true);
            })
            .on('ifUnchecked', function(event){
                $switcher.removeClass('switcher_active');
                localStorage.removeItem('altair_sidebar_mini');
                location.reload(true);
            });

        // hide style switcher
        $document.on('click keyup', function(e) {
            if( $switcher.hasClass('switcher_active') ) {
                if (
                    ( !$(e.target).closest($switcher).length )
                    || ( e.keyCode == 27 )
                ) {
                    $switcher.removeClass('switcher_active');
                }
            }
        });

        if(localStorage.getItem("altair_theme") !== null) {
            $theme_switcher.children('li[data-app-theme='+localStorage.getItem("altair_theme")+']').click();
        }
    });
</script>


</body>
</html>