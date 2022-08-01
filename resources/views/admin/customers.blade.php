@include('admin.header')

<div class="main-panel">
    <div class="content">
        <div class="container-fluid">
            <h4 class="page-title">Зарегистрированные клиенты</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-head-bg-default table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col" style="width:25px;">#</th>
                                    <th scope="col">ФИО</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Телефон</th>
                                    <th scope="col">Дата регистрации</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if($data['customers']) { ?>
                                <?php
                                foreach ($data['customers'] as $item) { ?>
                                <tr>
                                    <td style="width:25px;"><?= $item->id; ?></td>
                                    <td style="width: 30%;">
                                    	<?= $item->fio1; ?> <?= $item->fio2; ?>
                                    </td>
                                    <td style="width:50%;"><?= $item->email; ?></td>
                                    <td style="width:10%;"><?= $item->phone; ?></td>
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
