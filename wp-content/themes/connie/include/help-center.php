<div class="tab-pane tab-pane-spacing active show" id="helpCenter">
    <div class="help-center">
        <!-- <h2 class="my-account-page-title">Help Center</h2> -->
        <div class="my-account-content-wpr">
            <?php
            $myaccountsettings =  get_fields('account-settings');
            if(!empty($myaccountsettings)){
                if(isset($myaccountsettings['my_account_help_center']) && !empty($myaccountsettings['my_account_help_center'])){
                    echo apply_filters('the_content',$myaccountsettings['my_account_help_center']);
                }
            }
            ?>
        </div>
    </div>
</div>