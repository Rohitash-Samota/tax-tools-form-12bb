<div class="row">
    <div class="col-md-12 text-center">
        <h2>Details of Employee</h2>
        <div class="hr"></div>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="employee_name">Name <em>*</em></label>
        <input type="text" name="employee_name" value="" id="employee_name" maxlength="100" required class="form-control name" placeholder="Name" aria-required="true">
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="employee_pan">PAN <em>*</em></label>
        <input type="text" name="pan" value="" class="form-control pan_optional text-uppercase" required id="employee_pan" maxlength="10" placeholder="PAN No." aria-required="true" pattern="[A-Z]{5}[0-9]{4}[A-Z]{1}">
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="employee_father_name">Father's Name <em>*</em></label>
        <input type="text" name="father_name" value="" class="form-control name" required id="employee_father_name" maxlength="125" placeholder="Father's Name" aria-required="true">
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="place">Place<em>*</em></label>
        <input type="text" name="place" value="" class="form-control name" id="place" required maxlength="50" placeholder="Place" aria-required="true">
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="BasicDetails_mobile_no">Mobile <em>*</em></label>
        <input type="tel" inputmode="numeric" name="mobile_no" value="" class="form-control mobile_no" required id="BasicDetails_mobile_no" maxlength="10" placeholder="Mobile" aria-required="true" pattern="[6-9]{1}[0-9]{9}">
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12 form-group">
        <label for="BasicDetails_email">Email Id <em>*</em></label>
        <input type="email" name="email" value="" class="form-control" required id="BasicDetails_email" maxlength="125" placeholder="Email" aria-required="true" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
    </div>
</div>
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 form-group">
        <label for="employee_house_address">Address <em>*</em></label>
        <textarea class="form-control alpha_dash_space" rows="2" name="address" required id="employee_house_address" maxlength="200" aria-required="true"></textarea>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 text-center">
        <a class="green-btn btnNext">Next</a>
    </div>
</div>