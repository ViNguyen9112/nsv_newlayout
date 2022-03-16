<style>
    .modal-header{
        padding: 0.2rem 1.5rem;
    }
    .modal-footer button {
        width:80px !important;
    }
    /* Chrome, Safari, Edge, Opera */
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    /* Firefox */
    input[type=number] {
        -moz-appearance: textfield;
    }

    .form-text {
        width: 100%;
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1;
        color: #6e707e;
    }
</style>

<div class="modal fade" id="modalCustomer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="CustomerID">Customer ID<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <input list="listCustomerNameForm" class="form-control form-popup input-customer" id="CustomerID" maxlength="10"/>
                            <span id="spanCustomerID" class="form-text form-popup" style="display:none"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="CustomerName">Customer Name<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <input list="listCustomerNameForm" class="form-control form-popup input-customer" id="Name" maxlength="100"/>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Area">Area<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <select id="AreaID" class="form-control form-popup input-customer">
                                <option></option>
                                <?php foreach ($listArea as $each): ?>
                                    <option data-region="<?php echo $each->Region?>" value="<?php echo $each->AreaID?>"><?php echo '【' .$each->AreaID. '】' .$each->Name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Region">Region</label>
                        </div>
                        <div class="col-sm-8">
                            <select name="" class="form-control" id="Region" disabled>
                                <option value=""></option>
                                <?php foreach($region_opt as $key => $area): ?>
                                    <option value="<?=$key?>"><?=$area?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Address">Address</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" style="width:100%" class="form-control form-popup input-customer" id="Address" maxlength="100"/>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="ImplementDate">Implement Date</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" style="width:100%" class="form-control form-popup input-customer" readonly id="ImplementDate" maxlength="100"/>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="PositionNo">Position No</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" style="width:100%" class="form-control form-popup input-customer" id="PositionNo" maxlength="100"/>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="TaxCode">TaxCode</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" style="width:100%" class="form-control form-popup input-customer" id="TaxCode" maxlength="15"/>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Longitude">Longitude</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="number" style="width:100%" class="form-control form-popup input-customer" id="Longitude" maxlength="255"/>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Latitude">Latitude</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="number" style="width:100%" class="form-control form-popup input-customer" id="Latitude" maxlength="255"/>
                        </div>
                    </div>
                </div>
                <div hidden>
                    <input type="hidden" id="id_customer_form" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" id='btnMapCustomer' disabled="true">Map</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary" id='btnSubmitCustomer'>Submit</button>
            </div>
        </div>
    </div>
</div>
