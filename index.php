<?php 
include 'Backend/config.php';

?>
<!DOCTYPE html>
<html>

<head>
    <title>Tree Component in PHP JavaScript</title>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <link rel="stylesheet" href="style.css">
</head>

<body>


    <div class="container">

        <div class="row">
            <div class="col-md-6 m-auto">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h4>Add Data</h4>
                    </div>
                    <div class="card-body">
                        <form action="">
                            <div class="form-group mb-2">
                                <label for="">Account Name</label>
                                <input type="text" id="account_name" class="form-control select2"
                                    placeholder="Enter Account Name" required>
                            </div>
                            <div class="form-group mb-2">
                                <label for="">Parent Account</label>
                                <select type="text" class="form-select" id="parent_id">
                                    <option value="">Select</option>
                                    <?php 
                                    //start loop and show the value select tag
                                    if ($allCategory=$conn->query("SELECT * FROM categories")) {
                                        while ($rows=$allCategory->fetch_array()) {
                                            echo '<option value="'.$rows['id'].'">'.$rows['name'].'</option>';
                                        }
                                    }
                                
                                ?>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button type="submit" id="submitBtn" class="btn btn-primary"> Add Now</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-6 m-auto">
                <div class="card shadow">
                    <div class="card-header text-white bg-success text-center">
                        <h3 class="">Accounts Tree Component</h3>
                    </div>
                    <div class="card-body">
                        <ul id="myUL">
                            <?php
                        function displayCategories($parent_id = 0, $level = 0) {
                            global $conn;
                            $sql = "SELECT * FROM categories WHERE parent_id = $parent_id";
                            $result = $conn->query($sql);

                            while ($row = $result->fetch_assoc()) {
                                echo '<li>';
                                echo str_repeat('&nbsp;', $level * 4) . '<span class="caret">' . $row['name'] . '</span>';

                                // call to display child categories
                                $childCategories = $conn->query("SELECT * FROM categories WHERE parent_id = " . $row['id']);
                                if ($childCategories->num_rows > 0) {
                                    echo '<ul class="nested">';
                                    displayCategories($row['id'], $level + 1);
                                    echo '</ul>';
                                }

                                echo '</li>';
                            }
                        }

                        // show category 
                        displayCategories();
                        ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>








    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#parent_id").select2();


        $("#submitBtn").click(function() {
            var account_name = $("#account_name").val();
            var parent_id = $('#parent_id').val();
            if (account_name.length == 0) {
                toastr.error('Name is Require');
            } else {
                $("#submitBtn").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="visually-hidden">Loading...</span>');
                $.ajax({
                    url: 'Backend/Tree.php',
                    type: 'POST',
                    cache:false,
                    data: {
                        account_name: account_name,
                        parent_id: parent_id,
                        add_accounts_data:0,
                    },
                    success: function(response) {
                        if (response == 1) {
                            toastr.success("Insert Successfully");
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        } else {
                            toastr.error("Sothing Was Wrong!");
                        }
                    }
                });
            }
        });
    });

    var toggler = document.getElementsByClassName("caret");
    var i;

    for (i = 0; i < toggler.length; i++) {
        toggler[i].addEventListener("click", function() {
            this.parentElement.querySelector(".nested").classList.toggle("active");
            this.classList.toggle("caret-down");
        });
    }
    </script>


</body>

</html>