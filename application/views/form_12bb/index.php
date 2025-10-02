<section class="rent_rcpt_wpr">
    <div class="rent-receipt form_12bb">
        <div>
            <img src="https://tax2win.in/assets-new/img/form-12bb/form-12bb.svg" alt="Form 12BB" title="Form 12BB" class="img-responsive hidden-xs element">
        </div>
        <div class="">
            <div class="container">
                <div class="col-md-3 col-sm-12 col-xs-12">
                    <div class="left">
                        <h1><strong> Generate Form 12BB</strong></h1>
                        <p>Want to increase your take home salary or maximize your tax saving?
                            Use this Form 12BB generator to declare investments, interest paid against Home Loan, House Rent Allowance, Leave Travel Allowance and other tax saving deductions.</p>
                    </div>
                </div>
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="right">
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#tab1" data-toggle="tab" aria-expanded="true">
                                            <img src="https://tax2win.in/assets-new/img/form-12bb/12bb-1.png" alt="Details of Employee" title="Details of Employee">
                                            <span>Details of <br> Employee</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab2" data-toggle="tab" aria-expanded="false">
                                            <img src="https://tax2win.in/assets-new/img/form-12bb/12bb-2.png" alt="House Rent Allowance" title="House Rent Allowance">
                                            <span>House Rent <br> Allowance</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab3" data-toggle="tab" aria-expanded="false">
                                            <img src="https://tax2win.in/assets-new/img/form-12bb/12bb-3.png" alt="Leave Travel Concessions" title="Leave Travel Concessions">
                                            <span>Leave Travel <br> Concessions</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab4" data-toggle="tab" aria-expanded="false">
                                            <img src="https://tax2win.in/assets-new/img/form-12bb/12bb-4.png" alt="Interest on Home Loan" title="Interest on Home Loan">
                                            <span>Interest on <br> Home Loan</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#tab5" data-toggle="tab" aria-expanded="false">
                                            <img src="https://tax2win.in/assets-new/img/form-12bb/12bb-5.png" alt="Deduction under Chapter VI - A" title="Deduction under Chapter VI - A">
                                            <span>Deduction under <br> Chapter VI - A</span>
                                        </a>
                                    </li>
                                </ul>

                                <form action="/tax-tools/form-12bb/save" method="POST">
                                    <div class="tab-content clearfix">
                                        <div class="tab-pane smooth-load active" id="tab1" variant="employee">
                                            <?php $this->load->view('form_12bb/partials/employee'); ?>
                                        </div>
                                        <div class="tab-pane" id="tab2" variant="hra">
                                            <?php $this->load->view('form_12bb/partials/allowances', ['show' => 'hra']); ?>
                                        </div>
                                        <div class="tab-pane" id="tab3" variant="lta">
                                            <?php $this->load->view('form_12bb/partials/allowances', ['show' => 'lta']); ?>
                                        </div>
                                        <div class="tab-pane" id="tab4" variant="home_loan">
                                            <?php $this->load->view('form_12bb/partials/loans_deductions', ['show' => 'home_loan']); ?>
                                        </div>
                                        <div class="tab-pane" id="tab5" variant="deductions">
                                            <?php $this->load->view('form_12bb/partials/loans_deductions', ['show' => 'deductions']); ?>
                                        </div>
                                    </div>
                                     <input type="hidden" data-csrf name="<?= html_escape($csrf['name']) ?>" value="<?= html_escape($csrf['hash']) ?>">
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php $this->load->view('form_12bb/partials/static'); ?>
</section>