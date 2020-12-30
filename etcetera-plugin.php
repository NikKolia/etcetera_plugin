<?php
/*
 * Plugin Name: Etcetera test plugin
 */

add_filter('widget_text', 'do_shortcode');
do_shortcode($content, $ignore_html);

add_action('init', 'custom_post_init');
function custom_post_init()
{
    register_post_type('property', array(
        'labels' => array(
            'name' => 'Объекты недвижимости', // Основное название типа записи
            'singular_name' => 'Объект недвижимости', // отдельное название записи типа News
            'add_new' => 'Добавить новый',
            'add_new_item' => 'Добавить новый объект',
            'edit_item' => 'Редактировать объект',
            'new_item' => 'Новая объект',
            'view_item' => 'Посмотреть ',
            'search_items' => 'Найти объект',
            'not_found' => 'Объект не найден',
            'not_found_in_trash' => 'В корзине объект не найден',
            'parent_item_colon' => '',
            'menu_name' => 'Объекты недвижимости'
        ),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
    ));
}

function register_taxonomy_area()
{
    $labels = [
        'name' => _x('Районы', 'taxonomy general name'),
        'singular_name' => _x('Район', 'taxonomy singular name'),
        'search_items' => __('Найти район'),
        'all_items' => __('Все районы'),
        'parent_item' => __('Основной район'),
        'parent_item_colon' => __('Основной район:'),
        'edit_item' => __('Добавить район'),
        'update_item' => __('Обновить район'),
        'add_new_item' => __('Добавить новый район'),
        'new_item_name' => __('Новый район'),
        'menu_name' => __('Районы'),
    ];
    $args = [
        'hierarchical' => true, // make it hierarchical (like categories)
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => ['slug' => 'area'],
    ];
    register_taxonomy('area', ['property'], $args);
}

add_action('init', 'register_taxonomy_area');

function my_register_sidebars()
{
    register_sidebar(array(
        'name' => esc_html__('Filter area', 'nd_dosth'),
        'id' => 'filter',
        'description' => esc_html__('There should be added a filter that would appear inside the counter filter section in front of Home page', 'nd_dosth'),
        'before_widget' => '<aside id="%1$s" class="filter">', // то что стоит пред блоком виджета
        'after_widget' => '</aside>', // то что стоит после блока виджета
        'before_title' => '<p class="widget-title">', // стоит перед тайтлом
        'after_title' => '</p>', // после тайтла.
    ));
}

add_action('widgets_init', 'my_register_sidebars');


// Shortcode: [my_ajax_filter_search]
function my_ajax_filter_search_shortcode()
{
    my_ajax_filter_search_scripts();

    ob_start(); ?>

    <div id="my-ajax-filter-search">
        <form action="" method="get">
            <input type="text" name="search" id="search" value="" placeholder="Поищите в описании..">
            <div class="column-wrap">
                <div class="column">
                    <?php
                    get_filter_textfield('field_5fd3b76c17358');
                    ?>
                </div>
                <div class="column">
                    <?php
                    get_filter_textfield('field_5fd2b05051f51');
                    ?>
                </div>
                <div class="column">
                    <label for="district">Район</label>
                    <select name="district" id="district">
                        <option value="" selected="selected">выберите...</option>
                        <?php $wcatTerms = get_terms('area', array('hide_empty' => 0, 'parent' => 0));
                        foreach ($wcatTerms as $wcatTerm) :
                            ?>
                            <option value="<?php echo $wcatTerm->slug ?>"><?php echo $wcatTerm->name; ?></option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>
                <div class="column">
                    <?php
                    get_filter_choise('field_5fd2b09305abe');
                    ?>
                </div>
                <div class="column">
                    <?php
                    get_filter_choise('field_5fd2b15490219');
                    ?>
                </div>
                <div class="column">
                    <?php
                    get_filter_choise('field_5fd2b2023c8d9');
                    ?>
                </div>
                <div class="column">
                    <?php
                    get_filter_textSubField('field_5fd2b3086b23d', 'field_5fd2b872d4ac4');
                    ?>
                </div>
                <div class="column">
                    <?php
                    get_filter_choise('field_5fd3c14167342');
                    ?>
                </div>
                <div class="column">
                    <?php
                    get_filter_choise('field_5fd3c1a567343');
                    ?>
                </div>
                <div class="column">
                    <?php
                    get_filter_choise('field_5fd3c20867344');
                    ?>
                </div>
            </div>
            <input type="submit" id="submit" name="submit" value="Search">
        </form>
        <ul id="ajax_fitler_search_results"></ul>
    </div>

    <?php
    return ob_get_clean();
}

