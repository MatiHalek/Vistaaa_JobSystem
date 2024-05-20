<?php
	error_reporting(0);
	session_start();
	if(!isset($_SESSION["logged"]) || array_key_exists("user_id", $_SESSION["logged"]) && $_SESSION["logged"]["is_admin"] == 0)
	{
		header("Location: ./");
		exit();
	}
	require "connect.php";
	$connect = new mysqli($host, $db_user, $db_password, $db_name);
	if(isset($_GET["id"]) && array_key_exists("company_id", $_SESSION["logged"]))
	{
		$id = $_GET["id"];
		$result = $connect->execute_query('SELECT * FROM advertisement WHERE advertisement_id = ?', [$id]);
		if($result->num_rows == 1)
		{
			$editRow = $result->fetch_assoc();
			if($editRow["company_id"] != $_SESSION["logged"]["company_id"])
			{
				header("Location: ./");
				exit();
			}
		}
	}
	$connect->close();
	$contract_types = array("Umowa o pracę", "Umowa zlecenie", "Umowa o dzieło");
	$employment_types = array("Pełny etat", "Pół etatu", "1/4 etatu", "3/4 etatu", "Praktyki", "Staż", "Wolontariat");
	$work_types = array("Stacjonarna", "Zdalna", "Hybrydowa");
	if(isset($_POST["mode"]))
    {
        $success = true;
		if(!isset($_POST["title"]) || empty($_POST["title"]) || ctype_space($_POST["title"]) || strlen($_POST["title"]) < 3 || strlen($_POST["title"]) > 100)
		{
			$success = false;
			$_SESSION["offer_error_title"] = "Tytuł musi zawierać od 3 do 100 znaków.";
		}
		if(!isset($_POST["category"]) || empty($_POST["category"]) || !is_array($_POST["category"]))
		{
			$success = false;
			$_SESSION["offer_error_category"] = "Musisz wybrać co najmniej jedną kategorię.";
		}
		if(!array_key_exists("company_id", $_SESSION["logged"]) && (!isset($_POST["company"]) || empty($_POST["company"]) || !is_numeric($_POST["company"])))
		{
			$success = false;
			$_SESSION["offer_error_company"] = "Musisz wybrać firmę.";
		}
		if(!isset($_POST["name"]) || empty($_POST["name"]) || ctype_space($_POST["name"]) || strlen($_POST["name"]) < 3 || strlen($_POST["name"]) > 50)
		{
			$success = false;
			$_SESSION["offer_error_name"] = "Nazwa stanowiska musi zawierać od 3 do 50 znaków.";
		}
		if(!isset($_POST["level"]) || empty($_POST["level"]) || ctype_space($_POST["level"]) || strlen($_POST["level"]) < 3 || strlen($_POST["level"]) > 50)
		{
			$success = false;
			$_SESSION["offer_error_level"] = "Poziom stanowiska musi zawierać od 3 do 50 znaków.";
		}
		if(!isset($_POST["contract"]) || empty($_POST["contract"]) || !is_numeric($_POST["contract"]) || $_POST["contract"] < 0 || $_POST["contract"] >= count($contract_types))
		{
			$success = false;
			$_SESSION["offer_error_contract"] = "Musisz wybrać rodzaj umowy.";
		}
		if(!isset($_POST["employment"]) || empty($_POST["employment"]) || !is_numeric($_POST["employment"]) || $_POST["employment"] < 0 || $_POST["employment"] >= count($employment_types))
		{
			$success = false;
			$_SESSION["offer_error_employment"] = "Musisz wybrać rodzaj zatrudnienia.";
		}
		if(!isset($_POST["work"]) || empty($_POST["work"]) || !is_numeric($_POST["work"]) || $_POST["work"] < 0 || $_POST["work"] >= count($work_types))
		{
			$success = false;
			$_SESSION["offer_error_work"] = "Musisz wybrać rodzaj pracy.";
		}
		if(!isset($_POST["days"]) || empty($_POST["days"]) || ctype_space($_POST["days"]) || strlen($_POST["days"]) < 4 || strlen($_POST["days"]) > 50)
		{
			$success = false;
			$_SESSION["offer_error_days"] = "Dni i godziny pracy muszą zawierać od 4 do 50 znaków.";
		}
		if(isset($_POST["salary_lowest"]) && !empty($_POST["salary_lowest"] && $_POST["salary_lowest"] > $_POST["salary_highest"]))
		{
			$success = false;
			$_SESSION["offer_error_salary"] = "Najniższe wynagrodzenie nie może być wyższe od najwyższego.";
		}
		if(!isset($_POST["salary_highest"]) || empty($_POST["salary_highest"]) || !is_numeric($_POST["salary_highest"]) || $_POST["salary_highest"] < 0 || $_POST["salary_highest"] > 999999.99 || !preg_match('/^\d{1,6}(\.\d{1,2})?$/',  $_POST["salary_highest"]))
		{
			$success = false;
			$_SESSION["offer_error_salary"] = "Najwyższe wynagrodzenie musi być poprawną liczbą złotych z przedziału od 0 do 999 999,99.";
		}
		if(isset($_POST["salary_lowest"]) && !empty($_POST["salary_lowest"] && (!is_numeric($_POST["salary_lowest"]) || $_POST["salary_lowest"] < 0 || $_POST["salary_lowest"] > 999999.99 || !preg_match('/^\d{1,6}(\.\d{1,2})?$/',  $_POST["salary_lowest"]))))
		{
			$success = false;
			$_SESSION["offer_error_salary"] = "Najniższe wynagrodzenie musi być poprawną liczbą złotych z przedziału od 0 do 999 999,99.";
		}
		if(strtotime($_POST["date"]) < strtotime(date("Y-m-d\TH:i:sP")))
		{
			$success = false;
			$_SESSION["offer_error_date"] = "Data wygaśnięcia nie może być wcześniejsza niż obecna.";
		}
		if(!isset($_POST["date"]) || empty($_POST["date"]) || !preg_match("/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/", $_POST["date"]))
		{
			$success = false;
			$_SESSION["offer_error_date"] = "Nieprawidłowy format daty.";
		}
		else
		{
			$d = DateTime::createFromFormat("Y-m-d\TH:i", $_POST["date"]);
			if($d && $d->format("Y-m-d\TH:i") !== $_POST["date"])
			{
				$success = false;
				$_SESSION["offer_error_date"] = "Ta data nie jest prawidłowa.";
			}
		}
		if(!isset($_POST["responsibilities"]) || empty($_POST["responsibilities"]) || ctype_space($_POST["responsibilities"]) || strlen($_POST["responsibilities"]) < 20 || strlen($_POST["responsibilities"]) > 1000)
		{
			$success = false;
			$_SESSION["offer_error_responsibilities"] = "Zakres obowiązków musi zawierać od 20 do 1000 znaków.";
		}
		if(!isset($_POST["requirements"]) || empty($_POST["requirements"]) || ctype_space($_POST["requirements"]) || strlen($_POST["requirements"]) < 20 || strlen($_POST["requirements"]) > 1000)
		{
			$success = false;
			$_SESSION["offer_error_requirements"] = "Wymagania muszą zawierać od 20 do 1000 znaków.";
		}
		if(!isset($_POST["offer"]) || empty($_POST["offer"]) || ctype_space($_POST["offer"]) || strlen($_POST["offer"]) < 20 || strlen($_POST["offer"]) > 1000)
		{
			$success = false;
			$_SESSION["offer_error_offer"] = "Oferta musi zawierać od 20 do 1000 znaków.";
		}
        $editing = $_POST["mode"] == "edit" ? true : false;
		if($success)
		{
			$companyId = array_key_exists("company_id", $_SESSION["logged"]) ? $_SESSION["logged"]["company_id"] : $_POST["company"];
			$connect = new mysqli($host, $db_user, $db_password, $db_name);
			if($editing)
			{
				$connect->execute_query('UPDATE advertisement SET title = ?, company_id = ?, position_name = ?, position_level = ?, contract_type = ?, employment_type = ?, work_type = ?, work_days = ?, salary_lowest = ?, salary_highest = ?, date_expiration = ?, responsibilities = ?, requirements = ?, offer = ? WHERE advertisement_id = ?', [$_POST["title"], $companyId, $_POST["name"], $_POST["level"], $contract_types[$_POST["contract"]], $employment_types[$_POST["employment"]], $work_types[$_POST["work"]], $_POST["days"], ((!isset($_POST["salary_lowest"]) || empty($_POST["salary_lowest"])) ? null : $_POST["salary_lowest"]), $_POST["salary_highest"], $_POST["date"], $_POST["responsibilities"], $_POST["requirements"], $_POST["offer"], $_POST["id"]]);
				$connect->execute_query('DELETE FROM advertisement_category WHERE advertisement_id = ?', [$_POST["id"]]);
				foreach($_POST["category"] as $category)
					$connect->execute_query('INSERT INTO advertisement_category (advertisement_id, category_id) VALUES (?, ?)', [$_POST["id"], $category]);
				$returnedId = $_POST["id"];
			}
			else
			{
				$connect->execute_query('INSERT INTO advertisement (title, company_id, position_name, position_level, contract_type, employment_type, work_type, work_days, salary_lowest, salary_highest, date_added, date_expiration, responsibilities, requirements, offer) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', [$_POST["title"], $companyId, $_POST["name"], $_POST["level"], $contract_types[$_POST["contract"]], $employment_types[$_POST["employment"]], $work_types[$_POST["work"]], $_POST["days"], ((!isset($_POST["salary_lowest"]) || empty($_POST["salary_lowest"])) ? null : $_POST["salary_lowest"]), $_POST["salary_highest"], date("Y-m-d H:i:s"), $_POST["date"], $_POST["responsibilities"], $_POST["requirements"], $_POST["offer"]]);
				$returnedId = $connect->insert_id;
				foreach($_POST["category"] as $category)
					$connect->execute_query('INSERT INTO advertisement_category (advertisement_id, category_id) VALUES (?, ?)', [$returnedId, $category]);
			}
			$connect->close();
            header("Location: offerdetails.php?id=".$returnedId);
            exit();
		}
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
    <title>Dodawanie ogłoszenia | System ogłoszeniowy Vistaaa</title>
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
		$pageName = (isset($_GET["id"]) ? "Edytowanie" : "Dodawanie")." ogłoszenia";
		include "header.php";
	?>  
	<main>
		<article class="container-md">
			<?php         
				$editError = false;
				$isEditModeEnabled = false;  
				$connect = new mysqli($host, $db_user, $db_password, $db_name);
				$connect->set_charset('utf8mb4');            
				if(isset($_GET["id"]))               
				{
					$id = $_GET["id"];
					$result = $connect->execute_query('SELECT * FROM advertisement WHERE advertisement_id = ?', [$id]);           
					if($result->num_rows != 1)
						$editError = true;
					else
					{
						$isEditModeEnabled = true;
						$categories = array();
						$editRow = $result->fetch_assoc();
						$result = $connect->execute_query('SELECT * FROM advertisement_category WHERE advertisement_id = ?', [$id]);           
						while($offerCategory = $result->fetch_assoc())
							array_push($categories, $offerCategory["category_id"]);
					}  
				}           
				if(!$editError)
				{
					echo "<form action='offerform.php' id='offerForm'";
					if($isEditModeEnabled)
						echo "?id=".$_GET["id"];
					echo "' method='POST'>";
					if($isEditModeEnabled)
						echo "<script>document.title = 'Edytowanie ogłoszenia | System ogłoszeniowy Vistaaa';</script>";               
					echo "<div class='position-relative formInput mt-3'>";                  
                    echo "<input type='text' id='title' name='title' minlength='3' maxlength='100' placeholder='Tytuł' required class='rounded-4 border-0 w-100 py-2 px-3'";
					if($isEditModeEnabled)
						echo " value='".htmlspecialchars($editRow["title"])."'";
                    echo "><label for='title' class='position-absolute'>Tytuł</label>";
                    echo "</div>"; 
					if(isset($_SESSION["offer_error_title"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_title"]."</div>";
						unset($_SESSION["offer_error_title"]);
					}   
					echo "<div class='position-relative formInput mt-4'>";
					echo "<select id='category' multiple required name='category[]' class='rounded-4 border-0 w-100 py-2 px-3'>";
					$result = $connect->execute_query('SELECT category_id, name FROM category');
					while($row = $result->fetch_assoc())
					{
						echo "<option value='".$row["category_id"]."'";
						if($isEditModeEnabled && in_array($row["category_id"], $categories))
							echo " selected";
						echo ">".$row["name"]."</option>";
					}                                   
					$result->free_result();
                    echo "</select><label for='category' class='position-absolute'>Kategorie</label></div>";
					if(isset($_SESSION["offer_error_category"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_category"]."</div>";
						unset($_SESSION["offer_error_category"]);
					} 
					echo "<div class='position-relative formInput mt-4'>";
					echo "<select id='company' name='company' class='rounded-4 border-0 w-100 py-2 px-3'";
					if(array_key_exists("company_id", $_SESSION["logged"]))
						echo " disabled><option selected value='".$_SESSION["logged"]["company_id"]."'>".$_SESSION["logged"]["name"]."</option>";
					else
					{
						echo " required><option value='' disabled selected hidden>Wybierz...</option>";
						$result = $connect->execute_query('SELECT company_id, name FROM company');
						while($row = $result->fetch_assoc())
						{
							echo "<option value='".$row["company_id"]."'";
							if($isEditModeEnabled && $editRow["company_id"] == $row["company_id"])
								echo " selected";
							echo ">".$row["name"]."</option>";
						}                                   
						$result->free_result();
					}					
                    echo "</select><label for='company' class='position-absolute'>Firma</label></div>";
					if(isset($_SESSION["offer_error_company"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_company"]."</div>";
						unset($_SESSION["offer_error_company"]);
					}
					echo "<section class='row'>";
					echo "<div class='col-12 col-md-6'><div class='position-relative formInput mt-3'>";
					echo "<input type='text' id='name' name='name' minlength='3' maxlength='50' placeholder='Nazwa stanowiska' required class='rounded-4 border-0 w-100 py-2 px-3'";
					if($isEditModeEnabled)
						echo " value='".htmlspecialchars($editRow["position_name"])."'";
                    echo "><label for='name' class='position-absolute'>Nazwa stanowiska</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_name"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_name"]."</div>";
						unset($_SESSION["offer_error_name"]);
					}
					echo "</div>"; 
					echo "<div class='col-12 col-md-6'><div class='position-relative formInput mt-3'>";
					echo "<input type='text' id='level' name='level' minlength='3' maxlength='50' placeholder='Poziom stanowiska' required class='rounded-4 border-0 w-100 py-2 px-3'";
					if($isEditModeEnabled)
						echo " value='".htmlspecialchars($editRow["position_level"])."'";
                    echo "><label for='level' class='position-absolute'>Poziom stanowiska</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_level"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_level"]."</div>";
						unset($_SESSION["offer_error_level"]);
					}
					echo "</div>"; 
					echo "</section>";
					echo "<section class='row'>";
					echo "<div class='col-12 col-sm-6 col-md-4'><div class='position-relative formInput mt-4'>";
					echo "<select required id='contract' name='contract' class='rounded-4 border-0 w-100 py-2 px-3'><option value='' disabled selected hidden>Wybierz...</option>";
					for($i = 0; $i < count($contract_types); $i++)
					{
						echo "<option value='".$i."'";
						if($isEditModeEnabled && $editRow["contract_type"] == $contract_types[$i])
							echo " selected";
						echo ">".$contract_types[$i]."</option>";
					}
                    echo "</select><label for='contract' class='position-absolute'>Rodzaj umowy</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_contract"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_contract"]."</div>";
						unset($_SESSION["offer_error_contract"]);
					}
					echo "</div>"; 
					echo "<div class='col-12 col-sm-6 col-md-4'><div class='position-relative formInput mt-4'>";
					echo "<select required id='employment' name='employment' class='rounded-4 border-0 w-100 py-2 px-3'><option value='' disabled selected hidden>Wybierz...</option>";
					for($i = 0; $i < count($employment_types); $i++)
					{
						echo "<option value='".$i."'";
						if($isEditModeEnabled && $editRow["employment_type"] == $employment_types[$i])
							echo " selected";
						echo ">".$employment_types[$i]."</option>";
					}
                    echo "</select><label for='employment' class='position-absolute'>Rodzaj zatrudnienia</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_employment"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_employment"]."</div>";
						unset($_SESSION["offer_error_employment"]);
					}
					echo "</div>"; 
					echo "<div class='col-12 col-sm-6 col-md-4'><div class='position-relative formInput mt-4'>";
					echo "<select required id='work' name='work' class='rounded-4 border-0 w-100 py-2 px-3'><option value='' disabled selected hidden>Wybierz...</option>";
					for($i = 0; $i < count($work_types); $i++)
					{
						echo "<option value='".$i."'";
						if($isEditModeEnabled && $editRow["work_type"] == $work_types[$i])
							echo " selected";
						echo ">".$work_types[$i]."</option>";
					}
                    echo "</select><label for='work' class='position-absolute'>Rodzaj pracy</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_work"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_work"]."</div>";
						unset($_SESSION["offer_error_work"]);
					}
					echo "</div>"; 
					echo "</section>";
					echo "<div class='position-relative formInput mt-3'>";                  
                    echo "<textarea minlength='4' maxlength='50' rows='2' id='days' name='days' placeholder='Dni i godziny pracy' required class='rounded-4 border-0 w-100 py-2 px-3'>";
					if($isEditModeEnabled)
						echo htmlspecialchars($editRow["work_days"]);
                    echo "</textarea><label for='days' class='position-absolute'>Dni i godziny pracy</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_days"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_days"]."</div>";
						unset($_SESSION["offer_error_days"]);
					}
					echo "<div class='alert alert-info information mt-2'><i class='bi bi-info-circle-fill me-2'></i>Jeżeli podawana jest jedna konkretna płaca (bez widełek), wystarczy wprowadzić jej wartość w polu \"Najwyższe wynagrodzenie\". Podawane wynagrodzenia będą wyświetlane w szczegółach jako miesięczne.</div>";
					echo "<section class='row'>";
					echo "<div class='col-12 col-md-6'><div class='position-relative formInput mt-4'>";
					echo "<input type='number' id='salary_lowest' name='salary_lowest' min='0' max='999999.99' step='0.01' class='rounded-4 border-0 w-100 py-2 px-3'";
					if($isEditModeEnabled)
						echo " value='".$editRow["salary_lowest"]."'";
                    echo "><label for='salary_lowest' class='position-absolute'>Najniższe wynagrodzenie (zł)</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_salary"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_salary"]."</div>";
						unset($_SESSION["offer_error_salary"]);
					}
					echo "</div>"; 
					echo "<div class='col-12 col-md-6'><div class='position-relative formInput mt-4'>";
					echo "<input type='number' id='salary_highest' name='salary_highest' min='0' max='999999.99' step='0.01' required class='rounded-4 border-0 w-100 py-2 px-3'";
					if($isEditModeEnabled)
						echo " value='".$editRow["salary_highest"]."'";
                    echo "><label for='salary_highest' class='position-absolute'>Najwyższe wynagrodzenie (zł)</label>";
                    echo "</div></div>"; 
					echo "</section>";           
					echo "<div class='position-relative formInput mt-4'>";                  
                    echo "<input type='datetime-local' id='date' name='date' min='".date("Y-m-d\TH:i")."' required class='rounded-4 border-0 w-100 py-2 px-3'";
					if($isEditModeEnabled)
						echo " value='".$editRow["date_expiration"]."'";
                    echo "><label for='date' class='position-absolute'>Data i godzina wygaśnięcia</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_date"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_date"]."</div>";
						unset($_SESSION["offer_error_date"]);
					}              
					echo "<div class='alert alert-info information mt-2'><i class='bi bi-info-circle-fill me-2'></i>W każdym z poniższych pól zalecamy oddzielanie wpisywanych kwestii znakami nowej linii, aby zwiększyć ich czytelność w szczegółach ogłoszenia.</div>";
					echo "<div class='position-relative formInput mt-3'>";                  
                    echo "<textarea minlength='20' maxlength='1000' rows='4' id='responsibilities' name='responsibilities' placeholder='Zakres obowiązków' required class='rounded-4 border-0 w-100 py-2 px-3'>";
					if($isEditModeEnabled)
						echo htmlspecialchars($editRow["responsibilities"]);
                    echo "</textarea><label for='responsibilities' class='position-absolute'>Zakres obowiązków</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_responsibilities"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_responsibilities"]."</div>";
						unset($_SESSION["offer_error_responsibilities"]);
					}           
					echo "<div class='position-relative formInput mt-3'>";                  
                    echo "<textarea minlength='20' maxlength='1000' rows='4' id='requirements' name='requirements' placeholder='Wymagania od kandydata' required class='rounded-4 border-0 w-100 py-2 px-3'>";
					if($isEditModeEnabled)
						echo htmlspecialchars($editRow["requirements"]);
                    echo "</textarea><label for='requirements' class='position-absolute'>Wymagania od kandydata</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_requirements"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_requirements"]."</div>";
						unset($_SESSION["offer_error_requirements"]);
					}  
					echo "<div class='position-relative formInput mt-3'>";                  
                    echo "<textarea minlength='20' maxlength='1000' rows='4' id='offer' name='offer' placeholder='Oferta pracodawcy' required class='rounded-4 border-0 w-100 py-2 px-3'>";
					if($isEditModeEnabled)
						echo htmlspecialchars($editRow["offer"]);
                    echo "</textarea><label for='offer' class='position-absolute'>Oferta pracodawcy</label>";
                    echo "</div>";
					if(isset($_SESSION["offer_error_offer"]))
					{
						echo "<div class='text-danger mt-2'>".$_SESSION["offer_error_offer"]."</div>";
						unset($_SESSION["offer_error_offer"]);
					} 
					echo "<input type='hidden' name='mode' value='";
                    if($isEditModeEnabled)
                        echo "edit";
                    else
                        echo "add";
                	echo "'>";
					if($isEditModeEnabled)
						echo "<input type='hidden' name='id' value='".$editRow["advertisement_id"]."'>";
					echo "<button type='submit' class='commonButton d-block mx-auto mt-3'>";
						if($isEditModeEnabled)
							echo "<i class='bi bi-check-circle-fill me-2'></i>Zatwierdź zmiany";
						else
							echo "<i class='bi bi-plus-circle-fill me-2'></i>Dodaj ogłoszenie";
					echo "</button></form>";
				}
				else
					echo "<div class='alert alert-danger information'><strong>Wystąpił błąd: Nie znaleziono oferty o takim identyfikatorze.</strong></div>";
				$connect->close();
			?>      
		</article>			
	</main>
	<?php
		include "footer.php";
	?>
	<script>
		function ConfirmLeaving(e)
        {
            const confirmationMessage = "Jeżeli wyjdziesz z tej strony, wprowadzone zmiany zostaną utracone.";
            (e || window.event).returnValue = confirmationMessage;
            return confirmationMessage;
        }
        window.addEventListener("beforeunload", ConfirmLeaving);
        document.querySelector("#offerForm")?.addEventListener("submit", () => {
            window.removeEventListener("beforeunload", ConfirmLeaving);
        });
	</script>
</body>
</html>