<?php

/**
 * Header of the application
 * @author ThinkxSoftware
 * **/
include_once '../templates/SecurePageHeader.php';
/***
 * reusable components to inject code into the template
 * */
include_once '../templates/Components.php';

if (!can('create-deposit')) header('Location:../Errors/unAuthorized.php');

startContent();

// code here

$results  =  $dbAccess->select("fuelstation", ["fuelStationId", "fuelStationName"]);


breadCrumbs(['title' => 'Make Deposit', 'sub_title' => 'Make Deposit', 'previous' => 'Deposits', 'previous_action' => './index.php']);


?>


<div id="noMerchantCode">

</div>
<div class="row">
    <!--form add user -->
    <div class="register-box m-auto col-md-8">
        <div class="card card-outline card-primary">

            <div class="card-body">
                <p class="login-box-msg">Add New Deposit</p>

                <div class="form-group mb-3">
                    <label for="">Enter Merchant Code</label>
                    <input type="text" name="amount" id="merchant" required class="form-control" placeholder="enter merchant code" />

                </div>

                <form method="POST" id="form" enctype="multipart/form-data">



                    <!--bankname-->
                    <div class="form-group mb-3">
                        <label for="">Bank Name</label>
                        <input type="text" name="bankname" id="bankname" disabled required class="form-control" placeholder="enter bank anmes" />

                    </div>
                    <!--banknames-->
                    <!--Bank Branch-->
                    <div class="form-group mb-3">
                        <label for="">Bank Branch</label>
                        <input type="text" name="bankbranch" disabled id="bankbranch" required class="form-control" placeholder="enter station Bank Branch" />
                    </div>
                    <!--Bank Branch-->
                    <!--Account Name-->
                    <div class="form-group mb-3">
                        <label for="">Account Name</label>
                        <input type="text" name="accountname" id="accountname" disabled required class="form-control" placeholder="enter station Account Name" />
                    </div>
                    <!--Account Name-->
                    <!--Account Number-->
                    <div class="form-group mb-3">
                        <label for="">Account Number</label>
                        <input type="text" name="accountnumber" id="accountnumber" disabled required class="form-control" placeholder="enter station Account Number" />
                    </div>
                    <!--Account Number-->
                    <!--fuel station-->
                    <div class="form-group mb-3">
                        <div class="form-group">
                            <label for="my-select">Fuel Station</label>
                            <input type="text" name="stationname" id="station" disabled required class="form-control" placeholder="station name" />

                        </div>
                        <input class="form-control" type="hidden" name="stationId" id="stationId">

                    </div>
                    <!--fuel station-->

                    <div class="form-group mb-3">
                        <label for="">Amount</label>
                        <input type="number" name="amount" id="amount" disabled required class="form-control" placeholder="enter amount without commas" />

                    </div>

                    <!--person-->
                    <div class="form-group mb-3">
                        <label for="">Deposited By</label>
                        <input type="text" name="name" id="depositedBy" disabled required class="form-control" placeholder="enter names" />

                    </div>
                    <!--person-->


                    <!--Receipt-->
                    <div class="form-group mb-3">
                        <label for=""> Upload Receipt</label>
                        <input type="file" name="receipt" id="file" disabled required class="form-control" accept="image/*" />
                    </div>
                    <!--Receipt-->




                    <!-- /.col -->
                    <div class="col-12">

                        <button type="submit" class="style_button" name="addDeposit" id="save">Confirm Deposit</button>
                        <!-- <img src="/creditpluswebapp/dist/img/loader.gif" width="80px" height="80px" /> -->
                    </div>
                    <!-- /.col -->
            </div>

            </form>
            <div class="co1-12"></div>
            <!-- <button id="button">Register Agent</button> -->

        </div>



    </div>
    <!-- /.form-box -->
</div><!-- /.card -->



<?php
endContent();

/**
 * footer of the application
 * */
include_once '../templates/footer.php';

/**
 * custom page javascript
 * **/

?>
<script src="text/javascript">
    const spinner = $('#loader');
    $(function() {
        $("#button").click(function() {
            //spinner.show();
            alert("clicked");
        })
    })
</script>

<script>
    $(document).ready(() => {
        $('#merchant').keyup(() => {
            //alert("here");
            var merchantCode = $("#merchant").val();
            //alert(merchantCode)
            //fetch stations

            if (merchantCode == "") {
                $("#noMerchantCode").addClass("none");
            }
            $.ajax({
                url: "fetchstation.php",
                method: 'post',
                dataType: "json",
                data: {
                    action: "fetch",
                    merchantCode: merchantCode
                },
                beforeSend: function() {

                    //$("#fuelStationId").html('<option disabled selected>select fuel station</option>');

                },
                success: function(data) {
                    // $("#fuelStationId").attr('disabled', false);
                    $.each(data, function(key, value) {
                        if (value == "invalidMerchantCode") {
                            $("#noMerchantCode").removeClass("none");
                            //alert("here");
                            $("#noMerchantCode").html('<div class="alert alert-danger"><strong > Whoops! </strong> Invalid Merchant Code.<br><br></div>');
                            $("#bankname").val("");
                            $("#bankbranch").val("");
                            $("#accountname").val("");
                            $("#accountnumber").val("");
                            $("#stationId").val("");
                            $("#station").val("");
                            $("amount").attr("disabled", true);
                            $("#depositedBy").attr("disabled", true);
                            $("#file").attr("disabled", true)
                        } else {
                            //alert("here");
                            $("#noMerchantCode").addClass("none");
                            console.log(value);
                            //$("#fuelStationId").append('<option value=' + value.fuelStationId + '>' + value.fuelStationName + '</option>');
                            $("#bankname").val(value.bankName);
                            $("#bankbranch").val(value.bankName);
                            $("#accountname").val(value.bankBranch);
                            $("#accountnumber").val(value.AccName);
                            $("#stationId").val(value.fuelStationId);
                            $("#station").val(value.fuelStationName);
                            $("#amount").attr("disabled", false);
                            $("#depositedBy").attr("disabled", false);
                            $("#file").attr("disabled", false)

                        }
                    });

                }

            })
            //fetch stations
        });
    });
</script>
<script>
    $('#form').on('submit', function(e) {
        e.preventDefault();
        var form = this;
        $.ajax({
            url: "./store.php",
            method: $(form).attr('method'),
            data: new FormData(form),
            processData: false,
            dataType: 'json',
            contentType: false,
            beforeSend: function() {
                // $(form).find('span.error-text').text('');
                $("#save").html("saving...")
                $("#save").attr("disabled", true);
            },
            success: function(data) {
                 alert("deposit made successfully");
                //alert(data);
                $("#save").html("Confirm Payment")
                $("#save").attr("disabled", false);
                location.href = "./index.php";
            },
            error: function(data) {
                 console.log("=======data======")
                console.log(data)
                console.log("======data=======")
                if (data.message == 'success') {
                    alert("deposit made successfully");
                    $("#save").html("Confirm Payment")
                    $("#save").attr("disabled", false);
                    location.href = "./index.php";
                } else {

                    alert("deposit made successfully");
                    $("#save").html("Confirm Payment")
                    //location.reload();
                }

            }
        });
    });
</script>


<?php
endPage();
