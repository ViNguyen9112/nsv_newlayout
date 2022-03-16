<style>
    #gpsMapModal .btn{
        /* padding: 3px !important;
        height: 25px !important; */
        margin-right: 30px !important;
    }
    #gpsMapModal .modal-footer{
        height: 60px !important;
    }
    #gpsMapModal .modal-footer, #gpsMapModal .modal-body, #gpsMapModal .modal-header{
        padding: 0.2rem 1.5em !important;
    }
</style>

<div class="modal fade" id="gpsMapModal" tabindex="-1" role="dialog" aria-labelledby="application-leave-modal"
     aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" style="color:#7386D5 !important">GPS</h5>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body" id="modalEventBody" style="color:#7386D5 !important">
                <div class="pt-1"></div>
                <table class="table table-borderless">
                    <thead>
                    </thead>
                    <tbody id="gps-table-modal">
                    <tr>
                        <td id="GPSCONTENT" style="height: 400px"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary customer-adjust-long-lat" adjust-customer-id="" adjust-customer-long="" adjust-customer-lat="" >Adjust</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
