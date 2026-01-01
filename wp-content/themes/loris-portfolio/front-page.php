<?php get_header(); ?>

<div class="container">
    <div class="container__inner">

        <header class="navigation">
            <div>Logo svg</div>
            <nav>
                <ul>
                    <li><a href="">About</a></li>
                    <li><a href="">Articles</a></li>
                    <li><a href="/portfolio">Portfolio</a></li>
                </ul>
            </nav>
            <button
                id="theme-toggle"
                aria-pressed="false"
                aria-label="Activer le mode sombre">
                🌙</button>

            </button>
        </header>



        <section class="intro">
            <div class="intro__inner">
                <h1>Développeur Front-End, allergique au “ça passe”</h1>

                <p>Moi c’est Loris, développeur front-end.
                    J’aime les intégrations propres, les architectures claires
                    et les projets où la qualité du code compte vraiment.
                    Quand je ne code pas, je fais de la veille tech ou du piano,
                    souvent avec l’objectif d’améliorer ce que j’ai livré la veille.</p>

                <div>
                    URL Linkedin / Github / Malt
                </div>
            </div>
        </section>

        <section class="content-and-experience">
            <div class="inner">
                <div>colonne gauche avec mes articles</div>
                <div>colonne droite avec mes expériences</div>
                <?php if (have_posts()) : ?>
                    <ul class="articles-list">

                        <?php while (have_posts()) : the_post(); ?>
                            <li class="article-item">
                                <h3>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_title(); ?>
                                    </a>
                                </h3>

                                <p><?php the_excerpt(); ?></p>
                            </li>
                        <?php endwhile; ?>

                    </ul>
                <?php else : ?>
                    <p>Aucun article pour le moment.</p>
                <?php endif; ?>
            </div>
        </section>

        <footer>
            <ul>
                <li>
                    <a href="/about">About</a>
                </li>
                <li>
                    <a href="/articles">Articles</a>
                </li>
                <li>
                    <a href="/portfolio">Portfolio</a>
                </li>
            </ul>
            <div>
                <p>&copy; 2026 Loris Ducamps.</p>
            </div>
        </footer>
    </div>
</div>


<?php get_footer(); ?>