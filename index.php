<?php
get_header();
?>

<section class="section-padding">
	<div class="container">

		<div class="row mb-4">
			<div class="col-12">
				<h1>Blogs</h1>
			</div>
		</div>

		<div class="row mb-4">
			<div class="col-md-4">
				<select id="blog-category" class="form-select" multiple>
					<option value="">All Categories</option>

					<?php
					$categories = get_categories([
						'taxonomy'   => 'category',
						'hide_empty' => true,
					]);

					foreach ( $categories as $category ) :
					?>
						<option value="<?php echo esc_attr( $category->term_id ); ?>">
							<?php echo esc_html( $category->name ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="col-md-4">
				<input
					type="text"
					id="blog-search"
					class="form-control"
					placeholder="Search blogs..."
				>
			</div>
		</div>

		<div class="row gy-4" id="blog-posts"></div>

		<div id="loader-wrapper" style="display:none;">
			<div class="loader">Loading...</div>
		</div>

	</div>
</section>

<?php
get_footer();