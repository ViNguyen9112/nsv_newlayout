<style>
    .folder-image, .sub-folder-image{
        background-image:url(../img/admin/folder.png);
        background-size: 90px;
        background-position: center;
        background-repeat: no-repeat;/* width: 70px; */
        text-align: center;
        vertical-align: middle;
        line-height: 90px;
        color:#000;
        cursor:pointer;
    }

    .folder-image p{
        padding-top:20px;
        font-weight: bold;
        color:#fff;
        /* text-decoration: none; */
    }

    .folder-overlay {
        background: linear-gradient(rgb(118 177 163 / 50%),rgb(143 215 202 / 50%));
    }

    #downSort, #upSort, #backParent, #newFolder, #upParent {
        cursor:pointer;
    }

    .disable-color{
        color:#ccc;
    }

    .sec-tool button{
        margin-right: 3px;
    }

    .btn-cut{
        background-color: #328fa4;
        border-color: #328fa4;
    }

    .btn-shortcut{
        background-color: #43cbe8;
        border-color: #43cbe8;
    }

    .btn-refresh{
        background-color: #4dd7de;
        border-color: #4dd7de;
        color: #fff;
    }

    .btn-new-folder{
        padding-left:20px;
        font-size: 36px;
        line-height: 34px;
    }

    .btn-up-parent{
        padding-top:4px;
        font-size:28px;
    }

    .ip-label-folder{
        height: 24px;
        text-align: center;
        width: 78px;
    }

    .renaming {
        padding-top: 19px;
    }

    .shortcut-icon{
        position: absolute;
        top: 62%;
        left: 16%;
        background: #fff;
        border: #538d8f solid 1px;
        color: #2e97b7;
    }

    #nameSubFolder{
        margin-left: 30px;
    }
</style>
<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3 sec-tool">
        <h6 class="m-0 font-weight-bold text-primary float-left">Face Image</h6>
        <button type="button" class="btn btn-info btn-refresh rounded-pill float-right" id="refreshFolder"><i class="fas fa-spinner"></i> Refresh</button>
        <button type="button" class="btn btn-danger rounded-pill float-right" id="deleteFolder" style="visibility:hidden"><i class="fas fa-trash"></i> Delete</button>
        <button type="button" class="btn btn-success rounded-pill float-right" id="downloadFolder" style="visibility:hidden"><i class="fas fa-download"></i> Download</button>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6" style="font-size:23px;line-height:40px">
                <i class="fas fas fa-sort-alpha-down" data-display="block" id="downSort"></i>
                <i class="fas fas fa-sort-alpha-up disable-color" data-display="none" id="upSort" style="margin-left:10px"></i>
                <i class="fas fa-level-up-alt" id="backParent" style="margin-left:20px;display:none"></i>
            </div>
            <div class="col-6 sec-tool">
                <button type="button" class="btn btn-info rounded-pill float-right" id="copyFolder" style="display:none"><i class="far fa-copy"></i> Copy</button>
                <button type="button" class="btn btn-info btn-cut rounded-pill float-right" id="cutFolder" style="display:none"><i class="fas fa-cut"></i> Cut</button>
                <button type="button" class="btn btn-info btn-shortcut rounded-pill float-right" id="shortcutFolder" style="display:none"><i class="fas fa-share"></i> Shortcut</button>
            </div>
            <!-- TODO: SEARCH -->
            <!-- <div class="col-6">
            Search: <input type="text">
            </div> -->
        </div>

        <div class="row mt-4" id="mainFolder">
            <?php foreach($folder as $item):?>
            <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 pl-0 pr-0 mb-4">
                <div class="folder-image" data-class="normal" data-id="<?php echo $item->ID; ?>" data-name="<?php echo $item->Name; ?>">
                    <p class='folder-id'><?php echo $item->Name; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <input type="hidden" id="statusTool" value="default">
        <input type="hidden" id="arrayFolderID">
        <input type="hidden" id="arrayFolderName">
        <input type="hidden" id="arrayFolderDuplicate">
    </div>
</div>
<div class="card shadow">
    <div class="card-body">
        <div class="row">
            <div class="col-6">
                <div class="btn-new-folder">
                    <i class="fas fa-folder-plus" id="newFolder"></i>
                </div>
                <div class="btn-up-parent" id="inSubFolder" style="display:none">
                    <i class="fas fa-level-up-alt" id="upParent"></i>
                    <span id="nameSubFolder"></span>
                </div>

            </div>
            <div class="col-6">
                <button type="button" class="btn btn-warning rounded-pill float-right" id="renameFolder" style="display:none"><i class="far fa-edit"></i> Rename</button>
                <button type="button" class="btn btn-primary rounded-pill float-right" id="pasteFolder" style="display:none"><i class="far fa-clipboard"></i> Paste</button>
            </div>
        </div>
        <div class="row mt-4" id="subFolder">
            <?php if(isset($subFolder)):?>
                <?php foreach($subFolder as $item): ?>
                    <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 pl-0 pr-0 mb-4 text-center sub-folder">
                        <div class="sub-folder-image" data-class="normal" data-id="<?php echo $item->ID; ?>" data-name="<?php echo $item->Name; ?>">&ensp;</div>
                        <div class='name-folder'><p><?php echo $item->Name; ?></p></div>
                        <input type="hidden" class='id-folder' value="<?php echo $item->ID; ?>">
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="row mt-4" id="childrenFolder" style="display:none"></div>
        <input type="hidden" id="openSubFolder" value="0">
    </div>
</div>

<script id="tplFolder" type="text/template">
    <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 pl-0 pr-0 mb-4 __class__">
        <div class="folder-image" data-class="normal" data-id="__folderid__" data-name="__name__">
            <p class='folder-id'>__name__</p>
        </div>
    </div>
</script>

<script id="tplShortcutFolder" type="text/template">
    <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 pl-0 pr-0 mb-4 __class__">
        <div class="folder-image" data-class="normal" data-id="__folderid__" data-name="__name__">
            <i class="fas fa-share shortcut-icon"></i>
            <p class='folder-id'>__name__</p>
        </div>
    </div>
</script>

<script id="tplSubFolder" type="text/template">
    <div class="col-xl-1 col-lg-2 col-md-2 col-sm-2 pl-0 pr-0 mb-4 text-center sub-folder __class__" data-click="none">
        <div class="sub-folder-image" data-class="normal" data-id="__folderid__" data-name="__name__">&ensp;</div>
        <div class='name-folder'><p>__name__</p></div>
        <input type="hidden" class="id-folder" value="__folderid__">
    </div>
</script>

<?php $this->Html->script('admin/alsok/face-image.js?v='. date('YmdHis'), ['block' => 'scriptBottom']) . PHP_EOL ?>
<?php echo $this->element('Admin/popup_ask_folder'); ?>
