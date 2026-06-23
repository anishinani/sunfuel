<?php
/**
 * Create Deposit Page
 * @author ThinkxSoftware
 */
include_once '../templates/SecurePageHeader.php';
include_once '../templates/Components.php';

if (!can('create-deposits')) {
    echo "<script>window.open('../Errors/unAuthorized.php','_self')</script>";
}

startContent();

breadCrumbs(['title' => 'Create New Deposit', 'sub_title' => 'Create', 'previous' => 'Deposits', 'previous_action' => './index.php']);
?>

<style>
    .form-control:focus {
        border-color: #FF6B35;
        box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
    }
</style>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-plus-circle"></i> Deposit Information
                </h3>
                <div class="card-tools">
                    <a href="index.php" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Back to Deposits
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form id="depositForm" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="merchantCode" class="form-label">Merchant Code</label>
                                <input type="text" class="form-control" id="merchantCode" name="merchantCode"
                                       placeholder="Enter merchant code" required>
                                <small class="form-text text-muted">Enter the fuel station merchant code</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="station" class="form-label">Fuel Station</label>
                                <input type="text" class="form-control" id="station" name="station"
                                       placeholder="Station name will appear here" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bankname" class="form-label">Bank Name</label>
                                <input type="text" class="form-control" id="bankname" name="bankname" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="bankbranch" class="form-label">Bank Branch</label>
                                <input type="text" class="form-control" id="bankbranch" name="bankbranch" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="accountname" class="form-label">Account Name</label>
                                <input type="text" class="form-control" id="accountname" name="accountname" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="accountnumber" class="form-label">Account Number</label>
                                <input type="text" class="form-control" id="accountnumber" name="accountnumber" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" id="currentFloatSection" style="display: none;">
                        <label class="form-label">Current Float</label>
                        <div class="alert alert-info" id="currentFloat">
                            <strong>Current Float:</strong> <span id="floatAmount">shs 0</span>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount" class="form-label">Deposit Amount</label>
                                <input type="number" class="form-control" id="amount" name="amount"
                                       placeholder="Enter amount without commas" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="depositedBy" class="form-label">Deposited By</label>
                                <input type="text" class="form-control" id="depositedBy" name="depositedBy"
                                       placeholder="Enter depositor name" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Enter deposit description (optional)"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="receipt" class="form-label">Receipt Image</label>
                        <input type="file" class="form-control" id="receipt" name="receipt" accept="image/*">
                        <small class="form-text text-muted">Upload receipt image (JPG, PNG, GIF)</small>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Create Deposit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
endContent();
include_once '../templates/footer.php';
?>

<script>
$(document).ready(function() {
    $('#merchantCode').on('blur', function() {
        var merchantCode = $(this).val();
        if (merchantCode) {
            $.ajax({
                url: './fetchstation.php',
                method: 'POST',
                data: { action: 'fetch', merchantCode: merchantCode },
                dataType: 'json',
                success: function(response) {
                    if (response && response.length > 0) {
                        var station = response[0];
                        $('#station').val(station.fuelStationName);
                        $('#bankname').val(station.bankName);
                        $('#bankbranch').val(station.bankBranch);
                        $('#accountname').val(station.accountName);
                        $('#accountnumber').val(station.accountNumber);
                        $('#floatAmount').text('shs ' + new Intl.NumberFormat().format(station.currentAmount));
                        $('#currentFloatSection').show();
                    } else {
                        alert('Merchant code not found');
                        clearFields();
                    }
                },
                error: function() {
                    alert('Error fetching station details');
                    clearFields();
                }
            });
        }
    });

    $('#depositForm').on('submit', function(e) {
        e.preventDefault();

        var formData = new FormData(this);
        formData.append('addDeposit', '1');

        $.ajax({
            url: './store.php',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                if (response.message === 'success') {
                    alert('Deposit created successfully!');
                    window.location.href = 'index.php';
                } else {
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                alert('Error creating deposit');
            }
        });
    });

    function clearFields() {
        $('#station').val('');
        $('#bankname').val('');
        $('#bankbranch').val('');
        $('#accountname').val('');
        $('#accountnumber').val('');
        $('#currentFloatSection').hide();
    }
});
</script>

<?php endPage(); ?>