add_shortcode('my_ajax_filter_search', 'my_ajax_filter_search_shortcode');

// Ajax Callback
add_action('wp_ajax_my_ajax_filter_search', 'my_ajax_filter_search_callback');
add_action('wp_ajax_nopriv_my_ajax_filter_search', 'my_ajax_filter_search_callback');

function my_ajax_filter_search_callback()
{

    header("Content-Type: application/json");

    function repeater_field($rep_sub_field)
    {
        $rep_sub_field = str_replace("meta_key = 'grid_items_$", "meta_key LIKE 'grid_items_%", $rep_sub_field);
        return $rep_sub_field;
    }

    add_filter('posts_where', 'repeater_field');

    $meta_query = array('relation' => 'AND');

    if (isset($_GET['building_title'])) {
        $building_title = sanitize_text_field($_GET['building_title']);
        $meta_query[] = array(
            'key' => 'building_title',
            'value' => $building_title,
            'compare' => '='
        );
    }

    if (isset($_GET['building_location'])) {
        $building_location = sanitize_text_field($_GET['building_location']);
        $meta_query[] = array(
            'key' => 'building_location',
            'value' => $building_location,
            'compare' => '='
        );
    }

    if (isset($_GET['floors_number'])) {
        $floors_number = sanitize_text_field($_GET['floors_number']);
        $meta_query[] = array(
            'key' => 'floors_number',
            'value' => $floors_number,
            'compare' => '='
        );
    }

    if (isset($_GET['building_type'])) {
        $building_type = sanitize_text_field($_GET['building_type']);
        $meta_query[] = array(
            'key' => 'building_type',
            'value' => $building_type,
            'compare' => '='
        );
    }

    if (isset($_GET['building_eco'])) {
        $building_eco = sanitize_text_field($_GET['building_eco']);
        $meta_query[] = array(
            'key' => 'building_eco',
            'value' => $building_eco,
            'compare' => '='
        );
    }

    if (isset($_GET['square'])) {
        $square = sanitize_text_field($_GET['square']);
        $meta_query[] = array(
            'key' => 'grid_items_$_square',
            'value' => $square,
            'compare' => 'LIKE'
        );
    }

    if (isset($_GET['rooms'])) {
        $rooms = sanitize_text_field($_GET['rooms']);
        $meta_query[] = array(
            'key' => 'grid_items_$_rooms',
            'value' => $rooms,
            'compare' => 'LIKE'
        );
    }

    if (isset($_GET['balcony'])) {
        $balcony = sanitize_text_field($_GET['balcony']);
        $meta_query[] = array(
            'key' => 'grid_items_$_balcony',
            'value' => $balcony,
            'compare' => 'LIKE'
        );
    }

    if (isset($_GET['bathroom'])) {
        $bathroom = sanitize_text_field($_GET['bathroom']);
        $meta_query[] = array(
            'key' => 'grid_items_$_bathroom',
            'value' => $bathroom,
            'compare' => 'LIKE'
        );
    }

    $tax_query = array();

    if (isset($_GET['district'])) {
        $district = sanitize_text_field($_GET['district']);
        $tax_query[] = array(
            'taxonomy' => 'area',
            'field' => 'slug',
            'terms' => $district
        );
    }

    $per_page = -1; //set the per page limit

    $args = array(
        'post_type' => 'property',
        'post_status' => 'publish',
        'posts_per_page' => $per_page,
        'meta_query' => $meta_query,
        'tax_query' => $tax_query
    );

    if (isset($_GET['search'])) {
        $search = sanitize_text_field($_GET['search']);
        $search_query = new WP_Query(array(
            'post_type' => 'property',
            'post_status' => 'publish',
            'posts_per_page' => $per_page,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
            's' => $search
        ));
    } else {
        $search_query = new WP_Query($args);
    }

    if ($search_query->have_posts()) {

        $result = array();

        while ($search_query->have_posts()) {
            $search_query->the_post();

            $cats = strip_tags(get_the_term_list($post->ID, 'area'));
            if (have_rows('grid_items')):
                while (have_rows('grid_items')) : the_row();

                $result[] = array(
                    "id" => get_the_ID(),
                    "title" => get_the_title(),
                    "content" => get_the_content(),
                    "permalink" => get_permalink(),
                    "district" => $cats,
                    "building_title" => get_field('building_title'),
                    "building_location" => get_field('building_location'),
                    "floors_number" => get_field('floors_number'),
                    "building_type" => get_field('building_type'),
                    "building_eco" => get_field('building_eco'),
                    "square" => get_sub_field('square'),
                    "rooms" => get_sub_field('rooms'),
                    "balcony" => get_sub_field('balcony'),
                    "bathroom" => get_sub_field('bathroom'),
                    "poster" => wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'full')
                );

                endwhile;
            endif;

        }
        wp_reset_query();

        echo json_encode($result);

    } else {
        // no posts found
    }
    wp_die();
}

