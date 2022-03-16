<style>
.lb-qr {
    font-weight: bold;
    font-size: 14px;
    font-style: normal;
}
#modalQR i {
    font-size: 25px;
    color: #000;
}
</style>

<div class="modal fade" id="modalQR" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body row">
                <div class="col-5 mt-2">
                    <p class="lb-qr col-12">Staff ID: <span id="StaffIDQR"></span></p>
                    <p class="lb-qr col-12">Name: <span id="StaffNameQR"></span></p>
                </div>
                <div class="col-1 mt-3">
                    <i class="fas fa-long-arrow-alt-right"></i>
                </div>
                <div class="col-6 text-center">
                    <img src="" alt="" id="imgQR" width="200px">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" style="width:100px" class="btn btn-secondary" id="btnBackQR">Back</button>
                <button type="submit" style="width:150px" class="btn btn-primary" id='btnSaveQR'>Save QRCode</button>
            </div>
        </div>
    </div>
</div>
