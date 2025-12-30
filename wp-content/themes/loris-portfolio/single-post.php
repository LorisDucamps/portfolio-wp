<?php get_header(); ?>

<main class="single-article">

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>

            <article <?php post_class(); ?>>

                <h1><?php the_title(); ?></h1>

                <div class="article-meta">
                    <time datetime="<?php echo get_the_date('c'); ?>">
                        <?php echo get_the_date(); ?>
                    </time>
                </div>

                <div class="article-content">
                    <?php the_content(); ?>
                </div>

            </article>

        <?php endwhile; ?>
    <?php endif; ?>

</main>

<?php get_footer(); ?>