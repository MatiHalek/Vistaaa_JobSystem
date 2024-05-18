<?php
  //error_reporting(0);
  session_start();
  if(isset($_SESSION["logged"]) && (array_key_exists("company_id", $_SESSION["logged"]) || $_SESSION["logged"]["is_admin"]) && isset($_POST["deletingOffers"]))
  {
    require "connect.php";
    $connect = new mysqli($host, $db_user, $db_password, $db_name);
    $connect->set_charset('utf8mb4');
    foreach($_POST["deletingOffers"] as $offer)
    {
      if(array_key_exists("company_id", $_SESSION["logged"]))
      {
        $result = $connect->execute_query('SELECT * FROM advertisement WHERE advertisement_id = ? AND company_id = ?', [$offer, $_SESSION["logged"]["company_id"]]);
        if($result->num_rows == 0)
          continue;
      }
      $connect->execute_query('DELETE FROM advertisement WHERE advertisement_id = ?', [$offer]);
    }
    $connect->close();
    header("Refresh:0");
    exit();
  }
?>
<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Znajdziesz tu tysiące atrakcyjnych i dobrze płatnych ofert pracy od sprawdzonych pracodawców z renomowanych firm w kraju i za granicą. Jeżeli szukasz pracy, ten serwis jest w sam raz dla Ciebie. Zapraszamy!">
  <meta name="keywords" content="praca, oferty, ogłoszenia, system">
  <meta name="robots" content="index, follow">
  <meta name="author" content="Mateusz Marmuźniak">
  <title>Oferty pracy | System ogłoszeniowy Vistaaa</title>
  <base href="https://127.0.0.1/Vistaaa/">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="img/vistaaa_small_logo.png" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <?php
	  header('Content-Type: text/html; charset=utf-8');
    $pageName = "Oferty pracy"; 
	  include "header.php";
	?>  
    <main class="container-lg">
        <article class="d-flex justify-content-between flex-wrap">
            <section class='d-flex align-items-center mb-2'>
              <label class='fw-bold fs-5' for='sortingOffersSelect'>Sortuj: </label>
              <select class="form-select ms-2 w-auto" aria-label="Sortowanie ofert" id="sortingOffersSelect">
                <option value="">Od najnowszych</option>
                <option value="salary">Od najlepiej płatnych</option>
              </select>
            </section>
            <?php
              if(isset($_SESSION["logged"]) && array_key_exists("user_id", $_SESSION["logged"]))
              {
                echo "<section class='d-flex align-items-center mb-2'>";
                echo "<input class='form-check-input' type='checkbox' value='' id='savedOffersCheckbox'>";
                echo "<label class='fw-bold fs-5 ms-2 mt-1 user-select-none' for='savedOffersCheckbox'>Tylko zapisane</label>";              
                echo "</section>";
              }
            ?>
            <section class='d-flex align-items-center mb-2'>
              <label for="offersPerPageSelect" class='fw-bold fs-5'>Liczba ogłoszeń na stronie: </label>
              <select class="form-select ms-2 w-auto" aria-label="Liczba ogłoszeń na jednej stronie" id="offersPerPageSelect">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20" selected>20</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </section>
        </article>
        <?php
          if(isset($_SESSION["logged"]) && (array_key_exists("company_id", $_SESSION["logged"]) || $_SESSION["logged"]["is_admin"]))
          {
            echo "<article class='my-2 d-flex justify-content-end'>";
            echo "<div class='pt-2 pb-0 px-2 border-0 shadow alert alert-danger flex-wrap d-flex align-items-center justify-content-center'>";
            echo "<label for='deleteModeSwitch' class='me-2 mb-2 user-select-none'>Tryb usuwania</label>";
            echo "<div class='form-check form-switch mb-2'>";
            echo "<input class='form-check-input' type='checkbox' role='switch' id='deleteModeSwitch'>";
            echo "</div>";
            echo "<button type='button' id='deleteButton' data-bs-toggle='modal' data-bs-target='#deleteModal' class='px-3 py-2 mb-2 dangerButton' disabled><i class='bi bi-trash-fill me-2'></i><span>Usuń zaznaczone (0)</span></button>";
            echo "</div>";
            echo "</article>";
            echo "<div class='modal fade' id='deleteModal' tabindex='-1' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered'>
                      <div class='modal-content'>
						            <div class='modal-header'>
						              <h1 class='modal-title fs-5'>Potwierdź usunięcie ofert</h1>
						              <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
						            </div>
								        <div class='modal-body'>
                          <p>Czy na pewno chcesz usunąć zaznaczone oferty w liczbie: <span id='deleteCount'></span>?</p>
                          <p class='fw-bold'>Ta czynność jest nieodwracalna.</p>
									      </div>
								        <div class='modal-footer'>
                          <form action='' method='POST' id='deleteForm'>
                            <div class='d-none' id='deletingOffers'></div>
                            <button type='button' class='commonButton' data-bs-dismiss='modal'>Anuluj</button>
									          <button type='submit' class='dangerButton' data-bs-dismiss='modal'>Usuń</button>
                          </form>
								        </div>
							        </div>
						        </div>
					        </div>";
          }           
        ?>
        <article class="row g-3 justify-content-center" id="offersContainer">         
        </article>     
  </main>
  <?php
		  include "footer.php";
      $searches = array("position_name", "category", "city", "company", "position_level", "contract_type", "employment_type", "work_type");
	?>
  <script>
    let deleteMode = false;
    document.querySelector("#deleteModeSwitch")?.addEventListener("input", function() {
      if(this.checked)
        deleteMode = true;
      else
      { 
        deleteMode = false;
        deletingOffers = [];
        document.querySelectorAll("[data-offer]").forEach(element => {
          element.classList.remove("bg-danger");
          element.classList.remove("bg-opacity-50");
          element.classList.add("bg-white");
        });
        document.querySelector("#deleteButton").setAttribute("disabled", "");
        document.querySelector("#deleteButton").lastElementChild.textContent = "Usuń zaznaczone (0)";
      }     
    });
    let deletingOffers = [];
    const loadingAnimation = "<div class='spinner-border text-primary' style='scale: 2;' role='status'><span class='visually-hidden'>Loading...</span></div>";
    let page = <?php echo isset($_GET["page"]) ? $_GET["page"] : 1; ?>;
    let sort = "<?php echo isset($_GET["sort"]) ? $_GET["sort"] : ""; ?>";
    let search = "<?php echo isset($_GET["search"]) ? $_GET["search"] : ""; ?>";
    if(search === "")
    {
      const url = new URL(location.href);
      if(url.searchParams.has("search"))
      {
        url.searchParams.delete("search");
        history.replaceState(null, "", url);
      }    
    }
    <?php
      foreach($searches as $search)
        echo "const $search = \"".(isset($_GET["search_$search"]) ? implode(";", $_GET["search_$search"]) : "")."\";\n";
    ?>
    if(localStorage.getItem("offersPerPage") !== null)
      document.querySelector("#offersPerPageSelect").value = localStorage.getItem("offersPerPage");
    else
      localStorage.setItem("offersPerPage", document.querySelector("#offersPerPageSelect").value);
    document.querySelector("#offersPerPageSelect").addEventListener("input", function() {
      localStorage.setItem("offersPerPage", this.value);
      GetOffers();
    });
    if(sort !== "")
      document.querySelector("#sortingOffersSelect").value = sort;
    document.querySelector("#sortingOffersSelect").addEventListener("input", function() {
      sort = this.value;
      const url = new URL(location.href);
      if(url.searchParams.has("sort") && this.value === "")
        url.searchParams.delete("sort");
      else
        url.searchParams.set("sort", this.value);
      history.replaceState(null, "", url);
      GetOffers();
    });
    if(document.querySelector("#savedOffersCheckbox") !== null && new URL(location.href).searchParams.has("saved"))
      document.querySelector("#savedOffersCheckbox").checked = true;
    async function GetOffers()
    {
      try
      {
        document.querySelector("#offersContainer").innerHTML = loadingAnimation;
        const sendData = new FormData();
        sendData.append("search", search);
        sendData.append("page", page);
        sendData.append("sort", sort);
        sendData.append("offersPerPage", localStorage.getItem("offersPerPage"));
        if(document.querySelector("#savedOffersCheckbox") !== null && document.querySelector("#savedOffersCheckbox").checked)
          sendData.append("saved", "1");
        <?php
          foreach($searches as $search)
            echo "sendData.append(\"$search\", $search);\n";
        ?>
        const response = await fetch("./fetch/getoffers.php", {
          method: "POST",
          body: sendData
        });
        document.querySelector("#offersContainer").innerHTML = await response.text(); 
      }
      catch
      {
        document.querySelector("#offersContainer").innerHTML = "<div class='alert alert-danger mb-0 shadow text-center'><p class='fw-bold mb-1'>Coś poszło nie tak. Spróbuj ponownie lub odśwież stronę.</p>Kod błędu: 1 (Nie udało połączyć się z serwerem)<br><button type='button' class='commonButton mt-1' id='reload'><i class='bi bi-arrow-clockwise me-2'></i>Załaduj ponownie</a></div>";
        document.querySelector("#reload").addEventListener("click", GetOffers);
      }      
      document.querySelectorAll("[data-page]").forEach(element => {
        element.addEventListener("click", function() {
          page = this.getAttribute("data-page");
          const url = new URL(location.href);
          if(url.searchParams.has("page") && page === "1")
            url.searchParams.delete("page");
          else
            url.searchParams.set("page", page);
          history.replaceState(null, "", url);
          GetOffers();
        });
      });
      document.querySelectorAll("[data-offer]").forEach(element => {
        element.addEventListener("click", function(e) {
          if(deleteMode)
          {
            e.preventDefault();
            if(this.classList.contains("bg-danger"))
            {
              this.classList.remove("bg-danger");
              this.classList.remove("bg-opacity-50");
              this.classList.add("bg-white");
              deletingOffers.splice(deletingOffers.indexOf(this.getAttribute("data-offer")), 1);
            }            
            else
            {
              this.classList.add("bg-opacity-50");
              this.classList.remove("bg-white");
              this.classList.add("bg-danger");
              deletingOffers.push(this.getAttribute("data-offer"));

            } 
            deletingOffers = [...new Set(deletingOffers)];          
            const deleteButton = document?.querySelector("#deleteButton");
            if(document.querySelectorAll(".bg-danger").length > 0)
            {
              deleteButton.classList.remove("disabled");
              deleteButton.removeAttribute("disabled"); 
              deleteButton.lastElementChild.textContent = `Usuń zaznaczone (${document.querySelectorAll(".bg-danger").length})`;
            }
            else
            {
              deleteButton.classList.add("disabled");
              deleteButton.setAttribute("disabled", "");
              deleteButton.lastElementChild.textContent = "Usuń zaznaczone (0)";
            }
          }
        });
      });
      document.querySelectorAll(".jobOffer").forEach(element => {
        if(deletingOffers.includes(element.getAttribute("data-offer")))
        {
          element.classList.add("bg-danger");
          element.classList.add("bg-opacity-50");
          element.classList.remove("bg-white");
        }
      });
      UpdateTooltips();
    }
    GetOffers(); 
    if(document.querySelector("#deleteModeSwitch") !== null)
      window.addEventListener("pageshow", () => document.querySelector("#deleteModeSwitch").checked = false);  
    document.querySelector("#deleteModal")?.addEventListener("show.bs.modal", function() {
      document.querySelector("#deleteCount").textContent = deletingOffers.length;
      document.querySelector("#deletingOffers").innerHTML = "";
      deletingOffers.forEach(offer => {
        const input = document.createElement("input");
        input.setAttribute("type", "hidden");
        input.setAttribute("name", "deletingOffers[]");
        input.setAttribute("value", offer);
        document.querySelector("#deletingOffers").appendChild(input);
      });
    }); 
  document.querySelector("#deleteForm")?.addEventListener("submit", () => {
    const url = new URL(location.href);
    if(url.searchParams.has("page"))
        url.searchParams.delete("page");
    document.querySelector("#deleteForm").action = url.toString();
  });
  document.querySelector("#savedOffersCheckbox")?.addEventListener("change", function(){
    if(this.checked)
    {
      const url = new URL(location.href);
      url.searchParams.set("saved", "1");
      history.replaceState(null, "", url);
    }
    else
    {
      const url = new URL(location.href);
      if(url.searchParams.has("saved"))
      {
        url.searchParams.delete("saved");
        history.replaceState(null, "", url);
      }
    }
    GetOffers();
  });
  </script>
</body>
</html>