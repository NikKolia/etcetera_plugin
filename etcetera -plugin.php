<?php
/*
 * Plugin Name: Etcetera test plugin
 */

add_filter('widget_text', 'do_shortcode');
do_shortcode( $content, $ignore_html );

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

function my_register_sidebars() {
    register_sidebar( array(
        'name' => esc_html__( 'Filter area', 'nd_dosth' ),
        'id' => 'filter',
        'description'   => esc_html__( 'There should be added a filter that would appear inside the counter filter section in front of Home page', 'nd_dosth' ),
        'before_widget' => '<aside id="%1$s" class="filter">', // то что стоит пред блоком виджета
        'after_widget' => '</aside>', // то что стоит после блока виджета
        'before_title' => '<p class="widget-title">', // стоит перед тайтлом
        'after_title' => '</p>', // после тайтла.
    ));
}
add_action( 'widgets_init', 'my_register_sidebars' );

// Shortcode: [my_ajax_filter_search]
function my_ajax_filter_search_shortcode() {
    my_ajax_filter_search_scripts(); // Added here

    ob_start(); ?>

    <div id="my-ajax-filter-search">
        <form action="" method="get">
            <input type="text" name="search" id="search" value="" placeholder="Поищите здесь..">
            <div class="column-wrap">
                <div class="column">
                    <label for="building_title">Название дома</label>
                    <input type="text" name="building_title" id="building_title" placeholder="Введите здесь..">
                </div>
                <div class="column">
                    <label for="building_location">Координаты местонахождения</label>
                    <input type="text" name="building_location" id="building_location" placeholder="Введите здесь..">
                </div>
                <div class="column">
                    <label for="district">Район</label>
                    <select name="district" id="district">
                        <option value="">Любой</option>
                        <option value="comunar">Комунарский</option>
                        <option value="voznesen">Вознесеновский</option>
                        <option value="alexandr">Александровский</option>
                    </select>
                </div>
                <div class="column">
                    <label for="floors_number">Количество этажей</label>
                    <select name="floors_number" id="floors_number">
                        <option value="">Любое</option>
                        <option value="20">Не более 20</option>
                        <option value="19">Не более 19</option>
                        <option value="18">Не более 18</option>
                        <option value="17">Не более 17</option>
                        <option value="16">Не более 16</option>
                        <option value="15">Не более 15</option>
                        <option value="14">Не более 14</option>
                        <option value="13">Не более 13</option>
                        <option value="12">Не более 12</option>
                        <option value="11">Не более 11</option>
                        <option value="10">Не более 10</option>
                        <option value="9">Не более  9</option>
                        <option value="8">Не более  8</option>
                        <option value="7">Не более  7</option>
                        <option value="6">Не более  6</option>
                        <option value="5">Не более  5</option>
                        <option value="4">Не более 4</option>
                        <option value="3">Не более  3</option>
                        <option value="2">Не более  2</option>
                        <option value="1">Не более 1</option>
                    </select>
                </div>
                <div class="column">
                    <label for="building_type">Тип строения</label>
                    <select name="building_type" id="building_type">
                        <option value="">Любой</option>
                        <option value="панель">панель</option>
                        <option value="кирпич">кирпич</option>
                        <option value="пеноблок">пеноблок</option>
                    </select>
                </div>
                <div class="column">
                    <label for="building_eco">Экологичность</label>
                    <select name="building_eco" id="building_eco">
                        <option value="">Любая</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <div class="column">
                    <label for="square">Площадь</label>
                    <input type="number" name="square" id="square" placeholder="Введите здесь..">
                </div>
                <div class="column">
                    <label for="rooms">Количество комнат</label>
                    <select name="rooms" id="rooms">
                        <option value="">Любое</option>
                        <option value="10">10</option>
                        <option value="9">9</option>
                        <option value="8">8</option>
                        <option value="7">7</option>
                        <option value="6">6</option>
                        <option value="5">5</option>
                        <option value="4">4</option>
                        <option value="3">3</option>
                        <option value="2">2</option>
                        <option value="1">1</option>
                    </select>
                </div>
                <div class="column">
                    <label for="balcony">Балкон</label>
                    <select name="balcony" id="balcony">
                        <option value="да">Да</option>
                        <option value="нет">Нет</option>
                    </select>
                </div>
                <div class="column">
                    <label for="bathroom">Cанузел</label>
                    <select name="bathroom" id="bathroom">
                        <option value="да">Да</option>
                        <option value="нет">Нет</option>
                    </select>
                </div>
            </div>
            <input type="submit" id="submit" name="submit" value="Search">
        </form>
        <ul id="ajax_fitler_search_results"></ul>
    </div>

    <?php
    return ob_get_clean();
}
add_shortcode ('my_ajax_filter_search', 'my_ajax_filter_search_shortcode');

