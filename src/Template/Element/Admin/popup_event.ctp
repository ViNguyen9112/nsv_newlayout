<style>
    .modal-body {
        color:#7386D5 !important;
        height:63vh;
        overflow-y:auto;
    }

    .sec-report {
        border: 2px solid #ccc;
        padding: 5px;
        height: 45vh;
        overflow-y: auto;
    }
    legend{
        font-size: 13px;
        color: #5d5757;
        font-weight: bold;
    }

    .select-type-report {
        color:#000 !important;
        margin-bottom: 1rem;
    }

    #checkreport label {
        line-height: 17px;
        color: rgb(110, 111, 117);
    }

    .checkbox-report{
        margin-right:3px;
    }

    #ContentReport {
        border: 2px solid #ccc;
        background:#fff;
        color:rgb(110, 111, 117);
    }

    #NoteReport {
        color:rgb(110, 111, 117);
        width: 100% !important;
        padding: 4px;
    }
    .delete-image {
        position: absolute;
        top: -6px;
        right: -8px;
        color: #fff;
        background: #e84f4f;
        padding-left: 5px;
        padding-right: 5px;
        border-radius: 10px;
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
    .scrollBtn{
        position: absolute;
        bottom: 2vh;
        left: 5vw;
        /* z-index: 9999; */
        font-size: 30px;
        border: none;
        outline: none;
        /* background-color: #052852; */
        background: #fff;
        color: #37b1ab;
        cursor: pointer;
        /* padding: 10px 8px 8px 8px; */
        /* border-radius: 4px; */
        transition: all .2s;
    }
    .fileinput-button {
        width: 140px;
        line-height: 2;
        height: 20px;
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

<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="application-leave-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
    <form action="" class="ajax-form-report">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#7386D5 !important"><span id="IdCustomer"></span> <span id="CustomerName"></span></h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body" id="modalEventBody">
                <table class="table table-borderless">
                    <thead>
                    </thead>
                    <tbody id="table-modal">
                    <tr>
                        <td class='table-th w-35'>Start time</td>
                        <td id="Starttime" ></td>
                        <td class='table-th w-35' >End Time</td>
                        <td id="Endtime"></td>
                    </tr>
                    <tr>
                        <td id="Report" colspan="4">Report
                        </td>
                    </tr>
                    </tbody>
                </table>

                <div class="sec-report">
                    <fieldset id="roles">
                        <legend>Type:</legend>
                        <span class="error-type" style="color: red;font-size: 11px;display: block"></span>
                        <select class="select-type-report" name="Type" id="TypeReport"></select>
                        <p id="textType" style="color:#6e6f75"></p>
                    </fieldset>
                    <div class="report-check">
                        <fieldset id="checkreport"></fieldset>
                        <fieldset id="comment">
                            <legend>Note</legend>
                            <span class="error-note" style="color: red;font-size: 11px"></span>
                            <textarea id="NoteReport" rows="5" placeholder="Please enter your report..."></textarea>
                        </fieldset>
                    </div>
                    <div class="not-check">
                        <span class="error-note" style="color: red;font-size: 11px"></span>
                        <fieldset>
                            <textarea id="ContentReport" style="width:100%;padding:5px 6px" rows="10" placeholder="Please enter your report..."></textarea>
                        </fieldset>
                    </div>

                    <!-- START ATTACHED PHOTO -->
                    <div id="previewImages" class="files-preview row my-2" style="display: none;"></div>
                    <input type="hidden" id="IDReport">
                    <input type="hidden" id="IDTimeCard">
                    <input type="hidden" id="TypeSubmit">
                    <div class="attached-picture">
                        <span class="btn btn-primary fileinput-button">
                            <i class="glyphicon glyphicon-plus"></i>
                            <span id="btnAddImage" style="font-size: 12px;">+ Attached picture</span>
                        </span>
                        <!-- <input type="file" id="multiple_images" multiple accept="image/*" style="display:none" /> -->
                        <input type="file" id="multiple_images" multiple accept="image/*;capture=camera" style="display:none" />
                        <input type="hidden" id="currentIndexFiles" value="-1">
                        <input type="hidden" id="statusProgress" value="">
                    </div>
                    <!-- END ATTACHED PHOTO -->

                </div>

                <input type="hidden" id="IDReport">
                <input type="hidden" id="TypeSubmit">
                <input type="hidden" id="TypeCode">
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" data-toggle="modal" data-checkIn="" data-checkout="" id="Picture">
                    <i class="material-icons">&#xe420;</i>
                </a>
                <button id="MAP" class="btn btn-success">MAP</button>
                <button id="event-edit" class="btn btn-info">Edit</button>
                <button id="event-save" class="btn btn-info" style="display: none">Save</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                <button id="event-loading" class="btn btn-info" style="display: none"><div class="loader" style="margin-left: 5px;"></div></button>
                <input type="hidden" name="report_id" id="report_id">
                <input type="hidden" id="timecard_id">
                <!-- flagEdit:
                    dis: disabled edit
                    ena: enabled edit
                 -->
                <input type="hidden" id="flagEdit" value="dis">

                <button id="topBtn" class="scrollBtn" title="Go to top">
                    <i class="fas fa-arrow-circle-up"></i>
                </button>
                <button id="downBtn" class="scrollBtn" title="Go to bottom">
                    <i class="fas fa-arrow-circle-down"></i>
                </button>

            </div>
        </div>
    </form>
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
