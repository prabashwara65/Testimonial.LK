@extends('layouts.frontend')

<style>
    .policy-row {
        justify-content: center;
        display: flex;
        margin-bottom: 40px;
    }

    .policy-wraper {
        padding-right: 15px;
        padding-left: 15px;
        list-style: 1;
    }

    .policy-discription-wraper {
        padding-right: 15px;
        padding-left: 15px;
    }

    .points-order {
        padding: 0
    }

    .policy-wraper .points-order li {
        justify-content: space-between;
        display: flex;
        font-size: 18px;
        margin-bottom: 5px;
        margin-top: 5px;
    }

    .policy-wraper .points-order li a {
        color: #4e4e4e;
    }

    .policy-para {
        text-align: justify;
        font-size: 16px;
    }

    .dis-main-point {
        font-size: 17px;
    }

    .dis-card-ul li {
        font-size: 14px;
        /* font-weight: 600; */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.scroll-link').forEach(link => {
            link.addEventListener('click', function(event) {
                event.preventDefault();

                const targetId = this.getAttribute(
                    'data-target');
                const target = document.getElementById(targetId);

                if (target) {
                    const offset = 10;
                    const elementPosition = target.getBoundingClientRect().top;
                    const offsetPosition = elementPosition + window.pageYOffset + offset;
                    window.scrollTo({
                        top: offsetPosition,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>



@section('content')
    <div class="container">
        <div class="row policy-row">
            <div class="col-md-10 policy-col">
                <div class="policy-wraper">
                    <h2 class="text-center"><strong>Privacy Policy</strong></h2>
                    <h3>Table of Content</h3>
                    <ol class="points-order">
                        <li>
                            <div class="title"><strong>1.&nbsp;</strong><a href="#card1" class="scroll-link"
                                    data-target="card1">Purpose</a></div>
                            <div class="count">4</div>
                        </li>
                        <li>
                            <div class="title"><strong>2.&nbsp;</strong><a href="#card2" class="scroll-link"
                                    data-target="card2">Definitions</a></div>
                            <div class="count">4</div>
                        </li>
                        <li>
                            <div class="title"><strong>3.&nbsp;</strong><a href="#card3" class="scroll-link"
                                    data-target="card3">Information
                                    Collected</a></div>
                            <div class="count">4</div>
                        </li>
                        <li>
                            <div class="title"><strong>4.&nbsp;</strong><a href="#card4" class="scroll-link"
                                    data-target="card4">Reasons for Information
                                    Collection</a></div>
                            <div class="count">6</div>
                        </li>


                        <li>
                            <div class="title"><strong>5.&nbsp;</strong><a href="#card5" class="scroll-link"
                                    data-target="card5">Telephone
                                    Conservation</a></div>
                            <div class="count">6</div>
                        </li>
                        <li>
                            <div class="title"><strong>6.&nbsp;</strong><a href="#card6" class="scroll-link"
                                    data-target="card6">Transfer and Disclosure of
                                    Personal
                                    Data</a></div>
                            <div class="count">6</div>
                        </li>
                        <li>
                            <div class="title"><strong>7.&nbsp;</strong><a href="#card7" class="scroll-link"
                                    data-target="card7">Data Processing</a>
                            </div>
                            <div class="count">7</div>
                        </li>
                        <li>
                            <div class="title"><strong>8.&nbsp;</strong><a href="#card8" class="scroll-link"
                                    data-target="card8">Data Security</a></div>
                            <div class="count">8</div>
                        </li>
                        <li>
                            <div class="title"><strong>9.&nbsp;</strong><a href="#card9" class="scroll-link"
                                    data-target="card9">User Rights</a></div>
                            <div class="count">8</div>
                        </li>
                        <li>
                            <div class="title"><strong>10.&nbsp;</strong><a href="#card10" class="scroll-link"
                                    data-target="card10">Data Retention</a></div>
                            <div class="count">8</div>
                        </li>
                        <li>
                            <div class="title"><strong>11.&nbsp;</strong><a href="#card11" class="scroll-link"
                                    data-target="card11">Links To Third-Party Websites
                                    and
                                    Services</a></div>
                            <div class="count">8</div>
                        </li>
                        <li>
                            <div class="title"><strong>12.&nbsp;</strong><a href="#card12" class="scroll-link"
                                    data-target="card12">Changes
                                    To This Privacy
                                    Policy</a></div>
                            <div class="count">9</div>
                        </li>
                        <li>
                            <div class="title"><strong>13.&nbsp;</strong><a href="#card13" class="scroll-link"
                                    data-target="card13">Contact Points for
                                    data
                                    protection inquiries or exercising Data Subject Rights</a></div>
                            <div class="count">9</div>
                        </li>
                        <li>
                            <div class="title"><strong>14.&nbsp;</strong><a href="#card14" class="scroll-link"
                                    data-target="card14">Notification of Personal Data
                                    breaches
                                </a></div>
                            <div class="count">9</div>
                        </li>
                        <li>
                            <div class="title"><strong>15.&nbsp;</strong><a href="#card15" class="scroll-link"
                                    data-target="card15">Governing Law and
                                    Jurisdiction</a></div>
                            <div class="count">9</div>
                        </li>


                    </ol>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row policy-row">
            <div class="col-md-10 policy-col">
                <div class="policy-discription-wraper">
                    <div id="card1">
                        <h3><strong>1.Purpose</strong></h3>
                        <p class="policy-para">V-Tech Labs (pvt) Ltd (hereafter referred to as "Vtech" and "Organization")
                            operates the website
                            “testimonial.lk”. Vtech prioritizes the protection of user privacy and the security of personal
                            information, including Personal Data. The purpose of this policy is to describe management's
                            commitment to safeguarding PII maintained at the Vtech. By using the Website, you agree to the
                            collection and use of information as outlined in this policy. We use your data to provide and
                            improve the Website.</p>
                    </div>

                    <div id="card2">
                        <h3><strong>2.Definitions</strong></h3>
                        <ul>
                            <li><strong class="dis-main-point">Personally Identifiable Information (PII)</strong>
                                <p class="policy-para">Information that can be used to distinguish an individual from
                                    others. This could be
                                    direct
                                    identifiers like that can be used to contact or identify you ("Personal Data").
                                    Personally
                                    identifiable information may include, but is not limited to:</p>
                                <ul class="dis-card-ul" style="list-style: I">
                                    <li>Email address</li>
                                    <li>First and last name</li>
                                    <li>Phone number</li>
                                    <li>National Identity Card Number or passport information,</li>
                                    <li>Address</li>
                                    <li>Credit card number</li>
                                    <li>Cookies and usage data</li>
                                </ul>
                            </li>
                            <li>
                                <strong class="dis-main-point">Derivative data</strong>
                                <p class="policy-para">Derivative Data is collected automatically when using the Service.
                                    Derivative Data may
                                    include information such as your Device's Internet Protocol address (e.g., IP address),
                                    browser type, browser version, the pages of our Service that you visit, the time and
                                    date of your visit, the time spent on those pages, unique device identifiers, and other
                                    diagnostic data.</p>
                            </li>
                            <li>
                                <strong class="dis-main-point">Mobile device data</strong>
                                <p class="policy-para">When you access the Service through a mobile device, we may collect
                                    certain information
                                    automatically, including, but not limited to, the type of mobile device you use, your
                                    mobile device's unique ID, the IP address of your mobile device, your mobile operating
                                    system, the type of mobile Internet browser you use, unique device identifiers, and
                                    other diagnostic data. We may also collect information that your browser sends whenever
                                    you visit our Service or access the Service through a mobile device.</p>
                            </li>
                        </ul>
                    </div>



                    <div id="card3">
                        <h3><strong>3.Information Collected</strong></h3>
                        <p class="policy-para">The Vtech collects PII from users through various channels. This information
                            can be actively
                            provided by users or passively collected through automated means. The Organization prioritizes
                            data privacy. Sharing personal information allows for tailored services, and responses aligned
                            with user needs. To access certain essential services offered by the Vtech, users are required
                            to provide specific, mandatory information. This information is necessary for the Organization
                            to fulfill its contractual obligations. The Vtech remains committed to protecting user privacy
                            while delivering exceptional service.</p>
                        <p class="policy-para">We require basic information when you create your account, such as your name,
                            avatar, occupation,
                            company, user name, password and location. Additionally, we collect more detailed information
                            about you such as:</p>
                        <ul class="dis-card-ul">
                            <li><strong>User interaction data:</strong> This encompasses details about how users interact
                                with a platform,
                                such as clicks, page views, time spent, and features used.</li>
                            <li>Photographs, video images, voice recordings, or transcripts.</li>
                            <li><strong>Location information:</strong> This may include data or other methods to approximate
                                a user's general
                                geographic location.</li>
                            <li><strong>Cross-platform browsing activity (where applicable):</strong> In some cases, data
                                about a user's
                                activity across different platforms or services might be collected, providing insights into
                                their interests and preferences.</li>
                            <li>When you make purchases in our app, a secure window will be opened directly to our
                                third-party subscription provider and any payments will be securely processed through our
                                third-party payment providers.</li>
                            <li>When you install apps and sign up for our Services, we collect data required to provide our
                                Services.</li>
                            <li>When you visit and interact with any of our Services, we collect data about your device,
                                such as your operating system, hostname, browser type, and referring URLs.</li>
                            <li>To your consent, we automatically collect information from cookies and similar technologies
                                (such as cookie ID and settings) to keep you logged in, to remember your preferences, and to
                                identify you and your device.</li>
                        </ul>
                    </div>



                    <div id="card4">
                        <h3><strong>4.Reasons for Information Collection</strong></h3>
                        <p class="policy-para">The Vtech utilizes the information it collects for a variety of reasons:</p>
                        <ul class="dis-card-ul">
                            <li>Data collection may be necessary to fulfill contractual, legal, or regulatory obligations.
                            </li>
                            <li>Analysis of data like browsing duration and bounce rate helps enhance user experience and
                                platform functionality.</li>
                            <li>Contact information might be used to send essential updates, newsletters, or occasional
                                marketing materials.</li>
                            <li>Follow-up communication after inquiries may be conducted to ensure user satisfaction and
                                address any concerns. This is based on the legitimate interest in providing accurate
                                pre-sale information.</li>
                            <li>User journeys and actions within the platform (including navigation, visit frequency, bounce
                                rate, and download items) are tracked and analyzed via cookies to improve the platform and
                                related services.</li>
                        </ul>
                    </div>


                    <div id="card5">
                        <h3><strong>5.Transfer and Disclosure of Personal Data</strong></h3>
                        <ul class="dis-card-ul">
                            <li>Your Personal Data may be transferred to and maintained on computers located outside your
                                country where data protection laws may differ from those of your jurisdiction.</li>
                            <li>To facilitate effective service delivery, data may be disclosed to approved service
                                providers, and vendors under strict confidentiality agreements.</li>
                            <li>Disclosure of information may be mandated by regulation, law, or legal proceedings
                                (jurisdictional), or to safeguard the rights, property, or safety of the Organization, its
                                stakeholders, or the public.</li>
                        </ul>
                    </div>


                    <div id="card6">
                        <h3><strong>6.Data Processing</strong></h3>
                        <ul>
                            <li>
                                <strong class="dis-main-point">Information Usage:</strong>
                                <p>Collected information is used solely for internal purposes that enhance the user
                                    experience, including:</p>
                                <ul>
                                    <li>Analysis of usage data for improved user experience</li>
                                    <li>Identification of potential collaborations that benefit users.</li>
                                    <li>Tailoring communications and offerings to user interests.</li>
                                    <li>Satisfy applicable statutory, legal, contractual, and regulatory requirements.</li>
                                </ul>
                            </li>
                            <li>
                                <strong class="dis-main-point">Disclosure Requirements:</strong>
                                <ul>
                                    <li>The Organization reserves the right to disclose user information to government or
                                        legal authorities if a security threat is identified that could harm users, third
                                        parties, or the Organization itself.</li>
                                </ul>
                            </li>
                        </ul>
                    </div>


                    <div id="card7">
                        <h3><strong>7.Data Security</strong></h3>
                        <p class="policy-para">The Vtech has implemented various data security measures to ensure the
                            continued protection of
                            PII:</p>
                        <ul class="dis-card-ul">
                            <li>Restricting physical and logical access to infrastructure.</li>
                            <li>Regular security assessments are conducted to identify potential threats and strengthen
                                defenses.</li>
                            <li>Industry-standard encryption safeguards data transmission between user devices and the
                                Organization's services, ensuring the security of personal information during transit.</li>
                            <li>While the Organization prioritizes the highest security standards, inherent risks exist when
                                transmitting information online. Caution is recommended when transmitting sensitive
                                information.</li>
                            <li>Checks on third parties we work with when we share personal information to make sure they
                                work to agreed standards.</li>
                        </ul>
                    </div>


                    <div id="card8">
                        <h3><strong>8.User Rights</strong></h3>
                        <p class="policy-para">The Vtech empowers users to manage their personal information through the
                            following options:</p>
                        <ul class="dis-card-ul">
                            <li>To access, review, or download their personal information, users can submit a formal request
                                to customer care through ‘<a
                                    href="mailto:info.vtechlabs@gmail.com">info.vtechlabs@gmail.com</a>’. This process also
                                allows for
                                corrections of any inaccuracies identified within the data.</li>
                            <li>Users have the option to unsubscribe from the Vtech's mailing list, effectively withdrawing
                                from marketing communications.</li>
                        </ul>
                    </div>


                    <div id="card9">
                        <h3><strong>9.Data Retention</strong></h3>
                        <ul class="dis-card-ul">
                            <li>The Vtech retains personal data only for as long as necessary to comply with our legal
                                obligation resolve disputes and resolved our legal agreements and policies. This applies
                                equally to third-party service providers who handle user data on the Organization's behalf.
                            </li>
                            <li>We’re required to hold personal information by the Companies Act No. 7 of 2007, the
                                Prevention of Money Laundering Act No. 5 of 2006, the Financial Transactions Reporting Act
                                No. 6 of 2006, personal data protection act no: 09 of 2022 and other relevant Sri Lankan and
                                international laws.</li>
                        </ul>
                    </div>


                    <div id="card10">
                        <h3><strong>10.Links to Third-Party Websites and Services</strong></h3>
                        <ul class="dis-card-ul">
                            <li>The Vtech's website may contain links to third-party products, external websites, or social
                                media profiles. These platforms maintain independent privacy policies. Users are encouraged
                                to review these policies and familiarize themselves with the information practices of these
                                entities.</li>
                            <li>The Vtech assumes no responsibility for information knowingly or unknowingly submitted to
                                these external sources. In the event of a security concern identified on a linked website,
                                users are encouraged to report it to the Organization. The Vtech will take appropriate
                                action to address potential non-compliance with its privacy policy by these external
                                parties.</li>
                        </ul>
                    </div>


                    <div id="card11">
                        <h3><strong>11.Changes To This Privacy Policy</strong></h3>
                        <ul>
                            <li>The Vtech reserves the right to amend this Privacy Statement at any time. Continued use of
                                the Vtech's services constitutes consent to the current Privacy Statement and any future
                                revisions.</li>
                            <li>In the event the Vtech intends to utilize PII for a purpose not previously disclosed, users
                                will be contacted with relevant information and, if applicable, a request for consent.</li>
                        </ul>
                    </div>


                    <div id="card12">
                        <h3><strong>12.Changes To This Privacy Policy</strong></h3>
                        <p class="policy-para">For any inquiries regarding this Privacy Policy please contact us on
                            <a href="mailto:info.vtechlabs@gmail.com">info.vtechlabs@gmail.com</a>
                        </p>
                    </div>

                    <div id="card13">
                        <h3><strong>13.Governing Law and Jurisdiction</strong></h3>
                        <p class="policy-para">This Policy and any agreements entered with the Vtech shall be governed by
                            and construed in
                            accordance with the Personal Data Protection Act No. 9 of 2022, Sri Lanka.</p>
                    </div>






                </div>
            </div>
        </div>
    </div>
@endsection
