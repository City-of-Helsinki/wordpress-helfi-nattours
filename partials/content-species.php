<?php
$plants = wp_get_post_terms( $post->ID, 'plants' );
$mushrooms = wp_get_post_terms( $post->ID, 'mushrooms' );
$mosses_and_lichens = wp_get_post_terms( $post->ID, 'mosses_and_lichens' );
$birds = wp_get_post_terms( $post->ID, 'birds' );
$mammals = wp_get_post_terms( $post->ID, 'mammals' );
$reptiles = wp_get_post_terms( $post->ID, 'reptiles' );
$amphibians = wp_get_post_terms( $post->ID, 'amphibians' );
$insects = wp_get_post_terms( $post->ID, 'insects' );
$fishes = wp_get_post_terms( $post->ID, 'fishes' );

$bg_img = get_field('species_image');


function get_species($terms, $taxonomy = 'default') {
  echo '<div class="species-wrapper">';
    foreach( $terms as $value ):
        $thumb = get_field( 'featured_image', $value->taxonomy . '_' . $value->term_id );
        $thumb_img = $thumb['sizes']['location_thumb'] ?? '';
      echo '<a href="' . get_term_link($value, $taxonomy) . '" class="link-component">';
        echo '<div class="link-component__img" style="background-image: url(' .  $thumb_img . '); background-color: gray;"></div>';
        echo '<div class="link-component__text">';
          echo '<span class="h7">' . $value->name . '</span>';
          if ( get_field( 'is_rare', $value->taxonomy . '_' . $value->term_id ) ) :
            echo '&emsp;<span class="is-rare"><i class="fa fa-star" aria-hidden="true"> </i>&ensp;' . pll__('Rare') . '</span>';
          endif;
          echo '<p>' . $value->description . '</p>';
        echo '</div>';
      echo '</a>';
    endforeach;
  echo '</div>';
}
?>

<style>
  .header--location {
    background: linear-gradient(
      to bottom,
      rgba(0, 0, 0, 0.5),
      rgba(0, 0, 0, 0.1) 10%,
      rgba(0, 0, 0, 0.1) 70%,
      rgba(0, 0, 0, 0.6)
    ),
    url(<?= $bg_img['url'] ?>) center/cover no-repeat;
  }
</style>

</header>
<?php if ( wp_get_img_caption( $bg_img['id'] ) ): ?>
    <span class="img-caption"><?= wp_get_img_caption( $bg_img['id'] ); ?></span>
<?php endif; ?>

<main class="location__species">
	<section class="text-content">
		<?php if( $plants ) : ?>
				<h6><?= pll__('Plants'); ?></h6>
				<?php get_species($plants, 'plants') ?>
		<?php endif; ?>

		<?php if( $mushrooms ) : ?>
				<h6><?= pll__('Mushrooms'); ?></h6>
				<?php get_species($mushrooms, 'mushrooms') ?>
		<?php endif; ?>

		<?php if( $mosses_and_lichens ) : ?>
				<h6><?= pll__('Mosses and lichens'); ?></h6>
				<?php get_species($mosses_and_lichens, 'mosses_and_lichens') ?>
		<?php endif; ?>

		<?php if( $birds ) : ?>
				<h6><?= pll__('Birds'); ?></h6>
				<?php get_species($birds, 'birds') ?>
		<?php endif; ?>

		<?php if( $mammals ) : ?>
				<h6><?= pll__('Mammals'); ?></h6>
				<?php get_species($mammals, 'mammals') ?>
		<?php endif; ?>

		<?php if( $reptiles ) : ?>
				<h6><?= pll__('Reptiles'); ?></h6>
				<?php get_species($reptiles, 'reptiles') ?>
		<?php endif; ?>

		<?php if( $amphibians ) : ?>
				<h6><?= pll__('Amphibians'); ?></h6>
				<?php get_species($amphibians, 'amphibians') ?>
		<?php endif; ?>

		<?php if( $insects ) : ?>
				<h6><?= pll__('Insects'); ?></h6>
				<?php get_species($insects, 'insects') ?>
		<?php endif; ?>

		<?php if( $fishes ) : ?>
				<h6><?= pll__('Fishes'); ?></h6>
				<?php get_species($fishes, 'fishes') ?>
		<?php endif; ?>

	</section>
</main>
