<?php
/**
 * Widget Elementor para ROI Labs Link Page
 */

if (!defined('ABSPATH')) {
    exit;
}

class ROILabsElementorWidget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'roi_labs_links';
    }

    public function get_title() {
        return 'ROI Labs Links';
    }

    public function get_icon() {
        return 'eicon-social-icons';
    }

    public function get_categories() {
        return ['general'];
    }

    public function get_keywords() {
        return ['roi', 'labs', 'links', 'bio', 'social'];
    }

    protected function _register_controls() {
        
        // Seção de Conteúdo
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Configurações',
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'height',
            [
                'label' => 'Altura',
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'auto',
                'options' => [
                    'auto' => 'Automática',
                    '600px' => '600px',
                    '800px' => '800px',
                    '100vh' => 'Tela Cheia',
                ],
            ]
        );

        $this->add_control(
            'loading_text',
            [
                'label' => 'Texto de Carregamento',
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Carregando...',
                'placeholder' => 'Digite o texto de carregamento',
            ]
        );

        $this->add_control(
            'enable_analytics',
            [
                'label' => 'Habilitar Analytics',
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => 'Sim',
                'label_off' => 'Não',
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->end_controls_section();

        // Seção de Estilo
        $this->start_controls_section(
            'style_section',
            [
                'label' => 'Estilo',
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'border_radius',
            [
                'label' => 'Borda Arredondada',
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors' => [
                    '{{WRAPPER}} .roi-labs-container' => 'border-radius: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_shadow',
                'label' => 'Sombra',
                'selector' => '{{WRAPPER}} .roi-labs-container',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        $height = $settings['height'] !== 'auto' ? $settings['height'] : '100vh';
        $loading_text = !empty($settings['loading_text']) ? esc_html($settings['loading_text']) : 'Carregando...';
        
        // Unique ID para múltiplos widgets na mesma página
        $widget_id = 'roi-labs-root-' . $this->get_id();
        
        ?>
        <div class="roi-labs-elementor-widget">
            <div id="<?php echo $widget_id; ?>" 
                 class="roi-labs-container" 
                 style="min-height: <?php echo esc_attr($height); ?>;">
                
                <!-- Loading State -->
                <div class="roi-labs-loading" style="
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 200px;
                    background: linear-gradient(135deg, #0f172a, #581c87, #0f172a);
                    border-radius: 1rem;
                    color: white;
                    font-family: system-ui, -apple-system, sans-serif;
                ">
                    <div style="text-align: center;">
                        <div style="
                            width: 40px;
                            height: 40px;
                            border: 3px solid rgba(34,211,238,0.3);
                            border-top: 3px solid #22d3ee;
                            border-radius: 50%;
                            animation: spin 1s linear infinite;
                            margin: 0 auto 16px;
                        "></div>
                        <p><?php echo $loading_text; ?></p>
                    </div>
                </div>
            </div>
            
            <style>
                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }
            </style>
            
            <script>
                // Carregar React e dependências
                (function() {
                    const widgetId = '<?php echo $widget_id; ?>';
                    const container = document.getElementById(widgetId);
                    
                    if (!container) return;
                    
                    // Verificar se React já está carregado
                    if (typeof React === 'undefined') {
                        const reactScript = document.createElement('script');
                        reactScript.src = 'https://unpkg.com/react@18/umd/react.production.min.js';
                        document.head.appendChild(reactScript);
                        
                        reactScript.onload = function() {
                            loadReactDOM();
                        };
                    } else {
                        loadReactDOM();
                    }
                    
                    function loadReactDOM() {
                        if (typeof ReactDOM === 'undefined') {
                            const reactDOMScript = document.createElement('script');
                            reactDOMScript.src = 'https://unpkg.com/react-dom@18/umd/react-dom.production.min.js';
                            document.head.appendChild(reactDOMScript);
                            
                            reactDOMScript.onload = function() {
                                loadBabel();
                            };
                        } else {
                            loadBabel();
                        }
                    }
                    
                    function loadBabel() {
                        if (typeof Babel === 'undefined') {
                            const babelScript = document.createElement('script');
                            babelScript.src = 'https://unpkg.com/@babel/standalone/babel.min.js';
                            document.head.appendChild(babelScript);
                            
                            babelScript.onload = function() {
                                loadROILabsAssets();
                            };
                        } else {
                            loadROILabsAssets();
                        }
                    }
                    
                    function loadROILabsAssets() {
                        // Carregar CSS
                        if (!document.querySelector('link[href*="roi-labs-links.css"]')) {
                            const cssLink = document.createElement('link');
                            cssLink.rel = 'stylesheet';
                            cssLink.href = 'https://cdn.jsdelivr.net/gh/JeanZorzetti/roi-labs-links@main/assets/css/roi-labs-links.css';
                            document.head.appendChild(cssLink);
                        }
                        
                        // Carregar e executar JavaScript
                        fetch('https://cdn.jsdelivr.net/gh/JeanZorzetti/roi-labs-links@main/assets/js/roi-labs-links.js')
                            .then(response => response.text())
                            .then(jsCode => {
                                // Modificar o código para usar o ID específico do widget
                                const modifiedCode = jsCode.replace(
                                    "document.getElementById('roi-labs-root')",
                                    "document.getElementById('" + widgetId + "')"
                                );
                                
                                // Executar o código
                                try {
                                    const transformedCode = Babel.transform(modifiedCode, {
                                        presets: ['react']
                                    }).code;
                                    
                                    eval(transformedCode);
                                } catch (error) {
                                    console.error('Erro ao carregar ROI Labs Links:', error);
                                    container.innerHTML = '<div style="padding: 20px; text-align: center; color: #ff6b6b;">Erro ao carregar o conteúdo. Tente recarregar a página.</div>';
                                }
                            })
                            .catch(error => {
                                console.error('Erro ao buscar assets:', error);
                                container.innerHTML = '<div style="padding: 20px; text-align: center; color: #ff6b6b;">Erro de conexão. Verifique sua internet.</div>';
                            });
                    }
                })();
            </script>
        </div>
        <?php
    }

    protected function _content_template() {
        ?>
        <div class="roi-labs-elementor-widget">
            <div class="roi-labs-container" style="min-height: {{ settings.height }};">
                <div style="
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 200px;
                    background: linear-gradient(135deg, #0f172a, #581c87, #0f172a);
                    border-radius: 1rem;
                    color: white;
                    font-family: system-ui;
                ">
                    <div style="text-align: center;">
                        <div style="
                            width: 40px;
                            height: 40px;
                            border: 3px solid rgba(34,211,238,0.3);
                            border-top: 3px solid #22d3ee;
                            border-radius: 50%;
                            animation: spin 1s linear infinite;
                            margin: 0 auto 16px;
                        "></div>
                        <p>{{ settings.loading_text }}</p>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}
?>