// Ajax Callback
add_action('wp_ajax_my_ajax_filter_search', 'my_ajax_filter_search_callback');
add_action('wp_ajax_nopriv_my_ajax_filter_search', 'my_ajax_filter_search_callback');

function my_ajax_filter_search_callback() {

    header("Content-Type: application/json");

    $meta_query = array('relation' => 'AND');

    if(isset($_GET['building_title'])) {
        $building_title= sanitize_text_field( $_GET['building_title'] );
        $flex_field = 'building_title';
        $meta_query[] = array(
            'key' => $flex_field,
            'value' => $building_title,
            'compare' => '='
        );
    }

    if(isset($_GET['building_location'])) {
        $building_location = sanitize_text_field( $_GET['building_location'] );
        $flex_field = 'building_location';
        $meta_query[] = array(
            'key' => $flex_field,
            'value' => $building_location,
            'compare' => '='
        );
    }

    if(isset($_GET['floors_number'])) {
        $floors_number = sanitize_text_field( $_GET['floors_number'] );
        $flex_field = 'floors_number';
        $meta_query[] = array(
            'key' => $flex_field,
            'value' => $floors_number,
            'compare' => '<='
        );
    }

    if(isset($_GET['building_type'])) {
        $building_type = sanitize_text_field( $_GET['building_type'] );
        $flex_field = 'building_type';
        $meta_query[] = array(
            'key' => $flex_field,
            'value' => $building_type,
            'compare' => '='
        );
    }

    if(isset($_GET['building_eco'])) {
        $building_eco = sanitize_text_field( $_GET['building_eco'] );
        $flex_field = 'building_eco';
        $meta_query[] = array(
            'key' => $flex_field,
            'value' => $building_eco,
            'compare' => '='
        );
    }

//    if(isset($_GET['square'])) {
//        $square = sanitize_text_field( $_GET['square'] );
//        $flex_field = 'square';
//        $meta_query[] = array(
//            'key' => $flex_field,
//            'value' => $square,
//            'compare' => '='
//        );
//    }

/*    if(isset($_GET['rooms'])) {
        $rooms = sanitize_text_field( $_GET['rooms'] );

//        function network_grid( $where ) {
//            $where = str_replace("meta_key = 'grid_items_$", "meta_key LIKE 'grid_items_%", $where);
//            return $where;
//        }
//        add_filter('posts_where', 'network_grid');

//        function grid_items( $where ) {
//            $where = str_replace("meta_key = 'rooms_$", "meta_key LIKE 'rooms_%", $where);
//            return $where;
//        }
//        add_filter('posts_where', 'grid_items');

        $meta_query[] = array(
            'key' => 'grid_items_$_rooms',
            'value' => $rooms,
            'compare' => '='
        );
    }*/

//
//    if(isset($_GET['balcony'])) {
//        $balcony = sanitize_text_field( $_GET['balcony'] );
//        $meta_query[] = array(
//            'key' => 'balcony',
//            'value' => $balcony,
//            'compare' => '='
//        );
//    }
//
//    if(isset($_GET['bathroom'])) {
//        $bathroom = sanitize_text_field( $_GET['bathroom'] );
//        $meta_query[] = array(
//            'key' => 'bathroom',
//            'value' => $bathroom,
//            'compare' => '='
//        );
//    }

    $tax_query = array();

    if(isset($_GET['district'])) {
        $district= sanitize_text_field( $_GET['district'] );
        $tax_query[] = array(
            'taxonomy' => 'area',
            'field' => 'slug',
            'terms' => $district
        );
    }

    $args = array(
        'post_type' => 'property',
        'posts_per_page' => -1,
        'meta_query' => $meta_query,
        'tax_query' => $tax_query
    );

//    $network = get_field('network_grid');
//    foreach ($network as $grid) {
//        foreach ($grid['grid_items'] as $item) {
//         }
//    }

    if(isset($_GET['search'])) {
        $search = sanitize_text_field( $_GET['search'] );
        $search_query = new WP_Query( array(
            'post_type' => 'property',
            'posts_per_page' => -1,
            'meta_query' => $meta_query,
            'tax_query' => $tax_query,
            's' => $search
        ) );
    } else {
        $search_query = new WP_Query( $args );
    }

    if ( $search_query->have_posts() ) {

        $result = array();

        while ( $search_query->have_posts() ) {
            $search_query->the_post();

//            $cats = strip_tags( get_the_category_list(", ") );
            $cats = strip_tags( get_the_term_list( $post ->ID, 'area'));
            $network = get_field('network_grid');
            foreach ($network as $grid) {
                foreach ($grid['grid_items'] as $item) {

            $result[] = array(
                "id" => get_the_ID(),
                "title" => get_the_title(),
                "content" => get_the_content(),
                "permalink" => get_permalink(),
                "district" => $cats,
//                "building_title" => get_field('building_title'),
                "building_title" => $grid['building_title']['title'],
                "building_location" => $grid['building_location']['location'],
                "floors_number" => $grid['floors_number']['number'],
                "building_type" => $grid['building_type']['type'],
                "building_eco" => $grid['building_eco']['eco'],
                "square" => $item['square'],
                "rooms" => $item['rooms'] ,
                "balcony" => $item['balcony'],
                "bathroom" => $item['bathroom'],
                "poster" => wp_get_attachment_url(get_post_thumbnail_id($post->ID),'full')
            );
                }
            }
        }
        wp_reset_query();

        echo json_encode($result);

    } else {
        // no posts found
    }
    wp_die();
}

