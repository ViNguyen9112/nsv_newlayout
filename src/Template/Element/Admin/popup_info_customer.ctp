<style>
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

<div class="modal fade" id="modalInfoCustomer" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="CustomerID">Customer ID:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="CustomerID" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Name">Customer Name:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="Name" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="AreaName">Area:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="AreaName" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="ImplementDate">Implement Date:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="ImplementDate" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="PositionNo">Position No:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="PositionNo" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="TaxCode">TaxCode:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="TaxCode" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Longitude">Longitude:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="Longitude" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Latitude">Latitude:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="Latitude" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-4">
                            <label for="Address">Address:</label>
                        </div>
                        <div class="col-sm-8">
                            <span id="Address" class="form-text form-popup"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
