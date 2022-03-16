<style>
    .modal-header{
        padding: 0.2rem 1.5rem;
    }
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

    .selectBox {
        position: relative;
    }

    .selectBox select {
        width: 100%;
        font-weight: bold;
    }

    .overSelect {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
    }

    #checkboxes {
        display: none;
    }

    #checkboxes label {
        display: block;
    }

    #checkboxes label:hover {
        background-color: #1e90ff;
        color:#fff;
    }

    .form-checkbox {
        position: absolute;
        left: 5%;
        background: #fff;
        width: 90%;
        padding: 10px;
        border: 1px solid #000;
        padding-top: 30px;
        z-index: 99;
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
    #areaChecked {
        position: absolute;
    }
    /* .form-area {
        display: none !important;
    } */

    .areaChecked {
        top: 34px;
        position: absolute;
        left: 12px;
        width: 93%;
        height: 55px;
        overflow-x: auto;
        overflow-y: auto;
        border: 1px solid #d1d3e2;
        background: #fff;
        padding: 5px;
        font-size: 13px;
        border-radius: 0 0 .35rem .35rem;
    }

    .btnCloseCheckboxArea {
        position: absolute;
        right: 18px;
        top: 1px;
        z-index: 20;
        border-radius: 0;
        width: 25px;
        height: 25px;
        /* padding: 5px; */
        line-height: 0;
        z-index: 100;
    }
</style>

<div class="modal fade" id="modalStaff" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <label for="CustomerID">Staff ID<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input list="listCustomerIDForm" class="form-control form-popup input" id="StaffID" autocomplete="off" maxlength="10" />
                            <span id="spanStaffID" class="form-text form-popup" style="display:none"></span>
                            <i class="fas fa-times-circle" id="clearInputID"></i>
                        </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 id-helper">
                            <p>(Staff ID can not be more than 10 characters long)</p>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="CustomerName">Staff Name<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input list="listCustomerNameForm" class="form-control form-popup input" style="padding-right: 25px" id="Name" maxlength="50" />
                            <i class="fas fa-times-circle" id="clearInputName"></i>
                        </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <p>(Staff Name can not be more than 50 characters long)</p>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="Password">Password<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-popup input" id="Password" autocomplete="new-password" maxlength="6"  minlength="6" />
                            <i class="fas fa-times-circle" id="clearInputPassword"></i>
                        </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9">
                            <p>(The password must be 6 characters long, must contain letters (uppercase and lowercase) and digits)</p>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-3">
                            <label for="Position">Position<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <?php
                                $positions = $this->Common->getPositionByLogin();
                            ?>
                            <select name="" class="form-control" id="Position">
                                <option value="-1"></option>
                                <?php foreach($positions as $position) :?>
                                <option value="<?=$position?>"><?=$position?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1">
                        <div class="col-sm-3">
                            <label for="Region">Region<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <select name="" class="form-control" id="Region">
                                <option value=""></option>
                                <?php foreach($this->Common->getAreaByPosition() as $key => $area): ?>
                                    <option value="<?=$key?>"><?=$area?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-4 form-area">
                        <div class="col-sm-3">
                            <label for="Position">Area<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-9 selectBox" onclick="showCheckboxes()">
                            <select class="form-control multile-select">
                                <option></option>
                            </select>
                            <div class="overSelect"></div>
                            <div class="areaChecked"></div>
                            <input type="hidden" class="valuesChecked">
                        </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9" id="checkboxes">
                            <?php echo $this->Form->button('X',['class' => 'btn btn-danger btnCloseCheckboxArea']); ?>
                            <div class="form-checkbox multile-checked-area">
                                <?php foreach ($listArea as $area): ?>
                                <label><input type="checkbox" name="Area" value="<?php echo $area->AreaID; ?>" /><span><?php echo '【' .$area->AreaID. '】' .$area->Name; ?></span></label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text" style="margin-top:33px">
                        <div class="col-sm-3">
                            <label for="Title">Title</label>
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control form-popup input" style="padding-right: 25px" id="Title" />
                            <i class="fas fa-times-circle" id="clearInputTitle"></i>
                        </div>
                    </div>

                </div>
                <div hidden>
                    <input type="hidden" id="id_staff" value="">
                </div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" style="width:100px" class="btn btn-secondary btn-none" data-toggle="tooltip" title="Hold and Move" id="btnHoldMove"><i class="fas fa-arrows-alt"></i></button> -->
                <button type="button" style="width:100px" class="btn btn-secondary" data-dismiss="modal">Back</button>
                <button type="submit" style="width:100px" class="btn btn-primary" id='btnSubmitStaff'>Submit</button>
            </div>
        </div>
    </div>
</div>

<script>
    var expanded = false;

    function showCheckboxes() {
        var checkboxes = document.getElementById("checkboxes");
        if (!expanded) {
            checkboxes.style.display = "block";
            expanded = true;
        } else {
            checkboxes.style.display = "none";
            expanded = false;
        }
        // $('#areaChecked').html('')
        // $("input:checkbox[name=Area]:checked").each(function(){
        //     $('#areaChecked').append($(this).val())
        // });
    }
</script>
