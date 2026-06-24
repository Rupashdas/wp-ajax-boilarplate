(function ($) {
	'use strict';

	$(document).ready(function () {
		const wrapper = $('#blog-posts');
		let page_number = 0;
		let search_query = "";
		let category = 0;
		let is_loading = false;
		let next_page = false;

		function load_posts() {

			page_number++;

			const post_data = {
				action: 'load_global_posts',
				nonce: dublin_data.nonce,
				from_page: page_number,
			}
			if (search_query != "") {
				post_data.search_value = search_query;
			}
			if (category > 0) {
				post_data.category = category;
			}

			$.ajax({
				url: dublin_data.ajax_url,
				type: 'POST',
				data: post_data,
				beforeSend: function () {
					$("#loader-wrapper").show();
					is_loading = true;
				},
				success: function (response) {
					console.log(response);
					if (response.success) {
						is_loading = false;

						$("#loader-wrapper").hide();

						const data = response.data;

						if (page_number === 1) {
							wrapper.html(data.html);
						} else {
							wrapper.append(data.html);
						}
						next_page = data.has_more;

						$("#paginate-links").html(data.pagination_markup);
					}
				}
			});

		}
		if (wrapper.length > 0) {
			load_posts();
		}

		let searchTimer;
		$("#blog-search").on("keyup", function () {

			page_number = 0;
			category = $("#blog-category").val();
			clearTimeout(searchTimer);
			searchTimer = setTimeout(function () {
				search_query = $("#blog-search").val();
				load_posts();
			}, 500);
		});

		$("#blog-category").on("change", function () {
			page_number = 0;
			category = $(this).val();
			search_query = $("#blog-search").val();
			load_posts();
		});


		// $(window).on("scroll", function () {
		// 	// console.log(is_loading);
		// 	console.log(next_page);
		// 	if (is_loading || !next_page) {
		// 		return;
		// 	}

		// 	const bottomPosition = $(window).scrollTop() + $(window).height();
		// 	const triggerPoint = $(wrapper).offset().top + $(wrapper).outerHeight();

		// 	if (bottomPosition > triggerPoint) {
		// 		load_posts();
		// 	}

		// });

		$(document).on("click", ".page-link", function (e) {
			e.preventDefault();

			let value = $(this).attr("data-page-number");
			page_number = value - 1;
			load_posts();
		})

	});

})(jQuery);
