<script type="text/javascript">
    //const
    let PAGE_LIMIT_EXTENT = "<?=PAGE_LIMIT_EXTENT?>";
    let PAGE_LIMIT_FULL = "<?=PAGE_LIMIT_FULL?>";
    let PAGE_LIMIT_SPECIFIC = "<?=PAGE_LIMIT_SPECIFIC?>";
    let CST_SHOW_PASSWORD_DIALOG = "<?=CST_SHOW_PASSWORD_DIALOG?>";

    //const array
    let event_color = <?=json_encode(\Constants::$event_color)?>;
    let areas = <?=json_encode($region_opt)?>;
    let positions = <?=json_encode(\Constants::$positions)?>;
</script>
