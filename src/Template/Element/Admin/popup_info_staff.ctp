<style>
p {
    margin-top: 0;
    margin-bottom: 0;
    font-size: 11px;
    font-weight: bold;
    font-style: italic;
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

<div class="modal fade" id="modalInfoStaff" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="StaffID">Staff ID:</label>
                        </div>
                        <div class="col-sm-9">
                            <span id="StaffID" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="Name">Staff Name:</label>
                        </div>
                        <div class="col-sm-9">
                            <span id="Name" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-3">
                            <label for="Position">Position:</label>
                        </div>
                        <div class="col-sm-9">
                            <span id="Position" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-3">
                            <label for="Area">Area:</label>
                        </div>
                        <div class="col-sm-9">
                            <span id="InfoArea" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-3">
                            <label for="Title">Title:</label>
                        </div>
                        <div class="col-sm-9">
                            <span id="InfoTitle" class="form-text form-popup"></span>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-3">
                            <label for="Region">Region:</label>
                        </div>
                        <div class="col-sm-9">
                            <span id="InfoRegion" class="form-text form-popup"></span>
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
