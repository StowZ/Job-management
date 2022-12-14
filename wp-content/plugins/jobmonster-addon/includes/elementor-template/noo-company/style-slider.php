<?php
/**
 * style-grid.php
 *
 * @author  : NooTheme
 * @since   : 1.0.0
 */
wp_enqueue_script( 'vendor-carousel' );
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$data_slide = ($is_slider || $style == 'style-slider') ? ' data-slide="' . esc_attr(json_encode($data_slider)) . '"' : '' ;

?>
<div class="owl-carousel noo-company-sc <?php echo esc_attr( $style ); ?>" id="<?php echo ( $id_company = uniqid( 'company-id-' ) ); ?>" <?php echo $data_slide ?>>
    <?php
    while ( $wp_query->have_posts() ) : $wp_query->the_post();
        global $post;
        $company_name = $post->post_title;
        $count = noo_company_job_count($post->ID);
        $cover_image_id = noo_get_post_meta(get_the_ID(), 'cover_image');
        $cover_image = wp_get_attachment_image($cover_image_id, 'jm-thumbnail-square', false);
        $ft = ('yes' == noo_get_post_meta($post->ID, '_company_featured', '')) ? ' featured-company' : '';
        if (empty($cover_image)) {
            $cover_image = '<img src="' . JOB_ADDON_ASSETS . '/images/image-company-default.jpg">';
        }
        ?>
        <div class="noo-grid-item">
            <div class="company-item">
                <div class="company-thumbnail">
                    <a href="<?php the_permalink(); ?>">
                        <?php echo Noo_Company::get_company_logo( $post->ID, 'company-logo' ); ?>
                    </a>
                </div>
                <div class="company-meta">
                    <a class="company-name" href="<?php the_permalink(); ?>">
                        <?php echo noo_get_the_company_name( $post ); ?>
                    </a>
                    <p>
                        <span class="job-count"><?php echo sprintf( _n( '%s Job', '%s Jobs', $count, 'noo' ), $count ); ?></span>
                        <span class="company-address"><?php echo noo_get_company_address( $post->ID ); ?></span>
                    </p>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

</div>