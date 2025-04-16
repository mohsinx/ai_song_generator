<?php
/**
 * Plugin Name: AI Song Generator
 * Description: A WordPress plugin that generates songs using AI technology
 * Version: 1.0
 * Author: Your Name
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class AI_Song_Generator {
    
    public function __construct() {
        // Register shortcode
        add_shortcode('ai_song_generator', array($this, 'render_song_generator'));
        
        // Register scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Register AJAX handler
        add_action('wp_ajax_generate_ai_song', array($this, 'generate_ai_song'));
        add_action('wp_ajax_nopriv_generate_ai_song', array($this, 'generate_ai_song'));
    }
    
    /**
     * Enqueue necessary scripts and styles
     */
    public function enqueue_scripts() {
        // Only load on pages with our shortcode
        global $post;
        if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'ai_song_generator')) {
            // Enqueue styles
            wp_enqueue_style(
                'ai-song-generator-style',
                plugin_dir_url(__FILE__) . 'assets/css/ai-song-generator.css',
                array(),
                '1.0.0'
            );
            
            // Enqueue scripts
            wp_enqueue_script(
                'ai-song-generator-script',
                plugin_dir_url(__FILE__) . 'assets/js/ai-song-generator.js',
                array('jquery'),
                '1.0.0',
                true
            );
            
            // Pass variables to JavaScript
            wp_localize_script(
                'ai-song-generator-script',
                'aiSongGenerator',
                array(
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'nonce' => wp_create_nonce('ai_song_generator_nonce')
                )
            );
        }
    }
    
    /**
     * Render the song generator interface
     */
    public function render_song_generator() {
        ob_start();
        include plugin_dir_path(__FILE__) . 'templates/generator-interface.php';
        return ob_get_clean();
    }
    
    /**
     * Handle AJAX request to generate a song
     */
    public function generate_ai_song() {
        // Verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'ai_song_generator_nonce')) {
            wp_send_json_error(array('message' => 'Security check failed.'));
        }
        
        // Get parameters
        $prompt = sanitize_textarea_field($_POST['prompt']);
        $length = sanitize_text_field($_POST['length']);
        $genre = sanitize_text_field($_POST['genre']);
        
        // Validate input
        if (empty($prompt)) {
            wp_send_json_error(array('message' => 'Please provide a song description.'));
        }
        
        try {
            // This is where you would integrate with an AI API like OpenAI
            $generated_song = $this->call_ai_api($prompt, $length, $genre);
            
            wp_send_json_success($generated_song);
        } catch (Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }
    
    /**
     * Call the AI API to generate a song
     * 
     * @param string $prompt The song description
     * @param string $length The desired song length
     * @param string $genre The preferred genre
     * @return array The generated song data
     */
    private function call_ai_api($prompt, $length, $genre) {
        // Replace this with your actual API call
        // For now, we'll simulate a response
        
        // Example integration with OpenAI API (you would need API credentials)
        /*
        $api_key = 'YOUR_API_KEY';
        $api_url = 'https://api.openai.com/v1/completions';
        
        // Build prompt based on user input
        $ai_prompt = "Write a " . $length . " song";
        if ($genre !== 'any') {
            $ai_prompt .= " in the " . $genre . " genre";
        }
        $ai_prompt .= " about: " . $prompt;
        
        // Prepare request
        $response = wp_remote_post($api_url, array(
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'model' => 'gpt-3.5-turbo-instruct',
                'prompt' => $ai_prompt,
                'max_tokens' => 1000,
                'temperature' => 0.7,
            )),
            'timeout' => 30,
        ));
        
        if (is_wp_error($response)) {
            throw new Exception($response->get_error_message());
        }
        
        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        // Parse response to get title and lyrics
        $generated_text = $body['choices'][0]['text'];
        
        // Parse title and lyrics from generated text
        $lines = explode("\n", $generated_text);
        $title = trim($lines[0]);
        $lyrics = implode("\n", array_slice($lines, 2));
        
        return array(
            'title' => $title,
            'lyrics' => $lyrics,
        );
        */
        
        // For demonstration, return mock data
        $mock_titles = array(
            'Dreams of Tomorrow',
            'Whispers in the Wind',
            'Neon Heartbeat',
            'Mountain of Memories',
            'Ocean of Emotion'
        );
        
        // Generate sample lyrics based on genre
        $lyrics = $this->generate_sample_lyrics($prompt, $length, $genre);
        
        return array(
            'title' => $mock_titles[array_rand($mock_titles)],
            'lyrics' => $lyrics,
        );
    }
    
    /**
     * Generate sample lyrics for demonstration purposes
     */
    private function generate_sample_lyrics($prompt, $length, $genre) {
        // In a real implementation, this would be replaced with AI-generated content
        $sample_lyrics = "This is a placeholder for AI-generated lyrics.\n\n";
        $sample_lyrics .= "Your song would be about: " . $prompt . "\n";
        $sample_lyrics .= "Length: " . $length . "\n";
        $sample_lyrics .= "Genre: " . $genre . "\n\n";
        
        $sample_lyrics .= "Verse 1:\n";
        $sample_lyrics .= "The words flow like rivers through time,\n";
        $sample_lyrics .= "Painting pictures with rhythm and rhyme.\n";
        $sample_lyrics .= "Every story has meaning to find,\n";
        $sample_lyrics .= "In this melody, yours and mine.\n\n";
        
        $sample_lyrics .= "Chorus:\n";
        $sample_lyrics .= "This is where your song would shine,\n";
        $sample_lyrics .= "Words crafted just for you, divine.\n";
        $sample_lyrics .= "An AI creation, one of a kind,\n";
        $sample_lyrics .= "A perfect harmony, by design.\n\n";
        
        if ($length !== 'short') {
            $sample_lyrics .= "Verse 2:\n";
            $sample_lyrics .= "Another verse would follow here,\n";
            $sample_lyrics .= "With themes and emotions crystal clear.\n";
            $sample_lyrics .= "The story continues, drawing near,\n";
            $sample_lyrics .= "To the heart of what you hold dear.\n\n";
            
            $sample_lyrics .= "Chorus:\n";
            $sample_lyrics .= "This is where your song would shine,\n";
            $sample_lyrics .= "Words crafted just for you, divine.\n";
            $sample_lyrics .= "An AI creation, one of a kind,\n";
            $sample_lyrics .= "A perfect harmony, by design.\n\n";
        }
        
        if ($length === 'long') {
            $sample_lyrics .= "Bridge:\n";
            $sample_lyrics .= "A change of pace, a shift in tone,\n";
            $sample_lyrics .= "A moment to reflect, to condone.\n";
            $sample_lyrics .= "The bridge connects what was known,\n";
            $sample_lyrics .= "To what will be, seeds now sown.\n\n";
            
            $sample_lyrics .= "Verse 3:\n";
            $sample_lyrics .= "The final verse brings it all home,\n";
            $sample_lyrics .= "Words and music, no longer alone.\n";
            $sample_lyrics .= "A message clear, like polished chrome,\n";
            $sample_lyrics .= "In your heart, these words will roam.\n\n";
            
            $sample_lyrics .= "Chorus:\n";
            $sample_lyrics .= "This is where your song would shine,\n";
            $sample_lyrics .= "Words crafted just for you, divine.\n";
            $sample_lyrics .= "An AI creation, one of a kind,\n";
            $sample_lyrics .= "A perfect harmony, by design.\n\n";
        }
        
        $sample_lyrics .= "Outro:\n";
        $sample_lyrics .= "As the final notes fade away,\n";
        $sample_lyrics .= "The message remains, here to stay.\n";
        
        return $sample_lyrics;
    }
}

// Initialize the plugin
$ai_song_generator = new AI_Song_Generator();