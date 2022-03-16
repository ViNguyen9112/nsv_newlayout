<div class="modal fade" id="visitModal" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Visit log</h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> -->
            </div>
            <div class="modal-body" id="visit-log">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTableReport" width="100%" cellspacing="0">
                        <thead>
                        <tr>
                            <td class="border-right-0 border-top-0 border-bottom-0">
                                <?php
                                echo $this->Form->select(
                                    'sTimeVisit',
                                    $timeVisits,
                                    ['class' => "psTimeVisit w-150", "empty" => true]
                                );
                                ?>
                            </td>
                            <td class="border-0">
                                <?php
                                echo $this->Form->select(
                                    'sStaffID',
                                    $staffIds,
                                    ['class' => "psStaffID w-150", "empty" => true]
                                );
                                ?>
                            </td>
                            <td class="border-0"></td>
                            <td class="border-left-0 border-top-0 border-bottom-0"></td>
                        </tr>
                        <tr>
                            <th>Date</th>
                            <th>Staff ID</th>
                            <th>Staff Name</th>
                            <th>Report</th>
                        </tr>
                        </thead>
                        <tbody id="tblReport">
                        <!--dataTable-->
                        </tbody>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
