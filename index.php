<?php
include_once("config/sessioncheck.php");
include_once("templates/header.php");
?>

<div class="container">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tabla Diaria</h5>
                    <div id="dailyTable" style="height: 400px;"></div>
                    <br/>
                    <p id="dailyTotalAmount"></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tabla Semanal</h5>
                    <div id="weeklyChart" style="height: 400px;"></div>
                    <br/>
                    <p id="weeklyTotalAmount"></p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Tabla Mensual</h5>
                    <div id="monthlyChart" style="height: 400px;"></div>
                    <br/>
                    <p id="monthlyTotalAmount"></p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/daily.js"></script>
<script src="js/weekly.js"></script>
<script src="js/monthly.js"></script>
<?php include("templates/footer.php") ?>
