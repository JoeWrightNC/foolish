
  
<?php get_header(); ?>
 
 <div id="primary" class="content-area">
     <main id="main" class="site-main" role="main" style="margin-top: 0px !important;">
         <div class="container archiveContainer">
             <div class="row archiveRow" style="flex-direction:row;">
                <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-1 col-sm-0 col-0"></div>
                <div class="col-xxl-8 col-xl-8 col-lg-8 col-md-10 col-sm-12 col-12">     
                    <div class="row archiveTitleRow">
                        <div class="col-12 archiveTitleCol">
                            <h1 class="archiveTitle"><?php echo get_the_archive_title() ?></h1>
                        </div>
                    </div>         
                    <?php if ( have_posts() ) : ?>
                    <?php 
                    // Start the loop.
                    while ( have_posts() ) : the_post();
                    ?> 
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
                        
                    <?php 
                    // End the loop.
                    endwhile;
                    ?>
                    <?php else : ?>
                        <p class="noResults" style="margin-top: 48px">No recommendations yet, check back soon.</p>
                    <?php endif; ?>
                    <div class="d-flex justify-content-center">
                        <?php 
                            next_posts_link();
                            echo '<span>     </span>'; 
                            previous_posts_link(); 
                        ?>
                    </div>
                </div>
                <div class="col-xxl-2 col-xl-2 col-lg-2 col-md-1 col-sm-0 col-0"></div>
             </div>  
         </div>
     </main><!-- .site-main -->
 </div><!-- .content-area -->

<?php get_footer(); ?>
