<?php
// Simple helpers
function e($v){ return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }
$hra = $data['hra'] ?? [];
$ltc = $data['ltc'] ?? [];
$loans = $data['loans'] ?? [];
$deductions = $data['deductions'] ?? [];
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Form 12BB</title>
<style>
    *{ box-sizing: border-box; }
    body{ font-family: DejaVu Sans, Arial, sans-serif; font-size: 12px; color:#111; }
    h1,h2,h3{ margin: 8px 0; text-align:center; }
    .mt-8{ margin-top:8px; }
    .mb-12{ margin-bottom:12px; }
    table{ width:100%; border-collapse:collapse; margin:10px 0 16px; }
    th,td{ border:1px solid #333; padding:6px 8px; vertical-align:top; }
    th{ text-align:left; }
    .meta td{ border:none; padding:2px 0; }
    .section{ font-weight:bold; margin-top:16px; }
    .small{ font-size:11px; color:#555; }
</style>
</head>
<body>

<h2>FORM NO. 12BB <span class="small">(Rule 26C)</span></h2>
<p style="text-align:center">Statement showing particulars of claims by an employee for deduction of tax under section 192</p>

<table class="meta">
    <tr><td><b>Name of employee:</b> <?= e($data['employee_name'] ?? '') ?></td></tr>
    <tr><td><b>Father's Name:</b> <?= e($data['father_name'] ?? '') ?></td></tr>
    <tr><td><b>Permanent Account Number (PAN):</b> <?= e($data['pan'] ?? '') ?></td></tr>
    <tr><td><b>Email:</b> <?= e($data['email'] ?? '') ?></td></tr>
    <tr><td><b>Mobile:</b> <?= e($data['mobile'] ?? '') ?></td></tr>
    <tr><td><b>Address:</b> <?= e($data['address'] ?? '') ?></td></tr>
</table>

<h3 class="section">1. House Rent Allowance (HRA)</h3>
<table>
    <thead>
        <tr>
            <th>Rent paid</th>
            <th>Landlord name</th>
            <th>Landlord address</th>
            <th>Landlord PAN</th>
            <th>Evidence/Particulars</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($hra): foreach ($hra as $row): ?>
        <tr>
            <td><?= e($row['rent_paid'] ?? '') ?></td>
            <td><?= e($row['landlord_name'] ?? '') ?></td>
            <td><?= e($row['landlord_address'] ?? '') ?></td>
            <td><?= e($row['landlord_pan'] ?? '') ?></td>
            <td><?= e($row['evidence'] ?? '') ?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="5" class="small">No HRA records</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h3 class="section">2. Leave Travel Concessions/Assistance (LTC)</h3>
<table>
    <thead><tr><th>Amount (₹)</th><th>Evidence/Particulars</th></tr></thead>
    <tbody>
        <?php if ($ltc): foreach ($ltc as $row): ?>
        <tr><td><?= e($row['amount'] ?? '') ?></td><td><?= e($row['evidence'] ?? '') ?></td></tr>
        <?php endforeach; else: ?>
        <tr><td colspan="2" class="small">No LTC records</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h3 class="section">3. Deduction of interest on borrowing (Home Loan)</h3>
<table>
    <thead>
        <tr>
            <th>Interest payable</th>
            <th>Name of lender</th>
            <th>Address of lender</th>
            <th>Lender PAN</th>
            <th>Evidence/Particulars</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($loans): foreach ($loans as $row): ?>
        <tr>
            <td><?= e($row['interest_payable'] ?? '') ?></td>
            <td><?= e($row['loan_provider'] ?? '') ?></td>
            <td><?= e($row['loan_provider_address'] ?? '') ?></td>
            <td><?= e($row['lender_pan'] ?? '') ?></td>
            <td><?= e($row['evidence'] ?? '') ?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="5" class="small">No loan records</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<h3 class="section">4. Deductions under Chapter VI-A</h3>
<table>
    <thead>
        <tr><th>Section</th><th>Type</th><th>Amount (₹)</th><th>Evidence/Particulars</th></tr>
    </thead>
    <tbody>
        <?php if ($deductions): foreach ($deductions as $row): ?>
        <tr>
            <td><?= e($row['section'] ?? '') ?></td>
            <td><?= e($row['type'] ?? '') ?></td>
            <td><?= e($row['amount'] ?? '') ?></td>
            <td><?= e($row['evidence'] ?? '') ?></td>
        </tr>
        <?php endforeach; else: ?>
        <tr><td colspan="4" class="small">No deductions</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<hr class="mt-8 mb-12">

<table>
    <tr>
        <td style="width:60%">
            <b>Verification</b><br>
            I, <?= e(($data['employee_name'] ?? '')) ?>, do hereby certify that the information given above is complete and correct.<br>
            <b>Place:</b> <?= e($data['place'] ?? '') ?><br>
            <b>Date:</b> <?= date('d-m-Y') ?>
        </td>
        <td style="width:40%">
            <b>Signature of the employee</b><br><br><br>
            <b>Full Name:</b> <?= e($data['employee_name'] ?? '') ?>
        </td>
    </tr>
</table>

</body>
</html>
