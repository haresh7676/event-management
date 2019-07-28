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
                <a class="nav-link" data-toggle="tab" href="#contact">Contact</a>
            </li>
        </ul>
        <div class="tab-content">
            <!-- My Ticket Tab1 -->
            <div class="tab-pane fade active" id="editEvent">
                <h2>Edit Events</h2>
                <?php echo do_shortcode('[event_dashboard]'); ?>
            </div>
            <!-- My Ticket Tab2 -->
            <div class="tab-pane fade" id="myDashboard">
                <h2>My Dashboard</h2>
            </div>
            <!-- My Ticket Tab3 -->
            <div class="tab-pane fade" id="team">
                <div class="common-table-design">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-purple">
                            <tr>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>Location</th>
                                <th>Position</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Adam Denisov</td>
                                <td>298-323-2133</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>NYC, NY</td>
                                <td>Social Media Director</td>
                            </tr>
                            <tr>
                                <td>Adam Denisov</td>
                                <td>298-323-2133</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>NYC, NY</td>
                                <td>Social Media Director</td>
                            </tr>
                            <tr>
                                <td>Adam Denisov</td>
                                <td>298-323-2133</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>NYC, NY</td>
                                <td>Social Media Director</td>
                            </tr>
                            <tr>
                                <td>Adam Denisov</td>
                                <td>298-323-2133</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>NYC, NY</td>
                                <td>Social Media Director</td>
                            </tr>
                            <tr>
                                <td>Adam Denisov</td>
                                <td>298-323-2133</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>NYC, NY</td>
                                <td>Social Media Director</td>
                            </tr>
                            <tr>
                                <td>Adam Denisov</td>
                                <td>298-323-2133</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>NYC, NY</td>
                                <td>Events & Accounting Manager</td>
                            </tr>
                            <tr>
                                <td>Adam Denisov</td>
                                <td>298-323-2133</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>NYC, NY</td>
                                <td>Executive Editor/ Head Producer</td>
                            </tr>
                            <tr>
                                <td>Adam Denisov</td>
                                <td>298-323-2133</td>
                                <td>itslitty@gmail.com</td>
                                <td>NYC, NY</td>
                                <td>Assistant Operations Manager</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="export-list-row">
                        <a href="#"><i class="fas fa-chevron-up"></i> Export List</a>
                        <button>Add New Team Member</button>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab4 -->
            <div class="tab-pane fade" id="volunteer">
                <div class="common-table-design get_volunteer_data">
                    <div class="tabledataajax"></div>
                    <div class="tableloader" data-loader="<?php echo get_template_directory_uri().'/assets/images/loader.png'; ?>"></div>
                    <div class="export-list-row">
                        <a href="#"><i class="fas fa-chevron-up"></i> Export List</a>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab5 -->
            <div class="tab-pane fade" id="attendees">
                <div class="common-table-design">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-purple">
                            <tr>
                                <th>Comfirmation #</th>
                                <th>Date</th>
                                <th>Ticket Buyer</th>
                                <th>Emails</th>
                                <th>Price</th>
                                <th>Quantity</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            <tr>
                                <td>1234321</td>
                                <td>12 dec 2019</td>
                                <td>Adam Denisov</td>
                                <td>adamdenisov@gmail.com</td>
                                <td>$27.00</td>
                                <td>x1</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="export-list-row">
                        <a href="#"><i class="fas fa-chevron-up"></i> Export List</a>
                    </div>
                </div>
            </div>
            <!-- My Ticket Tab6 -->
            <div class="tab-pane fade" id="refunds">
                <div class="common-table-design">
                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-purple">
                            <tr>
                                <th>Comfirmation #</th>
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
                    <div class="export-list-row">
                        <a href="#"><i class="fas fa-chevron-up"></i> Export List</a>
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
                    <div class="m-e-contact-details">
                        <ul>
                            <li>
                                <div class="m-e-contact-profile-pic">
                                    <img src="assets/images/contact-list-pic1.png" alt="">
                                </div>
                                <div class="m-e-contact-profile-desc">
                                    <span>Alexis Davis</span>
                                    <h3>Friday Update</h3>
                                    <p>Happy Friday Team! This week's updates</p>
                                </div>
                                <div class="m-e-contact-profile-time">
                                    <span>5 min ago</span>
                                </div>
                            </li>
                            <li>
                                <div class="m-e-contact-profile-pic">
                                    <img src="assets/images/contact-list-pic2.png" alt="">
                                </div>
                                <div class="m-e-contact-profile-desc">
                                    <span>Amanda Smith</span>
                                    <h3>Refund</h3>
                                    <p>Hi, I was wondering about you refund policy</p>
                                </div>
                                <div class="m-e-contact-profile-time">
                                    <span>1 hr ago</span>
                                </div>
                            </li>
                            <li>
                                <div class="m-e-contact-profile-pic">
                                    <img src="assets/images/contact-list-pic3.png" alt="">
                                </div>
                                <div class="m-e-contact-profile-desc">
                                    <span>Bryanda Law</span>
                                    <h3>Let’s meet for dinner later</h3>
                                    <p>Hey Jason, How do you feel about grabbing</p>
                                </div>
                                <div class="m-e-contact-profile-time">
                                    <span>5 min ago</span>
                                </div>
                            </li>
                            <li class="disabled">
                                <div class="m-e-contact-profile-pic">
                                    <img src="assets/images/contact-list-pic1.png" alt="">
                                </div>
                                <div class="m-e-contact-profile-desc">
                                    <span>Max Martinez</span>
                                    <h3>Your insights?</h3>
                                    <p>Hey, If I'm not mistaken, you signed up for our</p>
                                </div>
                                <div class="m-e-contact-profile-time">
                                    <span>9:03 am</span>
                                </div>
                            </li>
                            <li class="disabled">
                                <div class="m-e-contact-profile-pic">
                                    <img src="assets/images/contact-list-pic2.png" alt="">
                                </div>
                                <div class="m-e-contact-profile-desc">
                                    <span>Max Martinez</span>
                                    <h3>Your insights?</h3>
                                    <p>Hey, If I'm not mistaken, you signed up for our</p>
                                </div>
                                <div class="m-e-contact-profile-time">
                                    <span>9:03 am</span>
                                </div>
                            </li>
                            <li class="disabled">
                                <div class="m-e-contact-profile-pic">
                                    <img src="assets/images/contact-list-pic3.png" alt="">
                                </div>
                                <div class="m-e-contact-profile-desc">
                                    <span>Max Martinez</span>
                                    <h3>Your insights?</h3>
                                    <p>Hey, If I'm not mistaken, you signed up for our</p>
                                </div>
                                <div class="m-e-contact-profile-time">
                                    <span>9:03 am</span>
                                </div>
                            </li>
                            <li class="disabled">
                                <div class="m-e-contact-profile-pic">
                                    <img src="assets/images/contact-list-pic1.png" alt="">
                                </div>
                                <div class="m-e-contact-profile-desc">
                                    <span>Max Martinez</span>
                                    <h3>Your insights?</h3>
                                    <p>Hey, If I'm not mistaken, you signed up for our</p>
                                </div>
                                <div class="m-e-contact-profile-time">
                                    <span>9:03 am</span>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>