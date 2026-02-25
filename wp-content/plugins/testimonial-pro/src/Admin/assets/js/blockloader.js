const { select, subscribe } = wp.data;
let i = 1;
const closeListener = subscribe(() => {
    // const isReady = select('core/editor').__unstableIsEditorReady();
    const isReady = ( null !== wp.data.select( 'core/block-editor' ) && undefined !== wp.data.select( 'core/block-editor' ) ) ? wp.data.select( 'core/block-editor' ).getBlocks() : false;
    if (!isReady) {
        // Editor not ready.
        return;
    }
    // Close the listener as soon as we know we are ready to avoid an infinite loop.
    closeListener();
    // Your code is placed after this comment, once the editor is ready.
    //alert( 'is ready' );
    let sptpBlockLoaded = false;
    setTimeout( function () {
        let sptpBlockLoadedInterval = setInterval(function () {
            let uniqId = jQuery(".sp-testimonial-pro-wrapper")
                .parents()
                .attr("id");
            if (document.getElementById(uniqId)) {

                jQuery.getScript(sp_testimonial_pro.loadRemodal);
                jQuery.getScript(sp_testimonial_pro.loadThumb);
                jQuery.getScript(sp_testimonial_pro.loadScript);
                sptpBlockLoaded = true;
                uniqId = "";
            }
            if (sptpBlockLoaded) {
                clearInterval(sptpBlockLoadedInterval);
            }
        }, 10);
    }, 10000);
});