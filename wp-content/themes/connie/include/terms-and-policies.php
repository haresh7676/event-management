<div class="tab-pane tab-pane-spacing active show" id="termsAndPolicies">
    <div class="terms-policies">
        <h2 class="my-account-page-title">Terms and Policies</h2>
        <div class="my-account-content-wpr">
            <?php
            $myaccountsettings =  get_fields('account-settings');
            if(!empty($myaccountsettings)){
                if(isset($myaccountsettings['my_account_terms_and_policies']) && !empty($myaccountsettings['my_account_terms_and_policies'])){
                    echo apply_filters('the_content',$myaccountsettings['my_account_terms_and_policies']);
                }
            }
            ?>
        </div>
    </div>
</div>