<?php

/**
 * Plugin Name: Livemesh Addons for Elementor (Premium)
 * Plugin URI: https://livemeshelementor.com
 * Description: A collection of premium quality addons or widgets for use in Elementor page builder. Elementor must be installed and activated.
 * Author: Livemesh
 * Author URI: https://livemeshelementor.com
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 * Version: 2.9.9
 * Text Domain: livemesh-el-addons
 * Domain Path: languages
 *
 * Livemesh Addons for Elementor is distributed under the terms of the GNU
 * General Public License as published by the Free Software Foundation,
 * either version 2 of the License, or any later version.
 *
 * Livemesh Addons for Elementor is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Livemesh Addons for Elementor. If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @fs_premium_only /includes/blocks/, /includes/gallery/, /assets/css/premium/, /assets/js/premium/, /includes/widgets/premium/, /i18n/wpml/modules/premium/, sample-data.xml, readme.html
 *
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Ensure the free version is deactivated if premium is running

class LaeFsNull {
    public function is_not_paying() {
        return false;
    }

    public function can_use_premium_code() {
        return true;
    }

    public function can_use_premium_code__premium_only() {
        return true;
    }
}

if ( !function_exists( 'lae_fs' ) ) {
    // Plugin version
    define( 'LAE_VERSION', '2.9.9' );
    // Plugin Root File
    define( 'LAE_PLUGIN_FILE', __FILE__ );
    // Plugin Folder Path
    define( 'LAE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
    // Plugin Folder URL
    define( 'LAE_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
    // Plugin Addons Folder Path
    define( 'LAE_ADDONS_DIR', plugin_dir_path( __FILE__ ) . 'includes/widgets/' );
    // Plugin Premium Addons Folder Path
    define( 'LAE_PREMIUM_ADDONS_DIR', plugin_dir_path( __FILE__ ) . 'includes/widgets/premium/' );
    // Plugin Folder URL
    define( 'LAE_ADDONS_URL', plugin_dir_url( __FILE__ ) . 'includes/widgets/' );
    // Plugin Folder URL
    define( 'LAE_PREMIUM_ADDONS_URL', plugin_dir_url( __FILE__ ) . 'includes/widgets/premium/' );
    // Plugin Help Page URL
    define( 'LAE_PLUGIN_HELP_URL', admin_url() . 'admin.php?page=livemesh_el_addons_documentation' );
    // Create a helper function for easy SDK access.
    function lae_fs()
    {
        global  $lae_fs ;
        
        if ( !isset( $lae_fs ) ) {
            $lae_fs = new LaeFsNull();
}
        
        return $lae_fs;
    }
    
    // Init Freemius.
    lae_fs();
    // Signal that SDK was initiated.
    do_action( 'lae_fs_loaded' );
    function lae_fs_add_licensing_helper()
    {
        ?>
        <script type="text/javascript">
            (function () {
                window.lae_fs = {can_use_premium_code: <?php 
        echo  json_encode( lae_fs()->can_use_premium_code() ) ;
        ?>};
            })();
        </script>
        <?php 
    }
    
    add_action( 'wp_head', 'lae_fs_add_licensing_helper' );
    require_once dirname( __FILE__ ) . '/plugin.php';
}
