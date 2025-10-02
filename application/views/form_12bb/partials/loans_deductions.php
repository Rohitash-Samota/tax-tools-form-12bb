<?php if(($show ?? '') === 'home_loan'): ?>
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
      <label for="home_loan_interest_payable"> Interest payable</label>
      <input type="number" name="home_loan_interest_payable" value="" min="0" max="100000000" step="1" class="form-control" id="home_loan_interest_payable" placeholder="Interest payable">
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
      <label for="home_loan_lender_name">Loan Provider(Name of Individual or Organization)</label>
      <input type="text" name="home_loan_lender_name" value="" class="form-control name" id="home_loan_lender_name" maxlength="100" placeholder="Name of Lender">
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
      <label for="home_loan_lender_pan"> PAN of Lender</label>
      <input type="text" name="home_loan_lender_pan" value="" class="form-control pan_optional uppercase" id="home_loan_lender_pan" maxlength="10" placeholder="PAN of Lender" pattern="^[A-Z]{5}[0-9]{4}[A-Z]$">
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12 form-group">
      <label for="home_loan_lender_evidence">Evidence</label>
      <input type="text" name="home_loan_lender_evidence" value="" class="form-control alpha_dash_space" id="home_loan_lender_evidence" maxlength="200" placeholder="Evidence">
      <em>Interest Certificate from Bank​ / Yearly Home Loan statement</em>
    </div>
    <div class="col-md-12 col-sm-6 col-xs-12 form-group">
      <label for="home_loan_lender_address">Address of Loan Provider</label>
      <input type="text" name="home_loan_lender_address" value="" class="form-control alpha_dash_space" id="home_loan_lender_address" maxlength="200" placeholder="Address of Lender">
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
      <a class="gray-btn btnPrevious">Back</a> &nbsp; <a class="green-btn btnNext">Next</a>
    </div>
  </div>
<?php elseif(($show ?? '') === 'deductions'): ?>
  <div class="row">
    <div class="col-md-12 col-sm-6 col-xs-12">
      <h5><strong> 1. Deductions under Sec 80C </strong></h5>
    </div>
  </div>
  <div class="items update_lastid">
    <div class="row item item-1">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <span class="inlineDel delete hideable hide">-</span>
      </div>
      <div class="col-md-4 col-sm-12 col-xs-12 form-group">
        <label for="dedn_eighty_c_1"> &nbsp;</label>
        <select name="dedn_eighty_c[1]" class="form-control" id="dedn_eighty_c_1">
          <option value="" selected="selected">Select</option>
          <option value="Life Insurance Premium">Life Insurance Premium</option>
          <option value="Investment in Tax Saving Fixed Deposit">Investment in Tax Saving Fixed Deposit</option>
          <option value="Investment in Tax Saving Mutual Fund">Investment in Tax Saving Mutual Fund</option>
          <option value="Investment in PPF (Public Provident Fund)">Investment in PPF (Public Provident Fund)</option>
          <option value="Childrens Tuition Fees">Childrens Tuition Fees</option>
          <option value="Principal repayment of Home Loan">Principal repayment of Home Loan</option>
          <option value="Others">Others</option>
        </select>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="dedn_eighty_c_amount_1">Amount</label>
        <input type="number" name="dedn_eighty_c_amount[1]" value="" min="0" max="100000000" step="1" class="form-control" placeholder="Amount" id="dedn_eighty_c_amount_1">
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="dedn_eighty_c_evidence_1">Evidence</label>
        <input type="text" name="dedn_eighty_c_evidence[1]" value="" class="form-control alpha_dash_space" placeholder="Evidence" id="dedn_eighty_c_evidence_1">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 text-right">
        <button type="button" class="add-more addItem" data-target="80C">+ Add</button>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <br>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
      <h5><strong> 2. Other Deductions </strong></h5>
    </div>
  </div>
  <div class="items">
    <div class="row item item-2">
      <div class="col-md-12 col-sm-12 col-xs-12"><span class="inlineDel delete hideable hide">-</span></div>
      <div class="col-md-4 col-sm-12 col-xs-12 form-group">
        <label for="name"> &nbsp;</label>
        <select name="dedn_other[1]" class="form-control" id="dedn_other_1">
          <option value="" selected="selected">Select</option>
          <option value="Sec 80CCC - Deduction for contribution to Certain Pension Funds">Sec 80CCC - Deduction for contribution to Certain Pension Funds</option>
          <option value="Sec 80CCD - Contribution to NPS">Sec 80CCD - Contribution to NPS</option>
          <option value="Sec 80D - Deduction for Health Insurance Premium">Sec 80D - Deduction for Health Insurance Premium</option>
          <option value="Sec 80DD - Deduction for Dependent Disabled">Sec 80DD - Deduction for Dependent Disabled</option>
          <option value="Sec 80E - Deduction for Interest on Eductaion Loan">Sec 80E - Deduction for Interest on Eductaion Loan</option>
          <option value="Sec 80G - Deduction for Donations">Sec 80G - Deduction for Donations</option>
        </select>
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="Amount">Amount</label>
        <input type="number" name="dedn_other_amount[1]" value="" min="0" max="100000000" step="1" class="form-control" placeholder="Amount" id="dedn_other_amount_1">
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="name">Evidence</label>
        <input type="text" name="dedn_other_evidence[1]" value="" class="form-control alpha_dash_space" placeholder="Evidence" id="dedn_other_evidence_1">
      </div>
    </div>
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12 text-right">
        <button type="button" class="add-more addItem" data-target="OTHER">+ Add</button>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
      <a class="gray-btn btnPrevious">Back</a> &nbsp;
      <button type="submit" class="green-btn btnNext">Submit</button>
    </div>
  </div>
<?php endif; ?>