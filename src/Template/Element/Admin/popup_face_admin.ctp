<style>
    #faceModal .modal-content{
        height:450px;
        width: 400px;
    }
    #faceModal img{
        max-height: 220px !important;
    }
    .img-zoom{
        cursor:pointer;
    }
</style>

<div class="modal fade" id="faceModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width: 400px;">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">Face Image</h4>
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> -->
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <thead>
                    </thead>
                    <tbody id="table-modal">
                    <tr>
                        <th class='table-th text-center'>Check In</th>
                        <th class='table-th text-center'>Check Out</th>
                    </tr>
                    <tr>
                        <td id="checkIn"></td>
                        <td id="checkOut" ></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" id="faceModeBack" class="btn btn-default" data-call="">Back</button>
                <button type="button" id="faceModeClose" class="btn btn-default" data-call="">Back</button>
            </div>
        </div>
    </div>
</div>
