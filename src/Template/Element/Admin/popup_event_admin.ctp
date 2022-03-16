<style>
    .img-flag{
        width: 30px !important;
        height: 15px;
    }
    #eventModal .modal-content{
        height:750px;
        width: 550px;
    }
</style>
<div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Report</h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> -->
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <thead>
                    </thead>
                    <tbody id="table-event-modal">
                    <tr>
                        <th class='table-th w-25'>StaffID</th>
                        <td id="StaffID" class="StaffID-Report">P0001</td>
                        <th class='table-th w-25'>Staff Name</th>
                        <td id="StaffName">P0001</td>
                    </tr>
                    <tr>
                        <th class='table-th'>Date</th>
                        <td id="date" >2020-10-26</td>
                        <th class='table-th'>Time</th>
                        <td id="time">17:00:00</td>
                    </tr>
                    <tr>
                        <th class='table-th'>Customer ID</th>
                        <td id="CustomerID" class="CustomerID-Report">0001</td>
                        <th class='table-th'>Customer Name</th>
                        <td id="CustomerName">0001</td>
                    </tr>
                    <tr>
                        <th class='table-th' colspan="4" style="padding:0 !important">Report
                            <span class="flags-report">
                                <?php echo $this->Html->image('lang/jp.png', ['alt' => 'Japan','class'=>'img-flag report-jp']); ?>
                                <?php echo $this->Html->image('lang/en.png', ['alt' => 'English','class'=>'img-flag report-en']); ?>
                                <?php echo $this->Html->image('lang/vn.png', ['alt' => 'Vietnam','class'=>'img-flag report-vn']); ?>
                            </span>
                        </th>
                    </tr>
                    </tbody>
                </table>

                <div class="content-report">
                    <fieldset class="fieldset-report" id="roles">
                        <legend class="legend-report" class="legend-report">Type Report: <span class="type-report"></span></legend>
                    </fieldset>
                    <fieldset>
                        <textarea name="report_event" class="textarea-report" readonly rows="10" max-rows="10" ></textarea>
                    </fieldset>
                </div>

                <div class="form-report">
                    <fieldset class="fieldset-report" id="roles">
                        <legend class="legend-report" class="legend-report">Type Report: <span class="type-report"></span></legend>
                    </fieldset>

                    <fieldset class="fieldset-report" id="checkreport">
                    </fieldset>

                    <fieldset class="fieldset-report" id="comment">
                        <legend class="legend-report">Note</legend>
                        <textarea class="textarea-report" rows="10" readonly></textarea>
                    </fieldset>
                </div>

                <!-- START ATTACHED PHOTO -->
                <legend class="legend-report">Attached Picture</legend>
                <div id="previewImages" class="files-preview row my-2"></div>
                <input type="hidden" id="currentIndexFiles" value="-1">
                <input type="hidden" id="statusProgress" value="">
                    <!-- END ATTACHED PHOTO -->

            </div>
            <div class="modal-footer">
                <button id="topBtn" class="scrollBtn" title="Go to top">
                    <i class="fas fa-arrow-circle-up"></i>
                </button>
                <button id="downBtn" class="scrollBtn" title="Go to bottom">
                    <i class="fas fa-arrow-circle-down"></i>
                </button>
                <a href="javascript:void(0)" data-toggle="modal" data-checkIn="" data-checkout="" id="Picture">
                    <i class="material-icons">&#xe420;</i>
                </a>
                <button type="button" class="btn btn-default" id="close-report">Back</button>
            </div>
        </div>
    </div>
</div>

<!-- translate -->
<input type="hidden" id="typeEN">
<input type="hidden" id="typeVN">
<input type="hidden" id="typeJP">

<input type="hidden" id="reportEN">
<input type="hidden" id="reportJP">
<input type="hidden" id="reportVN">

<script id="tplImageUploaded" type="text/template">
    <div class="col-2">
        <img src="__src__" alt="" data-id="__id__" data-id-uploaded="__id-uploaded__" class="item-image image-uploaded">
    </div>
</script>
