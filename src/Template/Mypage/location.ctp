<style>
    #map_canvas{
        height: 100%;
        border-radius: 20px;
    }
</style>
<div style="padding-top:3px">
    <div class="account row">
        <div class="col-5 staff">
            <strong><?php echo $staff->StaffID; ?></strong>
            <strong><?php echo $staff->Name; ?></strong>
        </div>
        <div class="col-6" style="padding:0">
            <strong><span id="today"></span></strong>
            <strong><span id="digital-clock"></span></strong>
        </div>
        <div class="col-1" style="padding:0;background:#c61111;border-radius:0 20px 20px 0">
            <a style="color:#fff" href="<?php echo $this->Url->build('/', true); ?>users/logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </div>
</div>
<div style="height:72vh">
    <div id="map_canvas"></div>
</div>
<input type="hidden" value="<?php echo $lat; ?>" id="lat">
<input type="hidden" value="<?php echo $long; ?>" id="long">

<script>
    function initializeMap() {
        const myLatLng = { lat: Number($('#lat').val()), lng: Number($('#long').val()) };
        const map = new google.maps.Map(document.getElementById("map_canvas"), {
            zoom: 16,
            center: myLatLng,
        });
        new google.maps.Marker({
            position: myLatLng,
            map,
            title: "You are here!",
        });
    }
    window.onload = initializeMap()
</script>
