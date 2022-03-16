<style>
    .img-face {
        width: 120px;
        height: 150px;
        margin: 10px;
    }

    /* .img-overlay {
        background-blend-mode: overlay;
        border: 5px solid #78d4ba;
    } */

    .filter-date {
        display: inline-block;
        width: 120px;
        margin-left: 88px;
    }

    #sortDate{
        cursor: pointer;
    }

    .div-item p{
        margin-bottom: 1px;
        padding-left: 10px;
    }

    .div-item span {
        font-weight: 900;
    }
</style>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary float-left">Face Image - <span id="nameFolder"><?php if(isset($folder)) echo $folder->Name;?></span></h6>
    </div>
    <div class="card-body">
    <?php if(isset($images)):?>
        <div class="row">
            <div class="col-6" style="font-size:20px">
                <span id="sortDate" data-order="DESC"><i class="far fa-caret-square-down"></i></span> <span style="font-size:14px">Date created</span>
                <!-- <i class="fas fa-sort-amount-up" style="margin-left:10px;color:#ccc"></i> -->
                <input type="text" class="form-control filter-date" name="datepicker" id="datepicker_filter_date" size="10" placeholder="Date filter">
            </div>
            <!-- <div class="col-6">
            Search: <input type="text">
            </div> -->
        </div>

        <div class="row mt-3" id="divImages">
                <?php foreach($images as $date=>$items):?>

                    <div class="col-12 mb-5 row">
                        <div class="col-12">
                            <h5 style="color:#17c324"><?php echo $date; ?></h5>
                            <hr>
                        </div>
                        <?php foreach($items as $item):?>
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-5 div-item">
                                <a class="pic-face" href="<?php echo $this->Url->build('/', true).$item->Source.$item->Name;?>" data-gall="<?php echo $date; ?>">
                                    <?php if (file_exists(WWW_ROOT . $item->Source.$item->Name)) : ?>
                                    <img src="<?php echo $this->Url->build('/', true).$item->Source.$item->Name;?>" alt="" class="img-face">
                                    <?php else: ?>
                                    <img src="<?php echo $this->Url->build('/', true)."img/deleted.jpg"?>" alt="" class="img-face">
                                    <?php endif; ?>
                                </a>
                                <p><span><?php echo $item->Type; ?></span>: <?php echo $item->Time; ?></p>
                                <p><span>Area</span>: <?php echo $item->Area; ?></p>
                                <p><span>Customer</span>: <?php echo $item->Customer; ?></p>
                            </div>
                        <?php endforeach;?>
                    </div>

                <?php endforeach; ?>
        </div>
    <?php else:?>
        <div class="row mt-3">
            <div class="col-12 text-center">
                <h5>This folder is empty.</h5>
            </div>
        </div>
    <?php endif; ?>
    </div>
</div>

<script type="text/template" id="tplSecImage">
    <div class="col-12 mb-5 row">
        <div class="col-12">
            <h5 style="color:#17c324">__date__</h5>
            <hr>
        </div>
        __images__
    </div>
</script>

<script type="text/template" id="tplImage">
    <div class="col-xl-2 col-lg-3 col-md-3 col-sm-3 mb-5 div-item">
        <a class="pic-face" href="__src__" data-gall="__date__">
            <img src="__src__" alt="" class="img-face">
        </a>
        <p><span>__type__</span>: __time__</p>
        <p><span>Area</span>: __area__</p>
        <p><span>Customer</span>: __customer__</p>
    </div>
</script>

<?= $this->Html->script('admin/alsok/gallery.js?v='. date('YmdHis'), ['block' => 'scriptBottom']) . PHP_EOL ?>


