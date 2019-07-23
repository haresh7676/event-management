<div class="tab-pane tab-pane-spacing active show" id="reportProblem">
    <div class="report-problem">
        <div class="contact-us-title">Contact Us</div>
        <div class="my-account-content-wpr">
            <?php
            $myaccountsettings =  get_fields('account-settings');
            if(!empty($myaccountsettings)){
                if(isset($myaccountsettings['report_a_problem']) && !empty($myaccountsettings['report_a_problem'])){
                    echo apply_filters('the_content',$myaccountsettings['report_a_problem']);
                }
            }
            ?>
        </div>
    </div>
</div>