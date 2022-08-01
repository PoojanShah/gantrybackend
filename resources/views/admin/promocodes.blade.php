@include('admin.header')

<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
            <div style="display: flex;justify-content: space-between;">
                <h4 class="page-title">Промокоды</h4>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-head-bg-default table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col" style="width:25px;">#</th>
                                    <th scope="col">Информация о покупателе</th>
                                    <th scope="col">Сумма</th>
                                    <th scope="col">Статус</th>
                                    <th scope="col">Дата</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($data['promocodes']) { ?>
                                <?php
                                foreach ($data['promocodes'] as $item) { ?>
                                <tr>
                                    <td style="width:25px;"><?= $item->id; ?></td>
                                    <td style="width: 30%;">
                                        Email: <?= $item->email; ?><br>
                                    </td>
                                    <td style="width:50%;"><?= $item->costs; ?> грн</td>
                                    <td style="width:10%;">
                                        <?= $item->status; ?>
                                    </td>
                                    <td style="width:10%;"><?= $item->date_created; ?></td>

                                </tr>
                                <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h4>Временные промокоды</h4>
                                </div>
                                <div class="col-md-6" style="text-align: right;">
                                    <a href="/admin/promocodes/add/" class="btn btn-success">+ Добавить</a>   
                                </div>
                            </div>    
                            <table style="margin-top:25px;" class="table table-head-bg-default table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col">Промокод</th>
                                    <th scope="col">Сумма</th>
                                    <th scope="col">Проценты</th>
                                    <th scope="col">Дата старта</th>
                                    <th scope="col">Дата завершения</th>
                                    <th scope="col">Количество использований</th>
                                    <th scope="col">Удалить</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($data['date_promocodes']) { ?>
                                <?php
                                foreach ($data['date_promocodes'] as $item) { ?>
                                <tr>
                                    <td style="width: 20%;">
                                        <?= $item->promocode; ?><br>
                                    </td>
                                    <td style="width:10%;"><?= $item->costs; ?> грн</td>
                                    <td style="width:10%;"><?= $item->percent; ?> %</td>
                                    <td style="width:20%;">
                                        <?= $item->date_start; ?>
                                    </td>
                                    <td style="width:20%;">
                                        <?= $item->date_end; ?>
                                    </td>
                                    <td style="width:10%;">
                                        <?= $item->count_use; ?>
                                    </td>
                                    <td style="width:10%;"><a href="/admin/promocodes/delete?id=<?= $item->id; ?>" class="btn btn-danger btn-round">Удалить</a></td>

                                </tr>
                                <?php } ?>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>

@include('admin.footer')
