<?php
/**
 * Plugin Name: ROI Labs Link Page
 * Description: Integração fácil da página de links ROI Labs com WordPress e Elementor Pro
 * Version: 1.0.0
 * Author: ROI Labs
 * Author URI: https://roilabs.com.br
 * Text Domain: roi-labs-links
 * Domain Path: /languages
 */

// Previne acesso direto
if (!defined('ABSPATH')) {
    exit;
}

class ROILabsLinkPage {
    
    private $plugin_version = '1.0.0';
    private $github_repo = 'https://cdn.jsdelivr.net/gh/JeanZorzetti/roi-labs-links@main';
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('roi_labs_links', array($this, 'shortcode_handler'));
        
        // Elementor Integration
        add_action('elementor/widgets/widgets_registered', array($this, 'register_elementor_widget'));
        
        // Admin
        add_action('admin_menu', array($this, 'admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
    }
    
    public function init() {
        load_plugin_textdomain('roi-labs-links', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Enfileira scripts e estilos
     */
    public function enqueue_scripts() {
        // Apenas carrega se o shortcode estiver presente na página
        if (is_singular() && has_shortcode(get_post()->post_content, 'roi_labs_links')) {
            $this->load_assets();
        }
    }
    
    /**
     * Carrega assets do GitHub
     */
    private function load_assets() {
        // React
        wp_enqueue_script('react', 'https://unpkg.com/react@18/umd/react.production.min.js', array(), '18.0.0', false);
        wp_enqueue_script('react-dom', 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js', array('react'), '18.0.0', false);
        wp_enqueue_script('babel-standalone', 'https://unpkg.com/@babel/standalone/babel.min.js', array(), '7.0.0', false);
        
        // Estilos ROI Labs
        wp_enqueue_style(
            'roi-labs-links-css',
            $this->github_repo . '/assets/css/roi-labs-links.css',
            array(),
            $this->plugin_version
        );
        
        // JavaScript ROI Labs
        wp_enqueue_script(
            'roi-labs-links-js',
            $this->github_repo . '/assets/js/roi-labs-links.js',
            array('react', 'react-dom', 'babel-standalone'),
            $this->plugin_version,
            true
        );
        
        // Adicionar type=\"text/babel\" ao script
        add_filter('script_loader_tag', array($this, 'add_babel_type'), 10, 3);
    }
    
    /**
     * Adiciona type=\"text/babel\" ao script principal
     */
    public function add_babel_type($tag, $handle, $src) {
        if ($handle === 'roi-labs-links-js') {
            $tag = str_replace('<script ', '<script type=\"text/babel\" ', $tag);
        }
        return $tag;
    }
    
    /**
     * Manipulador do shortcode
     */
    public function shortcode_handler($atts) {
        $atts = shortcode_atts(array(
            'height' => 'auto',
            'class' => '',
        ), $atts);
        
        // Carrega assets se não foram carregados
        $this->load_assets();
        
        $style = $atts['height'] !== 'auto' ? 'min-height: ' . esc_attr($atts['height']) . ';' : 'min-height: 100vh;';
        $class = !empty($atts['class']) ? ' ' . esc_attr($atts['class']) : '';
        
        return '<div id=\"roi-labs-root\" class=\"roi-labs-container' . $class . '\" style=\"' . $style . '\"></div>';
    }
    
    /**
     * Registra widget do Elementor
     */
    public function register_elementor_widget() {
        if (class_exists('\\Elementor\\Plugin')) {
            require_once plugin_dir_path(__FILE__) . 'elementor-widget.php';
            \\Elementor\\Plugin::instance()->widgets_manager->register_widget_type(new \\ROILabsElementorWidget());
        }
    }
    
    /**
     * Menu do admin
     */
    public function admin_menu() {
        add_options_page(
            'ROI Labs Links',
            'ROI Labs Links',
            'manage_options',
            'roi-labs-links',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Inicialização do admin
     */
    public function admin_init() {
        register_setting('roi_labs_links', 'roi_labs_links_options');
        
        add_settings_section(
            'roi_labs_links_general',
            'Configurações Gerais',
            null,
            'roi_labs_links'
        );
        
        add_settings_field(
            'auto_load',
            'Carregamento Automático',
            array($this, 'auto_load_field'),
            'roi_labs_links',
            'roi_labs_links_general'
        );
    }
    
    /**
     * Campo de carregamento automático
     */
    public function auto_load_field() {
        $options = get_option('roi_labs_links_options');
        $auto_load = isset($options['auto_load']) ? $options['auto_load'] : false;
        
        echo '<input type=\"checkbox\" name=\"roi_labs_links_options[auto_load]\" value=\"1\" ' . checked(1, $auto_load, false) . ' />';
        echo '<label for=\"roi_labs_links_options[auto_load]\">Carregar assets automaticamente em todas as páginas</label>';
    }
    
    /**
     * Página do admin
     */
    public function admin_page() {
        ?>
        <div class=\"wrap\">
            <h1>ROI Labs Link Page</h1>
            
            <div class=\"notice notice-info\">
                <p><strong>Como usar:</strong></p>
                <ul>
                    <li>📝 <strong>Shortcode:</strong> <code>[roi_labs_links]</code></li>
                    <li>🎨 <strong>Elementor:</strong> Procure por \"ROI Labs Links\" nos widgets</li>
                    <li>📱 <strong>Iframe:</strong> <code>&lt;iframe src=\"https://jeanzorzetti.github.io/roi-labs-links/\" width=\"100%\" height=\"800\"&gt;&lt;/iframe&gt;</code></li>
                </ul>
            </div>
            
            <form method=\"post\" action=\"options.php\">
                <?php
                settings_fields('roi_labs_links');
                do_settings_sections('roi_labs_links');
                submit_button();
                ?>
            </form>
            
            <div class=\"card\">
                <h2>Informações do Sistema</h2>
                <table class=\"widefat\">
                    <tr>
                        <td><strong>Versão do Plugin:</strong></td>
                        <td><?php echo $this->plugin_version; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Repositório GitHub:</strong></td>
                        <td><a href=\"https://github.com/JeanZorzetti/roi-labs-links\" target=\"_blank\">Ver Código</a></td>
                    </tr>
                    <tr>
                        <td><strong>CDN Status:</strong></td>
                        <td><span class=\"dashicons dashicons-yes-alt\" style=\"color: green;\"></span> Ativo</td>
                    </tr>
                </table>
            </div>
            
            <div class=\"card\">
                <h2>Integração Elementor Pro</h2>
                <p>Para usar no Elementor Pro:</p>
                <ol>
                    <li>Adicione um widget <strong>\"HTML\"</strong></li>
                    <li>Cole o código: <code>[roi_labs_links]</code></li>
                    <li>Ou use o widget específico <strong>\"ROI Labs Links\"</strong></li>
                </ol>
            </div>
        </div>
        <?php
    }
}

// Inicializa o plugin
new ROILabsLinkPage();

/**
 * Hook de ativação
 */
register_activation_hook(__FILE__, 'roi_labs_links_activate');
function roi_labs_links_activate() {
    // Flush rewrite rules se necessário
    flush_rewrite_rules();
}

/**
 * Hook de desativação
 */
register_deactivation_hook(__FILE__, 'roi_labs_links_deactivate');
function roi_labs_links_deactivate() {
    // Limpeza se necessário
    flush_rewrite_rules();
}
?>