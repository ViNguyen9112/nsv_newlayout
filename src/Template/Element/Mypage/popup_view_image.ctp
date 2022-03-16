<style>
.hr-form {
    width: 88%;
    margin: 10px auto;
    border: 1px solid rgba(250, 250, 250, 0.4);
}
#leftArrow{
    position: absolute;
    left: 5%;
    bottom: 0px;
    font-size: 40px;
    color: #fdfafa;
    display: none;
}

.leftArrow-disabled{
    position: absolute;
    left: 5%;
    bottom: 0px;
    font-size: 40px;
    color: rgb(167, 164, 164);
}

#rightArrow{
    position: absolute;
    right: 63%;
    bottom: 0px;
    font-size: 40px;
    color: #fdfafa;
    display: none;
}

.rightArrow-disabled{
    position: absolute;
    right: 63%;
    bottom: 0px;
    font-size: 40px;
    color: rgb(167, 164, 164);
}
#deleteImage{
    position: absolute;
    right: 5%;
    bottom: 5%;
    color: #e84f4f;
    font-size: 28px;
}
#venoboxImage {
    padding-top:24px;
    /* height: 68vw; */
    width: 100%;
    background-size: cover;
}
#toppage-body .btn-close{
    border-radius: 25px;
}
</style>

<div class="modal fade" id="viewImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content sign-modal-content">
            <div class="modal-body text-center d-flex justify-content-center align-items-center" style="height:67vh;overflow-y:auto">
                <!-- REPORT -->
                <img src="" alt="" id="venoboxImage" data-id="">

                <!-- <span id="deleteImage"><i class="fas fa-trash-alt"></i></span> -->
                <input type="hidden" id="currentImage" value="0">
            </div>
            <hr class="hr-form" />
            <div class="modal-footer" style="border-top:0px solid #fff">
                <span id="leftArrow"><i class="fas fa-chevron-circle-left"></i></span>
                <span class="leftArrow-disabled"><i class="fas fa-chevron-circle-left"></i></span>
                <span id="rightArrow"><i class="fas fa-chevron-circle-right"></i></span>
                <span class="rightArrow-disabled"><i class="fas fa-chevron-circle-right"></i></span>
                <button type="button" style="width: 100px;" class="btn btn-secondary btn-close" data-dismiss="modal"><?php echo (isset($data_language)) ? $data_language['close'] : "Close"; ?></button>
            </div>
        </div>
    </div>
</div>

