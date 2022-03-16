<style>
#viewImageModal .modal-dialog {
    max-width: 100%;
    margin: 0;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    height: 100vh;
    display: flex;
}
.hr-form {
    width: 88%;
    margin: 10px auto;
    border: 1px solid rgba(250, 250, 250, 0.4);
}
#leftArrow{
    position: absolute;
    left: 1%;
    top: 44%;
    font-size: 45px;
    /* color:#000; */
    /* color: #fdfafa; */
    display: none;
}
#rightArrow{
    position: absolute;
    right: 1%;
    top: 44%;
    font-size: 45px;
    /* color:#000; */
    /* color: #fdfafa; */
    display: none;
}
#deleteImage{
    position: absolute;
    right: 5%;
    bottom: 5%;
    color: #e84f4f;
    font-size: 28px;
}
#venoboxImage {
        /* padding-top: 20px; */
    /* width: 45%; */
    background-size: cover;
    /* height: 94vh; */
    width: auto;
    height: auto;
}
#viewImageModal{
    z-index:2000;
}

#viewImageModal .btn-back{
    position: absolute;
    bottom: 10px;
    right: 10px;
    width: 60px;
}
</style>

<div class="modal fade" id="viewImageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body text-center d-flex justify-content-center align-items-center" style="overflow-y:auto; background:#000">
                <!-- REPORT -->
                <img src="" alt="" id="venoboxImage" data-id="">

                <span id="leftArrow"><i class="fas fa-chevron-circle-left"></i></span>
                <span id="rightArrow"><i class="fas fa-chevron-circle-right"></i></span>

                <!-- <span id="deleteImage"><i class="fas fa-trash-alt"></i></span> -->
                <input type="hidden" id="currentImage" value="0">
                <button type="button" class="btn btn-secondary btn-back" data-dismiss="modal">Back</button>
            </div>
            <!-- <hr class="hr-form" />
            <div class="modal-footer" style="border-top:0px solid #fff">



            </div> -->
        </div>
    </div>
</div>

