<?php
//* this will bring in the Genesis Parent files needed:
include_once( get_template_directory() . '/lib/init.php' );

//* We tell the name of our child theme
define( 'Child_Theme_Name', __( 'Genesis Child', 'genesischild' ) );
//* We tell the web address of our child theme (More info & demo)
define( 'Child_Theme_Url', 'https://joewright.codes' );
//* We tell the version of our child theme
define( 'Child_Theme_Version', '1.0' );

//* Add HTML5 markup structure from Genesis
add_theme_support( 'html5' );

//* Add HTML5 responsive recognition
add_theme_support( 'genesis-responsive-viewport' );

function boostrap_theme_enqueue_scripts() {
    // all styles
    wp_enqueue_style( 'child-theme-mincss', get_stylesheet_directory_uri() . '/style.css' );	
    wp_enqueue_style( 'bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap.css', array(), 20141119 );
}
add_action( 'wp_enqueue_scripts', 'boostrap_theme_enqueue_scripts' );
function custom_add_scripts() {
    echo '<link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Barlow:wght@400;500;600;700;800&family=Montserrat:wght@700;800&family=Newsreader:ital,wght@0,400;0,500;0,600;1,400;1,500;1,600&display=swap" rel="stylesheet">';
}
add_action( 'wp_head', 'custom_add_scripts' );

function my_child_theme_scripts() {
    wp_enqueue_style( 'parent-theme-css', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'motley-css', 'https://g.foolcdn.com/static/dubs/CACHE/css/output.303db1f1330f.css');

    //wp_enqueue_script( 'child-theme-js', get_stylesheet_directory_uri() . '/js/main.js' );

}
add_action( 'wp_enqueue_scripts', 'my_child_theme_scripts' );

add_action( 'after_setup_theme', 'child_load_stylesheet' );
function child_load_stylesheet() {
	remove_action( 'genesis_meta', 'genesis_load_stylesheet' );
}

function retrieveStockData($company,$path) {
    $endpoint = $path.$company.'?apikey=cf44ce0b7f3798422bfacb630326e386';
    $response = wp_remote_get($endpoint);
    return $response;
}

add_action( 'template_redirect', function() {
    if ( is_singular() ) {
        global $wp_query;
        $page = ( int ) $wp_query->get( 'page' );
        if ( $page > 1 ) {
            // convert 'page' to 'paged'
            $wp_query->set( 'page', 1 );
            $wp_query->set( 'paged', $page );
        }
        // prevent redirect
        remove_action( 'template_redirect', 'redirect_canonical' );
    }
}, 0 );

function singlePagination( $label = NULL, $dir = 'next', WP_Query $query = NULL ) {
    if ( is_null( $query ) ) {
        $query = $GLOBALS['wp_query'];
    }
    $max_page = ( int ) $query->max_num_pages;
    // only one page for the query, do nothing
    if ( $max_page <= 1 ) {
        return;
    }
    $paged = ( int ) $query->get( 'paged' );
    if ( empty( $paged ) ) {
        $paged = 1;
    }
    $target_page = $dir === 'next' ?  $paged + 1 : $paged - 1;
    // if 1st page requiring previous or last page requiring next, do nothing
    if ( $target_page < 1 || $target_page > $max_page ) {
        return;
    }
    if ( null === $label ) {
        $label = __( 'Next Page &raquo;' );
    }

    $label = preg_replace( '/&([^#])(?![a-z]{1,8};)/i', '&#038;$1', $label );
    printf( '<a href="%s">%s</a>', get_pagenum_link( $target_page ), esc_html( $label ) );
}

