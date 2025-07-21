<!--<!DOCTYPE html>
<html>
<head>
    <title>Upload Excel to MySQL</title>
</head>
<body>
    <h2>Upload Excel File</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file" accept=".xlsx, .xls">
        <input type="submit" name="submit" value="Upload">
    </form>
</body>
</html>
-->

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-center">Muatnaik Fail Excel Taskforce</h5>
                    </div>
                    <div class="card-body">
                        <form action="upload.php" method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="file" class="form-label">Choose Excel File (.xlsx or .xls)</label>
                                <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary" name="submit">Upload</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
