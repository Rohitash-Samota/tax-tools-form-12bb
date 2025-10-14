<?php if(($show ?? '') === 'hra'): ?>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
      <h2>House Rent Allowance</h2>
      <div class="hr"></div>
    </div>
  </div>
  <div class="row">
    <div class="row">
      <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="hra_rent_paid">Rent paid to Landlord (Yearly)</label>
        <input type="number" name="hra_rent_paid" value="" min="0" max="100000000" step="1" class="form-control" id="hra_rent_paid" placeholder="Rent paid to Landlord">
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="hra_landlord_name">Name of Landlord</label>
        <input type="text" name="hra_landlord_name" value="" class="form-control name" id="hra_landlord_name" maxlength="100" placeholder="Name of Landlord">
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="hra_evidence"> Evidence Particulars <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-original-title="Rental agreement or Monthly rent receipts​.​"></i> </label>
        <input type="text" name="hra_evidence" value="" class="form-control alpha_dash_space" id="hra_evidence" maxlength="200" placeholder="Evidence Particlars">
      </div>
    </div>
    <div class="row">
      <div class="col-md-8 col-sm-6 col-xs-12 form-group">
        <label for="hra_landlord_address">Address of Landlord</label>
        <input type="text" name="hra_landlord_address" value="" class="form-control alpha_dash_space" id="hra_landlord_address" maxlength="200" placeholder="Address of Landlord">
      </div>
      <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="hra_landlord_pan"> PAN of Landlord <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-original-title="If the aggregate rent paid during the year exceeds one lakh rupees, its compulsory to give the PAN of the landlord."></i> </label>
        <input type="text" name="hra_landlord_pan" value="" class="form-control pan_optional uppercase hra_pan_required_above_lakh" id="hra_landlord_pan" maxlength="10" placeholder="PAN of Landlord" pattern="^[A-Z]{5}[0-9]{4}[A-Z]$">
      </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
      <br>
      <a class="gray-btn btnPrevious">Back</a> &nbsp; <a class="green-btn btnNext">Next</a>
    </div>
  </div>
<?php elseif(($show ?? '') === 'lta'): ?>
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
      <h2> Leave Travel Concessions <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="top" data-original-title="This tax exemption is allowed only on actual travel cost to the extend specified in CTC.  To claim LTA, employees need to submit travel bills like boarding passes, flight tickets, invoice of travel agent, boarding pass etc. to employer."></i> </h2>
      <div class="hr"></div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
      <label for="ltc_amount">Amount (Your total expenditure on travel)</label>
      <input type="number" name="ltc_amount" value="" min="0" max="100000000" step="1" class="form-control" id="ltc_amount" placeholder="Amount">
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12 form-group">
      <label for="ltc_evidence">Evidence of expenditure</label>
      <input type="text" name="ltc_evidence" value="" class="form-control alpha_dash_space" id="ltc_evidence" maxlength="200" placeholder="Evidence of expenditure">
      <em>For example: Flight Ticket, Railway Ticket, etc </em>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
      <br>
      <a class="gray-btn btnPrevious">Back</a> &nbsp; <a class="green-btn btnNext">Next</a>
    </div>
  </div>
<?php endif; ?>