function my_ajax_filter_search_scripts()
{
    wp_enqueue_script('my_ajax_filter_search', plugins_url('/assets/filter.js', __FILE__), array('jquery'), '1.0', true);
    wp_localize_script('my_ajax_filter_search', 'ajax_url', admin_url('admin-ajax.php'));
    wp_register_style('filter', plugins_url('/assets/filter.css', __FILE__));
    wp_enqueue_style('filter');
}

function get_filter_textfield($field_key)
{
    $field = get_field_object($field_key);
    ?>
    <label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label> <?php

    if ($field) {
        echo '<select name="' . $field['name'] . '" id="' . $field['name'] . '">';
        ?>
        <option value="" selected="selected">выберите...</option>
        <?php
        $post_ids = get_posts([
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'property',
            'fields' => 'ids',
        ]);

        foreach ($post_ids as $v) {
            $fields = get_field_object($field_key, $v);
            $field_value = $fields["value"];
            echo '<option value="' . $field_value . '">' . $field_value . '</option>';
        }
        echo '</select>';
    }
}

function get_filter_choise($field_key)
{
    $field = get_field_object($field_key);
    ?>
    <label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label> <?php

    if ($field) {
        echo '<select name="' . $field['name'] . '" id="' . $field['name'] . '">';
        ?>
        <option value="" selected="selected">выберите...</option>
        <?php
        foreach ($field['choices'] as $k => $v) {
            echo '<option value="' . $k . '">' . $v . '</option>';
        }
        echo '</select>';
    }
}

function get_filter_textSubField($repeater_field_key, $sub_field_key)
{
    $field = get_field_object($sub_field_key);
    ?>
    <label for="<?php echo $field['name']; ?>"><?php echo $field['label']; ?></label> <?php

    if ($field) {
        echo '<select name="' . $field['name'] . '" id="' . $field['name'] . '">';
        ?>
        <option value="" selected="selected">выберите...</option>
        <?php
        $post_ids = get_posts([
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'post_type' => 'property',
            'fields' => 'ids',
        ]);

        foreach ($post_ids as $v) {
            while (the_repeater_field($repeater_field_key, $v)) {
                echo '<option value="' . get_sub_field('square') . '">' . get_sub_field('square') . '</option>';
            }
        }

        echo '</select>';
    }
}