<?php $myaccountsettings =  get_fields('account-settings'); ?>
<div class="tab-pane manage-events-tab active show" id="manageEvents" >
    <div class="sub-tab-design manage-events" data-ajax="<?php echo admin_url('admin-ajax.php'); ?>">
        <ul class="nav nav-pills">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#editEvent">Edit Event</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#myDashboard">My Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#team">Team</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#volunteer">Volunteer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#attendees">Attendees</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#refunds">Refunds</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#contact">Inbox</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#discountcodes">Discount Codes</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- My Ticket Tab1 -->
            <div class="tab-pane fade active" id="editEvent">
                <div class="editEvent-main-wpr">
                <?php                 
                $eventcontent = '[event_dashboard]'; 
                  echo apply_filters('the_content',$eventcontent);

                ?>
                </div>
            </div>
            <!-- My Ticket Tab2 -->
            <div class="tab-pane fade" id="myDashboard">
                <!-- <h2>My Dashboard</h2> -->
                <div class="mydashboard-main-wpr">
                    <!-- <?php do_action('event_manager_event_dashboard_before'); ?> -->
                    <div class="me-md-box">
                        <h4>Total Registration<span>2</span></h4>
                        <div class="me-md-countbox">
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    New
                                </div>
                                <h6>2</h6>
                            </div>
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    Confirm
                                </div>
                                <h6>0</h6>
                            </div>
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    Waiting
                                </div>
                                <h6>0</h6>
                            </div>
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    Cancelled
                                </div>
                                <h6>0</h6>
                            </div>
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    Archived
                                </div>
                                <h6>0</h6>
                            </div>
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    Total Checkin
                                </div>
                                <h6>0</h6>
                            </div>
                        </div>
                        <div class="me-md-countbox countbox-row">
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    Total Sold Tickets
                                </div>
                                <h6>2</h6>
                            </div>
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    Paid Tickets
                                </div>
                                <h6>2</h6>
                            </div>
                            <div class="memd-cont">
                                <div class="memd-ttl">
                                    Free Tickets
                                </div>
                                <h6>2</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab3 -->
            <div class="tab-pane fade" id="team">
                <div class="common-table-design get_team_member_data">
                    <div class="tabledataajax"></div>
                    <div class="tableloader order-loader"><div class="lds-dual-ring"></div></div>
                    <div class="export-list-row">
                        <?php if(!empty($myaccountsettings) && (isset($myaccountsettings['manage_event']['add_team_form_id']) && !empty($myaccountsettings['manage_event']['add_team_form_id']))){
                            $add_team_formid = $myaccountsettings['manage_event']['add_team_form_id'];
                            ?>
                        <!-- <button data-toggle="modal" data-target="#AddteamModal">Add New Team Member</button> -->
                        <div class="modal fade volnuteer-form" id="AddteamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/close.png" class="modal-close-icon" alt="">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="modal-title">Add Team Member</h5>
                                        <div class="volunteer-body">
                                            <?php echo do_shortcode('[contact-form-7 id="'.$add_team_formid.'" title="Add Team Member"]'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab4 -->
            <div class="tab-pane fade" id="volunteer">
                <div class="get_volunteer_data">
                    <?php $events = connice_event_listing_by_current_user('ids');
                    if(!empty($events)){
                        echo '<div class="m-e-table-dropdown">';
                        echo '<select class="event-dropdown selectpicker" name="eventid">';
                        echo '<option value="">Select Event</option>';
                        foreach ($events as $eventitem){
                            echo '<option value="'.$eventitem.'" title="'.get_post_by_eventid($eventitem).'">'.get_post_by_eventid($eventitem).'</option>';
                        }
                        echo '</select>';
                        echo '</div>';
                    }
                    ?>
                    <div class="common-table-design">
                        <div class="tabledataajax"></div>
                        <div class="tableloader order-loader"><div class="lds-dual-ring"></div></div>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab5 -->
            <div class="tab-pane fade" id="attendees">
                <div class="get_attendees_data">
                    <?php $events = connice_event_listing_by_current_user('ids');
                    if(!empty($events)){
                        echo '<div class="m-e-table-dropdown">';
                        echo '<select class="event-dropdown selectpicker" name="eventid">';
                        echo '<option value="">Select Event</option>';
                        foreach ($events as $eventitem){
                            echo '<option value="'.$eventitem.'" title="'.get_post_by_eventid($eventitem).'">'.get_post_by_eventid($eventitem).'</option>';
                        }
                        echo '</select>';
                        echo '</div>';
                    }
                    ?>
                    <div class="common-table-design">
                        <div class="tabledataajax"></div>
                        <div class="tableloader order-loader"><div class="lds-dual-ring"></div></div>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab6 -->
            <div class="tab-pane fade" id="refunds">
                <div class="m-e-table-dropdown">
                    <select class="event-dropdown selectpicker">
                        <option>Refunds</option>
                        <option>Refunds</option>
                        <option>Refunds</option>
                        <option>Refunds</option>
                        <option>Refunds</option>
                        <option>Refunds</option>
                    </select>
                </div>
                <div class="common-table-design">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-purple">
                            <tr>
                                <th>Confirmation #</th>
                                <th>Date</th>
                                <th>Ticket Buyer</th>
                                <th>Emails</th>
                                <th>Price</th>
                                <th class="text-center">Accept/Deny</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Denied</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Accepted</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Accepted</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <button class="accept">Accept</button>
                                    <button class="deny">Deny</button>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Accepted</span>
                                </td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td align="center">
                                    <span class="accept-denied">Denied</span>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab7 -->
            <div class="tab-pane fade show" id="contact">
                <div class="manage-events-contact">
                    <div class="m-e-contact-search">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Search...">
                    </div>
                    <div class="m-e-contact-details get_report_problem_contact_data">
                        <?php $events = connice_event_listing_by_current_user('ids');
                        if(!empty($events)){
                            echo '<div class="m-e-table-dropdown">';
                            echo '<select class="event-dropdown selectpicker" name="eventid">';
                            echo '<option value="">Select Event</option>';
                            foreach ($events as $eventitem){
                                echo '<option value="'.$eventitem.'" title="'.get_post_by_eventid($eventitem).'">'.get_post_by_eventid($eventitem).'</option>';
                            }
                            echo '</select>';
                            echo '</div>';
                        }
                        ?>
                        <div class="tabledataajax"></div>
                        <div class="tableloader order-loader"><div class="lds-dual-ring"></div></div>
                    </div>
                </div>
            </div>
            <!-- discount codes -->
            <div class="tab-pane fade" id="discountcodes">
                <div class="common-table-design get_discount_code_data">
                    <div class="tabledataajax"></div>
                    <div class="tableloader order-loader"><div class="lds-dual-ring"></div></div>
                    <div class="export-list-row">
                        <?php if(!empty($myaccountsettings) && (isset($myaccountsettings['manage_event']['add_discount_code_form_id']) && !empty($myaccountsettings['manage_event']['add_discount_code_form_id']))){
                            $add_coupon_formid = $myaccountsettings['manage_event']['add_discount_code_form_id'];
                            ?>
                        <!-- <button data-toggle="modal" data-target="#AddteamModal">Add New Team Member</button> -->
                        <div class="modal fade volnuteer-form" id="AddcouponModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/close.png" class="modal-close-icon" alt="">
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="modal-title">Add discount code</h5>
                                        <div class="volunteer-body">
                                            <?php echo do_shortcode('[contact-form-7 id="'.$add_coupon_formid.'" title="Add Coupons"]'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>