remove_action( 'genesis_after_header', 'genesis_do_nav' );
remove_action( 'genesis_header', 'genesis_do_header' );
remove_action( 'genesis_after_header', 'genesis_do_subnav' ) ;
function customHeader(){ ?>
<div class="container-fluid px-0">
    <header class="navigation" role="banner" style="position:relative;top:0px;">
        <div class="navigation-wrapper">
            <div class="logo-container">
                <h1 class="site-logo" style="font-size:100%;">
                    <a href="/" class="logo" data-uw-rm-brl="false">
                        <img id="fool-logo" class="fool-logo" alt="The Motley Fool" src="https://g.foolcdn.com/misc-assets/logo-tmf-primary-3-blue-cyan.svg" data-uw-rm-ima-original="the motley fool">
                    </a>
                </h1>
            </div>
            <nav role="navigation">
                <ul class="nav-menu main-menu">
                    <li class="nav-item main-menu-item">
                        <a id="topnav-picks" href="https://www.fool.com/mms/mark/d-nav-btn" class="nav-link-stock-picks" data-uw-rm-brl="false">
                            Latest Stock Picks
                        </a>
                    </li>
                    <li class="nav-item main-menu-item">
                        <a id="our-services" href="/services/" class="" data-uw-rm-brl="false">Our Services</a>
                    </li>
                    <li class="nav-item main-menu-item dropdown">
                        <a id="topnav-stocks" href="javascript:void(0)" aria-haspopup="true" aria-controls="accessible-megamenu-1631799860137-1" aria-expanded="false" class="" data-uw-rm-brl="exc">
                            Investing Basics
                        </a>
                    </li>
                    <li class="nav-item main-menu-item dropdown">
                        <a id="topnav-stocks" href="javascript:void(0)" aria-haspopup="true" aria-controls="accessible-megamenu-1631799860140-2" aria-expanded="false" data-uw-rm-brl="exc">
                            Stock Market
                        </a>
                    </li>
                    <li class="nav-item main-menu-item dropdown">
                        <a id="topnav-stocks" href="javascript:void(0)" aria-haspopup="true" aria-controls="accessible-megamenu-1631799860142-3" aria-expanded="false" data-uw-rm-brl="exc">
                            Retirement
                        </a>
                    </li>
                    <li class="nav-item main-menu-item dropdown">
                        <a id="topnav-stocks" href="javascript:void(0)" aria-haspopup="true" aria-controls="accessible-megamenu-1631799860145-4" aria-expanded="false" data-uw-rm-brl="exc">
                            Personal Finance
                        </a>
                    </li>
                    <li class="nav-item main-menu-item dropdown">
                        <a id="topnav-stocks" href="javascript:void(0)" aria-haspopup="true" aria-controls="accessible-megamenu-1631799860149-5" aria-expanded="false" data-uw-rm-brl="exc">
                            About Us
                        </a>
                    </li>
                </ul>
            </nav>
            <button class="navigation-menu-button" id="mobile-menu-toggle" role="button" data-uw-rm-kbnav="role" aria-label="svg-icon" data-uw-rm-empty-ctrl="" tabindex="0">
                <svg class="fa-svg-icon icon-bars">||</svg>
                <svg class="fa-svg-icon icon-close">X</svg>
            </button>
            <nav class="mobile-nav-container">
                <ul class="mobile-nav">
                    <li class="main-menu-item">
                        <a id="m-topnav-picks" href="https://www.fool.com/#services-section" data-uw-rm-brl="false">Latest Stock Picks</a>
                    </li>
                    <li class="main-menu-item">
                        <div class="main-menu-item-link-wrapper" onclick="return true" data-uw-rm-kbnav="click">
                        <span class="main-menu-item-link no-dropdown">
                            <a id="our-services" href="/services/" data-uw-rm-brl="false">Our Services</a>
                        </span>
                        </div>
                    </li>
                    <li class="main-menu-item dropdown">
                        <div class="main-menu-item-link-wrapper" onclick="return true" data-uw-rm-kbnav="click">
                            <span class="main-menu-item-link">
                                <span class="dropdown" id="topnav-stocks">
                                    Investing Basics
                                    <svg class="fa-svg-icon"><use xlink:href="#angle-right"></use></svg>
                                </span>
                            </span>
                        </div>
                    </li>
                    <li class="main-menu-item dropdown">
                        <div class="main-menu-item-link-wrapper" onclick="return true" data-uw-rm-kbnav="click">
                            <span class="main-menu-item-link">
                                <span class="dropdown" id="topnav-stocks">
                                    Stock Market
                                    <svg class="fa-svg-icon"><use xlink:href="#angle-right"></use></svg>
                                </span>
                            </span>
                        </div>
                    </li>
                    <li class="main-menu-item dropdown">
                        <div class="main-menu-item-link-wrapper" onclick="return true" data-uw-rm-kbnav="click">
                            <span class="main-menu-item-link">
                                <span class="dropdown" id="topnav-stocks">
                                    Retirement
                                    <svg class="fa-svg-icon"><use xlink:href="#angle-right"></use></svg>
                                </span>
                            </span>
                        </div>
                    </li>
                    <li class="main-menu-item dropdown">
                        <div class="main-menu-item-link-wrapper" onclick="return true" data-uw-rm-kbnav="click">
                            <span class="main-menu-item-link">
                                <span class="dropdown" id="topnav-stocks">
                                    Personal Finance
                                    <svg class="fa-svg-icon"><use xlink:href="#angle-right"></use></svg>
                                </span>
                            </span>
                        </div>
                    </li>
                    <li class="main-menu-item dropdown">
                        <div class="main-menu-item-link-wrapper" onclick="return true" data-uw-rm-kbnav="click">
                            <span class="main-menu-item-link">
                                <span class="dropdown" id="topnav-stocks">
                                    About Us
                                    <svg class="fa-svg-icon"><use xlink:href="#angle-right"></use></svg>
                                </span>
                            </span>
                        </div>
                    </li>
                    <li class="main-menu-item" id="Help">
                        <a id="m-topnav-help" href="https://support.fool.com" data-uw-rm-brl="false">Help</a>
                    </li>
                    <li class="main-menu-item" id="logIn">
                        <a id="m-topnav-login" href="/auth/authenticate/?source=ilgsittph0000001" data-uw-rm-brl="false">Login</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <?php if (is_home()) { ?>
        <div class="container">
            <div id="singlePostContainer" class="singlePostContainer row">
                <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-0 col-0"></div>
                <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12"> 
                    <h4>Welcome to Joe Wright's submission for the engineering practical at Motley Fool!</h4>
                    <p>I had a great time tackling this project. I focused most of my time and attention on considerations related to app architecture and the back end, so I hope you don't mind that I've borrowed some familiar and foolish elements to complete the look! I look forward to discussing my work with you.</p>
                    <p>The most important thing to know about the App design is that before you recommend a company or write news about it, a company profile needs to be created using the "company post type".  The company's exchange symbol should be used as the post title.</p>
                    <p>Once the company is created, feel free to write news or recommend!  Each can be done using a custom post type found in the wp admin sidebar. From there, it is all pretty self-explanatory.</p>
                    <p>Thank you for your time and consideration!</p> 
                </div>
                <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-0 col-0"></div>
            </div>   
        </div>
    <?php } ?>
</div>
<?php
}

