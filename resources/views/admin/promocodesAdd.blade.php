@include('admin.header')
<?php echo date('Y-m-d --- H - i - s'); ?>
<style>
    table img {
        height:50px;
    }
    .card-body {
        width:50%;
    }
    #output_image, #output_big_image {
        height:170px;
    }
</style>
<script>
    <?php if($data['success'] == 'Successfully added!') { ?>
    setTimeout( function () {
        pageAdded();
    }, 1000);
    <?php } ?>
</script>
<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <ul class="ml-0" style="padding-left: 15px;">
                    <?php if(isset($data['breadcrumbs'])) {
                    $b_count = count($data['breadcrumbs']); ?>
                    <?php $i=1; foreach($data['breadcrumbs'] as $breadcrumb) { ?>
                    <li class="badge"><a class="btn btn-default btn-xs" href="<?= $breadcrumb['href']; ?>"><?= $breadcrumb['title']; ?></a></li>
                    <?php if($i!=$b_count) { ?>><?php } ?>
                    <?php $i++; } ?>
                    <?php } ?>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h4 class="page-title">Add New Promocode</h4>
                    <span style="color:#ff0000;"><?= $data['error']; ?></span>
                    <span style="color:#008000;"><?= $data['success']; ?></span>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <form action="/admin/promocodes/add/" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{!! csrf_token() !!}">
                            <div class="card-body">
                            	<div class="form-group">
                                    <label for="title">Промокод (если оставить пустым, будет сгенерирован автоматически)</label>
                                    <input type="text" name="promocode" id="promocode" class="form-control" placeholder="Enter Promocode" value="<?php if(isset($data['request']['promocode'])) { echo $data['request']['promocode']; } ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="title">Сумма</label>
                                    <input type="text" name="costs" id="costs" class="form-control" placeholder="Enter Costs" value="<?php if(isset($data['request']['costs'])) { echo $data['request']['costs']; } ?>">
                                </div>
                                или
                                <div class="form-group">
                                    <label for="title">Проценты</label>
                                    <input type="text" name="percent" id="percent" class="form-control" placeholder="Enter %" value="<?php if(isset($data['request']['percent'])) { echo $data['request']['percent']; } ?>">
                                </div>
                                <div class="form-group">
                                    <label for="date">Дата старта</label>
                                    <input style="width:200px;" type="date" name="date_start" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="date">Дата завершения</label>
                                    <input style="width:200px;" type="date" name="date_end" class="form-control" value="<?php echo date('Y-m-d'); ?>">
                                </div>


                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="/admin/promocodes/" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@include('admin.footer')
