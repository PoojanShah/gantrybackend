@include('admin.header')

<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
            <div style="display: flex;justify-content: space-between;">
                <h4 class="page-title">Заказы</h4>
                <form id="statuses" action="" method="get">
                    <select name="status" onChange="$('#statuses').submit();">
                        <option value="0">Все заказы</option>
                        <option value="Оплачен" <?php if(!empty($_GET['status']) && $_GET['status']=='Оплачен') { echo "selected"; } ?>>Оплачен</option>
                        <option value="Новый" <?php if(!empty($_GET['status']) && $_GET['status']=='Новый') { echo "selected"; } ?>>Новый</option>
                        <option value="Обработан" <?php if(!empty($_GET['status']) && $_GET['status']=='Обработан') { echo "selected"; } ?>>Обработан</option>
                        <option value="Выполнен" <?php if(!empty($_GET['status']) && $_GET['status']=='Выполнен') { echo "selected"; } ?>>Выполнен</option>
                        <option value="Отменён" <?php if(!empty($_GET['status']) && $_GET['status']=='Отменён') { echo "selected"; } ?>>Отменён</option>
                    </select>
                </form>
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
                                    <th scope="col">Товары</th>
                                    <th scope="col">Итого</th>
                                    <th scope="col">Статус</th>
                                    <th scope="col">Дата</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($data['orders']) { ?>
                                <?php
                                foreach ($data['orders'] as $item) { ?>
                                <tr>
                                    <td style="width:25px;"><?= $item->order_id; ?></td>
                                    <td style="width: 30%;">
                                    	ФИО: <?= $item->fio; ?><br>
                                    	Email: <?= $item->email; ?><br>
                                    	Телефон: <?= $item->phone; ?><br>
                                    	Адрес: <?= $item->address; ?><br>
                                    	Комментарий: <?= $item->comment; ?><br>
                                    	Способ доставки: <?= $item->delivery; ?><br>
                                    </td>
                                    <td style="width:50%;"><?= $item->products; ?></td>
                                    <td style="width:10%;"><?= $item->total_price; ?> грн</td>
                                    <td style="width:10%;">
										<select onChange="changeStatus('<?= $item->order_id; ?>', $(this).val());">
                                            <option value="Новый" <?php if($item->status=='Новый') { echo "selected"; } ?>>Новый</option>
											<option value="Оплачен" <?php if($item->status=='Оплачен') { echo "selected"; } ?>>Оплачен</option>
											<option value="Обработан" <?php if($item->status=='Обработан') { echo "selected"; } ?>>Обработан</option>
											<option value="Выполнен" <?php if($item->status=='Выполнен') { echo "selected"; } ?>>Выполнен</option>
											<option value="Отменён" <?php if($item->status=='Отменён') { echo "selected"; } ?>>Отменён</option>
										</select>
									</td>
                                    <td style="width:10%;"><?= $item->date; ?></td>

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