function my_ajax_filter_search_scripts() {
    wp_enqueue_script('my_ajax_filter_search', plugins_url( '/assets/filter.js' , __FILE__ ), array( 'jquery' ), '1.0', true );
    wp_localize_script( 'my_ajax_filter_search', 'ajax_url', admin_url('admin-ajax.php') );
    wp_register_style( 'filter', plugins_url('/assets/filter.css', __FILE__) );
    wp_enqueue_style( 'filter' );
}
// add_action('wp_enqueue_scripts','my_ajax_filter_search_scripts');



//function register_custom_post_type_movie() {
//    $args = array(
//        "label" => __( "Movies", "" ),
//        "labels" => array(
//            "name" => __( "Movies", "" ),
//            "singular_name" => __( "Movie", "" ),
//            "featured_image" => __( "Movie Poster", "" ),
//            "set_featured_image" => __( "Set Movie Poster", "" ),
//            "remove_featured_image" => __( "Remove Movie Poster", "" ),
//            "use_featured_image" => __( "Use Movie Poster", "" ),
//        ),
//        "public" => true,
//        "publicly_queryable" => true,
//        "show_ui" => true,
//        "show_in_rest" => false,
//        "has_archive" => false,
//        "show_in_menu" => true,
//        "exclude_from_search" => false,
//        "capability_type" => "post",
//        "map_meta_cap" => true,
//        "hierarchical" => false,
//        "rewrite" => array( "slug" => "movie", "with_front" => true ),
//        "query_var" => true,
//        "supports" => array( "title", "editor", "thumbnail" ),
//        "taxonomies" => array( "category" ),
//    );
//    register_post_type( "movie", $args );
//}
//add_action( 'init', 'register_custom_post_type_movie' );