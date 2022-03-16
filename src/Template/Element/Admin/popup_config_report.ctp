<style>
    .modal-header {
        padding: 0.2rem 1.5rem;
    }

    p {
        margin-top: 0;
        margin-bottom: 0;
        font-size: 11px;
        font-weight: bold;
        font-style: italic;
    }

    .selectBox select {
        width: 100%;
        font-weight: bold;
    }

    #checkboxes label {
        display: block;
    }

    #checkboxes label:hover {
        background-color: #1e90ff;
        color: #fff;
    }

    .form-checkbox label {
        margin-bottom: 5px;
    }

    .form-checkbox input {
        margin-right: 3px;
    }

    .form-checkbox span {
        font-size: 15px;
    }
</style>
<div class="modal fade" id="modalReportReport" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row">
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="ID">Report ID<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control form-popup inputDetailCode" autocomplete="off" maxlength="10"/>
                        </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 id-helper">
                            <p>(Report ID can not be more than 10 characters long)</p>
                        </div>
                    </div>                    
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="ID">English</label>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control form-popup inputDeName2"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="ID">Japanese</label>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control form-popup inputDeName3"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="ID">Native language</label>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control form-popup inputDeName1"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-3">
                            <label for="Position">Category</label>
                        </div>
                        <div class="col-sm-9">
                            <select name="" class="form-control inputCatCode">
                                <option value=""></option>
                                <?php foreach ($category_opt as $key => $each): ?>
                                    <option value="<?php echo $key ?>"><?php echo $each ?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">

                        </div>
                        <div class="col-sm-9" style="display: inline-flex;">
                            <input type="checkbox" name="ProcessFlag" id="ProcessFlag" class="inputProcessFlag" />
                            <label for="ProcessFlag" style="margin-left: 5px">Process</label>
                        </div>
                    </div>
                </div>
                <div hidden>
                    <input type="hidden" class="inputID" value="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" style="width:100px" class="btn btn-secondary" data-dismiss="modal">Back</button>
                <button type="button" style="width:100px" class="btn btn-primary" id='btnSubmitType'>Submit</button>
            </div>
        </div>
    </div>
</div>
