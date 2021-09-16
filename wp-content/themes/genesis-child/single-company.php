<?php get_header(); ?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">
        <div class="container">
            <div id="singlePostContainer" class="singlePostContainer row">
                <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-0 col-0"></div>
                <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-10 col-sm-12 col-12">
                    <div class="row">
                        <?php
                        // Start the loop.
                        while ( have_posts() ) : the_post();
                        ?>     
                        <?php 
                            $company = get_the_title();                           
                            $dataCP = json_decode(retrieveStockData($company,'https://financialmodelingprep.com/api/v3/profile/')['body']);
                            $dataSQ = json_decode(retrieveStockData($company,'https://financialmodelingprep.com/api/v3/quote/')['body']);
                        ?>
                        <div class="row titleRow">
                            <div class="col-xxl-9 col-xl-9 col-lg-9 col-md-8 col-sm-12 col-12 px-0">
                                <h1 class="companyName"><?php echo $dataCP[0]->companyName ?></h1> 
                            </div>
                            <div class="col-xxl-3 col-xl-3 col-lg-3 col-md-4 col-sm-12 col-12 px-0">
                                <?php if (has_post_thumbnail()) : ?>
                                    <figure class="singleFeaturedImage companyFloat"><?php the_post_thumbnail('large'); ?></a> </figure>
                                <?php else : ?>
                                    <figure class="singleFeaturedImage companyFloat"><img src="<?php echo $dataCP[0]->image ?>" alt="<?php echo $dataCP[0]->companyName ?> logo"></a> </figure>
                                <?php endif; ?>   
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
                            <div id="dataContent" class="col-xxl-10 col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12 contentHolder">
                                <?php if (tr_post_field('company_description')) : ?>
                                    <?php echo tr_post_field('company_description');?>
                                <?php else : //api image below?>
                                    <p><?php echo $dataCP[0]->description ?></p>
                                <?php endif; ?>  
                            </div>
                            <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
                        </div>
                        <div class="row">
                            <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
                            <div class="col-xxl-10 col-xl-10 col-lg-10 col-md-12 col-sm-12 col-12">
                            <div class="stockContainer">
                                <table class="watch-list">
                                    <thead>
                                    <th>Symbol</th>
                                    <th>Last Price</th>
                                    <th>Change</th>
                                    <th>% Change</th>
                                    </thead>
                                    <tr>
                                    <td>
                                        <a href="<?php echo $dataCP[0]->website; ?>"><?php echo $dataCP[0]->symbol; ?></a>
                                    </td>
                                    <td><?php echo $dataCP[0]->price ?></td>
                                    <td class="<?php if (intval($dataSQ[0]->change < 0)) { echo 'negative'; } ?>"><?php echo $dataSQ[0]->change ?></td>
                                    <td class="<?php if (intval($dataSQ[0]->changesPercentage < 0)) { echo 'negative'; } ?>"><?php echo $dataSQ[0]->changesPercentage ?></td>
                                    </tr>
                                </table>
                                <table class="stock-info mx-3">
                                    <tbody>
                                    <tr>
                                        <td>52 Week Range</td>
                                        <td><?php echo $dataCP[0]->range ?></td>
                                    </tr>
                                    <tr>
                                        <td>Beta</td>
                                        <td><?php echo $dataCP[0]->beta ?></td>
                                    </tr>
                                    <tr>
                                        <td>Volume Average</td>
                                        <td><?php echo $dataCP[0]->volAvg ?></td>
                                    </tr>
                                    <tr>
                                        <td>Market Capitalisation</td>
                                        <td><?php echo $dataCP[0]->mktCap ?></td>
                                    </tr>
                                    <tr>
                                        <td>Last Dividend</td>
                                        <?php if ($dataCP[0]->lastDiv != null): ?>
                                            <td><?php echo $dataCP[0]->lastDiv ?></td>
                                        <?php else: ?>
                                            <td><?php echo 'N/A' ?></td>
                                        <?php endif ?>
                                    </tr>
                                    </tbody>
                                </table>
                                </div>
                            </div>
                            <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-0 col-sm-0 col-0"></div>
                        </div>
                        <?php 
                        // End the loop.
                        endwhile;
                        ?>
                    </div>
                </div>
                <div class="col-xxl-1 col-xl-1 col-lg-1 col-md-1 col-sm-0 col-0"></div>
            </div>   
            <?php 
                $args = array(
                    'post_type' => array('recommendation'),
                    'posts_per_page' => -1,
                );
                $recommendationsQuery = new WP_Query($args);
            ?> 
            <div class="archiveContainer">   
                <div class="row">
                    <h3 class="sectionLabel">Past Recommendations:</h3>
                    <?php if ($recommendationsQuery->have_posts()): while($recommendationsQuery->have_posts()): $recommendationsQuery->the_post(); ?>
                        <?php if (tr_post_field('company_ticker') === $company): ?>
                            <article class="archiveCard">        
                                <div class="card-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="archiveCardImageHolder">
                                            <figure class="archiveCardImage"> <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a> </figure>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <figure class="archiveCardImage"> <a href="<?php the_permalink(); ?>"><img src="https://optimize.foolcdn.com/?url=https%3A%2F%2Fg.foolcdn.com%2Fmisc-assets/jester.png&w=400&op=resize"></a> </figure>
                                        </a>
                                    <?php endif; ?>

                                </div>
                                <div class="text">
                                    <a href="<?php the_permalink(); ?>">
                                        <h4 data-uw-rm-heading="level" role="heading" aria-level="3"><?php echo get_the_title(); ?></h4>
                                    </a>
                                    <div class="story-date-author"><?php the_author_posts_link(); ?> | <?php echo get_the_date( 'F j, Y' ); ?></div>
                                    <p class="article-promo"><?php echo get_the_excerpt(); ?></p>
                                </div>
                            </article>
                        <?php endif; ?>
                    <?php 
                    // End the loop.
                    endwhile;
                    ?>
                    <?php else : ?>
                        <p class="noResults" style="margin-top: 48px ">Coming Soon</p>
                    <?php endif; ?>
                </div>
            </div>
            <?php wp_reset_query(); ?>
            <?php 
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => array('news'),
                    'posts_per_page' => 10,
                    'orderby' => 'date',
                    'order'   => 'DESC',
                    'paged' => $paged
                );
                $newsQuery = new WP_Query($args);
            ?> 
            <div class="archiveContainer">   
                <div class="row">
                    <h3 class="sectionLabel">In The News:</h3>
                    <?php if ($newsQuery->have_posts()): while($newsQuery->have_posts()): $newsQuery->the_post(); ?>
                        <?php if (tr_post_field('ticker_symbol') === $company): ?>
                            <article class="archiveCard">        
                                <div class="card-image">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="archiveCardImageHolder">
                                            <figure class="archiveCardImage"> <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('large'); ?></a> </figure>
                                        </div>
                                    <?php else: ?>
                                        <a href="<?php the_permalink(); ?>">
                                            <figure class="archiveCardImage"> <a href="<?php the_permalink(); ?>"><img src="https://optimize.foolcdn.com/?url=https%3A%2F%2Fg.foolcdn.com%2Fmisc-assets/jester.png&w=400&op=resize"></a> </figure>
                                        </a>
                                    <?php endif; ?>

                                </div>
                                <div class="text">
                                    <a href="<?php the_permalink(); ?>">
                                        <h4 data-uw-rm-heading="level" role="heading" aria-level="3"><?php echo get_the_title(); ?></h4>
                                    </a>
                                    <div class="story-date-author"><?php the_author_posts_link(); ?> | <?php echo get_the_date( 'F j, Y' ); ?></div>
                                    <p class="article-promo"><?php echo get_the_excerpt(); ?></p>
                                </div>
                            </article>
                        <?php endif; ?>
                    <?php 
                    // End the loop.
                    endwhile;
                    ?>
                    <?php else : ?>
                        <p class="noResults" style="margin-top: 48px ">Coming Soon</p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-center">
                         <?php
                            singlePagination( 'Older Entries', 'next', $newsQuery );
                            echo '<span>     </span>'; 
                            singlePagination( 'Newer Entries', 'prev', $newsQuery );
                         ?>
                    </div>
                </div>
             </div>
            </div>
            <?php wp_reset_query(); ?>
        </div>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php get_footer(); ?>
