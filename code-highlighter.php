<?php
/**
 * CodeHighlighter.
 *
 * @wordpress-plugin
 * Plugin Name:       Code Highlighter
 * Plugin URI:        https://urielwilson.com/wordpress-plugins/
 * Description:       A WordPress plugin for syntax highlighting using the Prism.js library.
 * Version:           0.10.1
 * Requires PHP:      7.4
 * Author:            Uriel 
 * Author URI:        https://urielwilson.com
 * Text Domain:       easy-code-highlighter
 * Domain Path:       /languages
 * License:           GPLv2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */


// deny direct access.
if ( ! defined( 'WPINC' ) ) {
  	die;
}

/**
 * Class CodeHighlighter
 */
class CodeHighlighter
{
    /**
     * The URL to the plugin directory.
     *
     * @var string $pluginUrl
     */
    private $pluginUrl;

    /**
     * CodeHighlighter constructor.
     *
     * Initializes the plugin by setting the plugin URL, content wrapper, and
     * adding the necessary actions and filters.
     *
     * @param string $pluginUrl The URL to the plugin directory.
     */
    public function __construct($pluginUrl)
    {
        $this->pluginUrl = $pluginUrl;

        add_action('wp_enqueue_scripts', [$this, 'loadScripts']);
        add_filter('wp_head', [$this, 'addCodeBlockClasses']);
    }

    /**
     * Enqueues the required scripts and styles for the syntax highlighting.
     */
    public function loadScripts()
    {
        if (!wp_script_is('code-highlighter-core', 'enqueued')) {
            wp_enqueue_script(
                'code-highlighter-core',
                $this->pluginUrl . 'js/prism-core.js',
                array(),
                '1.1.13',
                true
            );
        }

        if (!wp_style_is('code-highlighter-css', 'enqueued')) {
            wp_enqueue_style(
                'code-highlighter-css',
                $this->pluginUrl . 'css/prism.css',
                array(),
                '1.1.13'
            );
        }
    }

    /**
     * Determines if the current page is a single post page.
     *
     * @return bool True if the current page is a single post page, false otherwise.
     */
    public function isSinglePost()
    {
        return is_single() && !is_attachment();
    }

    /**
     * Adds the necessary classes to code blocks for syntax highlighting.
     *
     * @return string The modified content with the necessary classes added.
     */
    public function addCodeBlockClasses()
    {
        if (!is_singular() || !$this->isSinglePost()) {
            return;
        }
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const contentWrap = document.querySelector('div.entry-content-wrap');
                if (!contentWrap) {
                    return;
                }

                const codeBlocks = contentWrap.querySelectorAll('pre.wp-block-code');

                // Add additional classes to each code block
                codeBlocks.forEach(block => {
                    //block.classList.add('language-python', 'language-php');
                    block.classList.add('language-php');
                });
            });
        </script>
        <?php
    }
}

new CodeHighlighter(plugin_dir_url(__FILE__));
