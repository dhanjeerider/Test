/**
 * Download Accordion Script
 * Handles collapsible download sections
 */

(function($) {
    'use strict';
    
    $(document).ready(function() {
        
        // Toggle accordion on button click
        $('.download-accordion-btn').on('click', function() {
            var $btn = $(this);
            var $content = $btn.next('.download-accordion-content');
            
            // Toggle active class
            $btn.toggleClass('active');
            $content.toggleClass('active');
            
            // Close other accordions if you want only one open at a time
            // $('.download-accordion-btn').not($btn).removeClass('active');
            // $('.download-accordion-content').not($content).removeClass('active');
        });
        
        // Initialize first accordion as open (optional)
        // $('.download-accordion-btn').first().trigger('click');
        
    });
    
})(jQuery);
