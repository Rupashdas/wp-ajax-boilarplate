<?php

$post_id = $args['post_id'] ?? null;

if ( $post_id ) :

	$title     = get_the_title( $post_id );
	$permalink = get_permalink( $post_id );
	$excerpt   = get_the_excerpt( $post_id );
	$image     = get_the_post_thumbnail_url( $post_id, 'medium' );
	$cat = get_the_category_list()
?>
<div class="col-lg-4">
	<div class="card h-100">
		<img src="<?php echo esc_url( $image ); ?>" class="card-img-top" alt="<?php echo esc_attr( $title ); ?>">

		<div class="card-body">
			<h5 class="card-title">
				<?php echo esc_html( $title ); ?>
			</h5>

			<p>Category: <?php echo wp_kses_post($cat); ?></p>
			<p class="card-text">
				<?php echo esc_html( wp_trim_words( $excerpt, 20 ) ); ?>
			</p>

			<a href="<?php echo esc_url( $permalink ); ?>" class="btn btn-primary">
				Read More
			</a>
		</div>
	</div>
</div>

<?php endif; ?>