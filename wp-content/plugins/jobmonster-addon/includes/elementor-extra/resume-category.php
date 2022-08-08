<?php
namespace Noo_Elementor_Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Scheme_Color;

class Resume_Category extends Widget_Base{
    public function get_name()
    {
        return 'resume_category';
    }
    public function get_title()
    {
       return esc_html__('Resume Category', 'noo');
    }
    public function get_icon()
    {
        return 'fa fa-tag';
    }
    public function get_categories()
    {
        return [ 'noo-element-widgets' ];
    }

    public function get_style_depends()
    {
        return [
         'owl-carousel',
    ];
    }
    public function get_script_depends() {
        return [
            'owl-carousel',
            'noo-elementor',
        ];
    }
    private function get_resume_categories($taxonomy='job_category'){
        if ( ! empty( $taxonomy ) ) {
            // Get categories for post type.
            $terms = get_terms(
                array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => true,
                )
            );
            if ( ! empty( $terms ) ) {
                foreach ( $terms as $term ) {
                    if ( isset( $term ) ) {
                        if ( isset( $term->term_id ) && isset( $term->name ) ) {
                            $options[ $term->term_id ] = $term->name;
                        }
                    }
                }
            }
        }

        return $options;
    }
    protected function _register_controls()
    {
      $this->start_controls_section(
          'resume_category',
          [
              'label' => esc_html__('Resume Category Options', 'noo'),
              'tab'   => Controls_Manager::TAB_CONTENT,
          ]
      );
      $this->add_control(
          'style',
          [
              'label' => esc_html__('Style', 'noo'),
              'type'  => Controls_Manager::SELECT,
              'options' =>[
                  'style-grid'=> esc_html__('Style Grid', 'noo'),
                  'style-list'  => esc_html__('Style List', 'noo'),
              ],
              'default' => 'style-grid',
          ]
      );
      $this->add_control(
          'list_resume_category',
          [
              'label' => esc_html__('Select Resume Category', 'noo'),
              'type'  => Controls_Manager::SELECT2,
              'multiple' => true,
              'options'  => $this->get_resume_categories('job_category'),
          ]
      );
      $this->add_control(
          'hide_empty',
          [
              'label' => esc_html__('Hide Empty', 'noo'),
              'type'  => Controls_Manager::SWITCHER,
              'label_on'=> esc_html__('Hide', 'noo'),
              'label_off'=> esc_html__('Show', 'noo'),
              'return_value' => true,
              'default' => true,

          ]
      );
      $this->add_control(
          'show_resume_count',
          [
              'label' => esc_html__('Show Resume Count', 'noo'),
              'type'  => Controls_Manager::SWITCHER,
              'label_on' => esc_html__('Show', 'noo'),
              'label_of' => esc_html__('Hide', 'noo'),
              'return_value' => true,
              'default' => true,
          ]
      );
      $this->add_control(
          'limit_category',
          [
              'label' => esc_html__('Limit Category', 'noo'),
              'type'  => Controls_Manager::NUMBER,
              'min'   => 5,
              'max'   => 100,
              'step'  => 1,
              'default'=> 8,

          ]
      );
        // Columns.
        $this->add_responsive_control(
            'columns',
            [
                'label'          => '<i class="fa fa-columns"></i> ' . esc_html__( 'Columns', 'noo' ),
                'type'           => Controls_Manager::SELECT,
                'default'        => 4,
                'tablet_default' => 2,
                'mobile_default' => 1,
                'options'        => [
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                ],
            ]
        );
        $this->add_control(
            'visibility',
            [
                'label' => esc_html__('Visibility', 'noo'),
                'type'  => Controls_Manager::SELECT,
                'options' => [
                    'all'    => esc_html__('All Devices', 'noo'),
                    'hidden-phone' => esc_html__('Hidden phone', 'noo'),
                    'hidden-tablet'=> esc_html__('Hidden Tablet', 'noo'),
                    'hidden-pc'    => esc_html__('Hidden PC', 'noo'),
                    'visible-phone'=> esc_html__('Visible Phone', 'noo'),
                    'visible-tablet'=> esc_html__('Visible Tablet', 'noo'),
                    'visible-pc'    => esc_html__('Visible PC', 'noo')
                ],
                'default' => 'all',
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label'     => esc_html__( 'Item Spacing for Grid', 'noo' ),
                'type'      => Controls_Manager::SLIDER,
                'default'   => [
                    'size' => 15,
                ],
                'range'     => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .noo-resume-category-widget'   => 'margin: -{{SIZE}}{{UNIT}}',
                    '{{WRAPPER}} .noo-resume-category-widget .category-item'   => 'padding: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->end_controls_section();

    }
    private function noo_visibility_class( $visibility = '' ) {
        switch ( $visibility ) {
            case 'hidden-phone':
                return ' hidden-xs';
            case 'hidden-tablet':
                return ' hidden-sm';
            case 'hidden-pc':
                return ' hidden-md hidden-lg';
            case 'visible-phone':
                return ' visible-xs';
            case 'visible-tablet':
                return ' visible-sm';
            case 'visible-pc':
                return ' visible-md visible-lg';
            default:
                return '';
        }
    }
    protected function render(){
        $settings = $this->get_settings();

            $categories_grid_class =  $data_slide = $item_class= '';
            if($settings['style']=='style-grid' || $settings['style']=='style-list'){
                $item_class ='noo-grid-item';
                $mobile_class = (!empty($settings['columns_mobile']) ? 'noo-mobile-' . $settings['columns_mobile'] : '');
                $tablet_class = (!empty($settings['columns_tablet']) ? 'noo-tablet-' . $settings['columns_tablet'] : '');
                $desktop_class = (!empty($settings['columns']) ? 'noo-desktop-' . $settings['columns'] : '');
                $this->add_render_attribute('category-grid', 'class', ['noo-resume-category is-flex', $desktop_class, $tablet_class, $mobile_class]);
                $categories_grid_class = $this->get_render_attribute_string('category-grid');
            }
            $visibility = ($settings['visibility'] != '') && ($settings['visibility'] != 'all') ? esc_attr($settings['visibility']) : '';
            $class = 'noo-resume-category-widget noo-resume-category clearfix' . " {$settings['style']}";
            $class .=$this->noo_visibility_class($visibility);

            $class = ($class != '') ? ' class="' . $class . '"' : '';
            ?>
            <div<?php echo($class ); ?>>
                <div class="noo-resume-category-wrap <?php echo esc_attr($settings['style']); ?>">
                    <div <?php echo $data_slide . implode('', [$categories_grid_class]) ?>>
                        <?php
                        $i = 0;
                        if (empty($settings['list_resume_category']) or $settings['list_resume_category'] == '') {
                            $categories = get_terms('job_category', array(
                                'orderby' => 'NAME',
                                'order' => 'ASC',
                                'hide_empty' => $settings['hide_empty'],
                            ));
                            foreach ($categories as $key => $cat) :
                                if ($i >= $settings['limit_category'])
                                    break;
                                $cate_name          = $cat->name;
                                $resume_count          = $cat->count;
                                $job_count = noo_get_total_resume_category( $cat->term_id );
                                $cate_link          = get_term_link($cat);
                                $icon_markers       = get_term_meta($cat->term_id, 'icon_type', true);                          
                                $cat_cover_id       = get_term_meta($cat->term_id, 'background_cover', true);
                                $cat_cover_image    = wp_get_attachment_image_url( $cat_cover_id, $size = 'large', $icon = false );
                                if (empty($icon_markers)) {
                                    $icon_markers = 'fa-home';
                                }
                                if($settings['style']=='style-list'):
                                ?>
                                    <div class="<?php echo $item_class ?> category-item">
                                        <a href="<?php echo esc_url_raw(add_query_arg( '_job_category', $cat->term_id, get_post_type_archive_link('noo_resume') ))?>">
                                            <span class="title">
                                                <?php echo esc_html( $cate_name ); ?>
                                                <span class="job-count">(<?php echo absint( $job_count ); ?> )</span>
                                            </span>
                                        </a>
                                    </div>
                                    <?php 
                                    else:
                                    ?>
                                <div class="<?php echo $item_class ?> category-item">
                                   <a href="<?php echo esc_url_raw(add_query_arg( '_job_category', $cat->term_id, get_post_type_archive_link('noo_resume') ));?>">
                                        <?php if($settings['style'] != 'style-list'): ?>
                                        <div class="noo-grid-item-bg" style="background-image: url('<?php echo esc_url( $cat_cover_image ); ?>')">
                                            </div>
                                           <span class="icon">
                                               <i class="fa <?php echo esc_attr($icon_markers) ?>"></i>
                                           </span>
                                        <?php endif; ?>
                                        <div class="item-content">
                                            <?php if($settings['style'] != 'style-grid'): ?>
                                                <span class="icon">
                                                    <i class="fa <?php echo esc_attr($icon_markers) ?>"></i>
                                                </span>
                                            <?php endif; ?>
                                            <h5 class="title">
                                                <?php echo esc_html($cate_name); ?>
                                            </h5>
                                            <?php if ($settings['show_resume_count']) : ?>
                                                <span class="resume-count">
                                                 (<?php echo sprintf( _n( '%s Available Worker', '%s Available Workers', noo_get_total_resume_category( $cat->term_id ), 'noo' ), noo_get_total_resume_category( $cat->term_id ) ); ?> )
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </a>
                                </div>
                                <?php endif;
                                ?>
                                <?php $i++; endforeach;
                        } else {
                            $list_cat =  $settings['list_resume_category'];
                            foreach ($list_cat as $key => $cat) :
                                $cate = get_term_by('id', absint($cat), 'job_category');
                                if (!empty($cate)):
                                    if ($i >= $settings['limit_category'])
                                        break;
                                    $cate_name    = $cate->name;
                                    $resume_count    = $cate->count;
                                    $cate_link    = get_term_link($cate);
                                    $icon_markers = get_term_meta($cat->term_id, 'icon_type', true);                    
                                    if (empty($icon_markers)) {
                                        $icon_markers = 'fa-home';
                                    }
                                    $job_count = noo_get_total_resume_category( $cat->term_id );
                                    if($settings['style']=='style-list'):
                                    ?>
                                    <div class="<?php echo $item_class ?> category-item">
                                        <a href="<?php echo esc_url_raw(add_query_arg( '_job_category', $cat->term_id, get_post_type_archive_link('noo_resume') ))?>">
                                            <span class="title">
                                                <?php echo esc_html( $cate_name ); ?>
                                                <span class="job-count">(<?php echo absint( $job_count ); ?> )</span>
                                            </span>
                                        </a>
                                    </div>
                                    <?php 
                                    else:
                                    ?>
                                    <div class="<?php echo $item_class ?> category-item">
                                       <a href="<?php echo esc_url_raw(add_query_arg( '_job_category', $cat->term_id, get_post_type_archive_link('noo_resume') ));?>">
                                            <span class="icon">
                                                <i class="fa <?php echo esc_attr($icon_markers) ?>"></i>
                                            </span>
                                            <span class="title">
                                                <?php echo esc_html($cate_name); ?>
                                            </span>
                                            <?php if ($settings['show_resume_count']) : ?>
                                                <span class="resume-count">
                                                    (<?php echo sprintf( _n( '%s Available worker', '%s Available Workers', noo_get_total_resume_category( $cat->term_id ), 'noo' ), noo_get_total_resume_category( $cat->term_id ) ); ?> )
                                                </span>
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <?php endif;?>
                                <?php
                                endif;
                            $i++; 
                            endforeach;
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
    }
}