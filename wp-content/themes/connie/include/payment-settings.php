<div class="tab-pane fade tab-pane-spacing active show" id="paymentSettings">
    <div class="account-settings" data-ajax="<?php echo admin_url( 'admin-ajax.php' ); ?>">
        <div class="notes"></div>
        <?php
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;
        $paypalreciver = get_user_meta($current_user_id, 'paypal_reciver_id', true);
        ?>
        <form class="paypalconnect" name="paypalconnect" id="paypalconnect" method="post">
            <div class="textfield-row">
                <label>PayPal Email</label><br>
                <input type="email" class="form-controls" placeholder="Hi@yourethebest.com" name="paypalEmail" value="<?php echo $paypalreciver; ?>" required><br>
                <span><em>This email id used for received payment for your event</em></span>

            </div>
            <div class="textfield-save-btn">
                <input type="hidden" name="action" value="connect_paypal">
                <button>Save</button>
            </div>
        </form>
    </div>

        <!--<div class="accordion" id="paymentAccordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <button class="btn" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">Connect Paypal</button>
                </div>
                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#paymentAccordion">
                    <div class="card-body">
                        <div class="connect-paypal">
                            <div class="account-settings">
                                <div class="textfield-row">
                                    <label>PayPal Email</label>
                                    <input type="email" class="form-controls" placeholder="Hi@yourethebest.com" name="paypal_email" required>
                                </div>
                                <div class="textfield-save-btn">
                                    <button>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">Add New Credit or Debit Card</button>
                </div>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#paymentAccordion">
                    <div class="card-body">
                        <div class="add-new-card">
                            <div class="add-card-textfield-row dsplyFlex">
                                <div class="card-number">
                                    <label>CARD NUMBER</label>
                                    <input type="text" class="form-controls">
                                </div>
                                <div class="card-cvc">
                                    <label>CVC</label>
                                    <input type="text" class="form-controls">
                                </div>
                            </div>
                            <div class="add-card-textfield-row">
                                <label>HOLDER NAME</label>
                                <input type="text" class="form-controls">
                            </div>
                            <div class="add-card-textfield-row">
                                <label>EXPIRATION DATE</label>
                                <div class="expire-card-detail">
                                    <div class="expire-date">
                                        <select class="form-controls">
                                            <option>Date</option>
                                            <option>01</option>
                                            <option>09</option>
                                            <option>12</option>
                                            <option>15</option>
                                            <option>22</option>
                                            <option>25</option>
                                            <option>27</option>
                                            <option>30</option>
                                            <option>31</option>
                                        </select>
                                    </div>
                                    <div class="expire-month">
                                        <select class="form-controls">
                                            <option>Month</option>
                                            <option>January</option>
                                            <option>May</option>
                                            <option>September</option>
                                            <option>November</option>
                                            <option>December</option>
                                        </select>
                                    </div>
                                    <div class="expire-year">
                                        <select class="form-controls">
                                            <option>Year</option>
                                            <option>2018</option>
                                            <option>2019</option>
                                            <option>2020</option>
                                            <option>2021</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button class="enter-card-btn">ENTER CARD</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header" id="headingThree">
                    <button class="btn collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">Connect Paypal</button>
                </div>
                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#paymentAccordion">
                    <div class="card-body">
                        <div class="connect-paypal">
                            <h3>Add Paypal details</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>-->
</div>
