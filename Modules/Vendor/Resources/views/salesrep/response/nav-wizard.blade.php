<div class="wizard-inner">
    <div class="connecting-line"></div>
    <ul class="nav nav-tabs wizard-tabs" role="tablist">

        <li role="presentation" class="{{ Request::is('vendor/response/step-one') ? 'active' : 'disabled' }}">
            <a href="#step1" class="d-flex align-center">
                <span class="round-tab mr-10">
                    1
                </span>
                <span class="f14 fw-500">Customer NIC</span>
            </a>
        </li>

        <li role="presentation" class="{{ Request::is('vendor/response/step-two') ? 'active' : 'disabled' }}">
            <a href="#step1" class="d-flex align-center">
                <span class="round-tab mr-10">
                    2
                </span>
                <span class="f14 fw-500">Customer Registration</span>
            </a>
        </li>

        <li role="presentation" class="{{ Request::is('vendor/response/step-three') ? 'active' : 'disabled' }}">
            <a href="#step2" class="d-flex align-center">
                <span class="round-tab mr-10">
                    3
                </span>
                <span class="f14 fw-500">OTP Verification</span>
            </a>
        </li>

        <li role="presentation" class="{{ Request::is('vendor/response/step-four') ? 'active' : 'disabled' }}">
            <a href="#step3" class="d-flex align-center">
                <span class="round-tab mr-10">
                    4
                </span>
                <span class="f14 fw-500">Product & Campaign</span>
            </a>
        </li>

        <li role="presentation" class="{{ Request::is('vendor/response/step-five') ? 'active' : 'disabled' }}">
            <a href="#step4" class="d-flex align-center">
                <span class="round-tab mr-10">
                    5
                </span>
                <span class="f14 fw-500">Testimonial or Feedback</span>
            </a>
        </li>
    </ul>
</div>
