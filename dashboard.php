<?php


/*
include("classes/conn.php");

// Query to retrieve data for Embassyview Condosuite - B
$queryEmbassyview = "SELECT COUNT(*) as count, STATUS_DATA FROM TBL_DATA WHERE NAMAPREMIS = 'EMBASSYVIEW CONDOSUITE - B' GROUP BY STATUS_DATA";
$resultEmbassyview = $connection->query($queryEmbassyview);

// Query to retrieve data for Residensi PV9
$queryResidensiPV9 = "SELECT COUNT(*) as count, STATUS_DATA FROM TBL_DATA WHERE NAMAPREMIS = 'RESIDENSI PV9' GROUP BY STATUS_DATA";
$resultResidensiPV9 = $connection->query($queryResidensiPV9);

// Close the database connection
$connection->close();
*/


?>

<div class="container mt-5">
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title">Embassyview Condosuite - B</h4>
                    <canvas id="embassyviewChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-3 mt-lg-0">
            <div class="card shadow">
                <div class="card-body">
                    <h4 class="card-title">Residensi PV9</h4>
                    <canvas id="residensiPV9Chart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script type="text/javascript">

        /*

        // Process the data from PHP and create pie charts
        const embassyviewData = <?php echo json_encode($resultEmbassyview->fetch_all(MYSQLI_ASSOC)); ?>;
        const residensiPV9Data = <?php echo json_encode($resultResidensiPV9->fetch_all(MYSQLI_ASSOC)); ?>;
        
        // Function to extract values from the data
        function extractData(data) {
            const labels = data.map(item => item.STATUS_DATA);
            const values = data.map(item => item.count);
            return { labels, values };
        }

        // Create pie charts
        const embassyviewChart = new Chart(document.getElementById('embassyviewChart'), {
            type: 'pie',
            data: {
                labels: extractData(embassyviewData).labels,
                datasets: [{
                    data: extractData(embassyviewData).values,
                    backgroundColor: ['#36A2EB', '#FFCE56'],
                }],
            },
        });

        const residensiPV9Chart = new Chart(document.getElementById('residensiPV9Chart'), {
            type: 'pie',
            data: {
                labels: extractData(residensiPV9Data).labels,
                datasets: [{
                    data: extractData(residensiPV9Data).values,
                    backgroundColor: ['#36A2EB', '#FFCE56'],
                }],
            },
        });
        */

    </script>

