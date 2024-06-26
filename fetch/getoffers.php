<?php
    error_reporting(0);
    if(session_status() === PHP_SESSION_NONE)
        session_start();
    require "../connect.php";
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    $connect->set_charset("utf8mb4");
    $page = (isset($_POST["page"]) && is_numeric($_POST["page"]) && $_POST["page"] > 0) ? $_POST["page"] : 1;
    $sortOptions = array("salary");
    $sortColumn = array(", salary_highest DESC");
    $search = (isset($_POST["search"]) && !empty($_POST["search"]) && !ctype_space($_POST["search"])) ? " AND POSITION(TRIM('".$_POST["search"]."') IN title) > 0" : "";
    $search_filters = array("position_name", "category", "city", "company", "position_level", "contract_type", "employment_type", "work_type");
    foreach($search_filters as $filter) 
    {
        if(isset($_POST[$filter]) && !empty($_POST[$filter]) && !ctype_space($_POST[$filter]))
        {
            if($filter == "category")
            {
                $tmp = str_replace(";", "', '", $_POST[$filter]);
                $search .= " AND advertisement_id IN (SELECT advertisement_id FROM advertisement_category INNER JOIN category USING(category_id) WHERE name IN ('".$tmp."'))";
                continue;
            }
            $tmp = str_replace(";", "', '", $_POST[$filter]);
            $search .= " AND $filter IN ('".$tmp."')";
        }
    }
    if(isset($_SESSION["logged"]) && array_key_exists("user_id", $_SESSION["logged"]) && isset($_POST["saved"]) && $_POST["saved"] == 1)
        $search .= " AND advertisement_id IN (SELECT advertisement_id FROM user_saved WHERE user_id = ".$_SESSION["logged"]["user_id"].")";
    $sort = (isset($_POST["sort"]) && in_array($_POST["sort"], $sortOptions)) ? $sortColumn[array_search($_POST["sort"], $sortOptions)] : ", date_added DESC";
    $offersPerPage = (isset($_POST["offersPerPage"]) && is_numeric($_POST["offersPerPage"]) && $_POST["offersPerPage"] > 0) ? $_POST["offersPerPage"] : 20;
    /*$query_company = "<br>SELECT *, 1 AS available FROM advertisement INNER JOIN company USING(company_id) WHERE company_id = 1 AND date_expiration >= NOW()".$search." UNION SELECT *, 0 AS available FROM advertisement INNER JOIN company USING(company_id) WHERE company_id = 1 AND date_expiration < NOW()".$search." ORDER BY available DESC".$sort." LIMIT ".(($page - 1) * $offersPerPage).", ".$offersPerPage.";";
    echo $query_company;*/
    if(isset($_SESSION["logged"]) && array_key_exists("company_id", $_SESSION["logged"]))
    {
        $result = $connect->execute_query("SELECT *, 1 AS available FROM advertisement INNER JOIN company USING(company_id) WHERE company_id = ? AND date_expiration >= NOW()".$search." UNION SELECT *, 0 AS available FROM advertisement INNER JOIN company USING(company_id) WHERE company_id = ? AND date_expiration < NOW()".$search." ORDER BY available DESC".$sort." LIMIT ".(($page - 1) * $offersPerPage).", ".$offersPerPage.";", [$_SESSION["logged"]["company_id"], $_SESSION["logged"]["company_id"]]);
        $totalOffers = ($connect->execute_query("SELECT advertisement_id FROM advertisement INNER JOIN company USING(company_id) WHERE company_id = ?".$search, [$_SESSION["logged"]["company_id"]]))->num_rows;
    }                
    else
    {
        $result = $connect->execute_query("SELECT *, 1 AS available FROM advertisement INNER JOIN company USING(company_id) WHERE date_expiration >= NOW()".$search." UNION SELECT *, 0 AS available FROM advertisement INNER JOIN company USING(company_id) WHERE date_expiration < NOW()".$search." ORDER BY available DESC".$sort." LIMIT ".(($page - 1) * $offersPerPage).",".$offersPerPage.";");
        $totalOffers = ($connect->execute_query("SELECT advertisement_id FROM advertisement INNER JOIN company USING(company_id) WHERE 1=1".$search))->num_rows;
    }
    $totalPages = ceil($totalOffers / $offersPerPage);
    if($result->num_rows > 0)
    {
        echo "<p class='text-center signika-negative fs-4 mt-5 fw-bold'>Znalezione wyniki: ".$totalOffers."</p>";
        $months = array("stycznia", "lutego", "marca", "kwietnia", "maja", "czerwca", "lipca", "sierpnia", "września", "października", "listopada", "grudnia");
        while($row = $result->fetch_assoc())
        {
            echo "<div class='d-flex col-12 col-sm-6 col-lg-4 col-xl-3 position-relative'>";
            if(isset($_SESSION["logged"]) && array_key_exists("company_id", $_SESSION["logged"]) && $row["company_id"] == $_SESSION["logged"]["company_id"] || isset($_SESSION["logged"]) && $_SESSION["logged"]["is_admin"] == 1)
                echo "<a href='offerform.php?id=".$row["advertisement_id"]."&mode=edit' class='d-flex align-items-center bg-primary text-white position-absolute bottom-0 end-0 text-success rounded-circle py-1 px-3' id='editOfferButton'><i class='bi bi-pen-fill'></i></a>";
            echo "<a data-offer='".$row["advertisement_id"]."' class='d-block w-100 text-decoration-none bg-white shadow rounded position-relative p-3 pt-4 position-relative jobOffer ".($row["available"] == 0 ? " jobOfferDisabled" : "")."' href='offerdetails.php?id=".$row["advertisement_id"]."'>";
            $date_added = new DateTime($row["date_added"]);
            echo "<div class='position-absolute bg-success text-white rounded-pill py-1 px-3 top-0'>".$date_added->format("j")." ".mb_substr($months[(int)($date_added->format("n")) - 1], 0, 3)." ".$date_added->format("Y")."</div>";
            $categoryResult = $connect->execute_query('SELECT name FROM advertisement_category INNER JOIN category USING(category_id) WHERE advertisement_id = ?', [$row["advertisement_id"]]);
            $categoryArray = array();
            while($categoryRow = $categoryResult->fetch_assoc())
                array_push($categoryArray, $categoryRow["name"]);
            echo "<p class='text-secondary fw-bold fs-6 mb-1 me-4'>".implode(", ", $categoryArray)."</p>";
            if(isset($_SESSION["logged"]) && array_key_exists("user_id", $_SESSION["logged"]))
            {
                $savedResult = $connect->execute_query('SELECT * FROM user_saved WHERE user_id = ? AND advertisement_id = ?', [$_SESSION["logged"]["user_id"], $row["advertisement_id"]]);
                if($savedResult->num_rows > 0)
                    echo "<div data-bs-toggle='tooltip' title='Zapisano' class='color-orange mt-4 me-3 position-absolute end-0 top-0'><i class='fs-4 bi bi-star-fill'></i></div>";
            }
            echo "<p class='text-primary fw-bold fs-5 mb-0'>{$row["title"]}</p>";           
            echo "<p class='text-primary-emphasis mb-2'>przez: ".$row["name"]."</p>";             
            echo "<p class='text-success fw-bold'>".(is_null($row["salary_lowest"]) ? number_format($row["salary_highest"], 2, ",", " ") : number_format($row["salary_lowest"], 2, ",", " ")." - ".number_format($row["salary_highest"], 2, ",", " "))." zł</p>";
            echo "<hr class='text-body-tertiary'>";
            echo "<p class='text-body-secondary'>".mb_strtolower($row["position_level"])." &#x2022; ".mb_strtolower($row["contract_type"])." &#x2022; ".strtolower($row["employment_type"])." &#x2022; ".mb_strtolower($row["work_type"])."</p>";
            echo "</a></div>";
        }
        echo "<nav class='row mt-5'>";
        echo "<ul class='pagination d-flex justify-content-center'>";
        echo "<li class='page-item'>";
        echo "<button title='Pierwsza strona' data-bs-toggle='tooltip' class='page-link".($page > 1 ? "' data-page='1'" : " disabled'")."><i class='bi bi-caret-left-fill'></i><i class='bi bi-caret-left-fill'></i></button>";
        echo "</li>";
        echo "<li class='page-item'>";
        echo "<button title='Poprzednia strona' data-bs-toggle='tooltip' class='page-link".($page > 1 ? "' data-page='".($page - 1)."'" : " disabled'")."><i class='bi bi-caret-left-fill'></i></button>";
        echo "</li>";
        for($i = $page - 3; $i <= $page + 3; $i++)
        {
            if($i > 0 && $i <= $totalPages)
            {
                echo "<li class='page-item".($i == $page ? " active" : "")."'>";
                echo "<button class='page-link' data-page='$i'>$i</button>";
                echo "</li>";
            }
        }
        echo "<li class='page-item'>";
        echo "<button title='Następna strona' data-bs-toggle='tooltip' class='page-link".($page >= $totalPages ? " disabled'" : "' data-page='".($page + 1)."'")."><i class='bi bi-caret-right-fill'></i></button>";
        echo "</li>";
        echo "<li class='page-item'>";
        echo "<button title='Ostatnia strona' data-bs-toggle='tooltip' class='page-link".($page >= $totalPages ? " disabled'" : "' data-page='$totalPages'")."><i class='bi bi-caret-right-fill'></i><i class='bi bi-caret-right-fill'></i></button>";
        echo "</li>";
        echo "</ul></nav>";
    }
    else
        echo "<p class='text-center signika-negative fs-4 fw-bold'>Niestety, nie znaleźliśmy pasujących ofert pracy.</p>";
    $result->free_result();
$connect->close();
?>