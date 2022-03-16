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
<div class="modal fade" id="modalReportType" tabindex="-1" role="dialog" aria-hidden="true">
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
                            <label for="ID">Type ID<span style="color:red">*</span></label>
                        </div>
                        <div class="col-sm-9">
                            <input class="form-control form-popup inputTypeCode" autocomplete="off" maxlength="10"/>
                        </div>
                        <div class="col-sm-3"></div>
                        <div class="col-sm-9 id-helper">
                            <p>(Type ID can not be more than 10 characters long)</p>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="ID">English</label>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control form-popup inputType2"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="ID">Japanese</label>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control form-popup inputType3"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="ID">Native language</label>
                        </div>
                        <div class="col-sm-9">
                            <textarea class="form-control form-popup inputType1"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text">
                        <div class="col-sm-3">
                            <label for="ID">Color of Type</label>
                        </div>
                        <div class="col-sm-9">
                             <select name="colorpicker-glyphicons">
                          <option value="#7bd148">Green</option>
                          <option value="#5484ed">Bold blue</option>
                          <option value="#a4bdfc">Blue</option>
                          <option value="#46d6db">Turquoise</option>
                          <option value="#7ae7bf">Light green</option>
                          <option value="#51b749">Bold green</option>
                          <option value="#fbd75b">Yellow</option>
                          <option value="#ffb878">Orange</option>
                          <option value="#ff887c">Red</option>
                          <option value="#dc2127">Bold red</option>
                          <option value="#dbadff">Purple</option>
                          <option value="#e1e1e1">Gray</option>
                        </select>
                        </div>
                    </div>

                    <div class="col-sm-12 row d-flex align-items-center mb-1 input-text" style="">
                        <div class="col-sm-3">
                            <label for="ID">Image of Type</label>
                        </div>
                        <div class="col-sm-9">
                            <div class="heroHeaderImgCreateArea">
                                <button class="bkNewsBtn eng upload-btn" type="button" name="main_image">SELECT IMG</button>
                                <?php echo $this->Form->file('TBLMRepType.TypeImage', array('id' => 'upload', 'class' => 'hidden', 'accept' => 'image/jpeg,image/png,.jpeg,.jpg,.png')); ?>
                                <div class="main-image-response">
                                    <span id="txt-name-main-image" class="eng"></span>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <button id="init" class="btn btn-primary" style="display: none">Init the jQuery plugin</button>
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
