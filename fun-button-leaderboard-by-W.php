<?php
/**
* Plugin name: Fun Button Leaderboard by W
*/

//hooks 'n stuff
add_action('wp_enqueue_scripts','js_init');
function js_init(){
    //load the scripts needed for the plugin
    wp_register_script('fun-button-leaderboard-js',"https://www.mowinpeople.com/wp-content/plugins/fun-button-leaderboard-by-W/fun-button-leaderboard.js",array('jquery'));
    wp_enqueue_script('fun-button-leaderboard-js');
    wp_localize_script('fun-button-leaderboard-js','ajax_object',array('ajaxurl' => admin_url('admin-ajax.php')));
}
//let ajax call functions
//add_action(wp_ajax_(func called in ajax), func to call here);
add_action('wp_ajax_get_leaderboard_info','get_leaderboard_info_ajax');
add_action('wp_ajax_nopriv_get_leaderboard_info','get_leaderboard_info_ajax');

//shortcodes
add_shortcode('fun-button-leaderboard','show_fun_button_leaderboard');

function get_leaderboard_info_ajax(){
    //get top 5 user names and click values from users_meta database
    $users = array();
    $userClicks = array();
    //Query the table

    global $wpdb;
    $table_name = $wpdb->prefix . "usermeta";
    $sql = "SELECT * FROM {$table_name} WHERE meta_key = 'numClicks' ORDER BY cast(meta_value as unsigned) DESC LIMIT 5";

    $result = $wpdb->get_results($sql);
    foreach ($result as $user){
        $username = get_userdata($user->user_id)->display_name;
        $users[] = $username;
        $userClicks[] = $user->meta_value;
    }
    $leaderboardInfo = array($users,$userClicks);
    $response = json_encode($leaderboardInfo);
    echo $response;
    wp_die();
}

function show_fun_button_leaderboard(){
    ob_start();
    ?>
    <html>
    <head>
        <style>
        table, th, td {
            border: 0px;
            font-size: 1.17em;
        }
        th:nth-child(1), td:nth-child(1){
            text-align: right;
            font-size: 1.875em;
        }
        th:nth-child(2), td:nth-child(2), th:nth-child(3), td:nth-child(3){
            text-align: left;
        }
        </style>
    </head>
    <body>
        <table id="fun-button-leaderboard" style="
        display:block;width:70vw;
        ">
            <tr>
                <th style="width:10vw"></th>
                <th style="width:40vw">Name</th>
                <th style="width:20vw">Clicks</th>
            </tr>
            <tr>
                <td style="color:gold">1.</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="color:silver">2.</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="color:rgb(205,127,50)">3.</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="color:green">4.</td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td style="color:green">5.</td>
                <td></td>
                <td></td>
            </tr>
        </table>
    </body>
    </html>
    <?php
    return ob_get_clean();
}

?>
