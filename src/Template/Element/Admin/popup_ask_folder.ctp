<style>
    .ask-folder p{
        font-size:15px;
    }
</style>
<div class="modal fade" id="askFolderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Face Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ask-folder">
                <p>This destination already contains a folder named <span id="nameFolderDuplicate"></span></p>
                <p>Do you want to</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" style="margin-right: 47%" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="replaceFolder">Replace</button>
                <button type="button" class="btn btn-info" id="mergeFolder">Merge</button>
            </div>
        </div>
    </div>
</div>
