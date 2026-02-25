<?php 
    $is_featured = ( $active == 'yes' ) ? 'featured' : '' ;
 ?>

<div class="cols-class"> 
    <div class="pricing-table <?php echo $is_featured; ?>">
        <div class="pricing-table-header">
            <h3><?php echo $table_title; ?></h3>
        </div>
        
        <div class="pricing-table-content">
            <?php echo $content; ?>
        </div>
        
        <div class="pricing-table-footer">
            <h3><sup><?php echo $table_currency; ?></sup>9.99</h3>
            <p><?php echo $table_price_period; ?></p>
            <?php if ($table_show_button=='yes'): ?>
                <a href="<?php echo ($table_link != '') ? $table_link : 'javascript:void(0)'; ?>" target="<?php echo $table_target; ?>"><?php echo $table_button_text; ?></a>
            <?php endif ?>
            
        </div>
    </div>
</div>