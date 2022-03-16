<!-- Modal -->
<div class="modal fade" id="lookCameraModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content sign-modal-content">
      <div class="modal-body" style="padding-bottom:0">
        <p id="text_look_cam" style="color:#fff;margin-bottom:0"><?php echo $data_language['look_camera']; ?></p>
      </div>
      <div class="modal-footer" style="border:0;padding-top:0">
        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button> -->
        <button id="CaptureCamera" data-type="checkin" type="button" class="btn btn-success"><?php echo $data_language['capture']; ?></button>
      </div>
    </div>
  </div>
</div>
