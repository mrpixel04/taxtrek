
     
    <style>

        /* Button Styles */
        .btn-primary {
            background-color: #fff;
            color: #000;
            border: 2px solid #000;
            border-radius: 10px;
            padding: 10px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: scale(1.05); /* Zoom in effect */
        }

         .instructions-container {
            background-color: #ff0000; /* Light Red */
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 45px; /* Space below instructions */
        }

        /* Style for instructions text */
        .instructions-text {
            color: #fff;
            font-size: 13px;
        }


    </style>


<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <h5 class="text-center">Borang Muatnaik Fail Excel</h5>
                </div>
                <div class="card-body">
                    <div class="instructions-container">
                        <p class="instructions-text">Sebelum proses muatnaik dilakukan, sila pastikan setiap ruangan fail Excel adalah betul dan tidak ada ruangan kosong. Jika terdapat ruangan kosong dan amaun cukai tidak betul, susunan akan menyebabkan ruangan di dalam pangkalan data tidak betul. Pihak kami tidak akan bertanggungjawab jika rekod tidak betul dan salah susunan di dalam pangkalan data.</p>
                    </div>
                   
                    <form action="upload.php" method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="file" class="form-label">Choose Excel File (.xlsx or .xls)</label>
                            <input type="file" class="form-control" id="file" name="file" accept=".xlsx, .xls">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" name="submit">Muatnaik</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

