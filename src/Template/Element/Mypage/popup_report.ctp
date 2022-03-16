<style>
.hr-form {
    width: 88%;
    margin: 10px auto;
    border: 1px solid rgba(250, 250, 250, 0.4);
}
.delete-image {
    position: absolute;
    top: -6px;
    right: -8px;
    color: #fff;
    background: #e84f4f;
    padding-left: 7px;
    padding-right: 7px;
    border-radius: 12px;
}
.item-image {
    height: 17vw;
    width: 17vw;
    background-size: cover;
    margin-bottom: 8px;
    border-radius: 10px;
}
#previewImages{
    width:100%;
}

.fileinput-button {
    width: 160px;
    line-height: 0.8 !important;
    height: 30px;
}

.scrollBtn{
    position: absolute;
    bottom: 3vh;
    left: 5vw;
    /* z-index: 9999; */
    font-size: 30px;
    border: none;
    outline: none;
    /* background-color: #052852; */
    background: #2d344e;
    color: #fff;
    cursor: pointer;
    /* padding: 10px 8px 8px 8px; */
    /* border-radius: 4px; */
    transition: all .2s;
}

.loader {
    border: 5px solid #f3f3f3;
    border-radius: 50%;
    border-top: 5px solid #3498db;
    width: 1.5rem;
    height: 1.5rem;
    -webkit-animation: spin 1s linear infinite; /* Safari */
    animation: spin 1s linear infinite;
}

/* Safari */
@-webkit-keyframes spin {
    0% { -webkit-transform: rotate(0deg); }
    100% { -webkit-transform: rotate(360deg); }
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content sign-modal-content">
            <div class="modal-body" style="height:72vh">
                <!-- REPORT -->
                <div id="div-modal" style="height:70vh;overflow-y:auto">
                    <form action="" id="survey-form" class="ajax-form-report">
                        <h1 id="title"><?php echo $data_language['report']; ?></h1>
                        <hr />
                        <fieldset id="roles">
                            <legend><?php echo $data_language['type']; ?></legend>
                            <span class="error-type" style="color: red;font-size: 11px"></span>
                            <select class="select-type-report" name="Type" id="TypeReport">
                                <option value="-1" id="onloadTypeReport"></option>
                                <?php foreach($types as $code => $type):?>
                                    <option value="<?php echo $code; ?>"><?php echo $type; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </fieldset>
                        <div class="report-check">
                            <fieldset id="checkreport"></fieldset>
                            <fieldset id="comment">
                                <legend>Note</legend>
                                <span class="error-note" style="color: red;font-size: 11px"></span>
                                <textarea id="NoteReport" rows="5" placeholder="Please enter your comment..."></textarea>
                            </fieldset>
                        </div>
                        <div class="not-check">
                            <span class="error-note" style="color: red;font-size: 11px"></span>
                            <fieldset>
                                <textarea id="ContentReport" style="width:100%;padding:5px 6px;border: 2px solid #ccc;" rows="12" placeholder="..."></textarea>
                            </fieldset>
                        </div>

                        <!-- START ATTACHED PHOTO -->
                        <div id="previewImages" class="files-preview row mb-2" style="display: none;"></div>
                        <input type="hidden" id="IDReport">
                        <input type="hidden" id="IDTimeCard">
                        <input type="hidden" id="TypeSubmit">
                        <div class="attached-picture">
                            <span class="btn btn-primary fileinput-button" style="line-height: 1;">
                                <i class="glyphicon glyphicon-plus"></i>
                                <span id="btnAddImage" style="font-size: 12px;">+ Attached picture</span>
                            </span>
                            <!-- <input type="file" id="multiple_images" multiple accept="image/*" style="display:none" /> -->
                            <input type="file" id="multiple_images" multiple accept="image/*;capture=camera" style="display:none" />
                            <input type="hidden" id="currentIndexFiles" value="-1">
                            <input type="hidden" id="statusProgress" value="">
                        </div>
                        <!-- END ATTACHED PHOTO -->

                    </form>
                </div>
            </div>
            <hr class="hr-form" />
            <div class="modal-footer" style="border-top:0px solid #fff">
                <button style="border-radius:20px" type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo $data_language['close']; ?></button>
                <button onclick="topFunction()" id="topBtn" class="scrollBtn" title="Go to top">
                    <i class="fas fa-arrow-circle-up"></i>
                </button>
                <button onclick="bottomFunction()" id="downBtn" class="scrollBtn" title="Go to bottom">
                    <i class="fas fa-arrow-circle-down"></i>
                </button>
                <button style="border-radius:20px" type="button" class="btn btn-primary" id="SubmitReport">
                    <span class="text-submit"><?php echo $data_language['submit']; ?></span>
                    <div class="loader" style="display:none"></div>
                </button>
            </div>
        </div>
    </div>
</div>

<script id="tplPreviewImage" type="text/template">
    <div class="col-3">
        <img src="__src__" alt="" data-id-select="__id-select__" data-id="__id__" class="item-image image-select">
        <span class="delete-image times-select" data-id-select="__id-select__" data-id="__id__"><i class="fas fa-times"></i></span>
    </div>
</script>

<script id="tplImageUploaded" type="text/template">
    <div class="col-3">
        <img src="__src__" alt="" data-id="__id__" data-id-uploaded="__id-uploaded__" class="item-image image-uploaded">
        <span class="delete-image times-uploaded" data-id="__id__"><i class="fas fa-times"></i></span>
    </div>
</script>
