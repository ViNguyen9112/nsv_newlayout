<span id="txt-name-main-image" class="eng"><?= $images['org_filename'] ?></span>
<?= $this->Form->hidden('filename', ['value' => $images['filename']]); ?>
<?= $this->Form->hidden('temp', ['value' => 1, 'id' => 'temp']); ?>
<?= $this->Form->hidden('org_filename', ['id' => 'org_filename', 'value' => $images['org_filename']]); ?>
<?= $this->Form->hidden('del_image', ['value' => '', 'id' => 'del_image']); ?>
