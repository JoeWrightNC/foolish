<?php get_header(); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="container">
            <div id="singlePostContainer" class="singlePostContainer row">
                <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-0 col-0"></div>
                <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                        <?php
                        // Start the loop.
                        while ( have_posts() ) : the_post();
                        ?>     
                        <?php 
                            $company = tr_post_field('company_ticker');     
                            $dataCP = json_decode(retrieveStockData($company,'https://financialmodelingprep.com/api/v3/profile/')['body']);
                        ?>
                        <div class="row titleRow">
                            <div class="col-12 px-0">
                                <h1 class="companyName"><?php echo get_the_title(); ?> | <?php echo tr_post_field('ticker_symbol'); ?></h1> 
                            </div>
                        </div>
                        <div class="row singleBylineRow">
                            <div class="col-12 px-0">
                                <div class="row author-tagline author-inline">
                                    <div class="author-tagline-top">
                                        <div class="author-avatar">
                                            <img src="https://g.foolcdn.com/avatar/2041224644/large.ashx" alt="" role="presentation" data-uw-rm-ima="exc">
                                        </div>
                                        <div class="author-and-date">
                                            <div class="author-name">                
                                                <div class="author-username">
                                                    <?php  the_author_posts_link(); ?>
                                                </div>
                                            </div>
                                            <div class="publication-date">
                                                <p><?php the_date( 'F j, Y' ); ?> </p>
                                            </div>      
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
                            <div id="dataContent" class="col-xxl-10 col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 contentHolder px-0 mx-0">
                                <?php echo the_content();?>
                            </div>
                            <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
                            <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12">
                            <div class="companyContainer">
                                <div class="companyCallout">
                                    <h1><?php echo $dataCP[0]->companyName ?></h1>
                                    <figure class="singleFeaturedImage"><img src="<?php echo $dataCP[0]->image ?>" alt="<?php echo $dataCP[0]->companyName ?> logo"></a> </figure>
                                </div>
                                <ul class="stats">
                                    <li>
                                        <div class="stat">
                                            <?php echo $dataCP[0]->exchangeShortName ?>
                                            <span class="label">Exchange</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="stat">
                                            <?php echo $dataCP[0]->industry ?>
                                            <span class="label">Industry</span>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="stat">
                                            <?php echo $dataCP[0]->sector ?>
                                            <span class="label">Sector</span>
                                        </div>
                                    </li>
                                    <li class="last">
                                        <div class="stat">
                                            <?php echo $dataCP[0]->ceo ?>
                                            <span class="label">CEO</span>
                                        </div>
                                    </li>
                                </ul>
                                <p class="companyProfDescription"><?php echo $dataCP[0]->description ?></p>
                                <a href="<?php echo $dataCP[0]->website ?>" target="_blank">Visit Website</a>
                            </div>
                            <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
                        </div>
                        <?php 
                        // End the loop.
                        endwhile;
                        ?>
                    </div>
                </div>
            </div>   
            <?php wp_reset_query(); ?>
        </div>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
