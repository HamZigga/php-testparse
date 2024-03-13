<!doctype html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Поиск лота</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <main>
        <div class="container">
            <div class="form-wrap d-flex align-items-center justify-content-center mb-3">

                <form action="findlot.php" method="GET" class="form p-5 shadow-lg rounded">
                    <h1 class="h3 mb-3 fw-normal">Поиск данных по лоту</h1>

                    <div class="mb-3">
                        <label for="biddingNumber" class="form-label">Номер торгов</label>
                        <input type="text" class="form-control" placeholder="Введите номер торгов" name="biddingNumber" id="biddingNumber" required>
                    </div>

                    <div class="mb-3">
                        <label for="lotNumber" class="form-label">Номер лота</label>
                        <input type="number" class="form-control" placeholder="Введите номер лота" name="lotNumber" id="lotNumber" required>
                    </div>

                    <?php
                    if (isset($_COOKIE["LotNotFound"])) {
                        echo '<p class = "text-danger">Лот не найден</p>';
                        setcookie("LotNotFound", "", time() - 3600);
                    }
                    ?>

                    <button type="submit" class="btn btn-primary">Найти</button>

                </form>
            </div>


            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">URL адрес</th>
                        <th scope="col">Сведения об имуществе</th>
                        <th scope="col">Номер лота</th>
                        <th scope="col">Начальная цена лота</th>
                        <th scope="col">Email контактного лица</th>
                        <th scope="col">Телефон контактного лица</th>
                        <th scope="col">ИНН должника</th>
                        <th scope="col">Номер дела о банкротстве</th>
                        <th scope="col">Дата начала торгов</th>
                        <th scope="col">Дата окончания торгов</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    include_once './lib/Database.php';
                    $query = "SELECT * FROM bankrupts INNER JOIN lots ON bankrupts.id = lots.bankrupt_id";
                    $result = (new Database())->select($query);
                    if (!empty($result)) {
                        foreach ($result as $row) {
                            echo "<tr><th scope='row'>" . $row["id"]
                                . "</th><td>" . $row["url"]
                                . "</th><td>" . $row["lot_info"]
                                . "</th><td>" . $row["lot_number"]
                                . "</td><td>" . $row["lot_price"]
                                . "</td><td>" . $row["email"]
                                . "</td><td>" . $row["phone"]
                                . "</td><td>" . $row["inn"]
                                . "</td><td>" . $row["case_number"]
                                . "</td><td>" . date('d-m-Y H:i:s', strtotime($row["start_date"]))
                                . "</td><td>" . date('d-m-Y H:i:s', strtotime($row["end_date"]))
                                . "</td></tr>";
                        }
                    }
                    ?>
                </tbody>

            </table>
        </div>

    </main>
</body>

</html>