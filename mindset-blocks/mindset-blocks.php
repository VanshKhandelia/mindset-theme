<?php

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function mindset_blocks_mindset_blocks_block_init() {
	register_block_type( __DIR__ . '/build/copyright' );
	register_block_type( __DIR__ . '/build/company-address' );
	register_block_type( __DIR__ . '/build/company-email' );
	register_block_type( __DIR__ . '/build/service-posts' , array( 'render_callback' => 'fwd_render_service_posts' ));
	register_block_type( __DIR__ . '/build/testimonial-slider', array( 'render_callback' => 'fwd_render_testimonial_slider' ) );
}
add_action( 'init', 'mindset_blocks_mindset_blocks_block_init' );

/**
* Registers the custom fields for some blocks.
*
* @see https://developer.wordpress.org/reference/functions/register_post_meta/
*/
function mindset_register_custom_fields() {
	register_post_meta(
		'page',
		'company_email',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'single'       => true
		)
	);
	register_post_meta(
		'page',
		'company_address',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'single'       => true
			)
		);
}
	
add_action( 'init', 'mindset_register_custom_fields' );


function fwd_render_service_posts( $attributes ) {
	ob_start();
    ?>
    <div <?php echo get_block_wrapper_attributes(); ?>>
		<?php
		$args = array( 
			'post_type'      => 'fwd-service',
			'posts_per_page' => -1,
			'orderBy'        => 'title',
			'order'          => 'ASC'
		);
		
		$query = new WP_Query( $args );
		if ( $query -> have_posts() ) {
			echo '<div class="services-nav">';
			while( $query -> have_posts() ) {
				$query -> the_post();
				
				echo '<a href="#'.get_the_ID() . '"><p>' . get_the_title() . '</p></a>';
				
			}
			echo '</div>';
			wp_reset_postdata(); 
		}
		
		$terms = get_terms( array(
			'taxonomy'   => 'fwd-service-category',
		) );
		
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				echo '<section>';
				echo '<h2>' . esc_html( $term->name ) . '</h2>'; 
				$query = new WP_Query( array(
					'post_type'      => 'fwd-service',
					'posts_per_page' => -1,
					'tax_query'      => array(
						array(
							'taxonomy' => 'fwd-service-category',
							'field'    => 'slug',
							'terms'    => $term->slug,
						),
					),
				) );
		
				if ( $query -> have_posts() ) {
					while( $query -> have_posts() ) {
						$query -> the_post();
						
						echo '<article id='  .get_the_ID(). '>';
						echo '<h3>' . get_the_title() . '</h3>';
						the_content(); 
						echo '</article>';
						
					}
					wp_reset_postdata(); 
				}

				echo '</section>';
			}
		}
		?>
    </div>
    <?php
    return ob_get_clean();
}

// Callback function for the Testimonial Slider
function fwd_render_testimonial_slider( $attributes, $content ) {
    ob_start();
    $swiper_settings = array(
        'pagination' => $attributes['pagination'],
        'navigation' => $attributes['navigation'],
    );
	$arrow_color = $attributes['arrowColor'];
    $style = "--arrow-color: {$arrow_color};";
    ?>
    <div <?php echo get_block_wrapper_attributes( array( 'style' => $style )); ?>>
        <script>
            const swiper_settings = <?php echo json_encode( $swiper_settings ); ?>;
        </script>
        <?php
        $args = array(
            'post_type'      => 'fwd-testimonial',
            'posts_per_page' => -1
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : ?>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <div class="swiper-slide">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php if ( $attributes['pagination'] ) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
            <?php if ( $attributes['navigation'] ) : ?>
                <button class="swiper-button-prev"></button>
                <button class="swiper-button-next"></button>
            <?php endif; ?>
            <?php
            wp_reset_postdata();
        endif;
        ?>
    </div>
    <?php
    return ob_get_clean();
}