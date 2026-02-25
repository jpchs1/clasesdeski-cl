<?php
namespace TestimonialsShowcase;

require_once plugin_dir_path( __FILE__ ) . 'class.testimonials-cpt.php';
require_once plugin_dir_path( __FILE__ ) . 'class.testimonials-enqueue.php';
require_once plugin_dir_path( __FILE__ ) . 'class.testimonials-shortcode.php';

class Testimonials_Showcase {
    private $cpt;
    private $enqueue;
    private $shortcode;

    public function __construct() {
        $this->cpt = new Testimonials_CPT();
        $this->enqueue = new Testimonials_Enqueue();
        $this->shortcode = new Testimonials_Shortcode();

        add_action('init', array($this, 'initialize_plugin'));
    }

    public function initialize_plugin() {
        // Initialize functionalities here.
        // They could be methods in this class or in their respective classes.
        
        // For example, to initialize custom post type:
        $this->cpt->init();

        // To initialize the shortcode:
        $this->shortcode->init();

        // To initialize script and style enqueuing:
        $this->enqueue->init();
    }

    
}

$testimonials_showcase = new Testimonials_Showcase();