add_action( 'genesis_header', 'customHeader' );


// Remove site footer.
remove_action( 'genesis_footer', 'genesis_footer_markup_open', 5 );
remove_action( 'genesis_footer', 'genesis_do_footer' );
remove_action( 'genesis_footer', 'genesis_footer_markup_close', 15 );
// Customize site footer
add_action( 'genesis_footer', 'leaguewp_custom_footer' );
function leaguewp_custom_footer() { ?>
<?php $pluginOptions = get_option( 'wporg_options' ); ?>
<footer class="footer bg-black" id="usmf-footer">
    <div class="footer-main">
        <div class="footer-site-info">
            <a href="/" data-uw-rm-brl="false" title="/"><img class="fool-logo" src="https://g.foolcdn.com/misc-assets/logo-tmf-primary-1-magenta-purple-reversed.svg" alt="The Motley Fool" data-uw-rm-ima-original="the motley fool"></a>

            <p class="tagline">Making the world smarter, happier, and richer.</p>

            <ul class="footer-social">
                <li class="footer-social-icon"><a target="_blank" data-action="social-icons" href="https://www.facebook.com/themotleyfool?fref=ts" data-uw-rm-brl="false" aria-label="Facebook - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.facebook.com/themotleyfool?fref=ts$facebook"><span class="sr-only">Facebook</span><svg class="fa-svg-icon"><use xlink:href="#facebook"></use></svg></a></li>
                <li class="footer-social-icon"><a target="_blank" data-action="social-icons" href="https://twitter.com/TheMotleyFool" data-uw-rm-brl="false" aria-label="Twitter - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://twitter.com/themotleyfool$twitter"><span class="sr-only">Twitter</span><svg class="fa-svg-icon"><use xlink:href="#twitter"></use></svg></a></li>
                <li class="footer-social-icon"><a target="_blank" data-action="social-icons" href="https://www.linkedin.com/company/the-motley-fool/" data-uw-rm-brl="exc" aria-label="Linked In - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.linkedin.com/company/the-motley-fool/$linkedin"><span class="sr-only">Linked In</span><svg class="fa-svg-icon"><use xlink:href="#linkedin"></use></svg></a></li>
                <li class="footer-social-icon"><a target="_blank" data-action="social-icons" href="https://www.pinterest.com/themotleyfool/" data-uw-rm-brl="false" aria-label="Pinterest - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.pinterest.com/themotleyfool/$pinterest"><span class="sr-only">Pinterest</span><svg class="fa-svg-icon"><use xlink:href="#pinterest"></use></svg></a></li>
                <li class="footer-social-icon"><a target="_blank" data-action="social-icons" href="https://www.youtube.com/user/TheMotleyFool" data-uw-rm-brl="exc" aria-label="YouTube - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.youtube.com/user/themotleyfool$youtube"><span class="sr-only">YouTube</span><svg class="fa-svg-icon"><use xlink:href="#youtube"></use></svg></a></li>
                <li class="footer-social-icon"><a target="_blank" data-action="social-icons" href="https://www.instagram.com/themotleyfoolofficial/" data-uw-rm-brl="false" aria-label="Instagram - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.instagram.com/themotleyfoolofficial/$instagram"><span class="sr-only">Instagram</span><svg class="fa-svg-icon"><use xlink:href="#instagram"></use></svg></a></li>
                <li class="footer-social-icon"><a target="_blank" data-action="social-icons" href="https://www.tiktok.com/@themotleyfoolofficial" data-uw-rm-brl="false" aria-label="Tiktok - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.tiktok.com/@themotleyfoolofficial$tiktok"><span class="sr-only">Tiktok</span><svg class="fa-svg-icon"><use xlink:href="#tiktok"></use></svg></a></li>
            </ul>

            <div class="footer-small">
                <p id="footer-copyright-text" class="copyright">© 1995 - 2021 The Motley Fool. All rights reserved.<br role="presentation" data-uw-rm-sr=""></p>
                <p>Market data powered by <a target="_blank" href="https://xignite.com/" data-uw-rm-brl="false" aria-label="Xignite - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://xignite.com/$xignite">Xignite</a>.</p>
            </div>
        </div>

        <div class="footer-lists">
            <div class="footer-list">
                <h2>About the Motley&nbsp;Fool</h2>
                <ul>
                    <li><a data-action="about" href="/about/" data-uw-rm-brl="false">About Us</a></li>
                    <li><a data-action="about" target="_blank" href="https://careers.fool.com/" data-uw-rm-brl="false" aria-label="Careers - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://careers.fool.com/$careers">Careers</a></li>
                    <li><a data-action="about" target="_blank" href="https://culture.fool.com/" data-uw-rm-brl="false" aria-label="Culture Blog - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://culture.fool.com/$cultureblog">Culture Blog</a></li>
                    <li><a data-action="about" href="/research/" data-uw-rm-brl="false">Research</a></li>
                    <li><a data-action="about" href="/contact/press-inquiries/" data-uw-rm-brl="false">Press Inquiries</a></li>
                    <li><a data-action="about" href="/legal/contact-us/" data-uw-rm-brl="false">Contact</a></li>
                    <li><a data-action="about" href="mailto:adinquiries@fool.com" data-uw-rm-brl="exc" aria-label="send an email to adinquiries@fool.com" uw-rm-vague-link-id="mailto:adinquiries@fool.com$send an email to adinquiries@fool.com" data-uw-rm-vglnk="">Advertise</a></li>
                </ul>
            </div>

            <div class="footer-list">
                <h2>Around the Globe</h2>
                <ul>
                    <li><a target="_blank" data-action="global" href="https://www.fool.co.uk/" data-uw-rm-brl="false" aria-label="Fool UK - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.fool.co.uk/$fooluk">Fool UK</a></li>
                    <li><a target="_blank" data-action="global" href="https://www.fool.com.au/" data-uw-rm-brl="false" aria-label="Fool Australia - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.fool.com.au/$foolaustralia">Fool Australia</a></li>
                    <li><a target="_blank" data-action="global" href="https://www.fool.ca/" data-uw-rm-brl="false" aria-label="Fool Canada - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.fool.ca/$foolcanada">Fool Canada</a></li>
                    <li><a target="_blank" data-action="global" href="https://www.fool.de/" data-uw-rm-brl="false" aria-label="Fool Deutschland - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.fool.de/$fooldeutschland">Fool Deutschland</a></li>
                    <li><a target="_blank" data-action="global" href="https://www.motleyfool.co.jp/" data-uw-rm-brl="false" aria-label="Fool Japan - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.motleyfool.co.jp/$fooljapan">Fool Japan</a></li>
                </ul>
            </div>

            <div class="footer-list">
                <h2>Motley Fool Companies</h2>
                <ul>
                    <li><a data-action="tmf-companies" href="/the-ascent/" data-uw-rm-brl="false">The Ascent</a></li>
                    <li><a data-action="tmf-companies" target="_blank" href="https://www.mfamfunds.com/" data-uw-rm-brl="false" aria-label="Asset Management - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.mfamfunds.com/$assetmanagement">Asset Management</a></li>
                    <li><a data-action="tmf-companies" target="_blank" href="https://foolwealth.com/" data-uw-rm-brl="false" aria-label="Wealth Management - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://foolwealth.com/$wealthmanagement">Wealth Management</a></li>
                    <li><a data-action="tmf-companies" target="_blank" href="https://www.millionacres.com/" data-uw-rm-brl="false" aria-label="Millionacres - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.millionacres.com/$millionacres">Millionacres</a></li>
                    <li><a data-action="tmf-companies" target="_blank" href="https://foolventures.com/" data-uw-rm-brl="false" aria-label="Motley Fool Ventures - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://foolventures.com/$motleyfoolventures">Motley Fool Ventures</a></li>
                    <li><a data-action="tmf-companies" target="_blank" href="https://www.fool.com/allstarmoney/" data-uw-rm-brl="false" aria-label="All Star Money - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://www.fool.com/allstarmoney/$allstarmoney">All Star Money</a></li>
                </ul>
            </div>

            <div class="footer-list">
                <h2>Premium Services</h2>
                <ul>
                    <li><a data-action="premium-services" href="https://www.fool.com/premium/stock-advisor/" data-uw-rm-brl="false">Stock Advisor</a></li>
                    <li><a data-action="premium-services" href="https://www.fool.com/premium/rule-breakers/" data-uw-rm-brl="false">Rule Breakers</a></li>
                    <li><a data-action="premium-services" href="https://www.fool.com/premium/rule-your-retirement/" data-uw-rm-brl="false">Rule Your Retirement</a></li>
                    <li><a data-action="premium-services" href="https://www.fool.com/premium/options/" data-uw-rm-brl="false">Options</a></li>
                    <li><a data-action="premium-services" href="https://www.fool.com/premium/boss-mode/" data-uw-rm-brl="false">Boss Mode</a></li>
                    <li><a data-action="premium-services" href="/services/" data-uw-rm-brl="false">All Services</a></li>
                </ul>
            </div>

            <div class="footer-list">
                <h2>Free Tools</h2>
                <ul>
                    <li><a data-action="tools" target="_blank" href="https://caps.fool.com/Index.aspx" data-uw-rm-brl="false" aria-label="CAPS Stock Ratings - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://caps.fool.com/index.aspx$capsstockratings">CAPS Stock Ratings</a></li>
                    <li><a data-action="tools" target="_blank" href="https://boards.fool.com/" data-uw-rm-brl="false" aria-label="Discussion Boards - opens in new tab" data-uw-rm-ext-link="" uw-rm-external-link-id="https://boards.fool.com/$discussionboards">Discussion Boards</a></li>
                    <li><a data-action="tools" href="/calculators/index/" data-uw-rm-brl="false">Calculators</a></li>
                </ul>
            </div>
        </div><!-- .footer-lists -->
    </div><!-- .footer-main -->
    <div class="legal-text">
        <ul>
            <li><a data-action="legal" id="footer-tos" href="/legal/terms-and-conditions/fool-rules/" title="Terms of Use" data-uw-rm-brl="false">Terms of Use</a> </li>
            <li><a data-action="legal" id="footer-pp" href="/legal/privacy-statement/" title="Privacy Policy" data-uw-rm-brl="false">Privacy Policy</a> </li>
            <li><a data-action="legal" id="footer-ap" href="/legal/accessibility-policy/" title="Accessibility Policy" data-uw-rm-brl="false">Accessibility Policy</a></li>
            <li><a data-action="legal" id="footer-tm" href="/legal/stuff-we-own/" title="Copyright, Trademark and Patent Information" data-uw-rm-brl="false">Copyright, Trademark and Patent Information</a> </li>
            <li><a data-action="legal" id="footer-tc" href="/legal/terms-and-conditions/" title="Terms and Conditions" data-uw-rm-brl="false">Terms and Conditions</a> </li>
            <li><a data-action="legal" id="footer-ccpa" href="/data-protection/ccpa-update/" title="Do Not Sell My Personal Information." data-uw-rm-brl="false">Do Not Sell My Personal Information</a> </li>
        </ul>
    </div>
</footer>
<?php
}?>