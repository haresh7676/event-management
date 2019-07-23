<div class="tab-pane tab-pane-spacing active show" id="about">
    <div class="about-tab">
        <h2 class="my-account-page-title">About Us</h2>
        <div class="my-account-content-wpr">
            <?php
            $myaccountsettings =  get_fields('account-settings');
            if(!empty($myaccountsettings)){
                if(isset($myaccountsettings['my_account_about']) && !empty($myaccountsettings['my_account_about'])){
                    echo apply_filters('the_content',$myaccountsettings['my_account_about']);
                }
            }
            ?>
        </div>
    </div>
</div>