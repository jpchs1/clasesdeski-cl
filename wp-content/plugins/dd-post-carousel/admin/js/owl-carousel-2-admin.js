//phpcs:disable
jQuery( function( $ ){
    'use strict';
    // Define Variables
    let gotPosts = 0, postSelected, taxOptions;
    let taxCounter = 0;
    const   $post_type = $("select#dd_owl_post_type"),
            $tax_options = $('input[name="dd_owl_tax_options"]'),
            $tax_value = $tax_options.val(),
            $term_row = $('#term-row'),
            $is_media = $('.is-media'),
            $image_size = $('#dd_owl_image_size'),
            $reviews = $('#reviews_options, [data-id="product_reviews"]'),
            $post_options = $('[data-id="display_post_options"]');
    $( '#dd_owl_image_wrapper' ).on( 'click', 'a.delete', function() {
        $(this).closest('li.dd-owl-image-preview').remove();
    });
    //Copy the shortcode
    $('#dd_shortcode_copy').on('click', function() {
        let shortcode = document.getElementById('dd_owl_shortcode').innerHTML;
        let aux = document.createElement("input"); // Create a "hidden" input
        aux.setAttribute("value", shortcode); // Assign it the value of the specified element
        document.body.appendChild(aux); // Append it to the body
        aux.select(); // Highlight its content
        document.execCommand("copy"); // Copy the highlighted text
        document.body.removeChild(aux); // Remove it from the body
        // DISPLAY 'Shortcode Copied' message
        document.getElementById('dd_owl_shortcode').innerHTML = "Copied!";
        setTimeout(function(){ document.getElementById('dd_owl_shortcode').innerHTML = shortcode; }, 1000);
    });

    postSelected = $post_type.val();
    taxOptions = $('input[name="dd_owl_tax_options"]:checked').val();

    $('#dd_owl_thumbs').on('click', function(){
        if ($(this).is(':checked')) {
            $('.image-options').removeClass('hidden');
        }
        else {
            $('.image-options').addClass('hidden');
        }
    });

    $('#dd_owl_show_cta').on('change', function(){
        if ($(this).is(':checked')) {
            $('.show-button').removeClass('hidden');
        }
        else {
            $('.show-button').addClass('hidden');
        }
    });
    $('select#dd_owl_btn_display').on('change', function(){
        if ($(this).val() !== 'inline'){
            $('.button-margin').addClass('visible').removeClass('hidden');
        }
        else {
            $('.button-margin').addClass('hidden').removeClass('visible');
        }
    });


    $post_type.on('change', function(){
        let postType = $(this).val();
        $('#dd-owl-loading').show();
        $('span.ajax-loader').show();
        $term_row.addClass('hidden').removeClass('visible');
        $is_media.removeClass('visible').addClass('hidden');
        $post_options.removeClass('hidden').addClass('visible');
        $('#tax-options').removeClass('hidden').addClass('visible');
        $reviews.removeClass('visible').addClass('hidden');
        $('#displayPostOptions').removeClass('hidden').addClass('visible');
        if (postType !== postSelected) {
            gotPosts = 0;
            $('select#dd_owl_post_ids').find('option').remove().end();
            $tax_options.trigger('change');
            postSelected = postType;
        }
        if (postType === 'product'){
            $('.product-rows').show();
            $('.not-media').removeClass('hidden').addClass('visible');
            $post_options.removeClass('hidden').addClass('visible');
        }
        else if (postType === 'reviews'){
            $('.product-rows').show();
            $('.not-media').removeClass('hidden').addClass('visible');
            $post_options.removeClass('hidden').addClass('visible');
            $reviews.removeClass('hidden').addClass('visible');
            $('#displayPostOptions').removeClass('visible').addClass('hidden');
        }
        else if (postType === 'attachment') {
            $is_media.removeClass('hidden').addClass('visible');
            $('.not-media').removeClass('visible').addClass('hidden');
            $('#tax-options').removeClass('visible').addClass('hidden');
            $post_options.removeClass('visible').addClass('hidden');
        }
        else {
            $('.product-rows').hide();
            $('.not-media').removeClass('hidden').addClass('visible');
            $term_row.addClass('hidden').removeClass('visible');
            $post_options.removeClass('hidden').addClass('visible');
        }
        // Select the product category
        let postID = $('input#post_ID').val();
        $.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: 'json',
            data: {
                posttype: postType,
                action: 'owl_carousel_tax',
                postid: postID,
                nonce: dd_owl_admin_script.nonce,
            },
            success: function(data){
                $('#taxonomy').html(data);
                ajax_get_terms();
            }
        });
        let no_tax = ['null', 'postID', 'featured_product'];
        if ( $.inArray($tax_value, no_tax) !== -1 ) {
            $('#term-row').addClass('hidden').removeClass('visible');
        }
    }); // End Post Type on Change

    $tax_options.on('change', function(){
        let ck = $('input[name="dd_owl_tax_options"]:checked').val();
        let ddOwlPostTaxonomyTerm = $('#dd_owl_post_taxonomy_term');
        let terms = (ddOwlPostTaxonomyTerm.length) ? ddOwlPostTaxonomyTerm.val() : -1;
        let showTerms = false;
        if (true === terms) {
            showTerms = !!(terms.length);
        }
        if (ck === 'taxonomy'){
            $('#category-row').removeClass('hidden').addClass('visible');
            $('#choose-postids.visible').addClass('hidden').removeClass('visible');
            $('#number_of_posts').show();
            if (showTerms) $term_row.addClass('visible').removeClass('hidden');
        }
        else if (ck === 'postID'){
            $('#number_of_posts').hide();
            if (gotPosts === 0) {
                dd_select_posts();
            }
            else {
                $('#choose-postids').removeClass('hidden').addClass('visible');
                $('#dd_owl_post_ids').show();
            }
            $('#category-row.visible, #term-row.visible').addClass('hidden').removeClass('visible');
        }
        else if (ck === 'featured_product') {
            $('#number_of_posts').show();
            $('#category-row.visible, #term-row.visible, #choose-postids').addClass('hidden').removeClass('visible');
            $term_row.hide();
        }
        else if (ck === 'show_tax_only') {
            $('#category-row').removeClass('hidden').addClass('visible');
            $('#choose-postids.visible').addClass('hidden').removeClass('visible');
            $('#number_of_posts').show();
            if (showTerms) $term_row.addClass('visible').removeClass('hidden');
        } else {
            $('#category-row.visible, #term-row.visible').addClass('hidden').removeClass('visible');
            $('#choose-postids.visible').addClass('hidden').removeClass('visible');
        }
        return false;
    });
    $image_size.on('change', function(){
        if ($image_size.val() === 'custom'){
            $('.show-custom').removeClass('hidden').addClass('visible');
        } else {
            $('.show-custom').addClass('hidden').removeClass('visible');
        }
    });
    if ( taxOptions !== null && taxCounter === 0 ) {
        $tax_options.trigger('change');
        taxCounter = 1;
    }
    // Specific AjaxComplete Functions
    $(window).on('ajaxComplete', function(){
        $(document).on('change', '#dd_owl_post_taxonomy_type', function () {
            ajax_get_terms();
        });
        if ($('#dd_owl_thumbs').is(':checked')){
            $('.image-options.hidden').removeClass('hidden');
        };
    });
    $(function(){

        $('body.post-type-owl-carousel #wpwrap').before('<div id="dd-owl-loading"></div>');
        $('.dd_owl_tooltip').tooltip();
        $('#dd_owl_image_wrapper').sortable();
        $post_type.trigger('change');
        $image_size.trigger('change');
        let dd_owl_media_upload;

        $('#dd-owl-add-media').on('click',function (e) {

            e.preventDefault();
            // If the uploader object has already been created, reopen the dialog
            if (dd_owl_media_upload) {
                dd_owl_media_upload.open();
                return;
            }
            // Extend the wp.media object
            dd_owl_media_upload = wp.media.frames.file_frame = wp.media({
                title: dd_owl_admin_script.select_images,
                button: {text: dd_owl_admin_script.insert_images},
                multiple: true //allowing for multiple image selection
            });

            dd_owl_media_upload.on('select', function () {

                let attachments = dd_owl_media_upload.state().get('selection').map(
                    function (attachment) {

                        attachment.toJSON();
                        return attachment;

                    });

                //loop through the array and do things with each attachment

                let i, $ul = $('#dd_owl_image_wrapper');

                for (i = 0; i < attachments.length; ++i) {
                    let image = (attachments[i].attributes.sizes.thumbnail) ? attachments[i].attributes.sizes.thumbnail.url : attachments[i].attributes.url
                    $ul.append(
                        '<li class="dd-owl-image-preview" id="dd-owl-media-' +
                        attachments[i].id + 'data-media-id="' + attachments[i].id + '">' +
                        '<img src="' + image + '" alt="'+ attachments[i].attributes.alt +'">' +
                        '<input id="dd-owl-image-input-' + attachments[i].id + '" type="hidden" name="dd_owl_media_items_array[]"  value="' + attachments[i].id + '">' +
                        '<ul class="actions"><li><a href="#" class="delete"></a></li></ul>' +
                        '</li>'
                    );

                }

            });
            dd_owl_media_upload.open();
        });
    });

    function ajax_get_terms(){
        $('#dd-owl-loading').show();
        $('span.ajax-loader').show();
        let postType = $('select#dd_owl_post_type').val();
        let taxType = $('select#dd_owl_post_taxonomy_type').val();
        let postID = $('input#post_ID').val();
        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: {
                posttype: postType,
                taxtype: taxType,
                postid: postID,
                action: 'owl_carousel_terms',
                nonce: dd_owl_admin_script.nonce,
            },
            dataType: 'json',
            success: function(data){
                $('#taxterm').html(data);
                $('#dd-owl-loading').hide();
                $('span.ajax-loader').hide();
                $('body:before').css('display', 'none');
                if (data.length > 234){
                    $term_row.addClass('visible').removeClass('hidden');
                }
                let $1 = $('select#dd_owl_post_taxonomy_term');
                $1.select2({
                    placeholder: "Choose Terms",
                    allowClear: true
                });
                $1.find(':selected').data('selected');
            },
            error: function(data) {
                console.log(data);
            }
        });

    }

    function dd_select_posts(){
        $('#dd-owl-loading').show();
        $('span.ajax-loader').show();
        let postType = $('select#dd_owl_post_type').val();
        let carouselID = $('input#post_ID').val();
        $.ajax({
            url: ajaxurl,
            type: "POST",
            dataType: 'json',
            data: {
                posttype: postType,
                carousel_id: carouselID,
                action : 'owl_carousel_posts',
                nonce: dd_owl_admin_script.nonce,
            },
            success: function(data){
                let ddOwlPostIds = $('#dd_owl_post_ids');
                ddOwlPostIds.append(data);
                $('.dd-owl-multi-select').select2({
                    placeholder: 'Choose the posts'
                });
                $('#choose-postids').removeClass('hidden').addClass('visible');
                ddOwlPostIds.show();
                $('#dd-owl-loading').hide();
                $('span.ajax-loader').hide();
            },
            error: function(data) {
                console.log(data);
            }
        });
        gotPosts = 1;
    }
});